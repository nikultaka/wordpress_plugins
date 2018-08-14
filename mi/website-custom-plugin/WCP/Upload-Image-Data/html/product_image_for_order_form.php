<script type="text/javascript" src="<?php echo plugins_url( 'website-custom-plugin/WCP/assets/js/jquery.min.js' ); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url( 'website-custom-plugin/WCP/assets/css/bootstrap.min.css' ); ?>" >
<script type="text/javascript" src="<?php echo plugins_url( 'website-custom-plugin/WCP/assets/js/bootstrap.min.js' ); ?>"></script>
<script type="text/javascript" src="<?php echo plugins_url( 'website-custom-plugin/WCP/assets/js/jquery.blockUI.min.js' ); ?>"></script>

<div class="row" id="order_step1">
    <div class="form-group">
        <input type="hidden" name="product_price" id="product_price" value="<?php echo $wcp_product_price;?>">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Selección</font></font><br></td>

                    <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Llave</font></font><br></td>
        <!--            <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Back Image</font></font><br></td>-->

                    <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Descripción</font></font><br></td>
                </tr>
                <?php
                foreach ($image_list as $key=>$value){?>
                    <tr>
                        <td style="vertical-align: central !important;"> <input type="checkbox" class="cls_product" name="product" data-imageurl="<?php echo $value->front_image; ?>" data-imagedescription="<?php echo $value->description; ?>" id="produc_<?php echo $value->id;?>" value="<?php echo $value->id;?>"> </td>
                        <td style="vertical-align: central !important;"><img src="<?php echo $value->front_image; ?>"  alt="<?php echo $value->description; ?>" style="height: 100px;" class="d-block w-100"> </td>
        <!--                <td style="vertical-align: central !important;"><img src="<?php echo $value->back_image; ?>" alt="<?php echo $value->description; ?>" style="height: 100px;" class="d-block w-100"> </td>-->

                        <td><font style="vertical-align: central !important;"><font style="vertical-align: inherit;"><?php echo $value->description; ?></font></font><br></td>
                </tr> 

               <?php }
                ?>


            </tbody>
        </table>
    </div>
    
    <div class="form-group">
        <div class="col-sm-2">
            <label for="ImageFront">Método de envio *</label>
        </div>
        <div class="col-sm-6">
            <ul style="list-style-type: none;">
                <?php if(!empty($service_list)) { 
                    foreach($service_list as $key => $service){ ?>
                    <li>
                        <input type="radio" class="cls_service" data-price='<?php echo $service->price; ?>' data-title='<?php echo $service->service_name; ?>' name="service" id="service_<?php echo $service->id; ?>" value="<?php echo $service->id; ?>" />&nbsp;&nbsp;&nbsp;<?php echo $service->service_name; ?>
                        <br/>
                    </li>
                <?php } } ?>
            </ul>
        </div>
        <div class="col-sm-4">&nbsp;</div>
    </div>
    
    <div class="form-group">
        <div class="col-sm-12">
            <input type="button" name="btnStep1" id="btnStep1" class="btn btn-success" value="Siguiente" />
        </div>
    </div>
    
</div>

