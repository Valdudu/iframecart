<?php
/**
* 2007-2022 PrestaShop
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
*  @copyright 2007-2022 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}
include(__DIR__.'/class/iframeCart.php');

class Iframecart extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'iframecart';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Valentin Duplan';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('iframe cart');
        $this->description = $this->l('Create carts to be display into iframe in others page or website');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        include(__DIR__ . '/sql/install.php');
        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader');
    }

    public function uninstall()
    {
        include(__DIR__ . '/sql/uninstall.php');

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
        if(Tools::isSubmit('addnew')) {
            return $this->renderForm();
        }
        $this->context->smarty->assign([
            'form' =>            $this->renderList()
        ]);
        return $this->display(__FILE__,'views/templates/admin/configure.tpl');
    }

    protected function renderList(){
        $fields_list = array(
            'id_iframecart' => array(
                'title' => $this->l('ID'),
                'width' => 120,
                'type' => 'text',
                'search' => false,
                'orderby' => false
            ),
            'title' => array(
                'title' => $this->l('Titre'),
                'width' => 420,
                'type' => 'text',
                'search' => true,
                'orderby' => false
            ),
            'active' => array(
                'title' => $this->l('Active'),
                'width' => 140,
                'type' => 'bool',
                'search' => false,
                'orderby' => false,
                'active' => 'status'
            ),
        );
        $datas = FrameCart::getAllCarts();
        $helper = new HelperList();   
        $helper->shopLinkType = '';        
        $helper->simple_header = false;       
        $helper->actions = array('edit', 'delete');
        $helper->identifier = 'id_iframecart';
        $helper->show_toolbar = true;
        $helper->title = $this->l('Cart list');
        $helper->table = 'iframecart';
        $helper->listTotal = sizeof($datas);
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->toolbar_btn['new'] =  array(
            'href' => (Context::getContext()->link->getAdminLink('AdminModules').'&addnew=true&configure='.$this->name),
            'desc' => $this->l('Add new')
        );
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
        .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        return $helper->generateList($datas, $fields_list);
    }


    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }
    protected function renderForm($id=0){
        return $this->display(__FILE__,'views/templates/admin/form.tpl');
    }
}
