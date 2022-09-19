<?php
class IframecartDisplayModuleFrontController extends ModuleFrontController
{

    public function __construct()
    {
        parent::__construct();

        $this->context = Context::getContext();

    }
    public function init()
    {
        parent::init();
    }
    public function initContent()
    {
        if(Tools::getValue('id')){
            $iCart = FrameCart::getCartArray(Tools::getValue('id'));
            if($iCart['id_iframecart']!=0){
                if($iCart['active'] == 1)
                {                
                    if(Tools::isSubmit('addCart')){
                        //atc
                        $cart = Context::getContext()->cart;
                        if(!$cart->id){
                            $cart = new Cart();
                            $cart->id_lang = (int)($this->context->cookie->id_lang);
                            $cart->id_currency = (int)($this->context->cookie->id_currency);
                            $cart->add();
                            $this->context->cookie->id_cart = (int)($cart->id);    
                            $cart->update();
                        }
                        $pId = Tools::getValue('pId');    
                        $qty = Tools::getValue('qty');    
                        $paId = Tools::getValue('paId');    
                        foreach($pId as $key => $p){
                            $cart->updateQty($qty[$key], $pId[$key], $paId[$key]);
                        }         
                        Tools::redirect('order');
                    } else{
                        $iCart['cart_list'] = IframeCartDetail::getAssembledProducts($iCart['cart_list']);
                        $this->context->smarty->assign([
                            "cart" =>$iCart,
                        ]);
                        $this->template = _PS_MODULE_DIR_ . 'iframecart/views/templates/front/display.tpl';
                    }
                } else {
                    Tools::redirect('index.php');   
                }
  
            } else {
                Tools::redirect('index.php');   
            }
        } else {
            Tools::redirect('index.php');   
        }
    }
}