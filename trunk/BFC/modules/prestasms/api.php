<?php
error_reporting(E_ALL ^ E_NOTICE);ini_set('error_reporting', E_ALL ^ E_NOTICE);
    
define('IS_ADMIN_FLAG', false);

include_once(dirname(__FILE__).'/../../config/config.inc.php');
include_once(dirname(__FILE__).'/../../config/setting.inc.php');

include_once('includes/model/smsAdapter.php');
include_once('includes/model/sms.php');
include_once('includes/model/variables.php');

class ControllerSmsApi
{
    public function __construct()
    {
        $this->index();
    }                  

    public function index()
    {
        die("DISABLED");
                         
        $to = $this->getVar("to");
        $text = $this->getVar("text");
        $unicode = $this->getVar("unicode");
        $type = $this->getVar("type");
        $transaction = $this->getVar("transaction");
        
        if(isset($to) && strlen($to) > 4 && strlen($text) > 0)
        {   
            $sms = new SmsModel(true, SmsModel::TYPE_SIMPLE, $type, ($transaction ? SmsModel::SMS_TRANSACTION : SmsModel::SMS_BULK));
            
            $sms->number($to)->text($text)->unicode($unicode)->send();

            if(!$sms->isError())
            {
                echo "SMSSTATUS:OK";
            }
            else
            {
                echo "SMSSTATUS:ERROR";
            }
        }
        else
        {
            echo "SMSSTATUS:ERROR";
        }
    }
    
    private function getVar($var)
    {
        if(filter_input(INPUT_POST, $var))
        {
            return filter_input(INPUT_POST, $var);
        }
        elseif(filter_input(INPUT_GET, $var))
        {
            return filter_input(INPUT_GET, $var);
        }
        else
        {
            return null;
        }
    }
}

new ControllerSmsApi();
  
?>