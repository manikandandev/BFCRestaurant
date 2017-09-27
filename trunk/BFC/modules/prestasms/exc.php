<?php

//define("v_refererID", "");

define("array_lang_names", "english|česky|slovensky|polski|italiano|srpski|русский|español|deutsch|türkçe|português|français|ελληνικά|svenska|العربية");
define("array_langs", "en|cs|sk|pl|it|sr|ru|es|de|tr|pt|fr|el|sv|ar");
 
$pref_lang = Configuration::get('PS_PRESTA_SMS_LANG');

if(!$pref_lang)
{
    include_once(_PS_MODULE_DIR_.'/prestasms/langs/en/lang.php');
}
else
{
    include_once(_PS_MODULE_DIR_.'/prestasms/langs/'.$pref_lang.'/lang.php');
}
?>