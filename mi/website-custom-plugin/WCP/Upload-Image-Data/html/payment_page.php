<script type="text/javascript" src="<?php echo plugins_url( 'website-custom-plugin/WCP/assets/js/jquery.min.js' ); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url( 'website-custom-plugin/WCP/assets/css/bootstrap.min.css' ); ?>" >
<script type="text/javascript" src="<?php echo plugins_url( 'website-custom-plugin/WCP/assets/js/bootstrap.min.js' ); ?>"></script>
<script type="text/javascript" src="<?php echo plugins_url( 'website-custom-plugin/WCP/assets/js/jquery.blockUI.min.js' ); ?>"></script>
<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<?php
 
$service_name=$order_details->service_name;
$service_price=$order_details->price;
?>
<table class="table table-bordered table-order-list" style="width: 90%;margin-left: 5%;">
    <tbody>
        <tr>
            <td style="background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;">Llave</td>
            <td style="background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;">Descripción</td>
            <td style="background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;">Cantidad</td>
<!--            <td style="background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;">Service</td>-->
            <td style="background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;">Precio</td>
        </tr>
        <tr>
            <td colspan="4" style="vertical-align: inherit;background-color:#BDC3C7;font-weight: bold;">Order Form</td>
        </tr>
        <?php
        foreach ($order_product_details as $key=>$value){
        ?>
        <tr>
            <td><img src="<?php echo $value->front_image; ?>" style="height: 100px;"></td>
            <td><?php echo $value->description; ?></td>
            <td><label id="value_count" value="<?php echo $value->total_item; ?>"><?php echo $value->total_item; ?></label></td>
<!--            <td style="font-weight: bold;text-align: right;"><?php echo $service_name;?></td>-->
            <td><label id="value_count_price"><?php echo $value->totalprice; ?> &euro;</label></td>
        </tr>
        <?php }?>
         <tr>
            <td colspan="4" style="vertical-align: inherit;background-color:#BDC3C7;font-weight: bold;">Detalles de envío</td>
        </tr>
        <tr>
            <td colspan="3" style="font-weight: bold;text-align: right;"><?php echo $service_name;?></td>
            <td><label id="value_service_type_price"><?php echo $service_price;?></label>&euro;</td>
        </tr>
        <tr>
            <td colspan="3" style="font-weight: bold;text-align: right;">Total :</td>
            <td style="font-weight: bold;"><label id="total_price" value="<?php echo number_format($order_details->total_price,2); ?>"><?php echo $order_details->total_price; ?> &euro;</label></td>
            
        </tr>
    </tbody>
</table>


<p id="err_msg" style="display:none;">&nbsp;</p>

<div class="form-group">
        <div class="col-sm-3">&nbsp;</div>
        <div class="col-sm-6">
            <input type="hidden" id="currency" name="currency" value="EUR" />
            <input type="hidden" id="order_id" name="order_id" value="<?php echo $_GET['id']; ?>" />
            <?php if($order_details->service_id == '3'){ ?>
                <input type="button" name="btcCon" id="btnCon" class="btn btn-success" style="float:right;padding-top: 15px;" value="Confirmar" onclick="confirm_order();" />
           <?php }else{?>
            <div id="paypal-button" style="float:right;padding-top: 20px;" ></div>
            <?php }?>
            <input type="button" name="btnBack" id="btnBack" class="btn btn-success" value="Cancelar" onclick="cancel_order();" />
			
        </div>
        <div class="col-sm-3">&nbsp;</div>
</div>


