<?php
if (!defined('_PS_VERSION_')) {
    exit;
}


class deliveryCallendar extends Module
{
    public function __construct()
    {
        $this->name = 'deliverycallendar';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Piotr Sułkowski | MilleniumStudio';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Kalendarz dostawy');
        $this->description = $this->l('Moduł wyświetla kalendarz podczas wyboru dostawy, aby użytkownik mógł wybrać dzień, w którym zostanie dostarczone jego zamówienie.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        $this->default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $this->db = Db::getInstance();
        $this->context = Context::getContext();
        $this->lang = $this->context->language->id;
        $this->helper = new HelperForm();
        $this->errors = array();
        $this->notifications = array();
        $this->panels = array();

        if (isset($_GET['saveCallendarDate']) && $_GET['saveCallendarDate'] != '') {
            $this->ajaxSaveCallendarDate($_GET['saveCallendarDate']);
        }

        $this->checkOrdersShippingNumber();
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (
            !parent::install()
            || !$this->registerHook('displayPaymentTop')
            || !$this->registerHook('displayAfterCarrier')
            || !$this->registerHook('actionValidateOrder')
            || !$this->registerHook('header')
            || !$this->registerHook('actionOrderStatusUpdate')
            || !$this->registerHook('actionOrderStatusPostUpdate')
        ) {
            return false;
        }


        return true;
    }

    public function uninstall()
    {
        if (
            !parent::uninstall()
        ) {
            return false;
        }


        return true;
    }

    private function initFormHelper()
    {
        $this->helper = new HelperForm();
        $this->helper->module = $this;
        $this->helper->name_controller = $this->name;
        $this->helper->token = Tools::getAdminTokenLite('AdminModules');
        $this->helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $this->helper->default_form_language = $this->lang;
        $this->helper->allow_employee_form_lang = $this->lang;
        $this->helper->title = $this->displayName;
        $this->helper->show_toolbar = true;
        $this->helper->toolbar_scroll = true;

        $this->helper->toolbar_btn = [
            'save' => [
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
                    '&token=' . Tools::getAdminTokenLite('AdminModules'),
            ],
            'back' => [
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            ]
        ];
    }

    private function saveFieldValue($name, $value)
    {
        Configuration::updateValue($this->name . '_input_val_' . $name, $value);
    }

    private function saveFieldFileValue($name)
    {
        $file_url = $this->saveImageFromPost($name);
        if ($file_url !== false) {
            $this->saveFieldValue($name, $file_url);
        }
    }

    private function saveImageFromPost($name)
    {
        if (isset($_FILES[$name]) && $_FILES[$name]['error'] == 0) {

            $target_dir = _PS_UPLOAD_DIR_;
            $ext = pathinfo($_FILES[$name]['name'])['extension'];
            $target_file_name = trim($name) . '.' . $ext;
            $target_file = $target_dir . $target_file_name;

            if (file_exists($target_file)) { //image already exist
                unlink($target_file);
            }

            if ($ext == 'png' || $ext == 'jpg' || $ext == 'svg') {
                if (move_uploaded_file($_FILES[$name]["tmp_name"], $target_file)) {
                    $file_url = _PS_BASE_URL_ . str_replace('//', '/', str_replace(_PS_ROOT_DIR_, __PS_BASE_URI__, $target_file));
                    return $file_url;
                }
            }
        }
        return false;
    }

    private function getFieldValue($name)
    {
        return Configuration::get($this->name . '_input_val_' . $name);
    }

    private function initFieldsForm($idForm, $title, $submit_label, $callback_on_submit = false)
    {
        $this->fieldsForm[$idForm] = array('form' => array(
            'callback' => $callback_on_submit,
            'legend' => [
                'title' => $this->l($title),
            ],
            'input' => [],
            'submit' => [
                'title' => $this->l($submit_label),
                'class' => 'btn btn-default pull-right'
            ]
        ));
    }

    private function addLabelToForm($idForm, $name, $label)
    {
        // if user not created this form before adding field
        if (!isset($this->fieldsForm[$idForm])) {
            echo 'Form is not defined!';
            exit();
        }
        //get new index
        $index = count($this->fieldsForm[$idForm]['form']['input']);

        // main field settings
        $this->fieldsForm[$idForm]['form']['input'][$index] = [
            'type' => null,
            'label' => $this->l($label),
            'name' => $name,
            'size' => 0,
            'lang' => false,
        ];
        $this->helper->fields_value[$name] = null;
    }

