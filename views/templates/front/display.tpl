<div class="container">
    <h1>Nos produits pour la recette {$cart.title}</h1>
    <form method="post">
        <table>
            <thead style="background:#e76087;color: #fff;padding:10px;">
                <tr>
                    <th class="cart_product first_item" style="padding: 10px;">Produit</th>
                    <th class="cart_description item" style="padding: 10px;">Description</th>
                    <th class="cart_avail item" style="padding: 10px;">Disponibilité</th>
                    <th class="cart_unit item" style="padding: 10px;">Prix unitaire</th>
                    <th class="cart_quantity last_item" style="padding: 10px;">Quantité</th>
                </tr>
            </thead>
            <tbody>
                {foreach $cart.cart_list as $p}
                    <tr class="cart_item last_item first_item odd" style="border-bottom:1px solid #d6d4d4;">
                        <td class="cart_product">
                            <a href="{$p.link}" target="_blank">
                                <img src="{$p.image}" alt="Chocolate Rainbow Frostings" width="98" height="98">
                            </a>
                        </td>
                        <td class="cart_description">
                            <p class="product-name">
                                <a href="{$p.link}" target="_blank" style="color: #e76087;font-size:22px;text-decoration: none;">
                                    {$p.product_name} {$p.product_attribute_name}
                                </a>
                            </p>
                            {*<small class="cart_ref">SKU : 4-RND073</small>*}
                        </td>
                        <td class="cart_avail">
                            <span class="label label-success" style="color: #13b9ec;font-weight:bold;">{$p.available}</span>
                        </td>
                        <td class="cart_unit" data-title="Prix unitaire">
                            <span class="price"> </span>
                            <span class="price" style="font-size:22px;font-weight:bold;color: #e76087;">{$p.price}</span>                   
                        </td>
                        <td class="cart_quantity text-center">
                            <input type="hidden" name="pId[]" value ="{$p.id_product}">
                            <input type="hidden" name="paId[]" value ="{$p.id_product_attribute}">
                            <input size="2" type="text" class="cart_quantity_input form-control grey" value="{$p.quantity}"  name="qty[]" >
                            <div class="cart_quantity_button clearfix">
                                <a class="cart_quantity_down btn btn-default button-minus" rel="#quantity_258" href="#" title="Soustraire">
                                    <span><i class="icon-minus"></i></span>
                                </a>
                                <a class="cart_quantity_up btn btn-default button-plus" rel="#quantity_258" href="#" title="Ajouter">
                                    <span><i class="icon-plus"></i></span>
                                </a>
                            </div>
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
        <p class="cart_navigation clearfix">
            <button type="submit" name="addCart" value="1" target="_blank" class="button"  title="Ajouter au panier" style="cursor: pointer; background-color: #e76087;color: #fff;display: block;font-weight: 700;float: right;text-decoration: none;padding: 10px 15px;border-radius: 22px;">
                <span><i class="icon-shopping-cart right"></i> Ajouter au panier</span>
            </button>
        </p>
    </form>
</div>
<style>
    table{
        width: 100%;
    }
    td{
        text-align:center;
    }
</style>