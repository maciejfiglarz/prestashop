<?php

use Symfony\Component\HttpFoundation\JsonResponse;

class ContactFooterFormAjaxModuleFrontController extends ModuleFrontController
{
    public function __construct()
	{
		parent::__construct();
	}

	public function init()
	{
		parent::init();
	}

    public function initContent()
    {
        return $this->setTemplate("module:contactfooterform/views/templates/front/ajax.tpl");
        //  return  new JsonResponse(['data' => 123]);
    }
    public function postProcess()
    {
        die(Tools::JsonEncode(['test'=>Tools::getValue('bar')]));
        
    }
}


// http://localhost/prestashopn/?fc=module&module=contactfooterform&controller=ajax&id_lang=1