    private function addFieldToForm($idForm, $type, $name, $label, $size, $required, $value = null, $options = null, $desc = null, $class = '')
    {
        // if user not created this form before adding field
        if (!isset($this->fieldsForm[$idForm])) {
            echo 'Form is not defined!';
            exit();
        }

        //get new field index
        $index = count($this->fieldsForm[$idForm]['form']['input']);

        // main field settings
        $this->fieldsForm[$idForm]['form']['input'][$index] = [
            'type' => ($type == 'image' ? 'file' : ($type == 'number' ? 'text' : $type)),
            'label' => $this->l($label),
            'name' => $name,
            'size' => $size,
            'lang' => false,
            'required' => $required,
            'class'    => $class,
            'desc' => ($type == 'image' ? $desc . '<div class="input_image"><img src="' . ($this->getFieldValue($name)) . '"></div>' : $desc)
        ];

        if ($type == 'number') {
            $this->fieldsForm[$idForm]['form']['input'][$index]['cast'] = 'intval';
        }

        //check another attr
        if ($type == 'select') {
            $this->fieldsForm[$idForm]['form']['input'][$index]['options'] = [
                'query' => $options,
                'id' => 'value',
                'name' => 'name'
            ];
        }

        $this->setHelperFieldValue($name, $value, $type);
    }

    private function setHelperFieldValue($name, $value, $type)
    {
        if ($type == 'hidden') {
            $this->helper->fields_value[$name] = $value;
        } else {
            if (!is_null($value)) {
                $this->helper->fields_value[$name] = $value;
            } else {
                $this->helper->fields_value[$name] = Configuration::get($this->name . '_input_val_' . $name);
            }
        }
    }

    private function generateForm($name)
    {
        $this->helper->submit_action = 'submit_' . $name;

        if ($this->checkIfSubmitActionOnForm($this->helper->submit_action)) {
            $this->saveFormFields($this->fieldsForm);
            $this->runCallbacksForForm($this->fieldsForm);
        }

        $return_form = $this->helper->generateForm($this->fieldsForm);
        $this->initFormHelper();
        $this->fieldsForm = null;
        return $return_form;
    }

    private function checkIfSubmitActionOnForm($action_name)
    {
        if (isset($_POST[$action_name])) {
            return true;
        }
        return false;
    }

    private function saveFormFields($fieldsForm)
    {
        foreach ($fieldsForm as $key => $form) {

            foreach ($form['form']['input'] as $key => $field) {

                $type = $field['type'];

                if (isset($_POST[$field['name']])) {
                    $name = $field['name'];

                    $value = $_POST[$field['name']];
                    $this->setHelperFieldValue($name, $value, $type);
                    if ($field['type'] == 'file') {
                        $this->saveFieldFileValue($name);
                    } else {
                        $this->saveFieldValue($name, $value);
                    }
                }
            }
        }
    }

    private function runCallbacksForForm($fieldsForm)
    {
        foreach ($fieldsForm as $key => $form) {
            if ($form['form']['callback'] != false) {
                $funcname = $form['form']['callback'];
                $this->$funcname($form);
            }
        }
    }

    public function getContent()
    {
        $output = null;
        $displayForm = $this->displayForm();

        if (count($this->errors) > 0) {
            foreach ($this->errors as $key => $error) {
                $output .= '<div class="bootstrap">
                    <div class="alert alert-error">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        ' . $error . '
                    </div>
                </div>';
            }
        }
        if (count($this->notifications) > 0) {
            foreach ($this->notifications as $key => $notification) {
                $output .= '<div class="bootstrap">
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        ' . $notification . '
                    </div>
                </div>';
            }
        }
        if (count($this->panels) > 0) {
            foreach ($this->panels as $key => $panels) {
                $output .= '
                <div class="panel">
                    <div class="col-12">' . $panels . '</div>
                </div>
                ';
            }
        }

        return $output . $displayForm;
    }

