<?php
class AdminIframeCartController extends ModuleAdminController
{
    public function __construct(){
        // $this->lang=true;
        $this->bootstrap = true;    
        $this->table = 'iframecart';
        parent::__construct();
        $this->identifier = 'id_iframecart';
        $this->fields_list = $this->buildList();
        $this->actions = array('edit', 'delete');
        $this->show_toolbar = false;
        $this->token = Tools::getAdminTokenLite('AdminIframeCart');
    }

    public function initContent(){
        parent::initContent(); 
        $this->addJqueryUi('ui.autocomplete');
        $this->addJS(_PS_MODULE_DIR_.'iframecart/views/js/back.js');
        Media::addJsDef(['moduleAdminLink' => $this->context->link->getAdminLink('AdminIframeCart')]);
        if (Tools::getValue('submitCart') == 1) {
            $this->postCart();
        }
        elseif(Tools::isSubmit('addiframecart') || (Tools::isSubmit('updateiframecart') && Tools::getValue('id_iframecart') != '')) {
            $this->context->smarty->assign([ 
                'cart' =>FrameCart::getCartArray((Tools::getValue('id_iframecart')===false?0:Tools::getValue('id_iframecart'))),
                'back_url' => $this->context->link->getAdminLink('AdminIframeCart'),
            ]);
            $this->setTemplate('form.tpl');
        }
        elseif (Tools::isSubmit('deleteiframecart') && Tools::getValue('id_iframecart') != '') {
            $cart = new FrameCart(Tools::getValue('id_iframecart'));
            $cart->delete();
        } elseif(Tools::isSubmit('statusiframecart') && Tools::getValue('id_iframecart') != ''){
            $cart = new FrameCart(Tools::getValue('id_iframecart'));
            $cart->changeStatut();
        }
    }
    private function buildList(): array{
        return array(
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
    }
    public function renderAddiframecart($id=0){
        $this->context->smarty->assign([ 'cart' =>FrameCart::getCartArray($id)
        ]);
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.'iframecart/views/templates/admin/form.tpl');
    }
    public function postCart(){
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
        if(Tools::getValue('cart_id') == 0 ){
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
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminIframeCart'));

    }
    public function ajaxProcessSearchProductName(){
        die(json_encode(Db::getInstance()->executeS('select id_product, name from ' . _DB_PREFIX_ . 'product_lang where id_lang=' . Context::getContext()->language->id . ' and name like \'%' . Tools::getValue('valeur') . '%\'')));
    }
    public function ajaxProcessSearchProductAttributeName(){
        $cleanProducts = array();
        $products = Db::getInstance()->executeS('select pa.id_product_attribute, CONCAT(agl.name, \' - \', al.name) as name
            from '. _DB_PREFIX_.'product_attribute pa 
            JOIN '. _DB_PREFIX_.'product_attribute_combination pac on pac.id_product_attribute = pa.id_product_attribute 
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
}