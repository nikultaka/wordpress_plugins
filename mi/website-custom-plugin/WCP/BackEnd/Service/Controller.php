<?php

include_once(dirname(__FILE__) . "/View.php");
include_once(dirname(__FILE__) . "/Helper.php");

class WCP_BackEnd_Service_Controller {

    public function get_data() {
        $user = wp_get_current_user();
        $obj_result = new \stdclass();
        $obj_result->is_success = false;

        $requestData = $_REQUEST;

        global $wpdb,$wp;

        $data = array();

        $sql = "SELECT s.* FROM ".$wpdb->prefix."service as s";
        //This is for search value
                
        $sql .= " WHERE s.status != -1 ";
        if (isset($requestData['search']['value']) && $requestData['search']['value'] != '') {
            $sql .= " AND (s.service_name LIKE '%" . $requestData['search']['value'] . "%') ";
        }

        //This is for order 
        $columns = array(
            0. => 'id',
            1 => 'service_name',
            2 => 'price',
            3 => 'gm_created',
        );
        if (isset($requestData['order'][0]['column']) && $requestData['order'][0]['column'] != '') {
            $order_by = $columns[$requestData['order'][0]['column']];
            $sql .= " ORDER BY " . $order_by;
        } else {
            $sql .= " ORDER BY s.id DESC";
        }

        if (isset($requestData['order'][0]['dir']) && $requestData['order'][0]['dir'] != '') {
            $sql .= " " . $requestData['order'][0]['dir'];
        } else {
            $sql .= " DESC ";
        }

        //echo $sql; die;    

        //This is for count
        $result = $wpdb->get_results($sql, OBJECT);
        $totalData = 0;
        $totalFiltered = 0;
        if (count($result > 0)) {
            $totalData = count($result);
            $totalFiltered = count($result);
        }

        //This is for pagination
        if (isset($requestData['start']) && $requestData['start'] != '' && isset($requestData['length']) && $requestData['length'] != '') {
            $sql .= " LIMIT " . $requestData['start'] . "," . $requestData['length'];
        }

        //echo $sql; die;

        $service_price_list = $wpdb->get_results($sql, "OBJECT");
        $arr_data = Array();
        $arr_data = $result;


        foreach ($service_price_list as $row) {
            $temp['ID'] = $row->id;
            $temp['Service'] = $row->service_name;
            $temp['Price'] = $row->price;
            $temp['Date'] = $row->gm_created;
            $status = $row->status;
            if ($status == "0") {

                $statusstring = "Deactive";
            } elseif ($status == "1") {
                $statusstring = "Active";
            } else {
                $statusstring = "";
            }
            $temp['Status'] = $statusstring;

            $id = $row->id;

            $action = "<input type='button' value='Delete' class='btn btn-danger' onclick='service_record_delete(" . $id . ")'>&nbsp;";
            $action .= '<input type="button" value="Update" class="btn btn-success"  onclick="service_record_update(' . $id . ')">';
            $temp['action'] = $action;
            $data[] = $temp;
            $id = "";
        }



        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
            "sql" => $sql
        );
        echo json_encode($json_data);
        exit(0);
    }

    public function index() {
        ob_start();
        global $wpdb;
        //This is for service list
        $sql = "SELECT * FROM service";
        $service_list = $wpdb->get_results($sql, "ARRAY_A");

        include(dirname(__FILE__) . "/html/service_level_list.php");
        $s = ob_get_contents();
        ob_end_clean();
        print $s;
    }
    
    public function add_service_price() {
        $result_array = array();
        $result_array['status'] = 0;
        if (!empty($_POST)) {
            global $wpdb,$wp;
            $service_name = $_POST['service_name'];
            $price = $_POST['price'];
            $status=$_POST['status'];
            $id = $_POST['id'];
            $date = date('Y-m-d H:i:s');
            $table = $wpdb->prefix."service";

            if(isset($id) && $id !='')
            {
                $result = $wpdb->update($table, array(
                    'service_name' => $service_name,
                    'price' => $price,
                    'status'=>$status,
                    'gm_created' => $date), array('id' => $id)
                );
            } else {
                $wpdb->insert($table, array(
                    'service_name' => $service_name,
                    'price' => $price,
                    'status'=>$status,
                    'gm_created' => $date
                    )
                );
            }
            $result_array['status'] = 1;
        }
        echo json_encode($result_array);exit;
    }

    public function delete_service_price_record() {
        $result_array = array();
        $result_array['status'] = 0;
        if (!empty($_POST)){
            global $wpdb;
            $id = $_POST['service_id'];
            $table = $wpdb->prefix."service";
            $result = $wpdb->update($table, array(
                'status' => '-1')
                    , array('id' => $id)
            );
            $result_array['status'] = 1;
        }
        echo json_encode($result_array);exit;
    }

    public function get_update_service_product() {
        $result = array();
        $result['status'] = 0;
        if (!empty($_POST)) {

            global $wpdb;
            $id = $_POST['id'];
            $sql = "SELECT * FROM ".$wpdb->prefix."service where id ={$id}";

            $service_price_details = $wpdb->get_results($sql, "ARRAY_A");
            $result['status'] = 1;
            $result['serice_price_details'] = $service_price_details[0];
            //return $result;
        }
        echo json_encode($result);
        exit;
    }

    public function update_service_list() {

        if (!empty($_POST)) {
            global $wpdb;
            $id = $_POST['id'];
            $service_id = $_POST['service_id'];
            $service_level_id = $_POST['service_level_id'];
            $price = $_POST['price'];
            $warranty_price = $_POST['warranty_price'];
            $zipcode = $_POST['zipcode'];
            $status=$_POST['status'];

            $table = "service_price";




            $result = $wpdb->update($table, array(
                'service_id' => $service_id,
                'service_level_id' => $service_level_id,
                'price' => $price,
                'warranty_price'=>$warranty_price,
                'status'=>$status,
                'zip_code' => $zipcode), array('id' => $id)
            );
            if ($result > 0) {
                echo "ok";
                exit();
            }
        }
    }

    function add_menu_pages() {
        add_menu_page('Service', 'Service', 'manage_options', 'service', Array("WCP_BackEnd_Service_Controller", "index"));
        /* add_submenu_page("stylus-user-profile", "User Size Detail", "User Size Detail", 'manage_options', "stylus-user-size-detail", Array("WCP_BackEnd_Profiles_Controller", "display_user_profile_detail"));
          add_submenu_page('stylus-user-profile', 'Payment Orders', 'Payment Orders', 0, 'stylus-payment-orders',Array("WCP_BackEnd_Profiles_Controller","display_user_payment_orders")); */
    }

}

