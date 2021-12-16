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

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_ . 'configwise/include.php';

class Configwise extends Module
{
    protected $config_form = false;

    protected $notices = [];

    public const CONFIGWISE_PRODUCT_ATTR_REFERENCE = 'reference';

    public const CONFIGWISE_PRODUCT_ATTR_ID_PRODUCT = 'id';
    public const CONFIGWISE_LANGUAGE_EN = 'en';
    public const CONFIGWISE_LANGUAGE_NL = 'nl';

    public function __construct()
    {
        $this->name = 'configwise';
        $this->tab = 'content_management';
        $this->version = '1.0.0';
        $this->author = 'ConfigWise';
        $this->need_instance = 1;

        $this->module_key = '';

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('ConfigWise AR');
        $this->description = $this->l('AR Content Management for your e-commerce.
            Free of charge high quality AR Content of your products with
            Augmented Reality (app and web), to drive sales and reduce returns.');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('CONFIGWISE_LIVE_MODE', false);

        include(dirname(__FILE__) . '/sql/install.php');

        return parent::install() &&
            $this->registerHook('actionProductUpdate') &&
            $this->registerHook('displayAdminProductsExtra') &&
            $this->registerHook('displayAfterProductThumbs');
    }

    public function uninstall()
    {
        Configuration::deleteByName('CONFIGWISE_LIVE_MODE');

        include(dirname(__FILE__) . '/sql/uninstall.php');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitConfigwiseModule')) == true) {
            $this->postProcess();
        }

        $errors = '';
        if (!empty($this->_errors)) {
            $errors = $this->displayError(implode('<br />', $this->_errors));
        }

        $notices = '';
        if (!empty($this->notices)) {
            $notices = $this->displayInformation(implode('<br />', $this->notices));
        }

        return $errors . $notices . $this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitConfigwiseModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Live mode'),
                        'name' => 'CONFIGWISE_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in live mode'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No')
                            )
                        ),
                    ),
                    array(
                        'label' => $this->l('Product ID'),
                        'name' => 'CONFIGWISE_PRODUCT_ATTR',
                        'required' => true,
                        'type' => 'select',
                        'options' => array(
                            'query' => array(
                                array(
                                    'id' => self::CONFIGWISE_PRODUCT_ATTR_REFERENCE,
                                    'name' => 'SKU'
                                ),
                                array(
                                    'id' => self::CONFIGWISE_PRODUCT_ATTR_ID_PRODUCT,
                                    'name' => 'Product ID'
                                ),
                            ),
                            'id' => 'id',
                            'name' => 'name',
                        )
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'required' => true,
                        'desc' => $this->l('Enter channel ID'),
                        'name' => 'CONFIGWISE_CHANNEL_ID',
                        'label' => $this->l('Channel ID'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'required' => true,
                        'desc' => $this->l('Domain'),
                        'name' => 'CONFIGWISE_DOMAIN',
                        'label' => $this->l('Domain'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'required' => true,
                        'desc' => $this->l('Enter company reference number'),
                        'name' => 'CONFIGWISE_COMPANY_REFERENCE_NUMBER',
                        'label' => $this->l('Company reference number'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'CONFIGWISE_LIVE_MODE' => Configuration::get('CONFIGWISE_LIVE_MODE'),
            'CONFIGWISE_CHANNEL_ID' => Configuration::get('CONFIGWISE_CHANNEL_ID'),
            'CONFIGWISE_PRODUCT_ATTR' => Configuration::get('CONFIGWISE_PRODUCT_ATTR'),
            'CONFIGWISE_DOMAIN' => Configuration::get('CONFIGWISE_DOMAIN'),
            'CONFIGWISE_COMPANY_REFERENCE_NUMBER' => Configuration::get('CONFIGWISE_COMPANY_REFERENCE_NUMBER'),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            $value = Tools::getValue($key);

            if (!empty($value) || $key == 'CONFIGWISE_LIVE_MODE') {
                Configuration::updateValue($key, $value);
            } else {
                $label = $this->getLabelByName($key);
                $this->_errors[] = $this->trans(
                    "The filed: " . $label . " can not be empty",
                    [],
                    'Modules.Configwise.Admin'
                );
            }
        }

        if (empty($this->_errors)) {
            $this->notices[] = $this->trans(
                "The fields have been saved",
                [],
                'Modules.Configwise.Admin'
            );
        }
    }

    protected function getLabelByName($key)
    {
        $label = false;
        foreach ($this->getConfigForm()['form']['input'] as $field) {
            if ($key == $field['name']) {
                $label = $field['label'];
                break;
            }
        }

        return $label;
    }

    public function hookDisplayAfterProductThumbs($params)
    {
        $product_id = null;
        $product = new Product(Tools::getValue('id_product'));
        if (Validate::isLoadedObject($product)) {
            switch (Configuration::get('CONFIGWISE_PRODUCT_ATTR')) {
                case self::CONFIGWISE_PRODUCT_ATTR_ID_PRODUCT:
                    $product_id = $product->{self::CONFIGWISE_PRODUCT_ATTR_ID_PRODUCT};
                    break;
                case self::CONFIGWISE_PRODUCT_ATTR_REFERENCE:
                    $product_id = $product->{self::CONFIGWISE_PRODUCT_ATTR_REFERENCE};
                    break;
            }
        }

        if ($configWiseProduct = ConfigwiseProduct::getByProductId(Tools::getValue('id_product'))) {
            if (!$configWiseProduct->active) {
                return;
            }
            if ($configWiseProduct->active_override) {
                $product_id = $configWiseProduct->value;
            }
        }

        $this->context->smarty->assign([
            'mode' => Configuration::get('CONFIGWISE_LIVE_MODE'),
            'channel_id' => Configuration::get('CONFIGWISE_CHANNEL_ID'),
            'product_id' => $product_id,
            'domain' => Configuration::get('CONFIGWISE_DOMAIN'),
            'company_product_number' => Configuration::get('CONFIGWISE_COMPANY_REFERENCE_NUMBER'),
            'language' => $this->context->language->iso_code,
        ]);

        return $this->display(__FILE__, 'views/templates/front/configwise.tpl');
    }

    public function hookDisplayAdminProductsExtra($params)
    {
        $use = true;
        $activeOverride = false;
        $product_id = '';
        $idProduct = $params['id_product'];
        if ($configWiseProduct = ConfigwiseProduct::getByProductId($idProduct)) {
            $product_id = $configWiseProduct->value;
            $use = (bool)$configWiseProduct->active;
            $activeOverride = (bool)$configWiseProduct->active_override;
        }
        $this->context->smarty->assign([
            'CONFIGWISE_USE' => $use,
            'CONFIGWISE_PRODUCT_ID' => $product_id,
            'CONFIGWISE_ACTIVE_OVERRIDE' => $activeOverride,
        ]);

        return $this->display(__FILE__, 'views/templates/admin/product_form.tpl');
    }

    public function hookActionProductUpdate($params)
    {
        if (Tools::isSubmit('CONFIGWISE_PRODUCT_ID')) {
            $idProduct = $params['id_product'];
            if (!$configWiseProduct = ConfigwiseProduct::getByProductId($idProduct)) {
                $configWiseProduct = new ConfigwiseProduct();
                $configWiseProduct->id_product = (int)$idProduct;
            }

            $configWiseProduct->value = (string)Tools::getValue('CONFIGWISE_PRODUCT_ID');
            $configWiseProduct->active = (bool)Tools::getValue('CONFIGWISE_USE');
            $configWiseProduct->active_override = (bool)Tools::getValue('CONFIGWISE_ACTIVE_OVERRIDE');
            $configWiseProduct->save();
        }
    }
}
