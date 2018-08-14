<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo plugins_url( 'website-custom-plugin/WCP/assets/css/bootstrap.min.css' ); ?>" >
<script type="text/javascript" src="<?php echo plugins_url( 'website-custom-plugin/WCP/assets/js/bootstrap.min.js' ); ?>"></script>
<script type="text/javascript" src="<?php echo plugins_url( 'website-custom-plugin/WCP/assets/js/jquery.blockUI.min.js' ); ?>"></script>

<div class="flex_container" style="padding-top:20px;">

    <div class="container">
        <h3>Payment Congfiguration</h3>
        <hr style="background-color:#000000; height:2px;">
        <div style="padding-bottom:10px;">
            <div style="clear:both;"></div>
        </div>

        <form class="form-horizontal" name="frmPaypalConfiguration" id="frmPaypalConfiguration" action="" onsubmit="return false;">
            <div class="form-group">
                <label class="control-label col-sm-3" for="email">Paypal Mode :</label>
                <div class="col-sm-9">
                    <select class="form-control" name="wcp_paypal_mode" id="wcp_paypal_mode">
                        <option value="sandbox">Sandbox</option>
                        <option value="production">Production</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3" for="pwd">Paypal Sandbox App Key</label>
                <div class="col-sm-9">
                    <input type="paypal_sandbox_app_key" class="form-control" id="wcp_paypal_sandbox_app_key" placeholder="Enter Paypal Sandbox App Key">
                </div>
            </div>
            
            <div class="form-group">
                <label class="control-label col-sm-3" for="pwd">Paypal Production App Key</label>
                <div class="col-sm-9">
                    <input type="paypal_production_app_key" class="form-control" id="wcp_paypal_production_app_key" placeholder="Enter Production App Key">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3" for="pwd">Product Price:</label>
                <div class="col-sm-9">
                    <input type="product_price" class="form-control" id="wcp_product_price" placeholder="Enter Product Price">
                </div>
            </div>
            <p id="err_msg" style="display:none;">&nbsp;</p>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    <button type="submit" class="btn btn-default" onclick="submitPaypalConfig()">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
function submitPaypalConfig()
{
    var err_msg = $("#err_msg");
    if (!$("#wcp_paypal_mode option:selected").length) {
        err_msg.css('color', "red");
        err_msg.html("Please select the paypal mode.");
        err_msg.show();
        return false;
    } else if($("#wcp_paypal_sandbox_app_key").val() == ''){
        err_msg.css('color', "red");
        err_msg.html("Please Enter Sandbox App Key");
        err_msg.show();
        return false;
    } else if($("#wcp_paypal_production_app_key").val() =='' ){
        err_msg.css('color', "red");
        err_msg.html("Please Enter Production App Key");
        err_msg.show();
        return false;
    }else if($("#wcp_product_price").val() =='' ){
        err_msg.css('color', "red");
        err_msg.html("Please Enter Product Price");
        err_msg.show();
        return false;
    } else {
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {"action": "WCP_BackEnd_PaymentConfiguration_Controller::save_paypal_configuration", wcp_paypal_mode: $("#wcp_paypal_mode option:selected").val(), wcp_paypal_sandbox_app_key: $("#wcp_paypal_sandbox_app_key").val(),wcp_paypal_production_app_key:$("#wcp_paypal_production_app_key").val(),wcp_product_price:$("#wcp_product_price").val()},
            success: function (data) {
                var result =  JSON.parse(data);
                if(result.status == 1){
                    err_msg.css('color', "green");
                    err_msg.html(result.msg);
                    err_msg.show();
                    return false;
                }
            }
        });
    }
}
</script>    
