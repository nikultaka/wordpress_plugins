<?php

include_once(dirname(__FILE__) . "/View.php");
include_once(dirname(__FILE__) . "/Helper.php");

class WCP_BackEnd_Timeline_Controller {

    public function get_data() {
        $user = wp_get_current_user();
        $obj_result = new \stdclass();
        $obj_result->is_success = false;

        $requestData = $_REQUEST;

        global $wpdb;

        $data = array();

        $sql = "SELECT t.*,s.service_name"
                . " FROM timeline as t "
                . " LEFT JOIN service as s ON s.id = t.type";


        //echo $sql; die;    

        //This is for count
        $result = $wpdb->get_results($sql, OBJECT);
        $totalData = 0;
        $totalFiltered = 0;
        if (count($result > 0)) {
            $totalData = count($result);
            $totalFiltered = count($result);
        }


        //echo $sql; die;

        $service_price_list = $wpdb->get_results($sql, "OBJECT");
        $arr_data = Array();
        $arr_data = $result;


        foreach ($service_price_list as $row) {
            $temp['Service'] = $row->service_name;
            $temp['Days'] = $row->days;
            $id = $row->id;

            $action = '<input type="button" value="Update" class="btn btn-success"  onclick="get_data(' . $id . ','.$row->days.')">';
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
        $sql = "SELECT * FROM timeline";
        $service_list = $wpdb->get_results($sql, "ARRAY_A");

        include(dirname(__FILE__) . "/html/timeline_list.php");
        $s = ob_get_contents();
        ob_end_clean();
        print $s;
    }

    public function update_timeline_list() {

        if (!empty($_POST)) {
            global $wpdb;
            $id = $_POST['id'];
            $service_id = $_POST['service_id'];
            $days = $_POST['days'];
            $table = "timeline";

            $result = $wpdb->update($table, array(
		    'days' => $days,
                ), array('id' => $id)
            );
            if ($result > 0) {
                echo "ok";
                exit();
            }
        }
    }

    function add_menu_pages() {
        add_menu_page('Timeline', 'Timeline', 'manage_options', 'timeline', Array("WCP_BackEnd_Timeline_Controller", "index"));
    }

}

add_action('admin_menu', array("WCP_BackEnd_Timeline_Controller", 'add_menu_pages'));

add_action('wp_ajax_WCP_BackEnd_Timeline_Controller::get_data', Array('WCP_BackEnd_Timeline_Controller', 'get_data'));
add_action('wp_ajax_nopriv_WCP_BackEnd_Timeline_Controller::get_data', array('WCP_BackEnd_Timeline_Controller', 'get_data'));

add_action('wp_ajax_WCP_BackEnd_Timeline_Controller::update_timeline_list', Array('WCP_BackEnd_Timeline_Controller', 'update_timeline_list'));
add_action('wp_ajax_nopriv_WCP_BackEnd_Timeline_Controller::update_timeline_list', array('WCP_BackEnd_Timeline_Controller', 'update_timeline_list'));

?>
