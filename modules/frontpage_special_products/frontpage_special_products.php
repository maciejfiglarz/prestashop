<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

// use PrestaShop\Module\FacetedSearch\Hook\Product;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\Entity\Product as EntityProduct;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;

class frontpage_special_products extends Module
{
    public function __construct()
    {
        $this->name = 'frontpage_special_products';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Piotr Sułkowski | MilleniumStudio';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Wyróżnione produkty na stronie głównej u góry');
        $this->description = $this->l('');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        $this->default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $this->db = Db::getInstance();
        $this->context = Context::getContext();
        $this->lang = $this->context->language->id;
        $this->helper = new HelperForm();
        $this->errors = array();
        $this->notifications = array();
        $this->panels = array();
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (
            !parent::install() || !$this->registerHook('displayHome')
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
        $this->initFieldsForm(0, 'Wyróżnione produkty u góry', 'Zapisz');
        $this->addFieldToForm(0, 'text', 'product_id_1', 'ID produktu 1', 10, false);
        $this->addFieldToForm(0, 'text', 'product_id_2', 'ID produktu 2', 10, false);
        $this->addFieldToForm(0, 'text', 'product_id_3', 'ID produktu 3', 10, false);
        $this->addFieldToForm(0, 'text', 'product_id_4', 'ID produktu 4', 10, false);
        $this->addFieldToForm(0, 'text', 'product_id_5', 'ID produktu 5', 10, false);
        $this->addFieldToForm(0, 'text', 'product_id_6', 'ID produktu 6', 10, false);
        $this->addFieldToForm(0, 'text', 'product_id_7', 'ID produktu 7', 10, false);
        $this->addFieldToForm(0, 'text', 'product_id_8', 'ID produktu 8', 10, false);
        $this->addFieldToForm(0, 'text', 'product_id_9', 'ID produktu 9', 10, false);
        $this->addFieldToForm(0, 'text', 'product_id_10', 'ID produktu 10', 10, false);
        $output_forms .= $this->generateForm('custom_form');

        return $output_forms;
    }

    private function getdisplayHomeVariables()
    {
        $link = new Link();

        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new ProductListingPresenter(
            new ImageRetriever(
                $this->context->link
            ),
            $this->context->link,
            new PriceFormatter(),
            new ProductColorsRetriever(),
            $this->context->getTranslator()
        );
        $assembler = new ProductAssembler($this->context);

        $query = '';
        for ($i = 1; $i <= 10; $i++) {
            $idString = 'product_id_' . $i;
            $id = $this->getFieldValue($idString);
            $query .= $i == 1 ? ' p.id_product = ' . $id :  ' OR p.id_product = ' . $id;
        }
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('product', 'p');

        $sql->where($query);

        $result = Db::getInstance()->executeS($sql);
        $preparedProducts = [];
        foreach ($result as $rawProduct) {
            $preparedProducts[] =  $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $this->context->language
            );
        }

        return array(
            'frontpage_special_products' => $preparedProducts
        );
    }

    public function hookdisplayHome($params)
    {
        $this->smarty->assign($this->getdisplayHomeVariables());
        return $this->display(__FILE__, 'displayHome.tpl');
    }
}