    private function displayForm()
    {
        $output_forms = '';

        $this->initFieldsForm(0, 'Metody dostaw dla których wyśweitlić kalendarz', 'Zapisz');
        foreach ($this->getCarriers() as $key => $Carrier) {
            $this->addFieldToForm(0, 'select', 'callendar_carrer_' . $Carrier['reference'], $Carrier['name'], 1, false, null, array(array('name' => 'Tak', 'value' => true), array('name' => 'Nie', 'value' => false)));
        }

        $this->initFieldsForm(1, 'Ustawienia kalendarza', 'Zapisz');
        $this->addFieldToForm(1, 'text', 'change_date_product_for_free', 'ID produktu do zmiany daty dostawy (w tym samym dniu)', 3, false, null, null, '',  'fixed-width-sm');
        $this->addFieldToForm(1, 'text', 'change_date_product', 'ID produktu do zmiany daty dostawy', 3, false, null, null, '',  'fixed-width-sm');
        $this->addFieldToForm(1, 'text', 'callendar_disabled_dates', 'Daty wyłączone z zamówień', 250, false, null, null, 'Daty w formacie dd.mm.rrrr oddzielone przecinkami.');
        $this->addFieldToForm(1, 'text', 'callendar_time_ranges', 'Godziny dostaw', 250, false, null, null, 'Przedziały godzin dostaw w formacie hh:mm-hh:mm oddzielone przecinkiem');
        $this->addFieldToForm(1, 'number', 'callendar_days_offset', 'Liczba dni przed możliwym terminem dostawy', 2, false, null, null, '',  'fixed-width-sm');
        $this->addFieldToForm(1, 'number', 'callendar_days_limit', 'Liczba dni do najpóźniejszego terminu dostawy', 2, false, null, null, '',  'fixed-width-sm');
        $this->addFieldToForm(1, 'number', 'callendar_max_day_hour', 'Godzina do której można zamówić w najbliższym możliwym dniu', 2, false, null, null, 'W formacie hh:mm',  'fixed-width-sm');
        $this->addFieldToForm(1, 'number', 'callendar_last_day_max_day_hour', 'Godzina do której można zamówić na najbliższy dzień w ostatnim dniu tygodnia', 2, false, null, null, 'W formacie hh:mm',  'fixed-width-sm');
        $output_forms .= $this->generateForm('delivery_callendar_settings');

        foreach ($this->getCarriers() as $key => $Carrier) {
            if ($this->getFieldValue('callendar_carrer_' . $Carrier['reference']) != true) continue;
            $this->initFieldsForm(2 + $key, 'Ustawienia dostawy | ' . $Carrier['name'], 'Zapisz');
            $this->addFieldToForm(2 + $key, 'select', 'callendar_carrer_' . $Carrier['reference'] . '_day_1', 'Poniedziałek', 1, false, null, array(array('name' => 'Tak', 'value' => true), array('name' => 'Nie', 'value' => false)));
            $this->addFieldToForm(2 + $key, 'select', 'callendar_carrer_' . $Carrier['reference'] . '_day_2', 'Wtorek', 1, false, null, array(array('name' => 'Tak', 'value' => true), array('name' => 'Nie', 'value' => false)));
            $this->addFieldToForm(2 + $key, 'select', 'callendar_carrer_' . $Carrier['reference'] . '_day_3', 'Środa', 1, false, null, array(array('name' => 'Tak', 'value' => true), array('name' => 'Nie', 'value' => false)));
            $this->addFieldToForm(2 + $key, 'select', 'callendar_carrer_' . $Carrier['reference'] . '_day_4', 'Czwartek', 1, false, null, array(array('name' => 'Tak', 'value' => true), array('name' => 'Nie', 'value' => false)));
            $this->addFieldToForm(2 + $key, 'select', 'callendar_carrer_' . $Carrier['reference'] . '_day_5', 'Piątek', 1, false, null, array(array('name' => 'Tak', 'value' => true), array('name' => 'Nie', 'value' => false)));
            $this->addFieldToForm(2 + $key, 'select', 'callendar_carrer_' . $Carrier['reference'] . '_day_6', 'Sobota', 1, false, null, array(array('name' => 'Tak', 'value' => true), array('name' => 'Nie', 'value' => false)));
            $this->addFieldToForm(2 + $key, 'select', 'callendar_carrer_' . $Carrier['reference'] . '_day_7', 'Niedziela', 1, false, null, array(array('name' => 'Tak', 'value' => true), array('name' => 'Nie', 'value' => false)));
        }
        $output_forms .= $this->generateForm('delivery_callendar_carrers_settings');

        return $output_forms;
    }

    private function getCarriers()
    {
        $Carriers = array();
        $_Carriers = CarrierCore::getCarriers($this->lang);
        foreach ($_Carriers as $key => $_Carrier) {
            $Carriers[] = array(
                'name' => $_Carrier['name'],
                'value' => $_Carrier['id_carrier'],
                'reference' => $_Carrier['id_reference'],
            );
        }
        return $Carriers;
    }

