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

{if $mode}
  {if !empty($channel_id) && !empty($domain) && !empty($company_product_number) && !empty($product_id)}
    <div id="configwise" style="position: relative; height: 400px;">
      <script type="text/javascript"
              src="https://ar.configwise.io/configwise/canvas/web-viewer.js?product_id={$product_id|escape:'html':'UTF-8'}&channel_id={$channel_id|escape:'html':'UTF-8'}&domain={$domain|escape:'html':'UTF-8'}&company_reference_number={$company_product_number|escape:'html':'UTF-8'}&language={$language|escape:'html':'UTF-8'}"></script>
      <div>
  {/if}
{else}
    <div id="configwise" style="position: relative; height: 400px;">
      <script type="text/javascript"
              src="https://ar.configwise.io/configwise/canvas/web-viewer.js?product_id=CONFIGWISE_TEST_EXAMPLE_CHAIR&channel_id=fbd6bc02-1bd3-446f-9ee9-9a3412f4d064&domain=d&company_reference_number=fbd6bc02-1bd3-446f-9ee9-9a3412f4d064&language={$language|escape:'html':'UTF-8'}"></script>
      <div>
{/if}
