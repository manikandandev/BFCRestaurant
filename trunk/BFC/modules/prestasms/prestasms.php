<?php

error_reporting(E_ALL ^ E_NOTICE);
ini_set('error_reporting', E_ALL ^ E_NOTICE);


if(!defined('_PS_VERSION_'))
    exit;

require_once(_PS_MODULE_DIR_ . '/prestasms/includes/model/sms.php');
require_once(_PS_MODULE_DIR_ . '/prestasms/includes/model/variables.php');
require_once(_PS_MODULE_DIR_ . '/prestasms/includes/model/hooks.php');
require_once(_PS_MODULE_DIR_ . '/prestasms/includes/model/smsAdapter.php');
require_once(_PS_MODULE_DIR_ . '/prestasms/lang.php');
require_once(_PS_MODULE_DIR_ . '/prestasms/exc.php');

class PrestaSms extends Module
{

    public $passwd5 = '';
    public $user5 = '';
    public $simulate5 = '';
    public $deletedb5 = '';
    public $pref_billing = 0;
    public $_html2 = '';

    public function __construct()
    {
        $this->name = 'prestasms';
        $this->tab = 'other';
        $this->author = 'TOPefekt s.r.o.';
        $this->module_key = '50995e3c9e65ed55c278cc2da870ede7';
        $this->version = number_format(SMS_VERSION, 2);
        $this->need_instance = 0;
        $this->is_configurable = 1;
        $this->displayName = $this->l('Presta SMS');
        parent::__construct();
        $this->displayName = $this->l(v_smsprestashop_prestashop);
        $this->description = $this->l(v_smsprestashop_description);
    }

    public function getContent()
    {
        $array_lang_names = explode("|", array_lang_names);
        $array_langs = explode("|", array_langs);

        $iso_code = '';
        $this->_html2 = '';

        global $cookie;

        if(isset($cookie->sms_lang_change) && $cookie->sms_lang_change)
        {
            $cookie->sms_lang_change = false;
            $this->_html2 .= '<div style="width:500px;background-color:#5cb85c;color:#fff; padding:10px;border-radius: 5px;margin-bottom: 10px;"> ' . $this->l('Settings updated') . '</div>';
        }

        if(isset($_POST['sms_language']))
        {
            $this->_html2 .= '<div style="width:500px;background-color:#5cb85c;color:#fff; padding:10px;border-radius: 5px;margin-bottom: 10px;"> ' . $this->l('Settings updated') . '</div>';

            $this->uninstallModuleTab('SmsprestaTab');
            $this->uninstallModuleTab('AdminSmsProfile');
            $this->uninstallModuleTab('AdminSms');
            $this->uninstallModuleTab('CustomerSms');
            $this->uninstallModuleTab('SmsCharging');
            $this->uninstallModuleTab('SmsWizard');
            $this->uninstallModuleTab('SmsHistory');
            $this->uninstallModuleTab('SmsStatistics');
            $this->uninstallModuleTab('SmsCredit');
            $this->uninstallModuleTab('SmsMarketing');
            $this->uninstallModuleTab('SmsAnswers');
            $this->uninstallModuleTab('SendSms');
            $this->uninstallModuleTab('SmsAbout');

            $tabNames = array();

            $result = Db::getInstance()->ExecuteS("SELECT * FROM " . _DB_PREFIX_ . "lang order by id_lang");
            if(is_array($result))
            {
                foreach($result AS $row)
                {
                    $tabNames["presta"][$row['id_lang']] = "Presta".v_smsanswers_sms;
                }
            }

            $this->installModuleTab('SmsprestaTab', $tabNames["presta"], 0);


            $result = Db::getInstance()->ExecuteS("SELECT * FROM " . _DB_PREFIX_ . "lang order by id_lang");

            $idTab = Tab::getIdFromClassName("IMPROVE");

            if(is_array($result))
            {
                foreach($result AS $row)
                {
                    $tabNames["admin"][$row['id_lang']] = v_smsprestashop_admin;
                    $tabNames["customer"][$row['id_lang']] = v_smsprestashop_customer;
                    $tabNames["setting"][$row['id_lang']] = v_smsprestashop_settings;
                    $tabNames["history"][$row['id_lang']] = v_smsprestashop_history;
                    $tabNames["credit"][$row['id_lang']] = v_smsprestashop_credit;
                    $tabNames["marketing"][$row['id_lang']] = v_smsprestashop_marketing;
                    $tabNames["answers"][$row['id_lang']] = v_smsprestashop_answers;
                    $tabNames["send"][$row['id_lang']] = v_smsprestashop_send;
                    $tabNames["about"][$row['id_lang']] = v_smsprestashop_about;
                    $tabNames["profile"][$row['id_lang']] = v_adminsmsprofile_editaccount;
                    $tabNames["optout"][$row['id_lang']] = v_smsprestashop_smscharging;
                    $tabNames["statistics"][$row['id_lang']] = v_statistics;
                }
            }

            $this->installModuleTab('SmsprestaTab', $tabNames["presta"], $idTab);
            $idTab = Tab::getIdFromClassName("SmsprestaTab");
            $this->installModuleTab('AdminSmsProfile', $tabNames["profile"], $idTab);
            $this->installModuleTab('SendSms', $tabNames["send"], $idTab);
            $this->installModuleTab('SmsMarketing', $tabNames["marketing"], $idTab);
            $this->installModuleTab('SmsHistory', $tabNames["history"], $idTab);
            $this->installModuleTab('SmsStatistics', $tabNames["statistics"], $idTab);
            $this->installModuleTab('SmsAnswers', $tabNames["answers"], $idTab);
            $this->installModuleTab('AdminSms', $tabNames["admin"], $idTab);
            $this->installModuleTab('CustomerSms', $tabNames["customer"], $idTab);
            $this->installModuleTab('SmsCharging', $tabNames["optout"], $idTab);
            $this->installModuleTab('SmsWizard', $tabNames["setting"], $idTab);
            $this->installModuleTab('SmsCredit', $tabNames["credit"], $idTab);
            $this->installModuleTab('SmsAbout', $tabNames["about"], $idTab);

            $cookie->sms_lang_change = true;

            $target = str_replace("&amp;", "&", $_SERVER["REQUEST_URI"]);

            if(!headers_sent())
            {
                header("Location: " . $target);
            }
            else
            {
                echo '
                    <script type="text/javascript">
                        window.location.href="' . $target . '";
                    </script>
                    <noscript>
                        <meta http-equiv="refresh" content="0;url=' . $target . '" />
                    </noscript>
                ';
            }
            die;
        }


        if(!Configuration::get('PS_PRESTA_SMS_LANG'))
        {
            $result = Db::getInstance()->ExecuteS("SELECT iso_code FROM " . _DB_PREFIX_ . "lang WHERE id_lang=" . Configuration::get('PS_LANG_DEFAULT')) . "";
            if(is_array($result))
            {
                foreach($result AS $row)
                {
                    $iso_code = $row["iso_code"];
                }
            }

            if(strlen($iso_code) > 0 && in_array($iso_code, $array_langs))
            {
                Configuration::updateValue('PS_PRESTA_SMS_LANG', $iso_code);
            }
            else
            {
                Configuration::updateValue('PS_PRESTA_SMS_LANG', 'en');
            }
        }

        $lang = Configuration::get('PS_PRESTA_SMS_LANG');
        $this->bootstrap = true;
        $this->_html2 .=
                '
                 <form action="' . Tools::htmlentitiesUTF8($_SERVER['REQUEST_URI']) . '" method="post">
                    
                    <div class="panel">
                    <div class="panel-heading">' . $this->l('Presta SMS settings') . '</div>
                        <table border="0" width="600" cellpadding="0" cellspacing="0" id="form">
                            <tr>
                                <td width="180" style="height: 35px;">' . $this->l('Select language') . ':</td>
                                <td>
                                    <select name="sms_language" id="sms_language" style="width:150px;">';

        foreach($array_langs as $key => $value)
        {
            if($lang == $value)
            {
                $selected = " selected=\"selected\"";
            }
            else
            {
                $selected = "";
            }
            $this->_html2 .= "<option value=\"" . $value . "\"" . $selected . ">" . $array_lang_names[$key] . "</option>";
        }

        $this->_html2 .= '
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td width="180" style="height: 35px;">&nbsp;</td>
                                <td><input class="btn btn-default" name="smsconfigSubmit" value="' . $this->l('Update settings') . '" type="submit" /></td>
                            </tr>
                        </table>
                    </div>
		</form>';

        return $this->_html2;
    }

