<form id='module_form' method='POST' action='' validate class='defaultForm form-horizontal'>
    <div class="panel col-lg-12">
        <div class="panel-heading">		
            <i class="icon-cogs"></i>	
            HelperList
        </div>                       

        <div class="form-wrapper">
            <div class="form-group">
                <label class="control-label col-lg-3 required">
                    {l s='Cart title' mod='iframecart'}
                </label>
                <div class="col-lg-6">
                    <input type="text" name="cart_title"  required="required">   
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3 required">
                    {l s='Active' mod='iframecart'}
                </label>
                <div class="col-lg-3">
                    <div class="input-group">				
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="cart_active" id="cart_active_on" value="1" checked="checked">
                            <label for="cart_active_on">Oui</label>
                            <input type="radio" name="cart_active" id="cart_active_off" value="0">
                            <label for="cart_active_off">Non</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" value="1" id="module_form_submit_btn" name="submitCart" class="btn btn-default pull-right">
                <i class="process-icon-save"></i> Enregistrer
            </button>
        </div>    
    </div>
</form>