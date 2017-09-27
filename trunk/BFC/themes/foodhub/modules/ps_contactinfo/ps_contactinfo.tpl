{*
* 2007-2015 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="block-contact col-md-4 links wrapper">
  
   		<h3 class="text-uppercase block-contact-title hidden-sm-down"><a href="{$urls.pages.stores}">{l s='Store information' d='Shop.Theme.Global'}</a></h3>
      
		<div class="title clearfix hidden-md-up" data-target="#block-contact_list" data-toggle="collapse">
		  <span class="h3">{l s='Store information' d='Shop.Theme.Global'}</span>
		  <span class="pull-xs-right">
			  <span class="navbar-toggler collapse-icons">
				<i class="material-icons add">&#xE313;</i>
				<i class="material-icons remove">&#xE316;</i>
			  </span>
		  </span>
		</div>
	  
	  <ul id="block-contact_list" class="collapse">
	  <span class="icon"><i class="material-icons">&#xE55F;</i></span>
	  <span class="data">{$contact_infos.address.formatted nofilter}</span>
	  
      {if $contact_infos.phone}
        <span class="icon"><i class="material-icons">&#xE0CD;</i></span>
		<span class="data">
        {* [1][/1] is for a HTML tag. *}
        {l s='Call us: [1]%phone%[/1]'
          sprintf=[
          '[1]' => '<span>',
          '[/1]' => '</span>',
          '%phone%' => $contact_infos.phone
          ]
          d='Shop.Theme.Global'
        }
		</span>
      {/if}
      {if $contact_infos.fax}
        <span class="icon"><i class="material-icons">&#xE0DF;</i></span>
		<span class="data">
        {* [1][/1] is for a HTML tag. *}
        {l
          s='Fax: [1]%fax%[/1]'
          sprintf=[
            '[1]' => '<span>',
            '[/1]' => '</span>',
            '%fax%' => $contact_infos.fax
          ]
          d='Shop.Theme.Global'
        }
		</span>
      {/if}
      {if $contact_infos.email}
        <span class="icon"><i class="material-icons">&#xE158;</i></span>
		  <span class="data email">
        {* [1][/1] is for a HTML tag. *}
        {l
          s='Email us: [1]%email%[/1]'
          sprintf=[
            '[1]' => '<span>',
            '[/1]' => '</span>',
            '%email%' => $contact_infos.email
          ]
          d='Shop.Theme.Global'
        }
		</span>
      {/if}
	  </ul>
  
</div>