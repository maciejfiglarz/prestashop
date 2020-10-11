<?php

class KPFlagsModifier extends Module
{
    public function __construct()
    {
        $this->name = 'kpflagsmodifier';
        $this->tab = 'front_office';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = array('min' => '1.7.5.0', 'max' => _PS_VERSION_);
        $this->author = 'Krystian Podemski';

        parent::__construct();

        $this->displayName = $this->l('Custom product flags');
        $this->description = $this->l('This module allows you to add extra flags/labels to your products.');
    }

    public function install()
    {
        return parent::install() && $this->registerHook('productFlagsModifier');
    }

    public function hookProductFlagsModifier($params)
    {
        $product = $params['product'];

        if ($product['price_amount'] < 30) {
            $params['flags']['custom_flag'] = [
                'type' => 'my_custom_flag',
                'label' => 'CHEAP',
            ];
        }
    }
}