<div class="row" id="order_step2" style="display: none;">
    <div class="form-group">
        <!--<div class="col-sm-2">
            <label for="fname">Name *</label>
        </div>-->
        <div class="col-sm-4">
            <input type="text" name="name" id="name" class="form-control" placeholder="Nombre" onfocus="this.value=''"/>
        </div>   
        <!--<div class="col-sm-2">
            <label for="fname">surname *</label>
        </div>-->
        <div class="col-sm-4">
            <input type="text" name="sname" id="surname" class="form-control" placeholder="Apellidos" onfocus="this.value=''"/>
        </div> 
    </div>
    
    <div class="form-group">
        <!--<div class="col-sm-2">
            <label for="ImageFront">Email *</label>
        </div>-->
        <div class="col-sm-4">
            <input type="text" name="email" id="email" class="form-control" placeholder="Email" onfocus="this.value=''"/>
        </div> 
        <!--<div class="col-sm-2">
            <label for="ImageFront">Contact No *</label>
        </div>-->
        <div class="col-sm-4">
            <input type="text" name="contact_no" id="contact_no" class="form-control" placeholder="Teléfono" onfocus="this.value=''"/>
        </div>
    </div>
    
    <div class="form-group">
        <!--<div class="col-sm-2">
            <label for="ImageFront">Address *</label>
        </div>-->
        <div class="col-sm-10">
            <input type="text" name="address" id="address" class="form-control" placeholder="Calle" onfocus="this.value=''"/>
        </div>    
    </div>
     <div class="form-group">
        <!--<div class="col-sm-2">
            <label for="ImageFront">City *</label>
        </div>-->
        <div class="col-sm-4">
            <input type="text" name="city" id="city" class="form-control" placeholder="Ciudad" onfocus="this.value=''"/>
        </div>   
         <!--<div class="col-sm-2">
            <label for="ImageFront">Postal Code *</label>
        </div>-->
        <div class="col-sm-4">
            <input type="text" name="p_code" id="p_code" class="form-control" placeholder="Código Postal" onfocus="this.value=''"/>
        </div> 
    </div>
    
    <div class="form-group">
        <!--<div class="col-sm-2">
            <label for="ImageFront">Notes *</label>
        </div>-->
        <div class="col-sm-10">
            <textarea type="text" name="description" id="description" class="form-control" cols="50" rows="5" placeholder="Notas" onfocus="this.value=''"/></textarea>
        </div>    
    </div>
    
    <div class="form-group">
        <!--<div class="col-sm-12">
            <label style="text-align: center;font-size: 24px;width: 100%;">Resumen</label>
        </div>-->
        <div class="col-sm-12" id="table-order-list">
            
        </div>
        
    </div>
    
    <p id="err_msg" style="display:none;">&nbsp;</p>
    
    <div class="form-group">
        <div class="col-sm-3">&nbsp;</div>
        <div class="col-sm-6">
            <input type="button" name="btnBack" id="btnBack" class="btn btn-success" value="Atras" />
            <input type="submit" name="btnSubmitOrder" id="btnSubmitOrder" class="btn btn-success" value="Siguiente" style="float:right;" onclick="submit_order()" />
        </div>
        <div class="col-sm-3">&nbsp;</div>
    </div>
    <div style="display:none;" id="paypal-button"></div>
</div>

<form name="frmPaypalCheckout" id="frmPaypalCheckout" action="" >
    
</form>

