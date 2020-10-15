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

class Footercontactinfo extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'footercontactinfo';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Maciej Figlarz';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Dane kontaktowe w stopce');
        $this->description = $this->l('Wyświetla i edytuje dane kontaktowe w stopce');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('FOOTERCONTACTINFO_LIVE_MODE', false);
        Configuration::updateValue('FOOTERCONTACTINFO_INFO1', 'Kraków, 30-732
        ul.Konstantego Brandla 3
        ');
        Configuration::updateValue('FOOTERCONTACTINFO_INFO2', 'info@faro.com.pl
        12 255 50 00
        ');

        Configuration::updateValue('FOOTERCONTACTINFO_SUPPORT', '+48 255 50 26
        handlowy@faro.com.pl
        ');
        Configuration::updateValue('FOOTERCONTACTINFO_BILL', '+48 12 255 50 31
        faktury@faro.com.pl
         ');

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayFooterContactInfo');
    }

    public function uninstall()
    {
        Configuration::deleteByName('FOOTERCONTACTINFO_LIVE_MODE');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitFootercontactinfoModule')) == true) {
            $this->postProcess();
        }

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
        $helper->submit_action = 'submitFootercontactinfoModule';
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
                        'col' => 4,
                        'type' => 'textarea',
                        'prefix' => '<i class="icon icon-info"></i>',
                        'name' => 'FOOTERCONTACTINFO_INFO1',
                        'label' => 'Kontakt - pierwsze pole',
                    ),
                    array(
                        'col' => 4,
                        'type' => 'textarea',
                        'prefix' => '<i class="icon icon-info"></i>',
                        'name' => 'FOOTERCONTACTINFO_INFO2',
                        'label' => 'Kontakt - drugie pole',
                    ),
                    array(
                        'col' => 4,
                        'type' => 'textarea',
                        'prefix' => '<i class="icon icon-info"></i>',
                        'label' => 'Obsługa klienta',
                        'name' => 'FOOTERCONTACTINFO_SUPPORT',
                    ),
                    array(
                        'col' => 4,
                        'type' => 'textarea',
                        'prefix' => '<i class="icon icon-info"></i>',
                        'label' => 'Dział faktur',
                        'name' => 'FOOTERCONTACTINFO_BILL',
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
            'FOOTERCONTACTINFO_LIVE_MODE' => Configuration::get('FOOTERCONTACTINFO_LIVE_MODE', true),
            'FOOTERCONTACTINFO_INFO1' => Configuration::get('FOOTERCONTACTINFO_INFO1'),
            'FOOTERCONTACTINFO_INFO2' => Configuration::get('FOOTERCONTACTINFO_INFO2'),

            'FOOTERCONTACTINFO_SUPPORT' => Configuration::get('FOOTERCONTACTINFO_SUPPORT'),
            'FOOTERCONTACTINFO_BILL' => Configuration::get('FOOTERCONTACTINFO_BILL')
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
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
     * Add the CSS & JavaScript files you want to be added on the FO.f
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path . '/views/js/front.js');
        $this->context->controller->addCSS($this->_path . '/views/css/front.css');
    }

    public function hookDisplayFooterContactInfo()
    {
        $this->context->smarty->assign(array(
            'bill' => Configuration::get('FOOTERCONTACTINFO_BILL'),
            'support' => Configuration::get('FOOTERCONTACTINFO_SUPPORT'),
            'info1' => Configuration::get('FOOTERCONTACTINFO_INFO1'),
            'info2' => Configuration::get('FOOTERCONTACTINFO_INFO2')
        ));

        return $this->display(__FILE__, 'views/templates/front/front.tpl');
    }
}
