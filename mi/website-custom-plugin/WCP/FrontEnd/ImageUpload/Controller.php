<?php

class WCP_ImageUpload_Controller {

    public function get_image_with_description_upload_form() {

        require_once plugin_dir_path(dirname(__FILE__)) . 'ImageUpload/HTML/image_upload_with_discription_form.php';
        $s = ob_get_contents();
        ob_end_clean();
        return $s;
    }

    public function store_image_description() {

        $result_array = array();
        $result_array['status'] = 0;
        if(!empty($_POST))
        {
            if(!empty($_FILES))
            {
                $error = array();
                require_once(ABSPATH . "wp-admin" . '/includes/file.php');
                if( isset($_FILES['ImageFront']) && $_FILES['ImageFront'] ){
                        $ImageFrontArr = wp_handle_upload( $_FILES['ImageFront'], array('test_form'=>false), current_time('mysql') ) ;
                        if ( $ImageFrontArr && ! isset( $ImageFrontArr['error'] ) ) {
                        } else {
                            $error['message'] = $ImageFrontArr['error'];
                        }
                }

                if( isset($_FILES['ImageBack']) && $_FILES['ImageBack'] ){
                        $ImageBackArr = wp_handle_upload( $_FILES['ImageBack'], array('test_form'=>false), current_time('mysql') ) ;
                        
                        if ( $ImageBackArr && ! isset( $ImageBackArr['error'] ) ) {
                            
                        } else {
                            $error['message'] = $ImageBackArr['error'];
                        }
                }
                
                if(empty($error))
                {
                    $description = isset($_POST['description'])?$_POST['description']:'';

                    $current_user = wp_get_current_user();
                    $user_id = $current_user->ID;
                    $data_insert = array();
                    $data_insert['user_id'] = $user_id;
                    $data_insert['front_image'] = $ImageFrontArr['url'];
                    $data_insert['back_image'] = $ImageBackArr['url'];
                    $data_insert['description'] = $description;
                    $data_insert['status'] = 1;
                    $data_insert['gm_created'] = date('Y-m-d H:i:s');

                    global $wpdb;
                    $tbl = $wpdb->prefix.'product';
                    $result = $wpdb->insert( $tbl,$data_insert, array( '%s', '%s', '%s', '%s', '%s', '%s'));
                    if($result){
                        $result_array['status'] = 1;
                        $result_array['message'] = "Llave subida correctamente";
                    } else {
                        $result_array['status'] = 0;
                        $result_array['error'] = "Hubo un error de MySQL. Intentelo de nuevo.";
                    }
                } else {
                    $result_array['status'] = 0;
                    $result_array['error'] = $error['message'];
                }
            }
        }
        echo json_encode($result_array);exit;
    }
}

$WCP_ImageUpload_Controller = new WCP_ImageUpload_Controller();
add_shortcode('Image_upload_form_with_description', array($WCP_ImageUpload_Controller, 'get_image_with_description_upload_form'));
/// Ajax
add_action('wp_ajax_nopriv_WCP_ImageUpload_Controller::store_image_description', Array($WCP_ImageUpload_Controller, 'store_image_description'));
add_action('wp_ajax_WCP_ImageUpload_Controller::store_image_description', Array($WCP_ImageUpload_Controller, 'store_image_description'));
?>