    private function getCarrersData()
    {
        $CarrersData = array();
        foreach ($this->getCarriers() as $key => $Carrier) {
            if ($this->getFieldValue('callendar_carrer_' . $Carrier['reference']) != true) continue;
            $CarrersData[$Carrier['reference']] = array(
                'name' => $Carrier['name'],
                'id' => $Carrier['value'],
                'days' => array(
                    1 => $this->getFieldValue('callendar_carrer_' . $Carrier['reference'] . '_day_1'),
                    2 => $this->getFieldValue('callendar_carrer_' . $Carrier['reference'] . '_day_2'),
                    3 => $this->getFieldValue('callendar_carrer_' . $Carrier['reference'] . '_day_3'),
                    4 => $this->getFieldValue('callendar_carrer_' . $Carrier['reference'] . '_day_4'),
                    5 => $this->getFieldValue('callendar_carrer_' . $Carrier['reference'] . '_day_5'),
                    6 => $this->getFieldValue('callendar_carrer_' . $Carrier['reference'] . '_day_6'),
                    7 => $this->getFieldValue('callendar_carrer_' . $Carrier['reference'] . '_day_7'),
                )
            );
        }
        return $CarrersData;
    }

    private function getCarrerData($reference)
    {
        $CarrersData = $this->getCarrersData();
        return (isset($CarrersData[$reference]) ? $CarrersData[$reference] : false);
    }

    private function getCurrentCartProducts()
    {
        global $cookie;
        if (!isset($cookie->id_cart)) {
            return array();
        }
        $Cart = new Cart($cookie->id_cart);
        $Products = $Cart->getProducts();
        return  $Products;
    }

    private function isFishProduct()
    {
        $cartProducts = $this->getCurrentCartProducts();
        foreach ($cartProducts as $key => $cartProduct) {
            if ($cartProduct['id_category_default'] == 66) {
                return true;
            }
        }
        return false;
    }

    private function getdisplayAfterCarrierVariables()
    {
        global $cookie;
        $CarrersData = $this->getCarrersData();

        if ($this->isFishProduct()) {
            foreach ($CarrersData as $key => &$CarrerData) {
                $CarrerData['days'] = array(
                    1 => '',
                    2 => '',
                    3 => '',
                    4 => '',
                    5 => '1',
                    6 => '',
                    7 => '',
                );
            }
        }

        return array(
            'callendar_disabled_dates' => $this->getFieldValue('callendar_disabled_dates'),
            'callendar_days_offset' => $this->getFieldValue('callendar_days_offset'),
            'callendar_days_limit' => $this->getFieldValue('callendar_days_limit'),
            'callendar_max_day_hour' => $this->getFieldValue('callendar_max_day_hour'),
            'callendar_last_day_max_day_hour' => $this->getFieldValue('callendar_last_day_max_day_hour'),
            'callendar_time_ranges' => $this->getFieldValue('callendar_time_ranges'),
            'CarrersData' => $CarrersData,
            'isFishProduct' => $this->isFishProduct(),
            'selectedDate' => Configuration::get($this->name . '_selected_date_' . $cookie->id_cart),
        );
    }

    public function getOrderDataByReference($reference)
    {
        $sql = "SELECT o.id_order,o.reference as order_reference, c.id_reference as carrer_reference FROM ps_orders o
        INNER JOIN ps_carrier c ON o.id_carrier = c.id_carrier
        WHERE o.reference = '" . $reference . "'";
        return $this->db->executeS($sql);
    }

