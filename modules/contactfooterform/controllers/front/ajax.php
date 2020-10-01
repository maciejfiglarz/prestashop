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
    }

    public function displayAjax()
    {
        $data = [];
        $data['name'] = Tools::getValue('name');
        $data['email'] = Tools::getValue('email');
        $data['message'] =  Tools::getValue('email');

        $data['name'] = 'test';
        $data['email'] = 'test@wp.pl';
        $data['message'] = 'email';

        $this->sendMail($data);

        die(Tools::JsonEncode(['status' => $data]));
    }

    public function sendMail($data)
    {
        // Mail::Send(2,'test','Sending email test','Salut','maciejfiglarz333@gmail.com','My name');
        Mail::Send(
            (int)(Configuration::get('PS_LANG_DEFAULT')), // defaut language id
            'contact', // email template file to be use
            'Wiadomość z formularza', // email subject
            array(
                '{email}' => Configuration::get('PS_SHOP_EMAIL'), // sender email address
                '{message}' => 'Hello world' // email content
            ),
            Configuration::get('PS_SHOP_EMAIL'), // receiver email address
            NULL, //receiver name
            NULL, //from email address
            'module/contactfooterform/mails'  //from name,
            
        );
    }
}