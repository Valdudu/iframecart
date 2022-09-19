<?php
include(__DIR__.'/iframeCartDetail.php');

class FrameCart{
    public $id;
    public $title;
    public $active;
    private $db;
    public function __construct($id=0){
        $this->db = Db::getInstance();
        $this->id = $id;
    }
    public static function getAllCarts(): array{
        return Db::getInstance()->executeS('select * from '._DB_PREFIX_.'iframecart');
    }
    public static function getCartArray($id):array{
        $cart = array();
        $cart = Db::getInstance()->executeS('select * from ' . _DB_PREFIX_ . 'iframecart where id_iframecart=' . $id);
        if(sizeof($cart)==0){
            $cart = ['id_iframecart' => 0, 'title' => '', 'active' => 1, 'cart_list' => array()];
        } else{
            $cart = $cart[0];      
            $cart['cart_list'] = IframeCartDetail::getProductWithTitle($id);
        }
        return $cart;
    }

    public function create(){
        $this->db->execute('INSERT INTO ' . _DB_PREFIX_ . 'iframecart (title, active) VALUES (\'' . $this->title . '\', ' . $this->active . ')');
        $this->id = (int)$this->db->Insert_ID();
    }
    public function update(){
        $this->db->execute('UPDATE ' . _DB_PREFIX_ . 'iframecart set title=\'' . $this->title . '\' , active= ' . $this->active . ' where id_iframecart='.$this->id);
    }
    private function cleanProducts(){
        $this->db->execute('delete from ' . _DB_PREFIX_ . 'iframecart_detail where id_iframecart=' . $this->id);
    }
    public function updateProducts($lists){
        $this->cleanProducts();
        foreach($lists as $l){
            $this->db->execute('INSERT INTO ' . _DB_PREFIX_ . 'iframecart_detail (id_iframecart, id_product, id_product_attribute, quantity) 
                VALUES ('.$this->id.', '.$l['id_product'].', '.$l['id_product_attribute'].', '.$l['quantity'].')');
        }
    }
    public function changeStatut(){
        $this->db->execute('UPDATE ' . _DB_PREFIX_ . 'iframecart set active =(1 - active) where id_iframecart=' . $this->id);
    }
    public function delete(){
        $this->db->execute('delete from ' . _DB_PREFIX_ . 'iframecart where id_iframecart=' . $this->id);

    }
}