{*
* 2007-2017 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<article class="blog-item clearfix">
	{if $blog.image && $config->get('listing_show_image',1)}
		<div class="blog-image col-xs-12 col-sm-6">
			<img src="{$blog.preview_url|escape:'html':'UTF-8'}" title="{$blog.title|escape:'html':'UTF-8'}" alt="" class="img-fluid" />
		</div>
	{/if}
	<div class="col-xs-12 col-sm-6 blog-content-wrap">
		{if $config->get('listing_show_title','1')}
		<h4 class="title media-heading"><a href="{$blog.link|escape:'html':'UTF-8'}" title="{$blog.title|escape:'html':'UTF-8'}">{$blog.title|escape:'html':'UTF-8'}</a></h4>
		{/if}
		<div class="blog-meta">
			{if $config->get('listing_show_author','1')&&!empty($blog.author)}
			<span class="blog-author">
				<i class="material-icons">&#xE853;</i>
				<span class="fa fa-user"> {l s='By' d='Modules.PsBlog.Shop'}:</span> 
				<a href="{$blog.author_link|escape:'html':'UTF-8'}" title="{$blog.author|escape:'html':'UTF-8'}">{$blog.author|escape:'html':'UTF-8'}</a> 
			</span>
			{/if}
			
			{if $config->get('listing_show_category','1')}
			<span class="blog-cat">
				<i class="material-icons">&#xE2C7;</i>
				<span class="fa fa-list"> {l s='In' d='Modules.PsBlog.Shop'}:</span> 
				<a href="{$blog.category_link|escape:'html':'UTF-8'}" title="{$blog.category_title|escape:'html':'UTF-8'}">{$blog.category_title|escape:'html':'UTF-8'}</a>
			</span>
			{/if}
			
			{if $config->get('listing_show_created','1')}
			<span class="blog-created">
				<i class="material-icons">&#xE24D;</i>
				<span class="fa fa-calendar"> {l s='On' d='Modules.PsBlog.Shop'}: </span> 
				<time class="date" datetime="{strtotime($blog.date_add)|date_format:"%Y"|escape:'html':'UTF-8'}">
					{l s=strtotime($blog.date_add)|date_format:"%a"|escape:'html':'UTF-8' d='Modules.PsBlog.Shop'},	<!-- day of week -->
					{l s=strtotime($blog.date_add)|date_format:"%b"|escape:'html':'UTF-8' d='Modules.PsBlog.Shop'}		<!-- month-->
					{l s=strtotime($blog.date_add)|date_format:"%e"|escape:'html':'UTF-8' d='Modules.PsBlog.Shop'},	<!-- day of month -->
					{l s=strtotime($blog.date_add)|date_format:"%Y"|escape:'html':'UTF-8' d='Modules.PsBlog.Shop'}		<!-- year -->
				</time>
			</span>
			{/if}
			
			{if isset($blog.comment_count)&&$config->get('listing_show_counter','1')}	
			<span class="blog-ctncomment">
				<i class="material-icons">&#xE0B9;</i>
				<span class="fa fa-comments-o"> {l s='Comment' d='Modules.PsBlog.Shop'}:</span> 
				{$blog.comment_count|intval}
			</span>
			{/if}

			{if $config->get('listing_show_hit','1')}	
			<span class="blog-hit">
				<i class="material-icons">&#xE068;</i>
				<span class="fa fa-heart"> {l s='Views' d='Modules.PsBlog.Shop'}:</span> 
				{$blog.hits|intval}
			</span>
			{/if}
		</div>


		<div class="blog-shortinfo">
			{if $config->get('listing_show_description','1')}
			{$blog.description|strip_tags:'UTF-8'|truncate:160:'...' nofilter}{* HTML form , no escape necessary *}
			{/if}
			{if $config->get('listing_show_readmore',1)}
			<p>
				<a href="{$blog.link|escape:'html':'UTF-8'}" title="{$blog.title|escape:'html':'UTF-8'}" class="more btn btn-default">{l s='Read more' d='Modules.PsBlog.Shop'}</a>
			</p>
			{/if}
		</div>
	</div>
</article>
	
<!---
	Translation Day of Week - NOT REMOVE
	{l s='Sunday' d='Modules.PsBlog.Shop'}
	{l s='Monday' d='Modules.PsBlog.Shop'}
	{l s='Tuesday' d='Modules.PsBlog.Shop'}
	{l s='Wednesday' d='Modules.PsBlog.Shop'}
	{l s='Thursday' d='Modules.PsBlog.Shop'}
	{l s='Friday' d='Modules.PsBlog.Shop'}
	{l s='Saturday' d='Modules.PsBlog.Shop'}
-->
<!---
	Translation Month - NOT REMOVE
		{l s='January' d='Modules.PsBlog.Shop'}
		{l s='February' d='Modules.PsBlog.Shop'}
		{l s='March' d='Modules.PsBlog.Shop'}
		{l s='April' d='Modules.PsBlog.Shop'}
		{l s='May' d='Modules.PsBlog.Shop'}
		{l s='June' d='Modules.PsBlog.Shop'}
		{l s='July' d='Modules.PsBlog.Shop'}
		{l s='August' d='Modules.PsBlog.Shop'}
		{l s='September' d='Modules.PsBlog.Shop'}
		{l s='October' d='Modules.PsBlog.Shop'}
		{l s='November' d='Modules.PsBlog.Shop'}
		{l s='December' d='Modules.PsBlog.Shop'}
-->