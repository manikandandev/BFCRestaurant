<?php

if(isset($_POST['sms_language']) && filter_input(INPUT_POST, "sms_language") != Configuration::get('PS_PRESTA_SMS_LANG'))
{
    Configuration::updateValue('PS_PRESTA_SMS_LANG', filter_input(INPUT_POST, "sms_language"));
}

$context = Context::getContext();

$pref_lang = Configuration::get('PS_PRESTA_SMS_LANG');

if(!$pref_lang)
{
    include_once(_PS_MODULE_DIR_ . '/prestasms/langs/en/lang.php');
}
else
{
    include_once(_PS_MODULE_DIR_ . '/prestasms/langs/' . $pref_lang . '/lang.php');
}

?>