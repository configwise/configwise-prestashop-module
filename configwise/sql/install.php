<?php
/**
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
 * @author    ConfigWise <support@configwise.io>
 * @copyright 2017-2021 ConfigWise
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'configwise_products ('
    . '`id_configwise_products` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, '
    . '`id_shop_default` INT(11) NOT NULL, '
    . '`id_product` INT(11) NOT NULL, '
    . '`value` CHAR(255) NOT NULL, '
    . '`active` BOOL NOT NULL, '
    . '`active_override` BOOL NOT NULL, '
    . '`date_add` TIMESTAMP, '
    . '`date_upd` TIMESTAMP '
    . ') ENGINE=' . _MYSQL_ENGINE_ . ' CHARACTER SET=UTF8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'configwise_products_shop ('
    . '`id_configwise_products` INT(11) NOT NULL, '
    . '`id_shop` INT(11) NOT NULL, '
    . '`id_product` INT(11) NOT NULL, '
    . '`value` CHAR(255) NOT NULL, '
    . '`active` BOOL NOT NULL, '
    . '`active_override` BOOL NOT NULL, '
    . 'UNIQUE KEY configwise_products_shop (`id_configwise_products`, `id_shop`) '
    . ') ENGINE=' . _MYSQL_ENGINE_ . ' CHARACTER SET=UTF8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