<script type="text/javascript">
    var countvalue=1;
    //alert('sdsdsd');
    //$("#paypal-button").trigger("click");
    $(document).ready(function(){
	//$.noConflict();
	//$("#paypal-button").trigger("click");
        var err_msg = $("#err_msg");
        $("#btnStep1").on("click",function(){
            $.blockUI();
            err_msg.hide();
            $("#order_step1").hide();
            $("#order_step2").show();
             
            $.unblockUI();
        });
        
        $("#btnBack").on("click",function(){
            $.blockUI();
            err_msg.hide();
            $("#order_step2").hide();
            $("#order_step1").show();
            $.unblockUI();
        });
        
        $("input:radio[name=service]").on("click",function(){
            err_msg.hide();
            var imageurl = $('input:checkbox[name=product]:checked').map(function(){
                return $(this).data('imageurl');;
            }).get();
            var imagedescription = $('input:checkbox[name=product]:checked').map(function(){
                return $(this).data('imagedescription');;
            }).get();
            
            if($("input:radio[name=service]").is(":checked")){
                //Code to append goes here
                var price = $(this).data('price');
                var title = $(this).data('title');
                var product_price=$("#product_price").val();
               
               var a1 = new Array();
              var keyword= imageurl.toString();
               a1=keyword.split(",");
               
               var image= '';
               $(".cls_product").each(function(){
                    if($(this).prop('checked')==true){
                        var id = $(this).val();
                        var image_url = $(this).data("imageurl");
                        var imagedescription = $(this).data("imagedescription");
                        image += '<tr>';
                        image += '<td><img src="'+image_url+'" style="height: 100px;"></td>';
                        image += '<td>'+imagedescription+'</td>';
                        image += '<td><label id="value_count_'+id+'" value="1">1</label><span style="margin-left: 20px;" onclick="incressvalue('+id+');">+</span><span style="margin-left: 10px;" onclick="decressvalue('+id+');" >-</span></td>';
                       // image += '<td style="font-weight: bold;text-align: right;">'+title+'</td>';
                        //image += '<td><label id="value_count_price_'+id+'">'+price+'</lable>&euro;</td>';
                        image += '<td><label id="value_count_price_'+id+'">'+product_price+'</label>&euro;</td>';
                        
                        image += '</tr>';
                        image +='<input type="hidden" id="value_count_price_base_'+id+'" name="value_count_price_base" value="'+product_price+'">';
                    }
                });
               
                
                var string = '';
                string += '<table class="table table-bordered table-order-list">';
                string += '<tbody>';
                string += '<tr>';
                string += '<td style="background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;">Llave</td>';
                string += '<td style="background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;">Descripción</td>';
                string += '<td style="background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;">Cantidad</td>';
               // string += '<td style="background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;">Service</td>';
                string += '<td style="background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;">Precio</td>';
                string += '</tr>';
              //  string += '<tr><td><img src="'+imageurl+'" style="height: 100px;"></td>';
               // string +='<td>'+imagedescription+'</td></tr>';
                
                string += '<tr><td colspan="4" style="vertical-align: inherit;background-color:#BDC3C7;font-weight: bold;">Order Form</td></tr>';
                string += image;
                string += '<tr><td colspan="4" style="vertical-align: inherit;background-color:#BDC3C7;font-weight: bold;">Detalles de envío</td></tr>';
                string += '<tr>';
                string += '<td colspan="3" style="font-weight: bold;text-align: right;">'+title+'</td>';
                string += '<td><label id="value_service_type_price">'+price+'</lable>&euro;</td>';
                string += '</tr>';
                
//                string += '<tr>';
//                string += '<td colspan="2" style="font-weight: bold;text-align: right;">'+title+'</td>';
//                string += '<td style="font-weight: bold;"><label id="value_count" value="1">1</label><span style="margin-left: 20px;" onclick="incressvalue();">+</span><span style="margin-left: 10px;" onclick="decressvalue();" >-</span></td>';
//                string += '<td style="font-weight: bold;"><label id="value_count_price">'+price+'</label> Euro</td>';
//                string += '</tr>';

                string += '<tr>';
                string += '<td colspan="3" style="font-weight: bold;text-align: right;">Total:</td>';
                string += '<td style="font-weight: bold;"><label id="totalprice" value="'+price+'">'+price+'</label>&euro;</td>';
                string += '</tr>';
                
                string += '</tbody>';
                string += '</table>';
                $("#table-order-list").html(string);
            }
             get_final_rate();
        });
        
    });
    function incressvalue(id){
    
        $.blockUI();
        var countvalue = $("#value_count_"+id).html();
        countvalue++;
        $("#value_count_"+id).html(countvalue);
        var price=$("#value_count_price_base_"+id).val();
        var finalprice = parseFloat(price * countvalue);
        document.getElementById("value_count_price_"+id).innerHTML =  finalprice ;
        get_final_rate();
        $.unblockUI();
    }
    function decressvalue(id){
        $.blockUI();
        var countvalue = $("#value_count_"+id).html();
        countvalue--;
        if(countvalue <= 0){
            countvalue = 0;
        }
        $("#value_count_"+id).html(countvalue);
        var price=$("#value_count_price_base_"+id).val();
        var finalprice = parseFloat(price * countvalue);
        document.getElementById("value_count_price_"+id).innerHTML =  finalprice ;
        get_final_rate();
        $.unblockUI();
    }
    
    function get_final_rate(){
        var total = 0;
        total +=parseFloat($("#value_service_type_price").html());
        $(".cls_product").each(function(){
            if($(this).prop('checked')==true){
                total += parseFloat($("#value_count_price_"+$(this).val()).html());
            }
        });
        $("#totalprice").html(total);
        $("#totalprice").val(total);
    }
    function submit_order(){
        
        var err_msg = $("#err_msg");
        var checkbox_checked = false;
        $(".cls_product").each(function(){
            if($(this).prop('checked')==true){
                checkbox_checked = true;
            }
        });
        
        var service_selecte = false;
        $(".cls_service").each(function(){
            if($(this).prop('checked')==true){
                service_selecte = true;
            }
        });
        
        if (!checkbox_checked){
            err_msg.html("Por favor selecciona una llave");
            err_msg.css('color', "red");
            err_msg.show();
            return false;
        } else if (!service_selecte){
            err_msg.html("Por favor selecciona el método de envío");
            err_msg.css('color', "red");
            err_msg.show();
            return false;
        }
        else if ($("#name").val() == ''){
            err_msg.html("Por favor introduce tu nombre");
            err_msg.css('color', "red");
            err_msg.show();
            return false;
        }else if ($("#surname").val() == ''){
            err_msg.html("Por favor introduce tu apellido");
            err_msg.css('color', "red");
            err_msg.show();
            return false;
        }else if ($("#email").val() == ''){
            err_msg.html("Por favor introduce tu email");
            err_msg.css('color', "red");
            err_msg.show();
            return false;
        }else if ($("#contact_no").val() == ''){
            err_msg.html("Por favor introduce tu teléfono");
            err_msg.css('color', "red");
            err_msg.show();
            return false;
        }else if ($("#address").val() == ''){
            err_msg.html("Por favor introduce tu calle");
            err_msg.css('color', "red");
            err_msg.show();
            return false;
        }else if ($("#city").val() == ''){
            err_msg.html("Por favor introduce tu ciudad");
            err_msg.css('color', "red");
            err_msg.show();
            return false;
        }else if ($("#p_code").val() == ''){
            err_msg.html("Por favor introduce tu código postal");
            err_msg.css('color', "red");
            err_msg.show();
            return false;
        } //else if ($("#description").val() == ''){
          //  err_msg.html("Por favor añade alguna nota");
          //  err_msg.css('color', "red");
          //  err_msg.show();
          //  return false;
        //}
        else
        {
            $.blockUI();
            var service_value=$('input[name=service]:checked').val();
            var service_obj = $('input[name=service]:checked');
            var price = service_obj.data('price');
            var title = service_obj.data('title');
            var name= $('#name').val();
            var surname= $('#surname').val();
            var email= $('#email').val();
            var contact_no=$('#contact_no').val();
            var address =$('#address').val(); 
            var city =$('#city').val(); 
            var postal_code =$('#p_code').val(); 
            var totalprice = parseFloat($('#totalprice').html());
            var description = $('#description').val();
           // var note= $("#description").value;
          //  var totalprice = $('#totalprice').val;
            
            var product_arr = $('input:checkbox[name=product]:checked').map(function(){
                return this.value;
            }).get();
            
            var product_count_array = [];
            var product_total_price_array = [];
            $('input:checkbox[name=product]:checked').each(function(n){
                product_count_array[n] = $("#value_count_"+$(this).val()).html();
                product_total_price_array[n] = $("#value_count_price_"+$(this).val()).html();
            });
            var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
            $.ajax({
               //action:'WCP_ImageUpload_Controller::store_image_description',
               url: ajaxurl, // Url to which the request is send
               type: "POST", // Type of request to be send, called as method
               data: {action:'WCP_ImageUpload_Controller::submit_order',price:price,title:title,product_arr:product_arr,name:name,surname:surname,email:email,contact_no:contact_no,address:address,city:city,postal_code:postal_code,service_value:service_value,product_count_array:product_count_array,total_price:totalprice,product_total_price_array:product_total_price_array,description:description} // Data sent to server, a set of key/value pairs (i.e. form fields and values)
               
            }).always(function (data) {

                var response = JSON.parse(data);
                if (response.status == 1) {
                        window.location.href = '<?php echo site_url('payment-page'); ?>?id='+response.order_id;
                } else {
                    err_msg.css('color', "red");
                    err_msg.html(response.error);
                    err_msg.show();
                    //alert(response.error);
                }
                $.unblockUI();
            });
        }
    }
    
</script>    

