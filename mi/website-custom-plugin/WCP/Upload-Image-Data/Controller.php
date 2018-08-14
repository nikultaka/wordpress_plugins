<?php

class WCP_UploadImageData_Controller {

    public function get_image_data_from_upload() {
        global $wpdb;
        $current_user_details = wp_get_current_user();

        $sql_lead = "SELECT * FROM wpqe_rg_lead_detail WHERE lead_id IN (SELECT id FROM wpqe_rg_lead WHERE created_by = " . $current_user_details->ID . ")";
        $lead_info_list = $wpdb->get_results($sql_lead);
        $image_list = array();
        if (!empty($lead_info_list)) {
            $i = 0;
            foreach ($lead_info_list as $key => $lead) {

                if ($key % 2 == 0) {
                    $i++;
                }
                if ($lead->field_number == 1 || $lead->field_number == 2) {
                    $image_list[$i]['image'] = $lead->value;
                }
                if ($lead->field_number == 3 || $lead->field_number == 4) {
                    $image_list[$i]['description'] = $lead->value;
                }
            }
        }

        require_once plugin_dir_path(dirname(__FILE__)) . 'Upload-Image-Data/View/Upload-Image-Data_form.php';
        $s = ob_get_contents();
        ob_end_clean();
        return $s;
    }

    public function get_image_data_from_upload_for_order_form() {
        global $wpdb;
        $current_user_details = wp_get_current_user();

        $sql_lead = "SELECT * FROM wpqe_rg_lead_detail WHERE lead_id IN (SELECT id FROM wpqe_rg_lead WHERE created_by = " . $current_user_details->ID . ")";
        $lead_info_list = $wpdb->get_results($sql_lead);
       
       
        $image_list = array();
        if (!empty($lead_info_list)) {
            $i = 0;
            foreach ($lead_info_list as $key => $lead) {

                if ($key % 2 == 0) {
                    $i++;
                }
                if ($lead->field_number == 1 || $lead->field_number == 2) {
                    $image_list[$i]['image'] = $lead->value;
                    $image_list[$i]['id'] = $lead->id;
                }
                if ($lead->field_number == 3 || $lead->field_number == 4) {
                    $image_list[$i]['description'] = $lead->value;
                }
            }
        }

        require_once plugin_dir_path(dirname(__FILE__)) . 'Upload-Image-Data/View/get-image-for-order-form.php';
        $s = ob_get_contents();
        ob_end_clean();
        return $s;
    }

    public function get_uploaded_Image_slider() {
        global $wpdb;
        $current_user_details = wp_get_current_user();

        $sql_lead = "SELECT * FROM " . $wpdb->prefix . "product WHERE status = 1 AND user_id = " . $current_user_details->ID . "";
        $image_list = $wpdb->get_results($sql_lead);
        require_once plugin_dir_path(dirname(__FILE__)) . 'Upload-Image-Data/html/product_image_with_description_for_account.php';
        $s = ob_get_contents();
        ob_end_clean();
        return $s;
    }

    public function get_uploaded_Image_slider_for_order() {
        global $wpdb;
        $current_user_details = wp_get_current_user();

        //This is to get service for that user
        $sql_service = "SELECT * FROM " . $wpdb->prefix . "service WHERE status = 1";
        $service_list = $wpdb->get_results($sql_service);

        $sql_product = "SELECT * FROM " . $wpdb->prefix . "product WHERE status = 1 AND user_id = " . $current_user_details->ID . "";
        $image_list = $wpdb->get_results($sql_product);
         $wcp_product_price = get_site_option( 'wcp_product_price' );
        require_once plugin_dir_path(dirname(__FILE__)) . 'Upload-Image-Data/html/product_image_for_order_form.php';
        $s = ob_get_contents();
        ob_end_clean();
        return $s;
    }