    public function hookheader()
    {
        global $cookie;

        if ($this->context->controller->php_self == 'product') {
            $id_product = (int)Tools::getValue('id_product');
            if ($id_product == $this->getFieldValue('change_date_product') || $id_product == $this->getFieldValue('change_date_product_for_free')) {
                $base = (Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE')) ? 'https:' : 'http:';
                $OrderData = $this->getOrderDataByReference($_GET['order_reference']);

                if (
                    !isset($_GET['order_reference']) ||
                    !isset($OrderData[0]['id_order']) ||
                    (!isset($cookie->id_customer) || $cookie->id_customer < 1)
                ) {
                    Tools::redirect($base . $this->context->link->getPageLink('index', null, $this->context->language->id, array(), false, null, true));
                }
                $_Order = new Order($OrderData[0]['id_order']);

                if ($id_product == $this->getFieldValue('change_date_product_for_free')) {
                    if (explode(' ', $_Order->date_add)[0] != date('Y-m-d')) {
                        $_ChangeDateProduct = new Product($this->getFieldValue('change_date_product'));
                        //Tools::redirect($this->context->link->getProductLink((int)$_ChangeDateProduct->id, $_ChangeDateProduct->link_rewrite[$this->lang]));
                    }
                }

                $this->context->controller->addJS(__PS_BASE_URI__ . 'modules/' . $this->name . '/views/js/front/productDevlieryCalendarv2.js', true);
                $this->context->smarty->assign($this->getdisplayAfterCarrierVariables());


                if ($_Order->id_customer != $cookie->id_customer) {
                    Tools::redirect($base . $this->context->link->getPageLink('index', null, $this->context->language->id, array(), false, null, true));
                }

                $this->context->smarty->assign(array(
                    'this_order_data' => $this->getOrderDataByReference($_GET['order_reference'])
                ));
            }

            $this->context->smarty->assign(array(
                'change_date_product_for_free' =>  $this->getFieldValue('change_date_product_for_free'),
                'change_date_product' =>  $this->getFieldValue('change_date_product')
            ));
        }

        if ($this->context->controller->php_self == 'order') {
            $this->context->controller->addJS(__PS_BASE_URI__ . 'modules/' . $this->name . '/views/js/front/select2.min.js', true);
            $this->context->controller->addJS(__PS_BASE_URI__ . 'modules/' . $this->name . '/views/js/front/devlieryCalendarv4.js', true);
            $this->context->controller->addCSS(__PS_BASE_URI__ . 'modules/' . $this->name . '/views/css/front/select2.min.css', true);
        }
    }

    public function hookdisplayAfterCarrier($params)
    {
        $this->smarty->assign($this->getdisplayAfterCarrierVariables());
        if ($this->isDateSelectForCarrer($params['cart']->id_carrier) == true) {
            global $cookie;
            $this->context->smarty->assign(array(
                'selectedDeliveryDate' => Configuration::get('deliverycallendar_selected_date_' . $cookie->id_cart)
            ));
        }
        return $this->display(__FILE__, 'displayAfterCarrier.tpl');
    }

    private function ajaxSaveCallendarDate($textDate)
    {
        global $cookie;
        echo (bool) Configuration::updateValue($this->name . '_selected_date_' . $cookie->id_cart, $textDate);
        exit();
    }

    public function hookactionValidateOrder(array $params)
    {
        $_order = $params['order'];
        $cart_id = $params['cart']->id;
        $order_id = $_order->getIdByCartId($cart_id);
        if ($this->isDateSelectForOrderCarrer($order_id) == true) {
            $selectedDate = Configuration::get($this->name . '_selected_date_' . $cart_id);
            $this->addDateInfoToOrder($order_id, $selectedDate);
        }

        $this->checkChangeDateOrder($order_id, $cart_id);
    }

    private function checkChangeDateOrder($order_id, $cart_id)
    {
        $ChangeDateProductId = false;

        if ($this->isChangeDateOrder($order_id)) {
            $ChangeDateProductId = (int) $this->getFieldValue('change_date_product');
        }
        if ($this->isChangeDateOrderForFree($order_id)) {
            $ChangeDateProductId = (int) $this->getFieldValue('change_date_product_for_free');
        }

        if ($ChangeDateProductId !== false) {
            $ThisOrder = new Order($order_id);
            $sql = "SELECT cfl.name, cd.value FROM ps_customization c
            INNER JOIN ps_customized_data cd ON cd.id_customization = c.id_customization
            INNER JOIN ps_customization_field_lang cfl ON cd.index = cfl.id_customization_field
            WHERE c.id_cart = " . $cart_id . " AND c.id_product = " . $ChangeDateProductId;
            $order_data = $this->db->executeS($sql);

            $data_to_change_order = array();

            foreach ($order_data as $key => &$row) {
                if ($row['name'] == 'Numer zamówienia') {
                    $data_to_change_order['OrderToChangeID'] = (int) $this->getOrderDataByReference($row['value'])[0]['id_order'];
                }
                if ($row['name'] == 'Nowa Data Dostawy') {
                    $data_to_change_order['new_order_date'] = $row['value'];
                }
                if ($row['name'] == 'Nowa Godzina Dostawy') {
                    $data_to_change_order['new_order_time'] = $row['value'];
                }
            }

            if ($ThisOrder->current_state == 14) {
                $newDateTime = $data_to_change_order['new_order_date'] . ' ' . $data_to_change_order['new_order_time'];
                $this->addDateInfoToOrder($data_to_change_order['OrderToChangeID'], $newDateTime);
            }
        }
    }

    private function isChangeDateOrder($order_id)
    {
        $Order = new Order($order_id);
        $CartProducts = $Order->getCartProducts();
        foreach ($CartProducts as $key => $product) {
            if ($product['id_product'] == (int) $this->getFieldValue('change_date_product')) {
                return true;
            }
        }
        return false;
    }

    private function isChangeDateOrderForFree($order_id)
    {
        $Order = new Order($order_id);
        $CartProducts = $Order->getCartProducts();
        foreach ($CartProducts as $key => $product) {
            if ($product['id_product'] == (int) $this->getFieldValue('change_date_product_for_free')) {
                return true;
            }
        }
        return false;
    }

    private function isDateSelectForOrderCarrer($order_id)
    {
        if (!($carrer_id = $this->getCarrerForOrder($order_id))) {
            return false;
        }

        if ($this->isDateSelectForCarrer($carrer_id) == true) {
            return true;
        }
        return false;
    }

    private function isDateSelectForCarrer($carrer_id)
    {
        $sql = "SELECT id_reference FROM ps_carrier WHERE id_carrier = " . $carrer_id . " LIMIT 1";
        $carrier_id_reference = Db::getInstance()->ExecuteS($sql);
        if (!$carrier_id_reference) return false;
        $carrier_id_reference = $carrier_id_reference[0]['id_reference'];

        if ($this->getFieldValue('callendar_carrer_' . $carrier_id_reference) == true) {
            return true;
        }
        return false;
    }

    private function getCarrerForOrder($order_id)
    {
        $sql = "SELECT id_carrier FROM ps_order_carrier WHERE id_order=" . $order_id;
        $order_carrier = Db::getInstance()->ExecuteS($sql);
        if (!isset($order_carrier[0]['id_carrier'])) return false;
        $order_carrier = $order_carrier[0]['id_carrier'];

        return (int) $order_carrier;
    }

    private function addDateInfoToOrder($order_id, $date)
    {
        $sql = "UPDATE ps_order_carrier SET tracking_number = 'Termin dostawy: " . $date . "' WHERE id_order=" . $order_id;


        if (!Db::getInstance()->execute($sql)) {
            return false;
        }
        return true;
    }

    public function hookdisplayOrderConfirmation(array $params)
    {
        if ($this->isDateSelectForOrderCarrer($params['order']->id) == true) {
            $selectedDate = Configuration::get($this->name . '_selected_date_' . $params['order']->id_cart);
            $this->context->smarty->assign(array(
                'thisSelectedDeliveryDate' =>  $selectedDate
            ));
        }
    }

    public function hookactionOrderStatusPostUpdate(array $params)
    {
        $Order = new Order($params['id_order']);
        $this->checkChangeDateOrder($params['id_order'], $Order->id_cart);
    }

    public function checkOrdersShippingNumber()
    {
        $sql = "SELECT o.id_order, oc.tracking_number FROM ps_order_carrier oc
        INNER JOIN ps_orders o ON o.id_order = oc.id_order
        WHERE oc.tracking_number != '' AND o.shipping_number = ''";

        $notValidOrders = Db::getInstance()->executeS($sql);
        foreach ($notValidOrders as $key => $notValidOrder) {
            $sql = "UPDATE ps_orders SET shipping_number = '" . $notValidOrder['tracking_number'] . "' WHERE id_order = " . $notValidOrder['id_order'];
            if (!Db::getInstance()->execute($sql)) {
                continue;
            }
        }

        $sql = "SELECT o.id_order, oc.tracking_number, o.shipping_number FROM ps_order_carrier oc
        INNER JOIN ps_orders o ON o.id_order = oc.id_order
        WHERE oc.tracking_number != o.shipping_number AND oc.tracking_number != ''";

        $notValidOrders = Db::getInstance()->executeS($sql);
        foreach ($notValidOrders as $key => $notValidOrder) {
            $sql = "UPDATE ps_orders SET shipping_number = '" . $notValidOrder['tracking_number'] . "' WHERE id_order = " . $notValidOrder['id_order'];
            if (!Db::getInstance()->execute($sql)) {
                continue;
            }
        }
    }
}