<script>
    var ajaxurl = "<?php echo site_url().'/wp-admin/admin-ajax.php'; ?>";
    var id = '<?php echo $_GET['id']; ?>';
    $(document).ready(function(){
        var amount = '<?php echo number_format($order_details->total_price,2); ?>';
        paypal.Button.render({
            env: '<?php echo get_site_option( 'wcp_paypal_mode' ); ?>', // Or 'sandbox'
            	style: {
            layout: 'vertical',  // horizontal | vertical
            size:   'responsive',    // medium | large | responsive
            shape:  'rect',      // pill | rect
            color:  'gold'       // gold | blue | silver | black
        		},
				funding: {
            allowed: [ paypal.FUNDING.CARD, paypal.FUNDING.CREDIT ],
            disallowed: [ ]
        		},
			client: {
                sandbox:    '<?php echo get_site_option( 'wcp_paypal_sandbox_app_key' );?>',
                //sandbox:    'AUDmB3FdZt0hBr0TnnzLydaW7HeXhTPrYeg-Oayl0P8gVrephhiw-gI9rrReFKwxKEONUVzOiJI7jQ',
                production: '<?php echo get_site_option( 'wcp_paypal_production_app_key' );?>'
            },//production : 'AWEgQ6vjvt1jEZbgmQcObBlC1TMMmbIAXUWNqedp89_mgBpUe-hWO9VAOpPf6mv32y1t1pXWQRnetJeC'
            commit: true, // Show a 'Pay Now' button
            payment: function(data, actions) {
                return actions.payment.create({
                    payment: {
                        transactions: [
                            {
                                amount: { total: amount, currency: $("#currency").val() }
                            }
                        ]
                    }
                });
            },
            onAuthorize: function(data, actions) {
                return actions.payment.execute().then(function(payment) {
                    console.log(payment);
                    
                    var transaction_id = payment.id;
                    var payment_status = payment.state;
                    var payer_email = payment.payer.payer_info.email;
                    var payer_id = payment.payer.payer_info.payer_id;
                    var currency = payment.transactions[0].amount.currency;
                    var total = payment.transactions[0].amount.total;
                    var id = $("#order_id").val();
                    $.ajax({
                        type: "POST",
                        url: ajaxurl,
                        dataType: 'json',
                        data:{action:'WCP_ImageUpload_Controller::update_payment_details',transaction_id:transaction_id,payment_status:payment_status,payer_email:payer_email,payer_id:payer_id,currency:currency,total:total,id:id},
                        success: function(response){
                            var err_msg = $("#err_msg");
                            if (response.status == 1) {
                                err_msg.css('color', "green");
                                err_msg.html(response.message);
                                err_msg.show();
                                window.location.href = window.location;
                            } else {
                                err_msg.css('color', "red");
                                err_msg.html(response.error);
                                err_msg.show();
                                window.location.href = window.location;
                            }
                        }
                    });
                });
            },
            onCancel: function(data, actions) {
                cancel_order();
            },
            onError: function(err) {
                var err_msg = $("#err_msg");
                console.log(log);
                // Show a cancel page or return to cart
                err_msg.css('color', "red");
                err_msg.html(err.error_description);
                err_msg.show();
            }
        }, '#paypal-button');
    });
    
    function cancel_order()
    {
        $.blockUI();
        // Show a cancel page or return to cart
        var id = $("#order_id").val();
        $.ajax({
                type: "POST",
                url: ajaxurl,
                dataType: 'json',
                data:{action:'WCP_ImageUpload_Controller::cancel_order',id:id},
                success: function(data){
                    var err_msg = $("#err_msg");
                    if (data.status == 1) {
                        err_msg.css('color', "green");
                        err_msg.html(data.msg);
                        err_msg.show();
                        window.location.href = window.location;
                    }
                    $.unblockUI();
                }
        });
    }
    function confirm_order(){
        $.blockUI();
        // Show a cancel page or return to cart
        var id = $("#order_id").val();
        $.ajax({
                type: "POST",
                url: ajaxurl,
                dataType: 'json',
                data:{action:'WCP_ImageUpload_Controller::confirm_order',id:id},
                success: function(data){
                    var err_msg = $("#err_msg");
                    if (data.status == 1) {
                        err_msg.css('color', "green");
                        err_msg.html(data.msg);
                        err_msg.show();
                        window.location.href = window.location;
                    }
                    $.unblockUI();
                }
        });
    }
</script>