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
 *  @author    ConfigWise <support@configwise.io>
 *  @copyright 2017-2021 ConfigWise
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

class ConfigwiseProduct extends ObjectModel
{
    public $id_configwise_products;

    public $id_shop_default;

    public $id_product;

    public $value;

    public $active;

    public $active_override;

    /**
     * @var string $date_add
     */
    public $date_add;

    /**
     * @var string $date_upd
     */
    public $date_upd;

    /**
     * @var array
     */
    public static $definition = array(
        'table' => 'configwise_products',
        'primary' => 'id_configwise_products',
        'multishop' => true,
        'fields' => array(
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'id_shop_default' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),

            'id_product' => array(
                'type' => self::TYPE_INT,
                'shop' => true,
                'validate' => 'isInt',
                'size' => 256
            ),
            'value' => array(
                'type' => self::TYPE_STRING,
                'shop' => true,
                'validate' => 'isString',
                'size' => 256
            ),
            'active' => array(
                'type' => self::TYPE_BOOL,
                'shop' => true,
                'validate' => 'isBool',
                'size' => 1
            ),
            'active_override' => array(
                'type' => self::TYPE_BOOL,
                'shop' => true,
                'validate' => 'isBool',
                'size' => 1
            ),
        ),
    );

    /**
     * PrsProducts constructor.
     * @param null $id
     * @param null $id_lang
     * @param null $id_shop
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        Shop::addTableAssociation(self::$definition['table'], array('type' => 'shop'));
        parent::__construct($id, $id_lang, $id_shop);
    }

    /**
     * @param bool $auto_date
     * @param bool $null_values
     * @return bool
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function add($auto_date = true, $null_values = false)
    {
        $context = Context::getContext();
        $this->id_shop_default = $context->shop->id;

        return parent::add($auto_date, $null_values);
    }

    /**
     * @param bool $null_values
     * @return bool
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function update($null_values = false)
    {
        $context = Context::getContext();
        $this->id_shop_default = $context->shop->id;

        return parent::update($null_values);
    }

    /**
     * @param $id
     * @return OrderReturns|false
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public static function getByProductId($id_product)
    {
        $shop_id = Context::getContext()->shop->id;

        $id = Db::getInstance()->getValue(
            ' SELECT cw.' . self::$definition['primary'] . ' FROM '
            . _DB_PREFIX_ . static::$definition['table'] . ' AS cw'
            . ' LEFT JOIN ' . _DB_PREFIX_ . self::$definition['table'] . '_shop AS cws '
            . ' ON (cws.' . self::$definition['primary'] . ' = cw.' . self::$definition['primary'] . ' '
            . ' AND cws.`id_shop` = ' . (int)$shop_id . ')'
            . ' WHERE cws.id_product = "' . pSQL($id_product) . '"'
        );

        $configwiseProduct = new OrderReturns($id, null, $shop_id);

        return Validate::isLoadedObject($configwiseProduct) ? $configwiseProduct : false;
    }
}