    public function install()
    {
        if(parent::install() == false)
        {
            return false;
        }

        $result = Db::getInstance()->ExecuteS("SELECT count(*) as count FROM " . _DB_PREFIX_ . "configuration where name like 'PS_SHOP_DOMAIN'");
        if(is_array($result))
        {
            foreach($result AS $row)
            {
                $count = $row["count"];
            }
        }

        if(!$count > 0)
        {
            $domainweb = $_SERVER['SERVER_NAME'];

            $url = array();

            $query = $_SERVER['PHP_SELF'];

            if(substr($query, 0, 1) == "/")
            {
                $query = substr($query, 1, strlen($query));
            }

            $url = explode("/", $query);
            foreach($url as $value)
            {
                if(strstr($value, "admin"))
                {
                    break;
                }
                else
                {
                    $domainweb .= "/" . $value;
                }
            }
            Db::getInstance()->Execute("INSERT INTO " . _DB_PREFIX_ . "configuration values (NULL , NULL ,NULL ,'PS_SHOP_DOMAIN', '" . $domainweb . "', NOW( ) , NOW( ) )");
        }

        Db::getInstance()->Execute("CREATE TABLE IF NOT EXISTS " . _DB_PREFIX_ . "sp_settings (
                                `ID` int(5) NOT NULL AUTO_INCREMENT,
                                `name` varchar(100) CHARACTER SET utf8 NOT NULL,
                                 `value` text NOT NULL,
                                PRIMARY KEY (`ID`)
                                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

        Db::getInstance()->Execute("CREATE TABLE IF NOT EXISTS " . _DB_PREFIX_ . "sp_optout_prices (
                                `ID` int(7) NOT NULL AUTO_INCREMENT,
                                `shop_id` int(5) NOT NULL,
                                `price` double(10,4) NOT NULL,
                                `currency` varchar(20) NOT NULL,
                                `active` tinyint(3) NOT NULL,
                                PRIMARY KEY (`ID`)
                              ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

        Db::getInstance()->Execute("CREATE TABLE IF NOT EXISTS " . _DB_PREFIX_ . "sp_optout_texts (
                                `ID` int(7) NOT NULL AUTO_INCREMENT,
                                `shop_id` int(5) NOT NULL,
                                `text` text NOT NULL,
                                `lang` varchar(20) NOT NULL,
                                PRIMARY KEY (`ID`)
                              ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

        Db::getInstance()->Execute("CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "sp_optout_deleted` (
                                `ID` int(10) NOT NULL AUTO_INCREMENT,
                                `cart_ID` int(10) NOT NULL,
                                `currency_ID` int(11) DEFAULT NULL,
                                `order_id` int(11) DEFAULT '-1',
                                PRIMARY KEY (`ID`),
                                KEY `order_id_optout` (`order_id`) USING HASH
                              ) ENGINE=MyISAM DEFAULT CHARSET=utf8");

        $result = Db::getInstance()->ExecuteS("DESCRIBE " . _DB_PREFIX_ . "sp_optout_deleted");

        if(is_array($result))
        {
            foreach($result as $row)
            {
                $userTable[] = $row["Field"];
            }
            if(count($userTable) && !in_array("order_id", $userTable))
            {
                Db::getInstance()->Execute("ALTER TABLE `" . _DB_PREFIX_ . "sp_optout_deleted` ADD COLUMN `order_id` int(11) DEFAULT '-1';");
            }
        }

        $result = Db::getInstance()->ExecuteS("SELECT count(*) as count FROM " . _DB_PREFIX_ . "sp_settings where name like 'AppID'");

        if(is_array($result))
        {
            foreach($result as $row)
            {
                $counter = $row["count"];
            }
        }

        if(!($counter > 0))
        {
            Db::getInstance()->Execute("INSERT INTO " . _DB_PREFIX_ . "sp_settings VALUES (NULL , 'AppID', '" . rand(1000000, 90000000) . "' )");
        }

        Db::getInstance()->Execute("CREATE TABLE IF NOT EXISTS " . _DB_PREFIX_ . "sp_smsuser (
                            `ID` tinyint(1) NOT NULL AUTO_INCREMENT,
                            `user` varchar(55) CHARACTER SET utf8 NOT NULL,
                            `passwd` varchar(55) CHARACTER SET utf8 NOT NULL,
                            `email` varchar(100) CHARACTER SET utf8 NOT NULL,
                            `companyname` varchar(100) CHARACTER SET utf8 NOT NULL,
                            `addressstreet` varchar(100) CHARACTER SET utf8 NOT NULL,
                            `addresscity` varchar(100) CHARACTER SET utf8 NOT NULL,
                            `addresszip` varchar(100) CHARACTER SET utf8 NOT NULL,
                            `country0` varchar(100) CHARACTER SET utf8 NOT NULL,
                            `companyid` varchar(100) CHARACTER SET utf8 NOT NULL,
                            `companyvat` varchar(100) CHARACTER SET utf8 NOT NULL,
                            `simulatesms` tinyint(3) NOT NULL,
                            `deletedb` tinyint(3) NOT NULL,
                            `pocetkredit` int(6) NOT NULL,
                            `deliveryemail` varchar(100) NOT NULL,
                            `URLreports` tinyint(3) NOT NULL,
                            `kosoba` varchar(100) CHARACTER SET utf8 NOT NULL,
                            `kprijmeni` varchar(100) CHARACTER SET utf8 NOT NULL,
                            PRIMARY KEY (`ID`)
                            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8  ;");

        $result = Db::getInstance()->ExecuteS("DESCRIBE " . _DB_PREFIX_ . "sp_smsuser");

        if(is_array($result))
        {
            foreach($result as $row)
            {
                $userTable[] = $row["Field"];
            }
            if(count($userTable) && !in_array("kosoba", $userTable))
            {
                Db::getInstance()->Execute("ALTER TABLE `" . _DB_PREFIX_ . "sp_smsuser` ADD COLUMN `kosoba` varchar(100) CHARACTER SET utf8 NOT NULL;");
            }

            if(count($userTable) && !in_array("kprijmeni", $userTable))
            {
                Db::getInstance()->Execute("ALTER TABLE `" . _DB_PREFIX_ . "sp_smsuser` ADD COLUMN `kprijmeni` varchar(100) CHARACTER SET utf8 NOT NULL;");
            }
        }

        Db::getInstance()->Execute("CREATE TABLE IF NOT EXISTS " . _DB_PREFIX_ . "sp_admins (
					`ID` int(5) NOT NULL AUTO_INCREMENT,
					`name` varchar(100) CHARACTER SET utf8 NOT NULL,
					`number` varchar(20) CHARACTER SET utf8 NOT NULL,
					PRIMARY KEY (`ID`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

        Db::getInstance()->Execute("CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "sp_hooks_admins_multi` (
                                        `name` varchar(100) NOT NULL,
                                        `status` tinyint(3) NOT NULL,
                                        `smstext` text NOT NULL,
                                        `adminIDs` varchar(250) NOT NULL,
                                        `storeID` bigint(25) NOT NULL
                                      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        Db::getInstance()->Execute("CREATE TABLE IF NOT EXISTS " . _DB_PREFIX_ . "sp_hooks_customers (
					`name` varchar(100) CHARACTER SET utf8 NOT NULL,
					`status` tinyint(3) NOT NULL,
					`smstext` text CHARACTER SET utf8 NOT NULL,
					`active` tinyint(3) NOT NULL,
  					`mutation` varchar(100) NOT NULL,
                                        `storeID` bigint(25) NOT NULL
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        $result = Db::getInstance()->ExecuteS("DESCRIBE " . _DB_PREFIX_ . "sp_hooks_customers");

        if(is_array($result))
        {
            foreach($result as $row)
            {
                $hooksTable[] = $row["Field"];
            }
            if(count($hooksTable) && !in_array("storeID", $hooksTable))
            {
                Db::getInstance()->Execute("ALTER TABLE `" . _DB_PREFIX_ . "sp_hooks_customers` ADD COLUMN `storeID` bigint(25) NOT NULL;");
            }
        }

        Db::getInstance()->Execute("CREATE TABLE IF NOT EXISTS " . _DB_PREFIX_ . "sp_hooks_templates (
					`area` varchar(10) CHARACTER SET utf8 NOT NULL,
					`area_text` varchar(50) CHARACTER SET utf8 NOT NULL,
					UNIQUE KEY `area` (`area`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        Db::getInstance()->Execute("CREATE TABLE IF NOT EXISTS " . _DB_PREFIX_ . "sp_hooks_unicode (
					`area` varchar(10) NOT NULL,
					`unicode` tinyint(3) NOT NULL,
					`type` varchar(10) NOT NULL
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        Db::getInstance()->Execute("CREATE TABLE IF NOT EXISTS " . _DB_PREFIX_ . "sp_ownnumbersenderIDs (
					`ownnumbersenderID` varchar(30) CHARACTER SET utf8 NOT NULL,
					UNIQUE KEY `textsenderID` (`ownnumbersenderID`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        Db::getInstance()->Execute("CREATE TABLE IF NOT EXISTS " . _DB_PREFIX_ . "sp_routes (
					`area` int(5) NOT NULL,
					`type` varchar(20) CHARACTER SET utf8 NOT NULL,
					`isms` int(5) NOT NULL,
					`sendertype` tinyint(3) NOT NULL,
					`senderID` varchar(30) CHARACTER SET utf8 NOT NULL,
					`info` text CHARACTER SET utf8 NOT NULL,
					`area_text` varchar(50) CHARACTER SET utf8 NOT NULL
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        Db::getInstance()->Execute("CREATE TABLE IF NOT EXISTS " . _DB_PREFIX_ . "sp_sms_history (
					`ID` int(8) NOT NULL AUTO_INCREMENT,
					`number` varchar(30) NOT NULL,
					`date` datetime NOT NULL,
					`text` text NOT NULL,
					`status` tinyint(3) NOT NULL,
					`price` double(5,3) NOT NULL,
					`credit` double(15,3) NOT NULL,
					`sender` varchar(30) NOT NULL,
					`unicode` tinyint(3) NOT NULL,
					`type` tinyint(3) NOT NULL,
					`smsID` varchar(220) NOT NULL,
					`note` varchar(100) NOT NULL,
					`total` tinyint(3) NOT NULL,
					`admin_ID` int(8) NOT NULL,
					`customer_ID` int(8) NOT NULL,
					`recipient` varchar(100) NOT NULL,
					`subject` varchar(100) NOT NULL,
                                        `change` tinyint(1) NOT NULL DEFAULT '1',
                                        `campaign` int(11) DEFAULT NULL,
					PRIMARY KEY (`ID`),
					KEY `vyber1` (`date`),
					KEY `vyber2` (`date`,`type`),
					KEY `vyber3` (`date`,`type`,`status`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

        $result = Db::getInstance()->ExecuteS("DESCRIBE " . _DB_PREFIX_ . "sp_sms_history");

        if(is_array($result))
        {
            foreach($result as $row)
            {
                $historyTable[] = $row["Field"];
            }
            if(count($historyTable) && !in_array("change", $historyTable))
            {
                Db::getInstance()->Execute("ALTER TABLE `" . _DB_PREFIX_ . "sp_sms_history` ADD COLUMN `change` tinyint(1) NOT NULL DEFAULT 1;");
            }

            if(count($historyTable) && !in_array("campaign", $historyTable))
            {
                Db::getInstance()->Execute("ALTER TABLE `" . _DB_PREFIX_ . "sp_sms_history` ADD COLUMN `campaign` int(11) DEFAULT NULL;");
            }
        }

        Db::getInstance()->Execute("CREATE TABLE IF NOT EXISTS " . _DB_PREFIX_ . "sp_textsenderIDs (
					`textsenderID` varchar(11) CHARACTER SET utf8 NOT NULL,
					UNIQUE KEY `textsenderID` (`textsenderID`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        Db::getInstance()->Execute("CREATE TABLE IF NOT EXISTS " . _DB_PREFIX_ . "sp_answers (
					`ID` int(5) NOT NULL AUTO_INCREMENT,
					`text` text NOT NULL,
					`from` varchar(50) NOT NULL DEFAULT '',
					`prohlednuto` tinyint(3) NOT NULL DEFAULT '0',
					`smsc` varchar(100) NOT NULL DEFAULT '',
					`cas` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
					PRIMARY KEY (`ID`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;");

        Db::getInstance()->Execute("CREATE TABLE IF NOT EXISTS " . _DB_PREFIX_ . "sp_outofstock (
					`ID` int(8) NOT NULL AUTO_INCREMENT,
					`datum` date NOT NULL,
					`product_ID` int(8) NOT NULL,
					PRIMARY KEY (`ID`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;");

        Db::getInstance()->Execute("CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "sp_marketing_filter` (
                                        `id` int(11) NOT NULL AUTO_INCREMENT,
                                        `name` varchar(200) NOT NULL,
                                        `filter` text,
                                        `disabled` text,
                                        `disabled_counter` int(11) NOT NULL DEFAULT '0',
                                        `date` datetime NOT NULL,
                                        PRIMARY KEY (`id`)
                                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        Db::getInstance()->Execute("CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "sp_template` (
                                        `id` int(11) NOT NULL AUTO_INCREMENT,
                                        `name` varchar(200) NOT NULL,
                                        `template` text,
                                        `unicode` tinyint(1) NOT NULL DEFAULT '0',
                                        `unique` tinyint(1) NOT NULL DEFAULT '0',
                                        `date` datetime NOT NULL,
                                        `type` int(11) NOT NULL,
                                        PRIMARY KEY (`id`)
                                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        Db::getInstance()->Execute("CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "sp_exceptions` (
                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                    `prefix` int(11) NOT NULL,
                                    `firstPrefix` int(11) NOT NULL,
                                    `length` int(11) NOT NULL,
                                    `trim` int(11) NOT NULL,
                                    PRIMARY KEY (`id`)
                                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        Db::getInstance()->Execute("CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "sp_config` (
                                    `shop_id` int(11) NOT NULL,
                                    `config_name` varchar(200) NOT NULL,
                                    `config_value` text NOT NULL,
                                    PRIMARY KEY (`shop_id`,`config_name`)
                                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        
        Db::getInstance()->Execute("CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "sp_routes_alternative` (
                                        `area` int(11) NOT NULL,
                                        `shop_id` int(11) NOT NULL,
                                        `type` varchar(20) NOT NULL,
                                        `textsender` varchar(11) NOT NULL,
                                        PRIMARY KEY (`area`,`shop_id`,`type`)
                                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        
        Db::getInstance()->Execute("CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "sp_marketing_black_list` (
                                    `customer_id` int(11) NOT NULL,
                                    `value` int(11) NOT NULL DEFAULT '0',
                                    PRIMARY KEY (`customer_id`)
                                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        
        if(!defined("SMS_DEFAULT_MUTATION"))
        {
            $result = Db::getInstance()->ExecuteS("SELECT value FROM `" . _DB_PREFIX_ . "configuration` WHERE `name` = 'PS_LOCALE_LANGUAGE'");

            if(count($result))
            {
                $result = $result[0];
                
                define("SMS_DEFAULT_MUTATION", $result["value"]);
            }
            else
            {
                define("SMS_DEFAULT_MUTATION", "en");
            }
        }
        
        Db::getInstance()->Execute("UPDATE `" . _DB_PREFIX_ . "sp_hooks_customers` SET `mutation`='".SMS_DEFAULT_MUTATION."' WHERE `mutation`='default'");
        Db::getInstance()->execute("UPDATE `" . _DB_PREFIX_ . "sp_hooks_unicode` SET `area`='".SMS_DEFAULT_MUTATION."' WHERE `area`='default'");

        $tabNames = array();

        $result = Db::getInstance()->ExecuteS("SELECT * FROM " . _DB_PREFIX_ . "lang order by id_lang");
        if(is_array($result))
        {
            foreach($result AS $row)
            {
                $tabNames["presta"][$row['id_lang']] = "Presta".v_smsanswers_sms;
            }
        }

        //$this->installModuleTab('SmsprestaTab', $tabNames["presta"], 0);

        $result = Db::getInstance()->ExecuteS("SELECT * FROM " . _DB_PREFIX_ . "lang order by id_lang");

        $idTab = Tab::getIdFromClassName("IMPROVE");

        if(is_array($result))
        {
            foreach($result AS $row)
            {
                $tabNames["admin"][$row['id_lang']] = v_smsprestashop_admin;
                $tabNames["customer"][$row['id_lang']] = v_smsprestashop_customer;
                $tabNames["setting"][$row['id_lang']] = v_smsprestashop_settings;
                $tabNames["history"][$row['id_lang']] = v_smsprestashop_history;
                $tabNames["credit"][$row['id_lang']] = v_smsprestashop_credit;
                $tabNames["marketing"][$row['id_lang']] = v_smsprestashop_marketing;
                $tabNames["answers"][$row['id_lang']] = v_smsprestashop_answers;
                $tabNames["send"][$row['id_lang']] = v_smsprestashop_send;
                $tabNames["about"][$row['id_lang']] = v_smsprestashop_about;
                $tabNames["profile"][$row['id_lang']] = v_adminsmsprofile_editaccount;
                $tabNames["optout"][$row['id_lang']] = v_smsprestashop_smscharging;
                $tabNames["statistics"][$row['id_lang']] = v_statistics;
            }
        }

        $this->installModuleTab('SmsprestaTab', $tabNames["presta"], $idTab);
        $idTab = Tab::getIdFromClassName("SmsprestaTab");
        $this->installModuleTab('AdminSmsProfile', $tabNames["profile"], $idTab);
        $this->installModuleTab('SendSms', $tabNames["send"], $idTab);
        $this->installModuleTab('SmsMarketing', $tabNames["marketing"], $idTab);
        $this->installModuleTab('SmsHistory', $tabNames["history"], $idTab);
        $this->installModuleTab('SmsStatistics', $tabNames["statistics"], $idTab);
        $this->installModuleTab('SmsAnswers', $tabNames["answers"], $idTab);
        $this->installModuleTab('AdminSms', $tabNames["admin"], $idTab);
        $this->installModuleTab('CustomerSms', $tabNames["customer"], $idTab);
        $this->installModuleTab('SmsCharging', $tabNames["optout"], $idTab);
        $this->installModuleTab('SmsWizard', $tabNames["setting"], $idTab);
        $this->installModuleTab('SmsCredit', $tabNames["credit"], $idTab);
        $this->installModuleTab('SmsAbout', $tabNames["about"], $idTab);

        $this->registerHook('displayFooter');
        $this->registerHook('displayAdminOrder');
        $this->registerHook('actionCartSave');
        $this->registerHook('actionOrderStatusPostUpdate');
        $this->registerHook('actionOrderReturn');
        $this->registerHook('actionOrderSlipAdd');
        $this->registerHook('actionPaymentConfirmation');
        $this->registerHook('actionAdminOrdersTrackingNumberUpdate');
        $this->registerHook('actionCustomerAccountAdd');
        $this->registerHook('actionProductDelete');
        $this->registerHook('actionUpdateQuantity');
        $this->registerHook('actionValidateOrder');
        $this->registerHook('actionProductOutOfStock');
        $this->registerHook('actionProductCancel');
        $this->registerHook('displayBackOfficeHeader');
        $this->registerHook('displayBackOfficeFooter');
        $this->registerHook('displayShoppingCartFooter');
        $this->registerHook('displayCustomerAccount');

        return true;
    }

    public function hookDisplayCustomerAccount($data)
    {
        $active_marketing = 0;
        $result = Db::getInstance()->executeS("SELECT value FROM `" . DB_PREFIX . "sp_settings` WHERE `name` = 'optoutMarketing'");
        
        if(count($result))
        {
            $result = $result[0];
            $active_marketing = $result["value"];
        }

        if($active_marketing)
        {
            if($_GET["do"] == "marketing")
            {
                $result = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'sp_marketing_black_list` WHERE `customer_id` = "'.Db::getInstance()->escape($data["cookie"]->id_customer).'"');

                if(count($result))
                {
                    $result = $result[0];

                    if($result["value"] == 1)
                    {
                        Db::getInstance()->execute("DELETE FROM `"._DB_PREFIX_."sp_marketing_black_list` WHERE (`customer_id` = '".Db::getInstance()->escape($data["cookie"]->id_customer)."')");
                    }
                    else
                    {
                        Db::getInstance()->execute("INSERT INTO `"._DB_PREFIX_."sp_marketing_black_list` (`customer_id`, `value`) VALUES ('".Db::getInstance()->escape($data["cookie"]->id_customer)."', '1')");
                    }
                }
                else
                {
                    Db::getInstance()->execute("INSERT INTO `"._DB_PREFIX_."sp_marketing_black_list` (`customer_id`, `value`) VALUES ('".Db::getInstance()->escape($data["cookie"]->id_customer)."', '1')");
                }
                $url = str_replace("?do=marketing", "", filter_input(INPUT_SERVER, "HTTP_ORIGIN").filter_input(INPUT_SERVER, "REQUEST_URI"));
                header('Location: ' .$url);
            }

            $result = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'sp_marketing_black_list` WHERE `customer_id` = "'.Db::getInstance()->escape($data["cookie"]->id_customer).'"');

            if(count($result))
            {
                $result = $result[0];

                if($result["value"] == 1)
                {
                    $active = '<b style="color: red;">'.v_smshistory_no.'</b>';
                }
                else
                {
                    $active = '<b style="color: green;">'.v_smshistory_yes.'</b>';
                }
            }
            else
            {
                $active = '<b style="color: #00dd66;">'.v_smshistory_yes.'</b>';
            }

            $url = str_replace("?do=marketing", "", filter_input(INPUT_SERVER, "HTTP_ORIGIN").filter_input(INPUT_SERVER, "REQUEST_URI"));
            
            return '
                <li>
                    <a href="'.$url.'?do=marketing">
                        <i class="icon-envelope"></i>
                        <span>'.v_smshistory_marketingsms.': '.$active.'</span>
                    </a>
                </li>';
        }
        return false;
    }
    
    public function hookDisplayBackOfficeHeader()
    {
        if(isset($this->context->controller) && method_exists($this->context->controller, "addCSS"))
        {
            $this->context->controller->addCSS(_PS_MODULE_DIR_ . '/prestasms/css/smsMenu.css');
        }
    }

    public function hookDisplayShoppingCartFooter($params)
    {
        $id_cart = $params["cart"]->id;
        $id_shop = $params["cart"]->id_shop;
        $id_address_delivery = $params["cart"]->id_address_delivery;

        

        if(isset($_POST["active"]))
        {
            $result = Db::getInstance()->ExecuteS("SELECT * FROM " . _DB_PREFIX_ . "product where ean13 like '" . SMS_EAN13 . "' limit 1 ");

            if(is_array($result))
            {
                foreach($result AS $row)
                {
                    $product_id = $row["id_product"];
                }
            }
            
            if($_POST["active"])
            {
                Db::getInstance()->Execute("INSERT INTO " . _DB_PREFIX_ . "cart_product (`id_cart`, `id_product`, `id_address_delivery`, `id_shop`, `id_product_attribute`, `quantity`, `date_add`) VALUES ('" . $id_cart . "', '" . $product_id . "', '" . $id_address_delivery . "', '" . $id_shop . "', 0, 1, NOW());");
                Db::getInstance()->Execute("UPDATE " . _DB_PREFIX_ . "sp_optout_deleted SET currency_ID = '0' WHERE cart_ID='" . $id_cart . "'");
            }
            else
            {
                Db::getInstance()->Execute("DELETE FROM " . _DB_PREFIX_ . "cart_product WHERE id_shop=" . $id_shop . " AND id_cart='" . $id_cart . "' AND id_product='".$product_id."'");
                Db::getInstance()->Execute("UPDATE " . _DB_PREFIX_ . "sp_optout_deleted SET currency_ID = '1' WHERE cart_ID='" . $id_cart . "'");
            }

            header('Location: ' . $_SERVER["REQUEST_URI"]);
        }

        $active = false;

        $result = Db::getInstance()->ExecuteS("SELECT * FROM " . _DB_PREFIX_ . "sp_optout_prices where shop_id='".$id_shop."'");
        if(is_array($result))
        {
            foreach($result AS $row)
            {
                $active = $row["active"];
                $price = $row["price"];
                $currency = $row["currency"];
            }
        }

        if($active)
        {
            $auto = false;

            $result = Db::getInstance()->ExecuteS("SELECT * FROM `" . DB_PREFIX . "sp_config` WHERE `shop_id` = '" . $id_shop . "' AND `config_name` = 'optout_auto'");
            if(is_array($result))
            {
                foreach($result AS $row)
                {
                    $auto = $row["config_value"];
                }
            }

            if(!isset($product_id))
            {
                $result = Db::getInstance()->ExecuteS("SELECT * FROM " . _DB_PREFIX_ . "product where ean13 like '" . SMS_EAN13 . "' limit 1 ");

                if(is_array($result))
                {
                    foreach($result AS $row)
                    {
                        $product_id = $row["id_product"];
                    }
                }
            }
            
            $tax_name = v_tax_without;
            
            if(defined("SMS_OPTOUT_PRICE_TAX") && SMS_OPTOUT_PRICE_TAX)
            {
                $tax = Product::getTaxesInformations(array("id_product" => $product_id));

                
                if(isset($tax["rate"]))
                {
                    $price = $price * (((float)$tax["rate"]/100)+1);
                    $tax_name = $tax["tax_name"];
                }
            }
            
            $sms_status = $auto ? -1 : 0;
            $result = Db::getInstance()->ExecuteS("SELECT * FROM " . _DB_PREFIX_ . "sp_optout_deleted where cart_ID='".$id_cart."'");
            if(is_array($result) && count($result))
            {
                foreach($result as $row)
                {
                    $sms_status = $row["currency_ID"];
                }
            }

            $result = Db::getInstance()->ExecuteS("SELECT * FROM " . _DB_PREFIX_ . "cart_product WHERE id_shop='" . $id_shop . "' AND id_cart='" . $id_cart . "' AND id_product = '" . $product_id . "'");

            if(is_array($result) && count($result))
            {
                $value = 1;
            }
            else
            {
                $value = 0;
            }

            Hook::exec('actionCartSave');

            global $cookie;
            $iso_code = Language::getIsoById((int) $cookie->id_lang);

            $result = Db::getInstance()->ExecuteS("SELECT * FROM `" . DB_PREFIX . "sp_optout_texts` WHERE `shop_id` = '" . $id_shop . "' AND `lang` = '" . $iso_code . "'");
            if(is_array($result))
            {
                foreach($result AS $row)
                {
                    $text = $row["text"];
                }
            }
            
            $output = '<div style="padding-left: 15px; padding-bottom: 20px;" class="box">';

            $output .= '<form action="" method="post">
                                <input type="hidden" name="active" value="' . ($value ? "0" : "1") . '"/>
                                <p class="checkbox smsoptout">
                                    <input type="checkbox" name="smsoptout" ' . ($value ? "checked" : "") . ' id="smsoptout"/>
                                    <label for="smsoptout"><strong>' . htmlspecialchars($text) . '</strong> - '.number_format($price, 2)." ".$currency.' ('.$tax_name.')</label>
                                </p>
                            </form>';

            $output .= '</div>';

            $output .= '
                    <script>
                        document.onreadystatechange = function () {
                            jQuery("#smsoptout").on("change", function() 
                            {
                                jQuery(this).closest("form").submit();
                            });

                        };
                    </script>    
                    ';

            return $output;
        }
        return "";
    }

    public function hookDisplayBackOfficeFooter()
    {
        if((float) substr(_PS_VERSION_, 0, 3) >= 1.6)
        {
            if($_GET["controller"] == "AdminOrders" && $_GET["id_order"] != NULL && isset($_GET["vieworder"]))
            {
                $result = Db::getInstance()->executeS("SELECT * FROM " . DB_PREFIX . "orders where id_order = '" . Db::getInstance()->_escape($_GET["id_order"]) . "' ");

                if(isset($result[0]))
                {
                    if(isset($result[0]["id_address_delivery"]))
                    {
                        $address_id = $result[0]["id_address_delivery"];
                    }
                    elseif(isset($result[0]["id_address_invoice"]))
                    {
                        $address_id = $result[0]["id_address_invoice"];
                    }
                    else
                    {
                        $address_id = 0;
                    }

                    if($address_id != 0)
                    {
                        $result = Db::getInstance()->executeS("SELECT * FROM " . DB_PREFIX . "address WHERE id_address = '" . Db::getInstance()->_escape($address_id) . "' ");

                        if(isset($result[0]))
                        {
                            if(strlen($result[0]["phone_mobile"]))
                            {
                                $phone = $result[0]["phone_mobile"];
                            }
                            elseif(strlen($result[0]["phone"]))
                            {
                                $phone = $result[0]["phone"];
                            }
                            else
                            {
                                $phone = 0;
                            }
                            $country_id = $result[0]["id_country"];
                        }
                    }
                }

                if($phone != 0)
                {
                    echo "<span id=\"cartsms_send_sms\">&nbsp; <a class=\"btn btn-default\" href=\"index.php?controller=SendSms&token=" . Tools::getAdminTokenLite("SendSms") . "&recipient=" . $phone . "&country=" . $country_id . "\"><i class=\"icon-SmsprestaTab\"></i> " . v_smsprestashop_send . "</a>&nbsp; </span>";
                    echo "<script>
                        $( document ).ready(function() {
                           $('#cartsms_send_sms').insertBefore('.well .btn.btn-default:first');
                        });
                        </script>";
                }
            }

            if($_GET["controller"] == "AdminCustomers" && $_GET["id_customer"] != NULL && isset($_GET["viewcustomer"]))
            {
                $phone = 0;

                $result = Db::getInstance()->executeS("SELECT * FROM " . DB_PREFIX . "address WHERE id_customer = '" . Db::getInstance()->_escape($_GET["id_customer"]) . "' ");

                if($result != NULL)
                {
                    foreach($result as $row)
                    {
                        if($phone == 0)
                        {
                            if(strlen($row["phone_mobile"]))
                            {
                                $phone = $row["phone_mobile"];
                            }
                            elseif(strlen($row["phone"]))
                            {
                                $phone = $row["phone"];
                            }
                            else
                            {
                                $phone = 0;
                            }
                            $country_id = $row["id_country"];
                        }
                    }
                }

                if($phone != 0)
                {
                    echo "<a id=\"cartsms_send_sms\" class=\"btn btn-default\" style=\"margin: -5px 5px 0 5px;\" href=\"index.php?controller=SendSms&token=" . Tools::getAdminTokenLite("SendSms") . "&recipient=" . $phone . "&country=" . $country_id . "\"><i class=\"icon-SmsprestaTab\"></i> " . v_smsprestashop_send . "</a>";
                    echo "<script>
                        $( document ).ready(function() {
                           $('#cartsms_send_sms').insertAfter('.panel-heading a:first');
                        });
                        </script>";
                }
            }
        }
    }

    public function hookDisplayFooter($params)
    {
        global $smarty;

        if(( strstr($_SERVER["PHP_SELF"], "contact-form.php") || strstr($_SERVER["QUERY_STRING"], "controller=contact") || strstr($_SERVER["REQUEST_URI"], "/contact-us") ) && $smarty->tpl_vars["confirmation"]->value == 1)
        {
            $hooks = new ModelSmsHooks();
            $hooks->contactFormHook(Context::getContext()->shop->id, $smarty->tpl_vars["message"]->value, $smarty->tpl_vars["email"]->value, "", $_POST["id_order"], $_POST["id_product"], $_POST["id_contact"]);
        }
    }

    public function hookActionCartSave($params)
    {
        $id_cart = $params["cart"]->id;
        $id_shop = $params["cart"]->id_shop;
        $id_address_delivery = $params["cart"]->id_address_delivery;
        $product_id = 0;

        $active = false;
        $result = Db::getInstance()->ExecuteS("SELECT * FROM " . _DB_PREFIX_ . "sp_optout_prices where shop_id='".$id_shop."'");

        if(is_array($result))
        {
            foreach($result AS $row)
            {
                $active = $row["active"];
            }
        }

        if($active)
        {

            $result = Db::getInstance()->ExecuteS("SELECT * FROM " . _DB_PREFIX_ . "product where ean13 like '" . SMS_EAN13 . "' limit 1 ");
            if(is_array($result))
            {
                foreach($result AS $row)
                {
                    $product_id = $row["id_product"];
                }
            }


            $result = Db::getInstance()->ExecuteS("SELECT * FROM `" . DB_PREFIX . "sp_config` WHERE `shop_id` = '" . $id_shop . "' AND `config_name` = 'optout_auto'");
            if(is_array($result))
            {
                foreach($result AS $row)
                {
                    $auto = $row["config_value"];
                }
            }


            $count = 0;
            $countSMS = 0;
            $result = Db::getInstance()->ExecuteS("SELECT * FROM " . _DB_PREFIX_ . "cart_product where id_shop='" . $id_shop . "' and id_cart='" . $id_cart."'");
            if(is_array($result))
            {
                foreach($result AS $row)
                {
                    $count++;
                    if($row["id_product"] == $product_id)
                    {
                        $countSMS++;
                    }
                }
            }

            $sms_state = -1;

            if(isset($id_cart))
            {
                $result = Db::getInstance()->ExecuteS("SELECT * FROM " . _DB_PREFIX_ . "sp_optout_deleted where cart_ID='" . $id_cart."'");

                if(is_array($result) && count($result))
                {
                    foreach($result AS $row)
                    {
                        $sms_state = $row["currency_ID"];
                    }
                }
                else
                {
                    Db::getInstance()->Execute("INSERT INTO " . _DB_PREFIX_ . "sp_optout_deleted VALUES (NULL,'" . $id_cart . "','0', -1)");
                }
            }

            Db::getInstance()->Execute("UPDATE " . _DB_PREFIX_ . "cart_product SET quantity = '1' WHERE id_cart='" . $id_cart . "' AND id_product = '".$product_id."'");
            
            if($sms_state == -1 && $count > 0 && $countSMS <= 0) /* FIRST AUTO INSERT */
            {
                if($auto)
                {
                    Db::getInstance()->Execute("INSERT INTO " . _DB_PREFIX_ . "cart_product (`id_cart`, `id_product`, `id_address_delivery`, `id_shop`, `id_product_attribute`, `quantity`, `date_add`) VALUES ('" . $id_cart . "', '" . $product_id . "', '" . $id_address_delivery . "', '" . $id_shop . "', 0, 1, NOW());");
                }
            }
            elseif($sms_state == 0 && $count == $countSMS) /*/* DELETE SMS IF CART EMPTY */
            {
                Db::getInstance()->Execute("DELETE FROM " . _DB_PREFIX_ . "cart_product WHERE id_shop=" . $id_shop . " AND id_cart='" . $id_cart . "' AND id_product='" . $product_id . "'");
                Db::getInstance()->Execute("DELETE FROM " . _DB_PREFIX_ . "sp_optout_deleted WHERE cart_ID='" . $id_cart . "'");
            }
            elseif($sms_state == 0 && $count > 0 && $countSMS <= 0) /* DELETE SMS */
            {
                Db::getInstance()->Execute("UPDATE " . _DB_PREFIX_ . "sp_optout_deleted SET currency_ID = '1' WHERE cart_ID='" . $id_cart . "'");
            }
            elseif($count == 0 && $countSMS == 0) /* DELETE CART */
            {
                Db::getInstance()->Execute("DELETE FROM " . _DB_PREFIX_ . "sp_optout_deleted WHERE cart_ID='" . $id_cart . "'");
            }
        }

        return true;
    }

    public function hookActionOrderStatusPostUpdate($params)
    {
        $hooks = new ModelSmsHooks();
        $hooks->changeOrderStatusHook($params["newOrderStatus"]->id, "", $params["id_order"]);

        return true;
    }

    public function hookActionValidateOrder($params)
    {        
        $customer_message = "";

        $result = Db::getInstance()->ExecuteS("SELECT * FROM " . _DB_PREFIX_ . "message where id_customer='" . Db::getInstance()->_escape($params["cart"]->id_customer) . "' AND id_cart='" . Db::getInstance()->_escape($params["cart"]->id) . "'  LIMIT 1");
        if(is_array($result))
        {
            foreach($result AS $row)
            {
                $customer_message = $row['message'];
            }
        }

        Db::getInstance()->execute("UPDATE " . _DB_PREFIX_ . "sp_optout_deleted SET order_id='" . Db::getInstance()->_escape($params["order"]->id) . "'  WHERE cart_ID='" . Db::getInstance()->_escape($params["cart"]->id) . "'");

        $hooks = new ModelSmsHooks();
        $hooks->orderAddHook($params["cart"]->id_customer, $params["order"]->id, $customer_message, $params["order"]->id_shop);

        return true;
    }

    public function hookActionOrderReturn($params)
    {
        $hooks = new ModelSmsHooks();
        $hooks->returnGoods($params["orderReturn"]->id, $params["orderReturn"]->id_customer, $params["orderReturn"]->id_order, $params["orderReturn"]->question);

        return true;
    }

    public function hookActionOrderSlipAdd($params)
    {
        $hooks = new ModelSmsHooks();
        $hooks->actionOrderSlipAddHook($params["order"]->id, $params["order"]->id_customer, $params["qtyList"]);

        return true;
    }

    public function hookActionAdminOrdersTrackingNumberUpdate($params)
    {
        $hooks = new ModelSmsHooks();
        $hooks->actionAdminOrdersTrackingNumberUpdateHook($params["order"]->id);

        return true;
    }

    public function hookActionPaymentConfirmation($params)
    {
        $hooks = new ModelSmsHooks();
        $hooks->actionPaymentConfirmation($params["id_order"]);

        return true;
    }

    public function hookActionCustomerAccountAdd($params)
    {
        $hooks = new ModelSmsHooks();
        $hooks->customerAddHook($params["newCustomer"]->id_shop, $params["newCustomer"]->id);

        return true;
    }

    public function hookActionProductDelete($params)
    {
        $data = array(
            "product_ref" => $params["product"]->reference,
            "product_supplier_ref" => $params["product"]->supplier_reference,
            "product_ean13" => $params["product"]->ean13,
            "product_upc" => $params["product"]->upc,
            "product_supplier_id" => $params["product"]->id_supplier,
        );

        $product_name_pre = $params["product"]->name;

        if(is_array($product_name_pre))
        {
            foreach($product_name_pre as $key => $value)
            {
                $data["product_name"] = $value;
            }
        }
        else
        {
            $data["product_name"] = $product_name_pre;
        }

        $result = Db::getInstance()->ExecuteS("SELECT * FROM " . _DB_PREFIX_ . "supplier WHERE id_supplier='" . Db::getInstance()->_escape($params["product"]->id_supplier) . "'");
        if(is_array($result))
        {
            foreach($result AS $row)
            {
                $data["product_supplier"] = $row['name'];
            }
        }

        $hooks = new ModelSmsHooks();
        $hooks->productDeleteHook($params["product"]->id, $data);

        return true;
    }

    public function hookActionUpdateQuantity($params)
    {
        $hooks = new ModelSmsHooks();
        $hooks->actionUpdateQuantityHook($params["id_product"], $params["quantity"], $params["id_product_attribute"]);

        return true;
    }

    public function hookActionProductOutOfStock($params)
    {
        $product_name_pre = $params["product"]->name;

        if(is_array($product_name_pre))
        {
            foreach($product_name_pre as $key => $value)
            {
                $product_name = $value;
            }
        }
        else
        {
            $product_name = $product_name_pre;
        }

        global $cookie;
        $customer_id = $cookie->id_customer;

        $hooks = new ModelSmsHooks();
        $hooks->productOutOfStockHook($params["product"]->id, $params["product"]->quantity, $params["product"]->minimal_quantity, $product_name, $customer_id, $params["product"]->id_shop_default);

        //return true;
    }

    public function hookActionProductCancel($params)
    {
        $hooks = new ModelSmsHooks();
        $hooks->actionProductCancelHook($params["order"]->id, $params["order"]->id_shop, $params["id_order_detail"]);

        return true;
    }

    public function uninstall()
    {
        if(parent::uninstall() == false)
        {
            return false;
        }

        $deleteDB = false;

        $result = Db::getInstance()->ExecuteS("SELECT deletedb FROM " . _DB_PREFIX_ . "sp_smsuser WHERE ID='1'");

        if(is_array($result))
        {
            foreach($result AS $row)
            {
                $deleteDB = ($row['deletedb'] == 1);
            }
        }

        if($deleteDB)
        {
            Db::getInstance()->Execute("DROP TABLE IF EXISTS " . _DB_PREFIX_ . "sp_settings");
            Db::getInstance()->Execute("DROP TABLE IF EXISTS " . _DB_PREFIX_ . "sp_smsuser");
            Db::getInstance()->Execute("DROP TABLE IF EXISTS " . _DB_PREFIX_ . "sp_admins");
            Db::getInstance()->Execute("DROP TABLE IF EXISTS " . _DB_PREFIX_ . "sp_hooks_admins_multi");
            Db::getInstance()->Execute("DROP TABLE IF EXISTS " . _DB_PREFIX_ . "sp_hooks_customers");
            Db::getInstance()->Execute("DROP TABLE IF EXISTS " . _DB_PREFIX_ . "sp_variables");
            Db::getInstance()->Execute("DROP TABLE IF EXISTS " . _DB_PREFIX_ . "sp_hooks_templates");
            Db::getInstance()->Execute("DROP TABLE IF EXISTS " . _DB_PREFIX_ . "sp_hooks_unicode");
            Db::getInstance()->Execute("DROP TABLE IF EXISTS " . _DB_PREFIX_ . "sp_ownnumbersenderIDs");
            Db::getInstance()->Execute("DROP TABLE IF EXISTS " . _DB_PREFIX_ . "sp_routes");
            Db::getInstance()->Execute("DROP TABLE IF EXISTS " . _DB_PREFIX_ . "sp_sms_history");
            Db::getInstance()->Execute("DROP TABLE IF EXISTS " . _DB_PREFIX_ . "sp_textsenderIDs");
            Db::getInstance()->Execute("DROP TABLE IF EXISTS " . _DB_PREFIX_ . "sp_answers");
            Db::getInstance()->Execute("DROP TABLE IF EXISTS " . _DB_PREFIX_ . "sp_outofstock");
            Db::getInstance()->Execute("DROP TABLE IF EXISTS " . _DB_PREFIX_ . "sp_optout_deleted");
            Db::getInstance()->Execute("DROP TABLE IF EXISTS " . _DB_PREFIX_ . "sp_optout_prices");
            Db::getInstance()->Execute("DROP TABLE IF EXISTS " . _DB_PREFIX_ . "sp_optout_texts");
            Db::getInstance()->Execute("DROP TABLE IF EXISTS " . _DB_PREFIX_ . "sp_marketing_filter");
            Db::getInstance()->Execute("DROP TABLE IF EXISTS " . _DB_PREFIX_ . "sp_template");
            Db::getInstance()->Execute("DROP TABLE IF EXISTS " . _DB_PREFIX_ . "sp_exceptions");
            Db::getInstance()->Execute("DROP TABLE IF EXISTS " . _DB_PREFIX_ . "sp_config");
            Db::getInstance()->Execute("DROP TABLE IF EXISTS " . _DB_PREFIX_ . "sp_routes_alternative");
            Db::getInstance()->Execute("DROP TABLE IF EXISTS " . _DB_PREFIX_ . "sp_marketing_black_list");
        }
        
        $this->uninstallModuleTab('SmsprestaTab');
        $this->uninstallModuleTab('AdminSmsProfile');
        $this->uninstallModuleTab('AdminSms');
        $this->uninstallModuleTab('CustomerSms');
        $this->uninstallModuleTab('SmsCharging');
        $this->uninstallModuleTab('SmsWizard');
        $this->uninstallModuleTab('SmsHistory');
        $this->uninstallModuleTab('SmsStatistics');
        $this->uninstallModuleTab('SmsCredit');
        $this->uninstallModuleTab('SmsMarketing');
        $this->uninstallModuleTab('SmsAnswers');
        $this->uninstallModuleTab('SendSms');
        $this->uninstallModuleTab('SmsAbout');

        return true;
    }

    private function installModuleTab($tabClass, $tabName, $idTabParent)
    {
        //copy(_PS_MODULE_DIR_ . $this->name . '/logo.gif', _PS_IMG_DIR_ . 't/' . $tabClass . '.gif');

        $tab = new Tab();
        $tab->name = $tabName;
        $tab->class_name = $tabClass;
        $tab->module = $this->name;
        $tab->id_parent = $idTabParent;

        if(!$tab->save())
        {
            return false;
        }
        return Tab::getIdFromClassName($tabClass);
    }

    private function uninstallModuleTab($tabClass)
    {
        $idTab = Tab::getIdFromClassName($tabClass);
        if($idTab != 0)
        {
            $tab = new Tab($idTab);
            $tab->delete();
            return true;
        }
        return false;
    }

}
