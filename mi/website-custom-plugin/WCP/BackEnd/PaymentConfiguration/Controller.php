<?php
class WCP_BackEnd_PaymentConfiguration_Controller {


	function save_paypal_configuration() {
            
                $result_array = array();
                $result_array['status'] = 0;
		if(!empty($_POST)){
                    
                    global $wpdb;
                    
                    //This is to check 
                    $wcp_paypal_mode = get_site_option( 'wcp_paypal_mode' );
                    if(isset($wcp_paypal_mode) && $wcp_paypal_mode!=''){
                        update_option( 'wcp_paypal_mode', $_POST['wcp_paypal_mode']);
                    } else { 
                        add_option( 'wcp_paypal_mode', $_POST['wcp_paypal_mode'], '', 'yes' ); 
                    }
                    
                    //This is to check 
                    $wcp_paypal_mode = get_site_option( 'wcp_paypal_sandbox_app_key' );
                    if(isset($wcp_paypal_mode) && $wcp_paypal_mode!=''){
                        update_option( 'wcp_paypal_sandbox_app_key', $_POST['wcp_paypal_sandbox_app_key']);
                    } else { 
                        add_option( 'wcp_paypal_sandbox_app_key', $_POST['wcp_paypal_sandbox_app_key'], '', 'yes' ); 
                    }
                    
                    //This is to check 
                    $wcp_paypal_mode = get_site_option( 'wcp_paypal_production_app_key' );
                    if(isset($wcp_paypal_mode) && $wcp_paypal_mode!=''){
                        update_option( 'wcp_paypal_production_app_key', $_POST['wcp_paypal_production_app_key']);
                    } else { 
                        add_option( 'wcp_paypal_production_app_key', $_POST['wcp_paypal_production_app_key'], '', 'yes' ); 
                    }
                    //this is for product price
                     $wcp_paypal_mode = get_site_option( 'wcp_product_price' );
                    if(isset($wcp_paypal_mode) && $wcp_paypal_mode!=''){
                        update_option( 'wcp_product_price', $_POST['wcp_product_price']);
                    } else { 
                        add_option( 'wcp_product_price', $_POST['wcp_product_price'], '', 'yes' ); 
                    }
                    $result_array['status'] = 1;
                    $result_array['msg'] = "Paypal Configuration updated successfully.";
                }
                echo json_encode($result_array);exit;
	}

	public function index() {
		ob_start();
		include(dirname(__FILE__)."/html/payment_configuratoin.php");
		$s = ob_get_contents();
		ob_end_clean();
		print $s;
		
	}
        
        function add_menu_pages() {
		add_menu_page( 'Payment Configuration', 'Payment Configuration', 'manage_options', 'payment-configuration', Array("WCP_BackEnd_PaymentConfiguration_Controller","index"));
		/* add_submenu_page("stylus-user-profile", "User Size Detail", "User Size Detail", 'manage_options', "stylus-user-size-detail", Array("WCP_BackEnd_Profiles_Controller", "display_user_profile_detail"));
		add_submenu_page('stylus-user-profile', 'Payment Orders', 'Payment Orders', 0, 'stylus-payment-orders',Array("WCP_BackEnd_Profiles_Controller","display_user_payment_orders")); */
	}
}
		
	add_action('admin_menu', array("WCP_BackEnd_PaymentConfiguration_Controller", 'add_menu_pages'));
	
	add_action( 'wp_ajax_WCP_BackEnd_PaymentConfiguration_Controller::save_paypal_configuration', Array('WCP_BackEnd_PaymentConfiguration_Controller','save_paypal_configuration'));
	add_action( 'wp_ajax_nopriv_WCP_BackEnd_PaymentConfiguration_Controller::save_paypal_configuration', array('WCP_BackEnd_PaymentConfiguration_Controller', 'save_paypal_configuration'));
?>
