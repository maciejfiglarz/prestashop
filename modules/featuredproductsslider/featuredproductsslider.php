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

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchResult;

if (!defined('_PS_VERSION_')) {
    exit;
}


class Featuredproductsslider extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'featuredproductsslider';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Maciej';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Featured Products Slider');
        $this->description = $this->l('Display featured products slider');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->templateFile = 'module:featuredproductsslider/views/templates/front/list.tpl';

        $this->allCategories = Category::getAllCategoriesName(null, $this->context->language->id);

        $this->tabs = ['TAB1', 'TAB2', 'TAB3'];
    }

    /**
     * Install module
     */
    public function install()
    {
        Configuration::updateValue('FEATUREDPRODUCTSSLIDER_LIVE_MODE', false);


        foreach ($this->tabs as $tab) {
            Configuration::updateValue('FEATUREDPRODUCTSSLIDER_TITLE_' . $tab, 'Najnowsze');
            
            // FEATURE, CATEGORY
            Configuration::updateValue('FEATUREDPRODUCTSSLIDER_TYPE_SELECTOR_' . $tab, 'FEATURE');

            //NEWEST, BESTSELLERS, SALE. RANDOM
            Configuration::updateValue('FEATUREDPRODUCTSSLIDER_TYPE_FEATURE_' . $tab, 'NEWEST');
            Configuration::updateValue('FEATUREDPRODUCTSSLIDER_TYPE_CATEGORY_' . $tab, 2);
            Configuration::updateValue('FEATUREDPRODUCTSSLIDER_NBR_' . $tab, 8);
        }

        // Configuration::updateValue('FEATUREDPRODUCTSSLIDER_TITLE', 'Najnowsze');

        // // FEATURE, CATEGORY
        // Configuration::updateValue('FEATUREDPRODUCTSSLIDER_TYPE_SELECTOR', 'FEATURE');

        // //NEWEST, BESTSELLERS, SALE. RANDOM
        // Configuration::updateValue('FEATUREDPRODUCTSSLIDER_TYPE_FEATURE', 'NEWEST');
        // // Configuration::updateValue('FEATUREDPRODUCTSSLIDER_TYPE_CATEGORY', Context::getContext()->shop->getCategory());
        // Configuration::updateValue('FEATUREDPRODUCTSSLIDER_TYPE_CATEGORY', 2);
        // Configuration::updateValue('FEATUREDPRODUCTSSLIDER_NBR', 8);

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayHome') &&
            $this->registerHook('displayHomeSlider1') &&
            $this->registerHook('displayHomeSlider2') &&
            $this->registerHook('displayHomeSlider3') &&
            $this->registerHook('displayHomeSlider4');
    }

    public function uninstall()
    {
        Configuration::deleteByName('FEATUREDPRODUCTSSLIDER_LIVE_MODE');

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
        if (((bool)Tools::isSubmit('submitFeaturedProductsSliderModuleTAB1')) == true) {
            $this->postProcess('TAB1');
        }
        if (((bool)Tools::isSubmit('submitFeaturedProductsSliderModuleTAB2')) == true) {
            $this->postProcess('TAB2');
        }
        if (((bool)Tools::isSubmit('submitFeaturedProductsSliderModuleTAB3')) == true) {
            $this->postProcess('TAB3');
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');
        $renderedForms = '';
        foreach ($this->tabs as $tab) {
            $renderedForms .= $this->renderForm($tab);
        }

        return $output . $renderedForms;
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm($tab)
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitFeaturedProductsSliderModule' . $tab;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues($tab), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );
        dump('fields',$this->getConfigFormValues($tab));
        return $helper->generateForm(array($this->getConfigForm($tab)));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm($tab)
    {

        $preparedCategory = [];
        foreach ($this->allCategories as $index => $category) {
            $preparedCategory[] = ['key' => $index, 'name' => $category['name']];
        }

        // array_push($preparedCategory, ['key' => 'no-category', 'name' => 'Bez kategorii']);

        // dump(Configuration::get('FEATUREDPRODUCTSSLIDER_NBR'));
        // dump(Configuration::get('FEATUREDPRODUCTSSLIDER_TYPE_SELECTOR'));
        // dump(Configuration::get('FEATUREDPRODUCTSSLIDER_TYPE_FEATURE'));
        // dump(Configuration::get('FEATUREDPRODUCTSSLIDER_TYPE_CATEGORY'));

        return array(
            'form' => array(
                'legend' => array(
                    'title' => 'Ustawienia',
                    'icon' => 'icon-cogs',
                ),

                'input' => array(
                    // array(
                    //     'type' => 'switch',
                    //     'label' => 'Widoczny',
                    //     'name' => 'FEATUREDPRODUCTSSLIDER_LIVE_MODE',
                    //     'is_bool' => true,
                    //     'desc' => $this->l('Aktywuj lub dezaktywuj wtyczkę'),
                    //     'values' => array(
                    //         array(
                    //             'id' => 'active_on',
                    //             'value' => true,
                    //             'label' => $this->l('Enabled')
                    //         ),
                    //         array(
                    //             'id' => 'active_off',
                    //             'value' => false,
                    //             'label' => $this->l('Disabled')
                    //         )
                    //     ),
                    // ),
                    // array(
                    //     'type' => 'hidden',
                    //     'label' => 'Wpisz tytuł sekcji',
                    //     'name' => 'FEATUREDPRODUCTSSLIDER_TAB',
                    //     'class' => 'fixed-width-lg',
                    //     'desc' => 'Ta wartość pojawi się nad sliderem',
                    // ),
                    array(
                        'type' => 'text',
                        'label' => 'Wpisz tytuł sekcji',
                        'name' => 'FEATUREDPRODUCTSSLIDER_TITLE_' . $tab,
                        'class' => 'fixed-width-lg',
                        'desc' => 'Ta wartość pojawi się nad sliderem',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans('Number of products to be displayed', array(), 'Modules.Featuredproducts.Admin'),
                        'name' => 'FEATUREDPRODUCTSSLIDER_NBR_' . $tab,
                        'class' => 'fixed-width-xs',
                        'desc' => $this->trans('Set the number of products that you would like to display on homepage (default: 8).', array(), 'Modules.Featuredproducts.Admin'),
                    ),
                    array(
                        'type' => 'radio',
                        'label' => 'Pokaż według',
                        'name' => 'FEATUREDPRODUCTSSLIDER_TYPE_SELECTOR_' . $tab,
                        // 'desc' => $this->l('Use this module in live mode'),
                        'values' => array(
                            array(
                                'id' => 'active_off',
                                'value' => 'CATEGORY',
                                'label' => 'Kategorii'
                            ),
                            array(
                                'id' => 'active_on',
                                'value' => 'FEATURE',
                                'label' => 'Właściwości'
                            ),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Cechy'),
                        'name' => 'FEATUREDPRODUCTSSLIDER_TYPE_FEATURE_' . $tab,
                        'multiple' => false,
                        'options' => array(
                            'query' => array(
                                array('key' => 'NEWEST', 'name' => 'Najnowsze'),
                                array('key' => 'BESTSELLERS', 'name' => 'Bestsellery'),
                                array('key' => 'SALE', 'name' => 'Wyprzedaże'),
                                array('key' => 'RANDOM', 'name' => 'Losowe'),
                            ),
                            'id' => 'key',
                            'name' => 'name',

                        )
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Kategorie'),
                        'name' => 'FEATUREDPRODUCTSSLIDER_TYPE_CATEGORY_' . $tab,
                        'multiple' => false,
                        'options' => array(
                            'query' => $preparedCategory,
                            'id' => 'key',
                            'name' => 'name'
                        ),
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
    protected function getConfigFormValues($tab)
    {
        return array(
            'FEATUREDPRODUCTSSLIDER_LIVE_MODE' => Configuration::get('FEATUREDPRODUCTSSLIDER_LIVE_MODE', true),
            'FEATUREDPRODUCTSSLIDER_NBR_' . $tab => Configuration::get('FEATUREDPRODUCTSSLIDER_NBR_' . $tab),
            'FEATUREDPRODUCTSSLIDER_TYPE_SELECTOR_' . $tab => Configuration::get('FEATUREDPRODUCTSSLIDER_TYPE_SELECTOR_' . $tab),
            'FEATUREDPRODUCTSSLIDER_TYPE_FEATURE_' . $tab => Configuration::get('FEATUREDPRODUCTSSLIDER_TYPE_FEATURE_' . $tab),
            'FEATUREDPRODUCTSSLIDER_TYPE_CATEGORY_' . $tab => Configuration::get('FEATUREDPRODUCTSSLIDER_TYPE_CATEGORY_' . $tab),
            'FEATUREDPRODUCTSSLIDER_TITLE_' . $tab => Configuration::get('FEATUREDPRODUCTSSLIDER_TITLE_' . $tab)
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess($tab)
    {
        $form_values = $this->getConfigFormValues($tab);
        dump('$form_values', $form_values);
        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookBackOfficeHeader()
    {
        // if (Tools::getValue('module_name') == $this->name) {
        $this->context->controller->addJS($this->_path . 'views/js/back.js');
        $this->context->controller->addCSS($this->_path . 'views/css/back.css');
        // }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookDisplayHeader()
    {
        $this->context->controller->addJS($this->_path . '/views/js/front.js');
        $this->context->controller->addCSS($this->_path . '/views/css/front.css');
    }

    protected function displaySlide()
    {
        $variables = $this->prepareProducts();
        // if (!$this->isCached($this->templateFile, $this->getCacheId('ps_featuredproducts'))) {
        // var_dump('variables',$variables);
        // if (empty($variables)) {
        $this->smarty->assign($variables);
        // }

        // }

        return $this->fetch($this->templateFile, $this->getCacheId('ps_featuredproducts'));
    }
    public function hookDisplayHomeBottom()
    {
        return $this->displaySlide();
    }

    public function hookDisplayHomeSlider1()
    {
        return $this->displaySlide();
    }

    public function hookDisplayHomeSlider3()
    {
        return $this->displaySlide();
    }


    protected function prepareProducts()
    {
        $products = $this->getProducts();
        if (!empty($products)) {
            return array(
                'products' => $products,
                'title' => Configuration::get('FEATUREDPRODUCTSSLIDER_TITLE'),
                'allProductsLink' => Context::getContext()->link->getCategoryLink($this->getConfigFieldsValues()['HOME_FEATURED_CAT']),
            );
        }

        return false;
    }

    protected function getProducts()
    {

        $category = new Category((int) Configuration::get('FEATUREDPRODUCTSSLIDER_TYPE_CATEGORY'));

        $searchProvider = new CategoryProductSearchProvider(
            $this->context->getTranslator(),
            $category
        );

        $context = new ProductSearchContext($this->context);

        $query = new ProductSearchQuery();

        $nProducts = Configuration::get('FEATUREDPRODUCTSSLIDER_NBR');

        if ($nProducts < 0) {
            $nProducts = 12;
        }
        $query
            ->setResultsPerPage($nProducts)
            ->setPage(1);

        if (Configuration::get('FEATUREDPRODUCTSSLIDER_TYPE_FEATURE') == 'RANDOM') {
            // dump('random');
            $query->setSortOrder(SortOrder::random());
        } else if (Configuration::get('FEATUREDPRODUCTSSLIDER_TYPE_FEATURE') == 'SALE') {
            // dump('sale');
            $query
                ->setQueryType('prices-drop')
                ->setSortOrder(new SortOrder('product', 'name', 'asc'));
        } else if (Configuration::get('FEATUREDPRODUCTSSLIDER_TYPE_FEATURE') == 'BESTSELLERS') {
            // dump('BESTSELLERS');
            $query
                ->setQueryType('best-sales')
                ->setSortOrder(new SortOrder('product', 'name', 'asc'));
        } else {
            // dump('newest');
            $query
                ->setQueryType('new-products')
                ->setSortOrder(new SortOrder('product', 'date_add', 'desc'));
        }

        $result = $searchProvider->runQuery(
            $context,
            $query
        );

        $assembler = new ProductAssembler($this->context);

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

        $products_for_template = [];

        foreach ($result->getProducts() as $rawProduct) {
            $products_for_template[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $this->context->language
            );
        }
        return $products_for_template;
    }
    public function getConfigFieldsValues()
    {
        return array(
            'HOME_FEATURED_NBR' => Tools::getValue('HOME_FEATURED_NBR', (int) Configuration::get('HOME_FEATURED_NBR')),
            'HOME_FEATURED_CAT' => Tools::getValue('HOME_FEATURED_CAT', (int) Configuration::get('HOME_FEATURED_CAT')),
            'HOME_FEATURED_RANDOMIZE' => Tools::getValue('HOME_FEATURED_RANDOMIZE', (bool) Configuration::get('HOME_FEATURED_RANDOMIZE')),
        );
    }
}
