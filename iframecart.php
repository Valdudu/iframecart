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
            $this->installTab()&&
            $this->registerHook('backOfficeHeader');
    }

    public function uninstall()
    {
        include(__DIR__ . '/sql/uninstall.php');

        return parent::uninstall() &&
        $this->uninstallTab();
    }
    public function installTab()
    {
        $tab = new Tab();
        $tab->class_name = 'AdminIframeCart';
        $tab->module = $this->name;
        $tab->id_parent = (int)Tab::getIdFromClassName('SELL');
        $tab->icon = 'shopping_basket';
        $tab->name = array();
        foreach(Language::getLanguages() as $lang)
        {
            $tab->name[$lang['id_lang']] = $this->trans('Creation de paniers', array(), 'Modules.orderbyproduct.Admin', $lang['locale']);
        } 
        try {
            $tab->save();
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
        return true;
    }
    public function uninstallTab(){
        $tabId = Tab::getIdFromClassName('AdminIframeCart');
        if($tabId){
            $tab = new Tab($tabId);
            try{
                $tab->delete();
            } catch (Exception $e) {
                echo $e->getMessage();
                return false;
            }
        }
        return true;
    }
    /**
     * Load the configuration form
     */
    public function getContent()
    {
      /*  $this->context->controller->addJqueryUi('ui.autocomplete');
        $this->context->controller->addJS($this->_path.'views/js/back.js');
        Media::addJsDef(['moduleAdminLink' => Context::getContext()->link->getAdminLink('AdminModules', true) . '&configure=' . $this->name . '&module_name=' . $this->name, ]);

        if (Tools::getValue('submitCart') == 1) {
            $this->postProcess();
        }
        elseif(Tools::isSubmit('addnew') || (Tools::isSubmit('updateiframecart') && Tools::getValue('id_iframecart') != '')) {
            return $this->renderForm(Tools::getValue('id_iframecart'));
        }
        elseif (Tools::isSubmit('deleteiframecart') && Tools::getValue('id_iframecart') != '') {
            $cart = new FrameCart(Tools::getValue('id_iframecart'));
            $cart->delete();
        } elseif(Tools::isSubmit('statusiframecart') && Tools::getValue('id_iframecart') != ''){
            $cart = new FrameCart(Tools::getValue('id_iframecart'));
            $cart->changeStatut();
        }
        $this->context->smarty->assign([
            'form' =>            $this->renderList(), 
        ]);
        return $this->display(__FILE__,'views/templates/admin/configure.tpl');*/
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
        $this->context->smarty->assign([ 'cart' =>FrameCart::getCartArray($id)
        ]);
        return $this->display(__FILE__,'views/templates/admin/form.tpl');
    }
    public function ajaxProcessSearchProductName(){
        die(json_encode(Db::getInstance()->executeS('select id_product, name from ' . _DB_PREFIX_ . 'product_lang where id_lang=' . Context::getContext()->language->id . ' and name like \'%' . Tools::getValue('valeur') . '%\'')));
    }
    public function ajaxProcessSearchProductAttributeName(){
        $cleanProducts = array();
        $products = Db::getInstance()->executeS('select pa.id_product_attribute, CONCAT(agl.name, \' - \', al.name) as name
            from '. _DB_PREFIX_.'product_attribute pa 
            join '. _DB_PREFIX_.'product_attribute_combination pac on pac.id_product_attribute = pa.id_product_attribute 
            LEFT JOIN '. _DB_PREFIX_.'attribute a ON a.id_attribute = pac.id_attribute 
            LEFT JOIN '. _DB_PREFIX_.'attribute_lang al on (pac.id_attribute=al.id_attribute and al.id_lang = 1) 
            LEFT JOIN '. _DB_PREFIX_.'attribute_group_lang agl on (a.id_attribute_group = agl.id_attribute_group AND agl.id_lang = 1) where pa.id_product = '.Tools::getValue('valeur2').' 
            ORDER BY pa.id_product_attribute');
        $counter=0;
        foreach($products as $key => $p){
            if($key==0){
                $cleanProducts[$counter]['id_product_attribute'] = $p['id_product_attribute'];
                $cleanProducts[$counter]['name'] = $p['name'];
            } elseif($p['id_product_attribute'] == $cleanProducts[$counter]['id_product_attribute']) {
                $cleanProducts[$counter]['name'] .=' '.$p['name'];
            } else{
                $counter++;
                $cleanProducts[$counter]['id_product_attribute'] = $p['id_product_attribute'];
                $cleanProducts[$counter]['name'] = $p['name'];
            }
        }
        foreach($cleanProducts as $k => $val){
            if(!str_contains(strtolower($val['name']), strtolower(Tools::getValue('valeur1')))){
                unset($cleanProducts[$k]);
            }
        }
        die(json_encode($cleanProducts));
    }
    protected function postProcess(){
        $list = array();
        $pId = Tools::getValue('product');
        $paId = Tools::getValue('product_attribute');
        $qty = Tools::getValue('quantity');
        if(is_array($pId))
        {        
            foreach($pId as $k => $p){
                $list[$k]['id_product'] = $p;
                $list[$k]['id_product_attribute'] = $paId[$k];
                $list[$k]['quantity'] = $qty[$k];
            }
        }
            if(Tools::getValue('cart_id') ==0 ){
            $cart = new FrameCart();
            $cart->title = Tools::getValue('cart_title');
            $cart->active = Tools::getValue('cart_active');
            $cart->create();
        } else {
            $cart = new FrameCart(Tools::getValue('cart_id'));
            $cart->title = Tools::getValue('cart_title');
            $cart->active = Tools::getValue('cart_active');
            $cart->update();
        }
        $cart->updateProducts($list);

    }
}
