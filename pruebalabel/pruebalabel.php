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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2021 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Pruebalabel extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'pruebalabel';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Ruben';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('pruebalabel');
        $this->description = $this->l('modulo para la tarea numero 5 ');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install() {
        if (!parent::install() || !$this->_installSql()
                
                || ! $this->registerHook('displayProductButtons')
                || ! $this->registerHook('displayAdminProductsMainStepLeftColumnMiddle')       
        ) {
            return false;
        }
 
        return true;
    }
 
    public function uninstall() {
        return parent::uninstall() && $this->_unInstallSql();
    }
 
    
    protected function _installSql() {
        $sqlInstall = "ALTER TABLE " . _DB_PREFIX_ . "product "
                . "ADD custom_field VARCHAR(255) NULL";
        $sqlInstallLang = "ALTER TABLE " . _DB_PREFIX_ . "product_lang "
                . "ADD custom_field_lang VARCHAR(255) NULL,"
                . "ADD custom_field_lang_wysiwyg TEXT NULL";
 
        $returnSql = Db::getInstance()->execute($sqlInstall);
        $returnSqlLang = Db::getInstance()->execute($sqlInstallLang);
 
        return $returnSql && $returnSqlLang;
    }
 
   
    protected function _unInstallSql() {
       $sqlInstall = "ALTER TABLE " . _DB_PREFIX_ . "product "
                . "DROP custom_field";
        $sqlInstallLang = "ALTER TABLE " . _DB_PREFIX_ . "product_lang "
                . "DROP custom_field_lang,DROP custom_field_lang_wysiwyg";
 
        $returnSql = Db::getInstance()->execute($sqlInstall);
        $returnSqlLang = Db::getInstance()->execute($sqlInstallLang);
 
        return $returnSql && $returnSqlLang;
    }
 
    public function hookDisplayProductButtons($params)
    {

        $this->context->smarty->assign(array(
            'custom_field' => $params['product']['custom_field'])

        );
      return $this->display(__FILE__, 'views/templates/hook/vista.tpl');
       
       
    }
                 
 
    public function hookDisplayAdminProductsMainStepLeftColumnMiddle($params) {
        $product = new Product($params['id_product']);
        $this->context->smarty->assign(array(
            'custom_field' => $product->custom_field,
            'default_language' => $this->context->employee->id_lang,
            )
           );
           return $this->display(__FILE__, 'views/templates/hook/amproductfields.tpl');
    }
}
