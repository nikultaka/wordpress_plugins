<?php

echo "123"
echo "test";
/**
* Plugin Name: Website Custom Plugin
* Plugin URI: 
* Description: Custom Plugin
* Version: 1.0.0
* Author: PalladiumHub
* Author URI: 
* License: GPL2
*/
define( 'WCP_PLUGIN_VERSION', '1.0.0' );
define( 'WCP_PLUGIN_DOMAIN', 'website-custom-plugin' );
define( 'WCP_PLUGIN_URL', WP_PLUGIN_URL . '/Website-Custome-Plugin' );
function create_database_table_for_website_custome_plugin(){
		global $table_prefix, $wpdb;

		$tblname = 'product';
		$wp_track_table = $table_prefix . "$tblname";
		#Check to see if the table exists already, if not, then create it
                if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table) 
		{
                    

			$sql = "CREATE TABLE `".$wp_track_table ."` ( ";
			$sql .= "  `id`  int(100)   NOT NULL AUTO_INCREMENT, ";
                        $sql .= "  `user_id`  int(100)   NOT NULL, ";
                        $sql .= "  `front_image`  VARCHAR(200)   NOT NULL, ";
                        $sql .= "  `back_image`  VARCHAR(200)   NOT NULL, ";
                        $sql .= "  `description`  TEXT   NOT NULL, ";
                        $sql .= "  `status`  int(11)   NOT NULL, ";
			$sql .= "  `gm_created`  DATETIME NOT NULL,PRIMARY KEY (id)  ";
			$sql .= ");";
                        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
			dbDelta($sql);
		}
                $tblname_order_list = 'order_list';
		$wp_track_table = $table_prefix . "$tblname_order_list";
		#Check to see if the table exists already, if not, then create it
                if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table) 
		{                   

			$sql = "CREATE TABLE `".$wp_track_table ."` ( ";
			$sql .= "  `id`  int(100)   NOT NULL AUTO_INCREMENT, ";
                        $sql .= "  `services_id`  VARCHAR(100)   NOT NULL, ";
                        $sql .= "  `user_id`  int(100)   NOT NULL, ";
                        $sql .= "  `total_price`  float   NOT NULL, ";
                        $sql .= "  `currency`  varchar(10)   NOT NULL, ";
                        $sql .= "  `first_name`  varchar(100)   DEFAULT NULL, ";
                        $sql .= "  `surname`  varchar(100)   DEFAULT NULL, ";
                        $sql .= "  `email`  varchar(100)   NOT NULL, ";
                        $sql .= "  `contact_no`  varchar(100)   NOT NULL, ";
                        $sql .= "  `address`  varchar(500)   DEFAULT NULL, ";
                        $sql .= "  `city`  varchar(100)   DEFAULT NULL, ";
                        $sql .= "  `postal_code`  varchar(50)   DEFAULT NULL, ";
                        $sql .= "  `description`  TEXT   NOT NULL, ";
                        $sql .= "  `payment_transaction_id`  varchar(255)   NOT NULL, ";
                        $sql .= "  `payer_id`  varchar(255)   NOT NULL, ";
                        $sql .= "  `payer_email`  varchar(255)   NOT NULL, ";
                        $sql .= "  `payment_status`  tinyint(1)   NOT NULL DEFAULT '0' COMMENT '0 - Initialize, 1 - Success, 2 - Fail, 3 - Cancel', ";
                        $sql .= "  `status`  int(10)   NOT NULL, ";
			$sql .= "  `gm_created`  DATETIME NOT NULL,PRIMARY KEY (id)  ";
			$sql .= ");";
                        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
			dbDelta($sql);
		}
                $tblname_produt_order_list = 'order_product_list';
		$wp_track_table = $table_prefix . "$tblname_produt_order_list";
		#Check to see if the table exists already, if not, then create it
                if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table) 
		{                   

			$sql = "CREATE TABLE `".$wp_track_table ."` ( ";
			$sql .= "  `id`  int(11)   NOT NULL AUTO_INCREMENT, ";
                        $sql .= "  `order_id`  int(11)   NOT NULL, ";
                        $sql .= "  `product_id`  int(11)   NOT NULL, ";
                        $sql .= "  `total_item`  int(11)   NOT NULL, ";
                        $sql .= "  `total_price`  float   NOT NULL, ";
                        $sql .= "  `gm_created`  DATETIME NOT NULL,PRIMARY KEY (id)  ";
			$sql .= ");";
                        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
			dbDelta($sql);
		}
	}

register_activation_hook( __FILE__, 'create_database_table_for_website_custome_plugin' );
include_once(dirname(__FILE__)."/WCP/Upload-Image-Data/Controller.php");
include_once(dirname(__FILE__)."/WCP/BackEnd/Service/Controller.php");
include_once(dirname(__FILE__)."/WCP/FrontEnd/ImageUpload/Controller.php");
include_once(dirname(__FILE__)."/WCP/BackEnd/Orders/Controller.php");
include_once(dirname(__FILE__)."/WCP/BackEnd/PaymentConfiguration/Controller.php");
