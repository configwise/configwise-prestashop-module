{*
* 2007-2021 PrestaShop
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
*  @author    ConfigWise <support@configwise.io>
*  @copyright 2017-2021 ConfigWise
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div id="quantities" style="">
  <h2>Settings</h2>
  <fieldset class="form-group">
    <div class="row" style="margin-bottom: 5%;">
      <div class="col-md-12">
        <label class="form-control-label">{l s='Active' mod='configwise'}</label>
        <div class="switch-input-lg" id="CONFIGWISE_USE_SWITCH">
          <input class="switch-input-lg" id="CONFIGWISE_USE"
                 data-toggle="switch" type="checkbox"
                 name="CONFIGWISE_USE" {if $CONFIGWISE_USE}checked="checked"{/if} value="{$CONFIGWISE_USE|intval}">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-5">
        <label class="form-control-label">{l s='Product ID' mod='configwise'}</label>
        <input type="text" id="CONFIGWISE_PRODUCT_ID" name="CONFIGWISE_PRODUCT_ID" required="required"
               class="form-control" value="{$CONFIGWISE_PRODUCT_ID|escape:'html':'UTF-8'}">
      </div>
    </div>
  </fieldset>
</div>
