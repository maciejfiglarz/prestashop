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
$autoloadPath = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}
include_once(__DIR__ . '/RewardsModel.php');

use Symfony\Component\HttpFoundation\Request;

class Rewards extends Module
{
    protected $config_form = false;
    protected $_html = '';
    protected $templateFile;

    public function __construct()
    {
        $this->name = 'rewards';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Maciej Figlarz';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Display Rewards');
        $this->description = $this->l('Display rewards on homepage');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->templateFile = 'module:ps_rewards/views/templates/hook/slider.tpl';
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('REWARDS_LIVE_MODE', false);
        Configuration::updateValue('REWARDS_TITLE', 'Nasze nagrody');

        $this->createTables();
        return parent::install() &&
            $this->registerHook('displayHomeBottom') &&
            $this->registerHook('backOfficeHeader');
    }

    public function uninstall()
    {
        Configuration::deleteByName('REWARDS_LIVE_MODE');
        Configuration::deleteByName('REWARDS_TITLE');
        $this->deleteTables();
        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        $errors = array();
        $shop_context = Shop::getContext();
        if (((bool)Tools::isSubmit('submitRewardsModule')) == true) {
            $this->postProcess();
        } else if (Tools::isSubmit('addReward')) {
            $this->_html .= $this->renderAddForm();
        } else if (Tools::getValue('edit')) {
            $this->_html .= $this->renderAddForm();
        } else if (Tools::isSubmit('submitReward')) {
            if (Tools::getValue('id_reward')) {
                $reward = new RewardsModel((int)Tools::getValue('id_reward'));
                if (!Validate::isLoadedObject($reward)) {
                    $this->_html .= $this->displayError($this->getTranslator()->trans('Invalid slide ID', array(), 'Modules.Imageslider.Admin'));
                    return false;
                }
            } else {
                $reward = new RewardsModel();
                /* Sets position */
                $reward->position = (int)$this->getNextPosition();
            }
            $reward->active = 1;
            $reward->title = Tools::getValue('title');
            $type = Tools::strtolower(Tools::substr(strrchr($_FILES['image']['name'], '.'), 1));
            $imagesize = @getimagesize($_FILES['image']['tmp_name']);

            if (
                isset($_FILES['image']) &&
                isset($_FILES['image']['tmp_name']) &&
                !empty($_FILES['image']['tmp_name']) &&
                !empty($imagesize) &&
                in_array(
                    Tools::strtolower(Tools::substr(strrchr($imagesize['mime'], '/'), 1)),
                    array(
                        'jpg',
                        'gif',
                        'jpeg',
                        'png'
                    )
                ) &&
                in_array($type, array('jpg', 'gif', 'jpeg', 'png'))
            ) {
                $temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
                $salt = sha1(microtime());
                if ($error = ImageManager::validateUpload($_FILES['image'])) {
                    $errors[] = $error;
                    dump('errors', $errors);
                } elseif (!$temp_name || !move_uploaded_file($_FILES['image']['tmp_name'], $temp_name)) {
                    dump('not upload');
                    return false;
                } elseif (!ImageManager::resize($temp_name, __DIR__ . '/images/' . $salt . '_' . $_FILES['image']['name'], null, null, $type)) {
                    dump('not resie');
                    $errors[] = $this->displayError($this->getTranslator()->trans('An error occurred during the image upload process.', array(), 'Admin.Notifications.Error'));
                }
                $reward->image = $salt . '_' . $_FILES['image']['name'];
            } elseif (Tools::getValue('image_old') != '') {
                $reward->image = Tools::getValue('image_old');
            }

            /* Processes if no errors  */
            if (!$errors) {
                /* Adds */
                if (!Tools::getValue('id_reward')) {
                    if (!$reward->add()) {
                        $errors[] = $this->displayError($this->getTranslator()->trans('The slide could not be added.', array(), 'Modules.Imageslider.Admin'));
                    }
                } elseif (!$reward->update()) {
                    $errors[] = $this->displayError($this->getTranslator()->trans('The slide could not be updated.', array(), 'Modules.Imageslider.Admin'));
                }
                $this->clearCache();
            }

            $this->clearCache();
            $this->_html .= $this->renderForm();
            $this->_html .= $this->renderList();
        } else if ((Tools::isSubmit('changeStatus') && Tools::isSubmit('id_reward'))) {

            $slide = new RewardsModel((int)Tools::getValue('id_reward'));
            $slide->active == 0 ? $slide->active = 1 : $slide->active = 0;
            $res = $slide->update();
            $this->clearCache();
            $this->_html .= ($res ? $this->displayConfirmation($this->getTranslator()->trans('Configuration updated', array(), 'Admin.Notifications.Success')) : $this->displayError($this->getTranslator()->trans('The configuration could not be updated.', array(), 'Modules.Imageslider.Admin')));
            $this->_html .= $this->renderAddForm();
        } elseif (Tools::isSubmit('delete_id_reward')) {
            $slide = new RewardsModel((int)Tools::getValue('delete_id_reward'));
            $res = $slide->delete();
            $this->clearCache();
            if (!$res) {
                $this->_html .= $this->displayError('Could not delete.');
            } else {
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true) . '&conf=1&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name);
            }
        } else {
            $this->_html .= $this->renderForm();
            $this->_html .= $this->renderList();
        }

        // $this->context->smarty->assign('module_dir', $this->_path);

        // $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        // return $output . $this->renderForm();

        return $this->_html;
    }

    protected function renderList()
    {
        $rewards = $this->getRewards();
        foreach ($rewards as $key => $reward) {
            $rewards[$key]['status'] = $this->displayStatus($reward['id_reward'], $reward['active']);
        }

        $this->context->smarty->assign(
            array(
                'link' => $this->context->link,
                'rewards' => $rewards,
                'image_baseurl' => $this->_path . 'images/'
            )
        );
        return $this->display(__FILE__, 'list.tpl');
    }

    protected function renderAddForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->getTranslator()->trans('Informacje o nagrodzie', array(), 'Modules.Imageslider.Admin'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'file',
                        'label' => $this->getTranslator()->trans('Image', array(), 'Admin.Global'),
                        'name' => 'image',
                        'required' => true,
                        'desc' => $this->getTranslator()->trans('Maximum image size: %s.', array(ini_get('upload_max_filesize')), 'Admin.Global')
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans('Title', array(), 'Admin.Global'),
                        'name' => 'title',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans('Enabled', array(), 'Admin.Global'),
                        'name' => 'active',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->getTranslator()->trans('Yes', array(), 'Admin.Global')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->getTranslator()->trans('No', array(), 'Admin.Global')
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->getTranslator()->trans('Save', array(), 'Admin.Actions'),
                )
            ),
        );

        if (Tools::isSubmit('id_reward')) {
            $reward = new RewardsModel((int)Tools::getValue('id_reward'));
            // $fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_reward');
            $fields_form['form']['images'] = $reward->image;

            $has_picture = true;

            if ($has_picture) {
                $fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'has_picture');
            }
        }

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitReward';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->tpl_vars = array(
            'base_url' => $this->context->shop->getBaseURL(),
            'language' => array(
                'id_lang' => $language->id,
                'iso_code' => $language->iso_code
            ),
            'fields_value' => $this->getAddFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'image_baseurl' => $this->_path . 'images/'
        );
        $helper->override_folder = '/';


        return $helper->generateForm(array($fields_form));
    }


    public function getAddFieldsValues()
    {
        $fields = array();


        if (Tools::getValue('id_reward')) {
            $reward = new RewardsModel((int)Tools::getValue('id_reward'));
        } else {
            $reward = new RewardsModel();
        }

        $fields['title'] = Tools::getValue('title', $reward->title);
        $fields['active'] = Tools::getValue('title', $reward->active);
        $fields['image'] = Tools::getValue('image', $reward->image);
        $fields['has_picture'] = true;

        // if (Tools::isSubmit('id_slide') && $this->slideExists((int)Tools::getValue('id_slide'))) {
        //     $slide = new Ps_HomeSlide((int)Tools::getValue('id_slide'));
        //     $fields['id_slide'] = (int)Tools::getValue('id_slide', $slide->id);
        // } else {
        //     $slide = new Ps_HomeSlide();
        // }

        // $fields['active_slide'] = Tools::getValue('active_slide', $slide->active);
        // $fields['has_picture'] = true;

        // $languages = Language::getLanguages(false);

        // foreach ($languages as $lang) {
        //     $fields['image'][$lang['id_lang']] = Tools::getValue('image_'.(int)$lang['id_lang']);
        //     $fields['title'][$lang['id_lang']] = Tools::getValue('title_'.(int)$lang['id_lang'], $slide->title[$lang['id_lang']]);
        //     $fields['url'][$lang['id_lang']] = Tools::getValue('url_'.(int)$lang['id_lang'], $slide->url[$lang['id_lang']]);
        //     $fields['legend'][$lang['id_lang']] = Tools::getValue('legend_'.(int)$lang['id_lang'], $slide->legend[$lang['id_lang']]);
        //     $fields['description'][$lang['id_lang']] = Tools::getValue('description_'.(int)$lang['id_lang'], $slide->description[$lang['id_lang']]);
        // }

        return $fields;
    }

    public function getNextPosition()
    {
        $row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
            SELECT MAX(`position`) AS `next_position`
            FROM `' . _DB_PREFIX_ . 'rewards`');
        return (++$row['next_position']);
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
        $helper->submit_action = 'submitRewardsModule';
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
                        'name' => 'REWARDS_LIVE_MODE',
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
                        'type' => 'text',
                        'label' => '',
                        'name' => 'REWARDS_TITLE',
                        // 'class' => 'fixed-width-large',
                        'desc' => $this->trans('Wpisz tutuł, który będzie widoczny nad sekcją'),
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
            'REWARDS_LIVE_MODE' => Configuration::get('REWARDS_LIVE_MODE'),
            'REWARDS_TITLE' => Configuration::get('REWARDS_TITLE'),
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
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path . '/views/js/front.js');
        $this->context->controller->addCSS($this->_path . '/views/css/front.css');
    }

    /**
     * Get rewards
     */

    protected function getRewards()
    {
        return  Db::getInstance()->executeS('
        SELECT r.`id_reward`, r.`position`, r.`active`, r.`image`, r.`title`
        FROM `' . _DB_PREFIX_ . 'rewards` r ORDER BY r.`id_reward` DESC');
    }

    /**
     * Creates tables
     */
    protected function createTables()
    {
        /* Slides configuration */
        $res = Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'rewards` (
              `id_reward` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `position` int(10) unsigned NOT NULL DEFAULT \'0\',
              `active` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
              `image` varchar(255) NOT NULL,
              `title` varchar(255) NOT NULL,
              PRIMARY KEY (`id_reward`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');

        return $res;
    }

    /**
     * deletes tables
     */
    protected function deleteTables()
    {
        return Db::getInstance()->execute('
            DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'rewards`;
        ');
    }
    public function clearCache()
    {
        $this->_clearCache($this->templateFile);
    }
    public function displayStatus($id_rewarde, $active)
    {
        $title = ((int)$active == 0 ? $this->getTranslator()->trans('Disabled', array(), 'Admin.Global') : $this->getTranslator()->trans('Enabled', array(), 'Admin.Global'));
        $icon = ((int)$active == 0 ? 'icon-remove' : 'icon-check');
        $class = ((int)$active == 0 ? 'btn-danger' : 'btn-success');
        $html = '<a class="btn ' . $class . '" href="' . AdminController::$currentIndex .
            '&configure=' . $this->name .
            '&token=' . Tools::getAdminTokenLite('AdminModules') .
            '&changeStatus&id_reward=' . (int)$id_rewarde . '" title="' . $title . '"><i class="' . $icon . '"></i> ' . $title . '</a>';

        return $html;
    }
}