add_action('admin_menu', array("WCP_BackEnd_Service_Controller", 'add_menu_pages'));

add_action('wp_ajax_WCP_BackEnd_Service_Controller::get_data', Array('WCP_BackEnd_Service_Controller', 'get_data'));
add_action('wp_ajax_nopriv_WCP_BackEnd_Service_Controller::get_data', array('WCP_BackEnd_Service_Controller', 'get_data'));

add_action('wp_ajax_WCP_BackEnd_Service_Controller::add_service_price', Array('WCP_BackEnd_Service_Controller', 'add_service_price'));
add_action('wp_ajax_nopriv_WCP_BackEnd_Service_Controller::add_service_price', array('WCP_BackEnd_Service_Controller', 'add_service_price'));

add_action('wp_ajax_WCP_BackEnd_Service_Controller::delete_service_price_record', Array('WCP_BackEnd_Service_Controller', 'delete_service_price_record'));
add_action('wp_ajax_nopriv_WCP_BackEnd_Service_Controller::delete_service_price_record', array('WCP_BackEnd_Service_Controller', 'delete_service_price_record'));

add_action('wp_ajax_WCP_BackEnd_Service_Controller::get_update_service_product', Array('WCP_BackEnd_Service_Controller', 'get_update_service_product'));
add_action('wp_ajax_nopriv_WCP_BackEnd_Service_Controller::get_update_service_product', array('WCP_BackEnd_Service_Controller', 'get_update_service_product'));

add_action('wp_ajax_WCP_BackEnd_Service_Controller::update_service_list', Array('WCP_BackEnd_Service_Controller', 'update_service_list'));
add_action('wp_ajax_nopriv_WCP_BackEnd_Service_Controller::update_service_list', array('WCP_BackEnd_Service_Controller', 'update_service_list'));

/* add_action( 'wp_ajax_WCP_BackEnd_Profiles_Controller::get_payment_data', Array('WCP_BackEnd_Profiles_Controller','get_payment_data'));

  add_action( 'wp_ajax_WCP_BackEnd_Profiles_Controller::delete_user',array( 'WCP_BackEnd_Profiles_Controller', 'delete_user' ));

  add_action( 'wp_ajax_WCP_BackEnd_Profiles_Controller::export_csv',array( 'WCP_BackEnd_Profiles_Controller', 'export_csv' ));
  add_action( 'wp_ajax_WCP_BackEnd_Profiles_Controller::delete_select_user',array( 'WCP_BackEnd_Profiles_Controller', 'delete_select_user' ));
  add_action( 'wp_ajax_WCP_BackEnd_Profiles_Controller::stripe_payment', Array('WCP_BackEnd_Profiles_Controller','stripe_payment') ); */

//add_action( 'wp_ajax_nopriv_get_client_info', array('FrontEnd_LeadsBoard_Controller', 'get_client_info'));
?>
