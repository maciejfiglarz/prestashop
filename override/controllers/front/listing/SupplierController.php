<?php

class SupplierController extends SupplierControllerCore
{

    /**
     * Assign template vars related to page content.
     *
     * @see FrontController::initContent()
     */
    public function initContent()
    {
        if (Configuration::get('PS_DISPLAY_SUPPLIERS')) {
            parent::initContent();

            if (Validate::isLoadedObject($this->supplier) && $this->supplier->active && $this->supplier->isAssociatedToShop()) {
                $this->assignSupplier();
                $this->label = $this->trans(
                    'List of products by supplier %supplier_name%',
                    array(
                        '%supplier_name%' => $this->supplier->name,
                    ),
                    'Shop.Theme.Catalog'
                );
                $this->doProductSearch(
                    'catalog/listing/supplier',
                    array('entity' => 'supplier', 'id' => $this->supplier->id)
                );
            } else {
                $this->assignAll();
                $this->label = $this->trans(
                    'List of all suppliers',
                    array(),
                    'Shop.Theme.Catalog'
                );
            
                $this->setTemplate('catalog/suppliers', array('entity' => 'suppliers'));

            }
        } else {
            $this->redirect_after = '404';
            $this->redirect();
        }
    }

    /**
     * Assign template vars if displaying the supplier list.
     */
    protected function assignAll()
    {
        $suppliersData = $this->getTemplateVarSuppliers();
        $suppliersVar =  $suppliersData['suppliers_for_display'];
        $voivodeships = $suppliersData['voivodeships'];

        if (!empty($suppliersVar)) {
            foreach ($suppliersVar as $k => $supplier) {
                $filteredSupplier = Hook::exec(
                    'filterSupplierContent',
                    array('object' => $supplier),
                    $id_module = null,
                    $array_return = false,
                    $check_exceptions = true,
                    $use_push = false,
                    $id_shop = null,
                    $chain = true
                );
                if (!empty($filteredSupplier['object'])) {
                    $suppliersVar[$k] = $filteredSupplier['object'];
                }
            }
        }

        $this->context->smarty->assign(array(
            'brands' => $suppliersVar,
            'voivodeships' => $voivodeships
        ));
    }
    public function getTemplateVarSuppliers()
    {
        $suppliers = Supplier::getSuppliers(true, $this->context->language->id, true);
        $suppliers_for_display = array();
        $voivodeships = array();

        foreach ($suppliers as $supplier) {
            $suppliers_for_display[$supplier['id_supplier']] = $supplier;
            $suppliers_for_display[$supplier['id_supplier']]['text'] = $supplier['description'];
            $suppliers_for_display[$supplier['id_supplier']]['image'] = $this->context->link->getSupplierImageLink($supplier['id_supplier'], 'small_default');
            $suppliers_for_display[$supplier['id_supplier']]['url'] = $this->context->link->getsupplierLink($supplier['id_supplier']);
            $suppliers_for_display[$supplier['id_supplier']]['nb_products'] = $supplier['nb_products'] > 1
                ? $this->trans('%number% products', array('%number%' => $supplier['nb_products']), 'Shop.Theme.Catalog')
                : $this->trans('%number% product', array('%number%' => $supplier['nb_products']), 'Shop.Theme.Catalog');
            $address = new Address(Address::getAddressIdBySupplierId($supplier['id_supplier']));
            $suppliers_for_display[$supplier['id_supplier']]['address2'] = $address->address2;
            $voivodeships[$supplier['id_supplier']] = $address->address2;
        }
        $voivodeships = array_unique($voivodeships);
        return ['suppliers_for_display' => $suppliers_for_display, 'voivodeships' => $voivodeships];
    }
}
