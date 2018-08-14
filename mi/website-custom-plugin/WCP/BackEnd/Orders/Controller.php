<?php
include_once(dirname(__FILE__)."/View.php");
class WCP_BackEnd_Orders_Controller {
	
	function add_menu_pages() {
		add_menu_page( 'Orders List', 'Orders List', 'manage_options', 'order-list', Array("WCP_BackEnd_Orders_Controller","index"));
	}
	
	public function index() {
		ob_start();
		include(dirname(__FILE__)."/html/order_list.php");
		$s = ob_get_contents();
		ob_end_clean();
		print $s;
		
	}
	
	function get_orders(){
		
		global $wpdb;
                $colomn_array = array("o.id","s.service_name","u.user_nicename","o.first_name","o.surname","o.email","o.contact_no","o.address","o.city","o.postal_code","o.status","o.gm_created");
                $where = '';
                $search_txt = isset($_POST['search']['value']) && $_POST['search']['value']!=''?mysql_real_escape_string($_POST['search']['value']):'';
                if($search_txt!=''){
                    $where .= " AND (o.id = '".$search_txt."' "
                            . "OR o.first_name LIKE '%".$search_txt."%' "
                            . "OR u.user_nicename LIKE '%".$search_txt."%' "
                            . "OR o.email LIKE '%".$search_txt."%' "
                            . "OR o.surname LIKE '%".$search_txt."%'"
                            . "OR s.service_name LIKE '%".$search_txt."%')";
                }
                
                //This is for sorting
                $order_by = '';
                if(isset($_POST['order'][0]['column']) && $_POST['order'][0]['column']!=''){
                    $order_by = $colomn_array[$_POST['order'][0]['column']];
                } else {
                    $order_by = "o.id";
                }
                
                $order_type = '';
                if(isset($_POST['order'][0]['dir']) && $_POST['order'][0]['dir']!=''){
                    $order_type = " DESC";
                } else {
                    $order_type = " DESC";
                }
                
                $order_str = "ORDER BY ".$order_by." ".$order_type;
                
                //This is for pagination
                $limit = '';
                if(isset($_POST['start']) && $_POST['start']!='' && isset($_POST['length']) && $_POST['length']!=''){
                    $limit .= ' LIMIT '.$_POST['start'].','.$_POST['length'];
                }
                
		$query= "Select o.*,s.service_name,u.user_nicename  FROM ".$wpdb->prefix."order_list as o "
                        . "LEFT JOIN ".$wpdb->prefix."service as s ON s.id = o.services_id "
                        . "LEFT JOIN ".$wpdb->prefix."users as u ON o.user_id = u.id "
                        . "WHERE o.status = 1 $where $order_str $limit"; 
               
                $result = $wpdb->get_results($query, "ARRAY_A");
                $arr_data = Array();
		$arr_data = $result;
                
                if(!empty($arr_data)){
                    foreach($arr_data as $key=>$row){
                        if($row['payment_status'] == 1){$payment_status='Success';}
                        elseif ($row['payment_status'] == 0) {$payment_status='Initialize';}
                        elseif ($row['payment_status'] == 2) {$payment_status='Fail';}
                        else {$payment_status='Cancel';}
                         $arr_data[$key]['status'] = $payment_status;
                       // $arr_data[$key]['status'] = ($row['status'] == 1)?'Active':'Inactive';
                        $arr_data[$key]['created_on'] = date('m/d/Y H:i:s', strtotime($row['gm_created']));
                        $arr_data[$key]['action'] = "<a href='javascript:void(0);' onclick='view_order(".$row['id'].")' >View</a>";
                    }
                }
                
		$totalData = $wpdb->get_var("Select count(*) from ".$wpdb->prefix."order_list WHERE 1 $where");
			
		$totalFiltered = $totalData;
		$json_data = array(
			"recordsTotal"    => intval( $totalData ),
			"recordsFiltered" => intval( $totalFiltered ),
			"data"            => $arr_data,
			"sql"             => $query
		); 
		echo json_encode($json_data);
		exit(0);
		wp_die(); 
	} 
        