    public function submit_order() {


        if (!empty($_POST)) {
            $productid = implode(',', $_POST['product_arr']);
            $product_count_array = !empty($_POST['product_count_array'])?$_POST['product_count_array']:array();
            $product_total_price_array = !empty($_POST['product_total_price_array'])?$_POST['product_total_price_array']:array();
            $price = isset($_POST['price']) && $_POST['price'] != '' ? $_POST['price'] : '';
            $price = isset($_POST['price']) && $_POST['price'] != '' ? $_POST['price'] : '';
            $title = isset($_POST['title']) && $_POST['title'] != '' ? $_POST['title'] : '';
            $useremail = isset($_POST['email']) && $_POST['email'] != '' ? $_POST['email'] : '';
            $total_price = isset($_POST['total_price']) && $_POST['total_price'] != '' ? $_POST['total_price'] : '';
            $description = isset($_POST['description']) && $_POST['description'] != '' ? $_POST['description'] : '';
            $serviceid=$_POST['service_value'];
            global $wpdb;
            $tbl = $wpdb->prefix . 'product';
            // $quary="SELECT * FROM"
            $quary = "SELECT * FROM " . $wpdb->prefix . "product WHERE id IN (" . $productid . ")";
            $productlist = $wpdb->get_results($quary);

            


            // For Insert Data In Order_list Table
            $current_user = wp_get_current_user();
            $user_id = $current_user->ID;
            $data_insert = array();

            $data_insert['services_id'] = $serviceid;
            $data_insert['user_id'] = $user_id;
            $data_insert['total_price'] = $total_price;
            $data_insert['first_name'] = $_POST['name'];
            $data_insert['surname'] = $_POST['surname'];
            $data_insert['email'] = $_POST['email'];
            $data_insert['contact_no'] = $_POST['contact_no'];
            $data_insert['address'] = $_POST['address'];
            $data_insert['city'] = $_POST['city'];
            $data_insert['postal_code'] = $_POST['postal_code'];
            $data_insert['description'] = $_POST['description'];
            $data_insert['status'] = 1;
            $data_insert['gm_created'] = date('Y-m-d H:i:s');

            global $wpdb;
            $tbl = $wpdb->prefix . 'order_list';
            $result = $wpdb->insert($tbl, $data_insert, array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'));
            $lastid = $wpdb->insert_id;
            if ($result) {
                $product_ids = explode(",", $productid);
                if(!empty($product_ids))
                {
                    foreach($product_ids as $key=>$value)
                    {
                        $data_insert_product = array();
                        $data_insert_product['order_id'] = $lastid;
                        $data_insert_product['product_id'] = $value;
                        $data_insert_product['total_item'] = $product_count_array[$key];
                        $data_insert_product['totalprice'] = $product_total_price_array[$key];
                        $data_insert_product['gm_created'] = date('Y-m-d H:i:s');
                        $tbl = $wpdb->prefix . 'order_product_list';
                        $resultorder = $wpdb->insert($tbl, $data_insert_product, array('%s', '%s', '%s'));
                    }
                }

                
                
                if ($productide[1] != '' && isset($productide[1])) {
                    $data_insert_product1 = array();
                    $data_insert_product1['order_id'] = $lastid;
                    $data_insert_product1['product_id'] = $productide[1];
                    $data_insert_product1['gm_created'] = date('Y-m-d H:i:s');
                    $tbl = $wpdb->prefix . 'order_product_list';
                    $resultorder1 = $wpdb->insert($tbl, $data_insert_product1, array('%s', '%s', '%s'));
                }
            }
            if ($resultorder) {
                $result_array['status'] = 1;
               // $result_array['service_value']=$serviceid;
                $result_array['message'] = "Product Added successfully";
                $result_array['order_id'] = $lastid;
            } else {
                $result_array['status'] = 0;
                $result_array['error'] = "There was an error in the MySQL query.";
            }
        } else {
            $result_array['status'] = 0;
            $result_array['error'] = $error['message'];
        }
        echo json_encode($result_array);
        exit;
    }
    
    public function payment_page() {
        global $wpdb;
        $current_user_details = wp_get_current_user();
        if(isset($_GET['id']) && $_GET['id']!='')
        {
            
            $data=array();
            $id = $_GET['id'];
            $sql_order ="SELECT DISTINCT wo.user_id,wo.total_price,wo.id,wo.first_name,wo.surname,wo.email,wo.contact_no,wo.address,wo.city,wo.postal_code,wo.payment_status,wp.service_name,wp.price,wp.id as service_id
                          FROM ". $wpdb->prefix."order_list as wo 
                          LEFT JOIN ". $wpdb->prefix."service as wp ON  wo.services_id = wp.id
                                            WHERE wo.id = ".$id;
            $order_details = $wpdb->get_row($sql_order);
            
            if(isset($order_details->payment_status) && $order_details->payment_status != 0)
            {
                
                $redirect_url = site_url()."/account-page";
                $script_str = '<script>';
                $script_str .= 'window.location.href = "'.$redirect_url.'"';
                $script_str .= '</script>';
                echo $script_str;
                exit();
            }
            
            
            $sql_product_order_list="SELECT DISTINCT wo.*,wp.id as product_id,
                                        wp.front_image,wp.description,wp.status
                                        FROM ". $wpdb->prefix."order_product_list as wo
                                        INNER JOIN ". $wpdb->prefix."product as wp ON  wo.product_id = wp.id
                                        WHERE wo.order_id = ".$order_details->id;
            $order_product_details = $wpdb->get_results($sql_product_order_list);
   
            
        }
        
        require_once plugin_dir_path(dirname(__FILE__)) . 'Upload-Image-Data/html/payment_page.php';
        $s = ob_get_contents();
        ob_end_clean();
        return $s;
    }
    
    public function update_payment_details()
    {
        $result_array = array();
        $result_array['status'] = 0;
        if(!empty($_POST))
        {
            global $wpdb;
            $id = isset($_POST['id']) && $_POST['id']!=''?$_POST['id']:'';
            if($id != ''){
                $currency = isset($_POST['currency']) && $_POST['currency']!=''?$_POST['currency']:'';
                $transaction_id = isset($_POST['transaction_id']) && $_POST['transaction_id']!=''?$_POST['transaction_id']:'';
                $payment_status = isset($_POST['payment_status']) && $_POST['payment_status']!=''?$_POST['payment_status']:'';
                $payer_email = isset($_POST['payer_email']) && $_POST['payer_email']!=''?$_POST['payer_email']:'';
                $payer_id = isset($_POST['payer_id']) && $_POST['payer_id']!=''?$_POST['payer_id']:'';
                
                $data_update = array();
                $data_update['currency'] = $currency;
                $data_update['payment_transaction_id'] = $transaction_id;
                if($payment_status == 'approved')
                    $data_update['payment_status'] = 1;
                else
                    $data_update['payment_status'] = 2;
                
                $data_update['payer_email'] = $payer_email;
                $data_update['payer_id'] = $payer_id;
                $wpdb->update($wpdb->prefix."order_list", $data_update, array("id"=>$id));
                
                
                
                if($payment_status == 'approved')
                {
                    
                    $sql_order ="SELECT DISTINCT wo.user_id,wo.total_price,wo.id,wo.first_name,wo.surname,wo.email,wo.contact_no,wo.address,wo.city,wo.postal_code,wo.description as notes,wp.service_name,wp.price
                          FROM ". $wpdb->prefix."order_list as wo 
                          LEFT JOIN ". $wpdb->prefix."service as wp ON  wo.services_id = wp.id
                                            WHERE wo.id = ".$id;
                    $order_details = $wpdb->get_row($sql_order);
                    
                    $breakprice_string_u = "<table class='table table-bordered table-order-list' border='1' width='50%'><tbody><tr><td colspan='2' style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>  Detalles del cliente  </td></tr>";
                    $breakprice_string_u .= '<tr><td>Nombre </td><td>' . $order_details->first_name . '</td></tr>';
                    $breakprice_string_u .= '<tr><td>Apellidos </td><td>' . $order_details->surname . '</td></tr>';
                    $breakprice_string_u .= '<tr><td>Email </td><td>' . $order_details->email . '</td></tr>';
                    $breakprice_string_u .= '<tr><td>Telefono </td><td>' . $order_details->contact_no . '</td></tr>';
                    $breakprice_string_u .= '<tr><td>Calle </td><td>' . $order_details->address . '</td></tr>';
                    $breakprice_string_u .= '<tr><td>Ciudad </td><td>' . $order_details->city . '</td></tr>';
                    $breakprice_string_u .= '<tr><td>Codigo Postal </td><td>' . $order_details->postal_code . '</td></tr>';
                    $breakprice_string_u .= '<tr><td>Descripcion </td><td>' . $order_details->notes . '</td></tr>';
                    $breakprice_string_u .= '</tbody></table>';
                    
                    $quary = "SELECT * FROM " . $wpdb->prefix . "product as p "
                            . "LEFT JOIN " . $wpdb->prefix . "order_product_list as opl ON p.id = opl.product_id "
                            . "WHERE opl.order_id = ".$id;
                    $productlist = $wpdb->get_results($quary);
                    
                    $breakprice_string = "<table class='table table-bordered table-order-list' border='1' width='50%'><tbody>"
                                        . "<tr>"
                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Llave</td>"
                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Descripcion</td>"
                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Cantidad</td>"
//                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Service</td>"
                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Precio</td>"
                                        . "</tr>";
//                                        .'<tr><td colspan="4" style="vertical-align: inherit;background-color:#BDC3C7;font-weight: bold;">Order Form</td></tr>';    
                    foreach ($productlist as $product) {
                        $breakprice_string .= '<tr>'
                                            . '<td><img src="' . $product->front_image . '" style="height:100px; width:100px"/> </td>'
                                            . '<td>' . $product->description . '</td>'
                                            . '<td>' . $product->total_item . '</td>'
                                         //   . '<td>' . $order_details->service_name . '</td>'
                                            . '<td>' . $product->totalprice . ' &euro;</td>'
                                            . '</tr>';
                    }
                    
                    
                    $breakprice_string .='<tr>';
                    $breakprice_string .='<td colspan="4" style="vertical-align: inherit;background-color:#BDC3C7;font-weight: bold;">Destalles del envio</td>';
                    $breakprice_string .='</tr>';
                    
                    $breakprice_string .='<tr>';
                    $breakprice_string .='<td colspan="3" style="font-weight: bold;text-align: right;">'. $order_details->service_name . '</td>';
                    $breakprice_string .='<td>'.$order_details->price. ' &euro;</td>';
                    $breakprice_string .='</tr>'; 
             
                    $breakprice_string .= '<tr><td style="font-weight: bold;text-align: right;" colspan="3">Total</td><td style="font-weight: bold;text-align: center;">' . $order_details->total_price . ' &euro;</td></tr>';
                    $breakprice_string .= '</tbody></table>';
                    
                      // for admin mail
                    $breakprice_string_admin = "<table class='table table-bordered table-order-list' border='1' width='50%'><tbody>"
                                        . "<tr>"
                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Frontal</td>"
                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Trasera</td>"
                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Descripcion</td>"
                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Cantidad</td>"
//                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Service</td>"
                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Precio</td>"
                                        . "</tr>";
//                                        .'<tr><td colspan="5" style="vertical-align: inherit;background-color:#BDC3C7;font-weight: bold;">Order Form</td></tr>';    
                    foreach ($productlist as $product) {
                        $breakprice_string_admin .= '<tr>'
                                            . '<td><img src="' . $product->front_image . '" style="height:100px; width:100px"/> </td>'
                                            . '<td><img src="' . $product->back_image . '" style="height:100px; width:100px"/> </td>'
                                            . '<td>' . $product->description . '</td>'
                                            . '<td>' . $product->total_item . '</td>'
                                         //   . '<td>' . $order_details->service_name . '</td>'
                                            . '<td>' . $product->totalprice . ' &euro;</td>'
                                            . '</tr>';
                    }
                    
                  
                    
                    
                    $breakprice_string_admin .='<tr>';
                    $breakprice_string_admin .='<td colspan="5" style="vertical-align: inherit;background-color:#BDC3C7;font-weight: bold;">Detalles del envio</td>';
                    $breakprice_string_admin .='</tr>';
                    
                    $breakprice_string_admin .='<tr>';
                    $breakprice_string_admin .='<td colspan="4" style="font-weight: bold;text-align: right;">'. $order_details->service_name . '</td>';
                    $breakprice_string_admin .='<td>'.$order_details->price. ' &euro;</td>';
                    $breakprice_string_admin .='</tr>'; 
             
                    $breakprice_string_admin .= '<tr><td style="font-weight: bold;text-align: right;" colspan="4">Total</td><td style="font-weight: bold;text-align: center;">' . $order_details->total_price . ' &euro;</td></tr>';
                    $breakprice_string_admin .= '</tbody></table>';


                    /****************** Send Email to user ************* */
                    $current_user = wp_get_current_user();
                    $to = $order_details->email;
                    $subject = 'Pedido recibido '; //.//$quote_details->order_id;
					$body = "<b>¡Gracias por su pedido!</b><br>";
                    $body = "Este es un email de confirmación de tu pedido Nº <b>" . $order_details->postal_code ."".$id."</b> con fecha <b>" .date('d/m/Y')."</b>.<br> Te rogamos que verifiques que los datos sean correctos y en caso contrario contacte con nosotros para corregir cualquier error a través del siguiente email soportemillavero@gmail.com<br>";					
                    $body .= "Detalles del pedido : <br>" .$breakprice_string_u."<br>" . $breakprice_string;
					$body = "Para seguir tu pedido, <b>(EMPRESA TRANSPORTISTA)</b> te enviará un email con el numero de seguimiento. Cuando lo recibas, podrás introducirlo en su página y realizar el seguimiento al detalle del mismo.";
                    $headers = array('Content-Type: text/html; charset=UTF-8');
                    wp_mail($to, $subject, $body, $headers);


                    /*             * **************** Send Email to Admin ************* */
                    $admin_email = get_option( 'admin_email' );
                    $admin_email = 'millaveroapp@gmail.com';					
                    $subject = 'Nuevo pedido'; //.$quote_details->order_id;
                    $body = "Nuevo pedido recibido<br> Nº <b>" . $order_details->postal_code ."".$id."</b> con fecha <b>" .date('d/m/Y')."</b>.<br>";
                    $body .= "Detalles del pedido : <br>".$breakprice_string_u."<br>" . $breakprice_string_admin;
                    $headers = array('Content-Type: text/html; charset=UTF-8');
                    wp_mail($admin_email, $subject, $body, $headers);
            
            
                    $result_array['status'] = 1;
                    $result_array['message'] = 'Su pedido ha sido tramitado correctamente.';
                }
                else
                {
                    $result_array['status'] = 0;
                    $result_array['error'] = 'Su pedido no se ha realizado.';
                }
            }
        }
        echo json_encode($result_array);exit;
    }
    
    public function cancel_order()
    {
        $result_array = array();
        $result_array['status'] = 0;
        if(!empty($_POST)){
            global $wpdb;
            $id = isset($_POST['id']) && $_POST['id']!=''?$_POST['id']:'';
            $data_update = array();
            $data_update['payment_status'] = 3;
            $wpdb->update($wpdb->prefix."order_list", $data_update, array("id"=>$id));
            
            $result_array['status'] = 1;
            $result_array['msg'] = "Su pedido se ha cancelado.";
        }
        echo json_encode($result_array);exit;
    }
    public function confirm_order(){
        $result_array = array();
        $result_array['status'] = 0;
        if(!empty($_POST)){
            global $wpdb;
            $id = isset($_POST['id']) && $_POST['id']!=''?$_POST['id']:'';
            $data_update = array();
            $data_update['payment_status'] = 1;
            $wpdb->update($wpdb->prefix."order_list", $data_update, array("id"=>$id));
                  
                    $sql_order ="SELECT DISTINCT wo.user_id,wo.total_price,wo.id,wo.first_name,wo.surname,wo.email,wo.contact_no,wo.address,wo.city,wo.postal_code,wo.description as notes,wp.service_name,wp.price
                          FROM ". $wpdb->prefix."order_list as wo 
                          LEFT JOIN ". $wpdb->prefix."service as wp ON  wo.services_id = wp.id
                                            WHERE wo.id = ".$id;
                    $order_details = $wpdb->get_row($sql_order);
                    
                    $breakprice_string_u = "<table class='table table-bordered table-order-list' border='1' width='50%'><tbody><tr><td colspan='2' style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>  Destalles del cliente  </td></tr>";
                    $breakprice_string_u .= '<tr><td>Nombre </td><td>' . $order_details->first_name . '</td></tr>';
                    $breakprice_string_u .= '<tr><td>Apellidos </td><td>' . $order_details->surname . '</td></tr>';
                    $breakprice_string_u .= '<tr><td>Email </td><td>' . $order_details->email . '</td></tr>';
                    $breakprice_string_u .= '<tr><td>Telefono </td><td>' . $order_details->contact_no . '</td></tr>';
                    $breakprice_string_u .= '<tr><td>Calle </td><td>' . $order_details->address . '</td></tr>';
                    $breakprice_string_u .= '<tr><td>Ciudad </td><td>' . $order_details->city . '</td></tr>';
                    $breakprice_string_u .= '<tr><td>Codigo Postal </td><td>' . $order_details->postal_code . '</td></tr>';
                    $breakprice_string_u .= '<tr><td>Descripcion </td><td>' . $order_details->notes . '</td></tr>';
                    $breakprice_string_u .= '</tbody></table>';
                    
                    $quary = "SELECT * FROM " . $wpdb->prefix . "product as p "
                            . "LEFT JOIN " . $wpdb->prefix . "order_product_list as opl ON p.id = opl.product_id "
                            . "WHERE opl.order_id = ".$id;
                    $productlist = $wpdb->get_results($quary);
                    
//                    $breakprice_string = "<table class='table table-bordered table-order-list' border='1' width='50%'><tbody>"
//                                        . "<tr>"
//                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Product</td>"
//                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Product Description</td>"
//                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Quantity</td>"
////                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Service</td>"
//                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Price</td>"
//                                        . "</tr>"
//                                        .'<tr><td colspan="4" style="vertical-align: inherit;background-color:#BDC3C7;font-weight: bold;">Order Form</td></tr>';    
//                    foreach ($productlist as $product) {
//                        $breakprice_string .= '<tr>'
//                                            . '<td><img src="' . $product->front_image . '" style="height:100px;"/> </td>'
//                                            . '<td>' . $product->description . '</td>'
//                                            . '<td>' . $product->total_item . '</td>'
//                                         //   . '<td>' . $order_details->service_name . '</td>'
//                                            . '<td>' . $product->totalprice . ' &euro;</td>'
//                                            . '</tr>';
//                    }
//                    $breakprice_string .='<tr>';
//                    $breakprice_string .='<td colspan="4" style="vertical-align: inherit;background-color:#BDC3C7;font-weight: bold;">Shipping Detail</td>';
//                    $breakprice_string .='</tr>';
//                    
//                    $breakprice_string .='<tr>';
//                    $breakprice_string .='<td colspan="3" style="font-weight: bold;text-align: right;">'. $order_details->service_name . '</td>';
//                    $breakprice_string .='<td>'.$order_details->price. ' &euro;</td>';
//                    $breakprice_string .='</tr>'; 
//             
//                    $breakprice_string .= '<tr><td style="font-weight: bold;text-align: right;" colspan="3">Total</td><td style="font-weight: bold;text-align: center;">' . $order_details->total_price . ' &euro;</td></tr>';
//                    $breakprice_string .= '</tbody></table>';
//                    
                    $breakprice_string = "<table class='table table-bordered table-order-list' border='1' width='50%'><tbody>"
                                        . "<tr>"
                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Llave</td>"
                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Descripcion</td>"
                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Cantidad</td>"
//                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Service</td>"
                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Precio</td>"
                                        . "</tr>";
//                                        .'<tr><td colspan="4" style="vertical-align: inherit;background-color:#BDC3C7;font-weight: bold;">Order Form</td></tr>';    
                    foreach ($productlist as $product) {
                        $breakprice_string .= '<tr>'
                                            . '<td><img src="' . $product->front_image . '" style="height:100px; width:100px"/> </td>'
                                            . '<td>' . $product->description . '</td>'
                                            . '<td>' . $product->total_item . '</td>'
                                         //   . '<td>' . $order_details->service_name . '</td>'
                                            . '<td>' . $product->totalprice . ' &euro;</td>'
                                            . '</tr>';
                    }
                    
                    
                    $breakprice_string .='<tr>';
                    $breakprice_string .='<td colspan="4" style="vertical-align: inherit;background-color:#BDC3C7;font-weight: bold;">Destalles del envio</td>';
                    $breakprice_string .='</tr>';
                    
                    $breakprice_string .='<tr>';
                    $breakprice_string .='<td colspan="3" style="font-weight: bold;text-align: right;">'. $order_details->service_name . '</td>';
                    $breakprice_string .='<td>'.$order_details->price. ' &euro;</td>';
                    $breakprice_string .='</tr>'; 
             
                    $breakprice_string .= '<tr><td style="font-weight: bold;text-align: right;" colspan="3">Total</td><td style="font-weight: bold;text-align: center;">' . $order_details->total_price . ' &euro;</td></tr>';
                    $breakprice_string .= '</tbody></table>';
                    
                      // for admin mail
                    $breakprice_string_admin = "<table class='table table-bordered table-order-list' border='1' width='50%'><tbody>"
                                        . "<tr>"
                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Frontal</td>"
                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Trasera</td>"
                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Descripcion</td>"
                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Cantidad</td>"
//                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Service</td>"
                                        . "<td style='background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;'>Precio</td>"
                                        . "</tr>";
//                                        .'<tr><td colspan="5" style="vertical-align: inherit;background-color:#BDC3C7;font-weight: bold;">Order Form</td></tr>';    
                    foreach ($productlist as $product) {
                        $breakprice_string_admin .= '<tr>'
                                            . '<td><img src="' . $product->front_image . '" style="height:100px; width:100px"/> </td>'
                                            . '<td><img src="' . $product->back_image . '" style="height:100px; width:100px"/> </td>'
                                            . '<td>' . $product->description . '</td>'
                                            . '<td>' . $product->total_item . '</td>'
                                         //   . '<td>' . $order_details->service_name . '</td>'
                                            . '<td>' . $product->totalprice . ' &euro;</td>'
                                            . '</tr>';
                    }
                    
                  
                    
                    
                    $breakprice_string_admin .='<tr>';
                    $breakprice_string_admin .='<td colspan="5" style="vertical-align: inherit;background-color:#BDC3C7;font-weight: bold;">Detalles del envio</td>';
                    $breakprice_string_admin .='</tr>';
                    
                    $breakprice_string_admin .='<tr>';
                    $breakprice_string_admin .='<td colspan="4" style="font-weight: bold;text-align: right;">'. $order_details->service_name . '</td>';
                    $breakprice_string_admin .='<td>'.$order_details->price. ' &euro;</td>';
                    $breakprice_string_admin .='</tr>'; 
             
                    $breakprice_string_admin .= '<tr><td style="font-weight: bold;text-align: right;" colspan="4">Total</td><td style="font-weight: bold;text-align: center;">' . $order_details->total_price . ' &euro;</td></tr>';
                    $breakprice_string_admin .= '</tbody></table>';



                    /****************** Send Email to user ************* */
                    $current_user = wp_get_current_user();
                    $to = $order_details->email;
                    $subject = 'Pedido recibido '; //.//$quote_details->order_id;
					$body = "<b>¡Gracias por su pedido!</b><br>";                    
					$body = "Este es un email de confirmación de tu pedido Nº <b>" . $order_details->postal_code ."".$id."</b> con fecha <b>" .date('d/m/Y')."</b>.<br> Te rogamos que verifiques que los datos sean correctos y en caso contrario contacte con nosotros para corregir cualquier error a través del siguiente email soportemillavero@gmail.com<br>";
                    $body .= "Detalles del pedido : <br>" .$breakprice_string_u."<br>" . $breakprice_string;
					$body = "Para seguir tu pedido, <b>(EMPRESA TRANSPORTISTA)</b> te enviará un email con el numero de seguimiento. Cuando lo recibas, podrás introducirlo en su página y realizar el seguimiento al detalle del mismo.";
                    $headers = array('Content-Type: text/html; charset=UTF-8');
                    wp_mail($to, $subject, $body, $headers);


                    /*             * **************** Send Email to Admin ************* */
                    $admin_email = get_option( 'admin_email' );
                    $admin_email = 'millaveroapp@gmail.com';
                    $subject = 'Nuevo pedido recibido'; //.$quote_details->order_id;
                    $body = "Nuevo pedido recibido<br> Nº <b>" . $order_details->postal_code ."".$id."</b> con fecha <b>" .date('d/m/Y')."</b>.<br>";
                    $body .= "Detalles del pedido : <br>".$breakprice_string_u."<br>" . $breakprice_string_admin;
                    $headers = array('Content-Type: text/html; charset=UTF-8');
                    wp_mail($admin_email, $subject, $body, $headers);
            
            
                    $result_array['status'] = 1;
                    $result_array['msg'] = 'Su pedido ha sido tramitado correctamente.';
                
            
            }
        echo json_encode($result_array);exit;
    }

    public function delete_image(){
         if(!empty($_POST)){
            global $wpdb;
            $id = isset($_POST['productid']) && $_POST['productid']!=''?$_POST['productid']:'';
            $data_update = array();
            $data_update['status'] = '-1';
         $wpdb->update($wpdb->prefix."product", $data_update, array("id"=>$id));
          $result_array['status'] = 1;
           $result_array['msg'] = 'La llave se ha borrado correctamente.';
         }
//        $productid = $_POST['productid'];
//        
//        
//        
//        
//        
//        
//        
//        
//        global $wpdb;
//       $wpdb->delete($wpdb->prefix."product", array( 'id' => $productid ) );
        echo json_encode($result_array);exit;
    }

}

$WCP_UploadImageData_Controller = new WCP_UploadImageData_Controller();
add_shortcode('wcp_uploaded_Image_slider', array($WCP_UploadImageData_Controller, 'get_uploaded_Image_slider'));
add_shortcode('wcp_uploaded_Image_for_order', array($WCP_UploadImageData_Controller, 'get_uploaded_Image_slider_for_order'));

add_shortcode('wcp_uploadImage_Data', array($WCP_UploadImageData_Controller, 'get_image_data_from_upload'));
add_shortcode('wcp_uploadImage_Data_for_order_form', array($WCP_UploadImageData_Controller, 'get_image_data_from_upload_for_order_form'));
add_action('admin_enqueue_scripts', array($WCP_UploadImageData_Controller, 'load_cutom_plugin_scripts'));

add_action('wp_ajax_WCP_ImageUpload_Controller::submit_order', array($WCP_UploadImageData_Controller, 'submit_order'));
add_action('wp_ajax_nopriv_WCP_ImageUpload_Controller::submit_order', array($WCP_UploadImageData_Controller, 'submit_order'));

add_action('wp_ajax_WCP_UploadImageData_Controller::delete_image', array($WCP_UploadImageData_Controller, 'delete_image'));
add_action('wp_ajax_nopriv_WCP_UploadImageData_Controller::delete_image', array($WCP_UploadImageData_Controller, 'delete_image'));

add_shortcode('wcp_payment_page', array($WCP_UploadImageData_Controller, 'payment_page'));

add_action('wp_ajax_WCP_ImageUpload_Controller::update_payment_details', array($WCP_UploadImageData_Controller, 'update_payment_details'));
add_action('wp_ajax_nopriv_WCP_ImageUpload_Controller::update_payment_details', array($WCP_UploadImageData_Controller, 'update_payment_details'));

add_action('wp_ajax_WCP_ImageUpload_Controller::cancel_order', array($WCP_UploadImageData_Controller, 'cancel_order'));
add_action('wp_ajax_nopriv_WCP_ImageUpload_Controller::cancel_order', array($WCP_UploadImageData_Controller, 'cancel_order'));


add_action('wp_ajax_WCP_ImageUpload_Controller::confirm_order', array($WCP_UploadImageData_Controller, 'confirm_order'));
add_action('wp_ajax_nopriv_WCP_ImageUpload_Controller::confirm_order', array($WCP_UploadImageData_Controller, 'confirm_order'));
?>