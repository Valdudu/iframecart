<?php
class IframeCartDetail{
    private $id_iframe='';
    function __construct($id_iframe){
        $this->id_iframe = $id_iframe;
    }
    public static function getProductWithTitle($id_iframe): array{
        $cleanProducts = array();
        $products = Db::getInstance()->executeS('select id.id_product, id.id_product_attribute, pl.name, CONCAT(agl.name, \' - \', al.name) as attribute_name, id.quantity
            from '. _DB_PREFIX_.'iframecart_detail id 
            JOIN '._DB_PREFIX_.'product_lang pl on (id.id_product=pl.id_product and pl.id_lang = '.Context::getContext()->language->id.')
            left join '. _DB_PREFIX_.'product_attribute_combination pac on pac.id_product_attribute = id.id_product_attribute 
            LEFT JOIN '. _DB_PREFIX_.'attribute a ON a.id_attribute = pac.id_attribute 
            LEFT JOIN '. _DB_PREFIX_.'attribute_lang al on (pac.id_attribute=al.id_attribute and al.id_lang = '.Context::getContext()->language->id.') 
            LEFT JOIN '. _DB_PREFIX_.'attribute_group_lang agl on (a.id_attribute_group = agl.id_attribute_group AND agl.id_lang = '.Context::getContext()->language->id.') 
            where id.id_iframecart = '.$id_iframe.' 
            ORDER BY id.id_iframecart, id.id_iframecart_detail');
        $counter=0;
        foreach($products as $k => $p){
            if($k==0){
                $cleanProducts[$counter]['id_product']=$p['id_product'];
                $cleanProducts[$counter]['product_name']=$p['name'];
                $cleanProducts[$counter]['id_product_attribute']=$p['id_product_attribute'];
                $cleanProducts[$counter]['product_attribute_name'] = $p['attribute_name'];
                $cleanProducts[$counter]['quantity']=$p['quantity'];
            } elseif($cleanProducts[$counter]['id_product'] == $p['id_product'] && $cleanProducts[$counter]['id_product_attribute'] == $p['id_product_attribute'])
            {
                $cleanProducts[$counter]['product_attribute_name'] .=' '. $p['attribute_name'];
            } else  {
                $counter++;
                $cleanProducts[$counter]['id_product']=$p['id_product'];
                $cleanProducts[$counter]['product_name']=$p['name'];
                $cleanProducts[$counter]['id_product_attribute']=$p['id_product_attribute'];
                $cleanProducts[$counter]['product_attribute_name'] = $p['attribute_name'];
                $cleanProducts[$counter]['quantity']=$p['quantity'];
            }
        }
        //need nom, id_product, id_product_attribute, id_product_name
        return $cleanProducts;
    }


}