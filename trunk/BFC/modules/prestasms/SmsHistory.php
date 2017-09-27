<?php
define("SMS_CONTROLER_URL", _PS_MODULE_DIR_.'/prestasms/includes/model/');
require_once(_PS_MODULE_DIR_ . '/prestasms/includes/model/sms.php');
include_once(_PS_MODULE_DIR_.'/prestasms/includes/controller/history.php');
include_once(_PS_MODULE_DIR_.'/prestasms/exc.php'); 

class SmsHistory extends AdminController
{
    public $sms;

    public function __construct()
    {
        $this->bootstrap = true;
        $this->display = 'view';

        $this->sms = new ControllerSmsHistory();
        parent::__construct();
    }

    public function renderView()
    {
        return "<link rel=\"stylesheet\" type=\"text/css\" href=\"../modules/prestasms/css/jquery.datetimepicker.css\">" . "<link rel=\"stylesheet\" type=\"text/css\" href=\"../modules/prestasms/css/style.css\">" . "<div class=\"smsTab\">".$this->sms->display()."</div>";
    }

    public function initPageHeaderToolbar()
    {
        $this->page_header_toolbar_title = v_adminsmsprofile_editaccount;

        parent::initPageHeaderToolbar();

        $this->context->smarty->assign('help_link', 'http://www.presta-sms.com/module-activation.html');

        array_pop($this->meta_title);
    }
}