        function get_order_details(){
                if(!empty($_POST))
                {
                    global $wpdb;
                    $id = isset($_POST['id']) && $_POST['id']!=''?$_POST['id']:'';
                    $query= "Select o.*,s.service_name,u.user_nicename  FROM ".$wpdb->prefix."order_list as o "
                        . "LEFT JOIN ".$wpdb->prefix."service as s ON s.id = o.services_id "
                        . "LEFT JOIN ".$wpdb->prefix."users as u ON o.user_id = u.id "
                        . "WHERE o.id = ".$id; 
                    $result = $wpdb->get_row($query, "ARRAY_A");
                   
                    if(!empty($result))
                    {
                        //This is for product details
                        $query_product = "Select p.*,op.total_item,op.totalprice,ol.services_id,s.service_name,s.price FROM ".$wpdb->prefix."order_product_list as op "
                            . "LEFT JOIN ".$wpdb->prefix."product as p ON p.id = op.product_id "
                            ."LEFT JOIN ".$wpdb->prefix."order_list as ol ON ol.id = op.order_id "  
                            ."LEFT JOIN ".$wpdb->prefix."service as s ON s.id = ol.services_id "  
                            . "WHERE op.order_id= ".$id; 
                        
                        
                        $product_result = $wpdb->get_results($query_product, "ARRAY_A");
           
                        $product_string = '';
                        if(!empty($product_result))
                        {
                            $product_string .= '<table class="table table-bordered table-order-list">';
                            $product_string .= '<tbody>';
                            $product_string .= '<tr>';
                            $product_string .= '<td style="background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;">Front Image</td>';
                            $product_string .= '<td style="background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;">Back Image</td>';
                            
                            $product_string .= '<td style="background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;">Description</td>';
                            $product_string .= '<td style="background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;">Quantity</td>';
                           //  $product_string .= '<td style="background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;">Shipping</td>';
                            $product_string .= '<td style="background-color:#1ABC9C;vertical-align: inherit;color:white;font-weight: bold;" >Price</td>';
                            $product_string .= '</tr>';
                            foreach($product_result as $key=>$product){
                                $product_string .= '<tr>';
                                $product_string .= '<td><img src="'.$product['front_image'].'" height="50px" /></td>';
                                $product_string .='<td><img src="'.$product['back_image'].'" height="50px" /></td>';
                                $product_string .= '<td>'.$product['description'].'</td>';
//                                if($key == 0)
//                                {
                                $product_string .= '<td>'.$product['total_item'].'</td>';
                               //   $product_string .= '<td>'.$product['service_name'].'</td>';
                                $product_string .= '<td>'.$product['totalprice'].' &euro;</td>';
                              //  }
                                $product_string .= '</tr>';
                            }
                            $breakprice_string .='<tr>';
                            $breakprice_string .='<td colspan="4" style="vertical-align: inherit;background-color:#BDC3C7;font-weight: bold;">Shipping Detail</td>';
                            $breakprice_string .='</tr>';
                            
                            $product_string .='<tr>';
                            $product_string .= '<td colspan="4"><b style="float:right;">'.$product_result[0]['service_name'].'</b></td>';
                            $product_string .= '<td>'.$product_result[0]['price'].' &euro;</td>';
                            $product_string .='</tr>';
                            
                            $product_string .= '<tr>';
                            $product_string .= '<td colspan="4"><b style="float:right;">Total</b></td>';
                            $product_string .= '<td>'.$result['total_price'].' &euro;</td>';
                            $product_string .= '</tr>';
                            $product_string .= '</table>';
                            $result['product_string'] = $product_string;
                        }
                    }
                    $result_array = array();
                    $result_array['status'] = 1;
                    $result_array['order_details'] = $result;
                }
		echo json_encode($result_array);exit;
		exit(0);
		wp_die(); 
	}
}
$wcp_backend_orders_controller = new WCP_BackEnd_Orders_Controller();
add_action('admin_menu', array("WCP_BackEnd_Orders_Controller", 'add_menu_pages'));

add_action( 'wp_ajax_WCP_BackEnd_Orders_Controller::get_orders',array($wcp_backend_orders_controller, 'get_orders' ));
add_action( 'wp_ajax_WCP_BackEnd_Orders_Controller::get_order_details',array($wcp_backend_orders_controller, 'get_order_details' ));
?>