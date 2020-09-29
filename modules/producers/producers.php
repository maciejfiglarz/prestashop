<?php

/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2020 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class Producers extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'producers';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Maciej';
        $this->need_instance = 0;
        $this->imagesPath = _PS_BASE_URL_ . '/modules/producers' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Producers');
        $this->description = $this->l('Display producers logos');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->templateFile = 'module:producers/views/templates/front/slider.tpl';
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('PRODUCERS_LIVE_MODE', false);

        return parent::install() && $this->installDB() &&
            $this->registerHook('displayProducers') && $this->registerHook('displayHome');
    }

    public function uninstall()
    {
        Configuration::deleteByName('PRODUCERS_LIVE_MODE');

        return parent::uninstall() && $this->uninstallDB();
    }

    /**
     * Install database.
     */
    public function installDB()
    {
        $return = true;
        $return &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'producers` (
                `id_producers` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `file_name` VARCHAR(100) NOT NULL,
                PRIMARY KEY (`id_producers`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 ;');
        return $return;
    }

    public function uninstallDB()
    {
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'producers`');
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitProducersModule')) == true) {
            $this->postProcess();
        }

        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'producers`';

        // var_dump(Db::getInstance()->executeS($sql));

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        return $output . $this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitProducersModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }


    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Live mode'),
                        'name' => 'PRODUCERS_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in live mode'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Enter a valid email address'),
                        'name' => 'PRODUCERS_ACCOUNT_EMAIL',
                        'label' => $this->l('Email'),
                    ),

                    array(
                        'type' => 'file',
                        'name' => 'file_input',
                        'label' => $this->l('File'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'PRODUCERS_LIVE_MODE' => Configuration::get('PRODUCERS_LIVE_MODE', true),
            'PRODUCERS_ACCOUNT_EMAIL' => Configuration::get('PRODUCERS_ACCOUNT_EMAIL', 'contact@prestashop.com'),
            'PRODUCERS_ACCOUNT_PASSWORD' => Configuration::get('PRODUCERS_ACCOUNT_PASSWORD', null),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();
        $target_dir = $this->imagesPath;
        $target_file = $target_dir . basename($_FILES['file_input']['name']);

        //1
        // if (move_uploaded_file($_FILES['file_input']['tmp_name'], $target_file)) {
        //     echo "The file " . basename($_FILES['file_input']["name"]) . " has been uploaded.";
        // } else {
        //     echo "Sorry, there was an error uploading your file.";
        // }

        $ext = pathinfo($_FILES['file_input']['name'], PATHINFO_EXTENSION);

        if ($error = ImageManager::validateUpload($_FILES['file_input'])) {
            return false;
        } elseif (!($tmpName = tempnam(_PS_TMP_IMG_DIR_, 'PS')) || !move_uploaded_file($_FILES['file_input']['tmp_name'], $tmpName)) {
            return false;
        } elseif (!ImageManager::resize($tmpName, dirname(__FILE__) . '/img/' . md5($_FILES['file_input']['name']))) {
            return false;
        }

        unlink($tmpName);
        Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'producers` (`file_name`) VALUES ("' . md5($_FILES['file_input']['name']) . '.' . $ext . '")');


        // var_dump('tmp', $target_file, $_FILES['file_input']['tmp_name'], dirname(__FILE__));
        // unlink($tmpName);

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }

        // if ($error = ImageManager::validateUpload($file_object)) {
        //     return $error;
        // } else {
        //     $ext = substr($file_name, strrpos($file_name, '.') + 1);
        //     $file_name = md5($file_name) . '.' . $ext;
        //     if (!move_uploaded_file($temp, _PS_MODULE_DIR_ . DIRECTORY_SEPARATOR . 'mypath' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $file_name)) {
        //         return $this->displayError($this->trans('An error occurred while attempting to upload the file.', array(), 'Admin.Notifications.Error'));
        //     } else {
        //         $myfile = _PS_BASE_URL_ . '/modules/mymodule' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $file_name;
        //     }
        // }
    }

    protected function getListContent()
    {
        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'producers`';

        return Db::getInstance()->executeS($sql);
    }

    public function hookDisplayHome()
    {
        $items =  $this->getListContent();
        $i = 0;
        foreach ($items as $item) {
            $items[$i]['preparedUrl'] = $this->getImageURL($item['file_name']);
            $i++;
        }
        // var_dump('items', $items);
        $this->context->smarty->assign('items', $items);
        return $this->display(__FILE__, 'views/templates/front/slider.tpl');
    }

    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
            $this->context->controller->addCSS($this->_path . 'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path . '/views/js/front.js');
        $this->context->controller->addCSS($this->_path . '/views/css/front.css');
    }

    private function getImageURL($image)
    {
        return __PS_BASE_URI__ . 'modules/' . $this->name . '/img/' . $image;
    }
}
