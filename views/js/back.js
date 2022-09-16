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
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/
var counter = 0;
$(document).ready(function () {


});


function createProductRow() {
    var product = '<div class="col-lg-offset-3 col-lg-3"><input type="text" data-id="'+counter.toString()+'" class="product_name'+counter.toString()+'"  required><p class="help-block">Product name.</p><input type="hidden" class="product_id'+counter.toString()+'" name="product[]" required></div>';
    var product_attribute = '<div class="col-lg-3"><input type="text" data-id="'+counter.toString()+'" class="product_attribute_name'+counter.toString()+'" disabled><p class="help-block">Product attribute.</p><input type="hidden" class="product_attribute_id'+counter.toString()+'" name="product_attribute[]" value="0"></div>';
    var quantity = '<div class="col-lg-1"><input type="text" value ="1" name="quantity[]" required><p class="help-block">Quantity.</p></div>';
    var deleteBtn = '<div class="col-lg-1"><a class="btn tooltip-link delete pl-0 pr-0"" style="display:inline !important;"><i class="material-icons">delete</i></a></div>';
    $('.js_auto').append('<div class="form-group">' + product + product_attribute + quantity + deleteBtn + '</div>');   
    $('.product_name'+counter.toString()).autocomplete({
        source: function (request, response) {
            $.ajax({
                url : moduleAdminLink,
                data :{
                  ajax : true,
                  valeur : request.term,
                  dataType: "json",
                  action : 'SearchProductName',
                },
                success: function (d) {
                    var res = JSON.parse(d);
                    response($.map(res, function (value, key) {
                        return {
                            label: value.name,
                            value: value.name,
                            key: value.id_product
                        }
                    }));
                }
            })
        },
        minLength: 3,
        select: function (event, ui) {
            console.log($(this).attr('data-id'));
            $('.product_id' +$(this).attr('data-id')).val(ui.item.key);
            $('.product_attribute_name' + $(this).attr('data-id')).attr("disabled", false);
        },
        change: function (event, ui) {
            if (ui.item === null) {
                let id = $(this).attr('data-id');
                console.log($(this).attr('data-id'));
                $('.product_id' + id).val(0);
                $('.product_attribute_name' + id).attr("disabled", true);
                $('.product_attribute_name' + id).val('');
                $('.product_attribute_id' + id).val(0);
            }
          }
    });
    $('.product_attribute_name' + counter.toString()).autocomplete({
        source: function (request, response) {
            $.ajax({
                url : moduleAdminLink,
                data :{
                  ajax : true,
                    valeur1: request.term,
                    valeur2: $('.product_id'+this.bindings.first().attr('data-id')).val(),
                  dataType: "json",
                  action : 'SearchProductAttributeName',
                },
                success: function (d) {
                    console.log(d);
                    var res = JSON.parse(d);
                    response($.map(res, function (value, key) {
                        return {
                            label: value.name,
                            value: value.name,
                            key: value.id_product_attribute
                        }
                    }));
                }
            })
        },
        minLength: 0,
        select: function (event, ui) {
            console.log(ui.item.key);
            $('.product_attribute_id' +$(this).attr('data-id')).val(ui.item.key);

        },
        change: function (event, ui) {
            if (ui.item === null) {
                let id = $(this).attr('data-id');
                console.log($(this).attr('data-id'));;
                $('.product_attribute_id' + id).val(0);
            }
          }
    });
    counter++;
}



$('.delete').on('click', function () {
    console.log(this);    
});
$(document).on('click', '.delete', function(){
    $('#addFeature').attr("value", parseInt($('#addFeature').val())-1);
    $(this).parent().parent().remove();
});