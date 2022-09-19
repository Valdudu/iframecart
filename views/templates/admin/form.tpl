<form id='module_form' method='POST' action='' validate class='defaultForm form-horizontal' autocomplete="off">
    <input type="hidden" name="cart_id" value="{$cart.id_iframecart}">
    <div class="panel col-lg-12">
        <div class="panel-heading">		
            <i class="icon-cogs"></i>	
            {l s='Création d\'un nouveau panier' mod='iframecart'}
        </div>                       
        <div class="form-wrapper">
            <div class="form-group">
                <label class="control-label col-lg-3 required">
                    {l s='Cart title' mod='iframecart'}
                </label>
                <div class="col-lg-6">
                    <input type="text" name="cart_title"  value="{$cart.title}"required="required">   
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-3 required">
                    {l s='Active' mod='iframecart'}
                </label>
                <div class="col-lg-3">
                    <div class="input-group">				
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="cart_active" id="cart_active_on" value="1" {if $cart.active==1 }checked="checked"{/if}>
                            <label for="cart_active_on">Oui</label>
                            <input type="radio" name="cart_active" id="cart_active_off" value="0"{if $cart.active==0 }checked="checked"{/if}>
                            <label for="cart_active_off">Non</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                </div>
            </div>            
            <div class="js_auto">
                {foreach $cart.cart_list as $k => $c}
                    <div class="form-group">
                        <div class="col-lg-offset-3 col-lg-3">
                            <input type="text" data-id="{$k}" class="product_name{$k}"  value="{$c.product_name}" required>
                            <p class="help-block">Product name.</p>
                            <input type="hidden" class="product_id{$k}" name="product[]" value="{$c.id_product}" required>
                        </div>
                        <div class="col-lg-3">
                            <input type="text" data-id="{$k}" value="{$c.product_attribute_name}" class="product_attribute_name{$k}" >
                            <p class="help-block">Product attribute.</p>
                            <input type="hidden" class="product_attribute_id{$k}" name="product_attribute[]" value="{$c.id_product_attribute}" >
                        </div>
                        <div class="col-lg-1">
                            <input type="text" value ="{$c.quantity}" name="quantity[]" required>
                            <p class="help-block">Quantity.</p>
                        </div>
                        <div class="col-lg-1">
                            <a class="btn tooltip-link delete pl-0 pr-0" style="display:inline !important;">
                                <i class="material-icons">delete</i>
                            </a>
                        </div>
                    </div>
                {/foreach}
            </div>
            <div class="form-group">
                <div class="col-lg-offset-3 col-lg-3">
                    <button type="button" class="btn btn-primary" onclick="createProductRow();"> 
                        {l s='Add new product' mod='iframecart'}
                    </button>
                </div>
            </div>
        </div>
        <div class="panel-footer">
        <a class="btn btn-default pull-left" href="{$back_url}">                <i class="process-icon-cancel"></i> Rentour
        </a>

            <button type="submit" value="1" id="module_form_submit_btn" name="submitCart" class="btn btn-default pull-right">
                <i class="process-icon-save"></i> Enregistrer
            </button>
        </div> 

    </div>
</form>
