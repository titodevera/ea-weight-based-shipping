<?php
namespace EA_Weight_Based_Shipping;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class EAWBS_Plugin{

	function __construct(){
		add_action( 'woocommerce_shipping_init', array($this, 'shipping_init') );
		add_filter( 'woocommerce_shipping_methods', array($this, 'shipping_methods') );
		add_action( 'admin_enqueue_scripts', array($this, 'register_scripts') );
		add_action( 'wp_ajax_eawbs_pricing_table_save', array($this, 'eawbs_pricing_table_save') );
		add_action( 'wp_ajax_nopriv_eawbs_pricing_table_save', array($this, 'eawbs_pricing_table_save') );
	}

	public function register_scripts($hook){
		wp_register_style( 'eawbs-admin-styles', EAWBS_PLUGIN . '/assets/css/styles-admin.css', false, EAWBS_VERSION );
		wp_register_script( 'eawbs-admin-scripts', EAWBS_PLUGIN . '/assets/js/functions-admin.js', array('jquery'), EAWBS_VERSION, true );
		if($hook==='woocommerce_page_wc-settings' && isset($_GET['tab']) && $_GET['tab']=='shipping'){
			wp_enqueue_style( 'eawbs-admin-styles' );
			wp_enqueue_script( 'eawbs-admin-scripts' );
			wp_localize_script( 'eawbs-admin-scripts', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		}
	}

	public function eawbs_pricing_table_save() {
		if(isset($_POST['pricing_rows']) && is_array($_POST['pricing_rows']) && isset($_POST['instance_id'])){
			if( update_option( 'eawbs-pricing-table-'.$_POST['instance_id'], json_encode($_POST['pricing_rows'])) ){
				echo 'ok';
			}else{
				echo 'error';
			}
		}
		wp_die();
	}

	public function shipping_init(){
		if ( ! class_exists( 'EA_Weight_Based_Shipping' ) ) {
			require_once 'class-ea-weight-based-shipping.php';
		}
	}

	public function shipping_methods($methods){
		$methods['ea_weight_based_shipping'] = new EA_Weight_Based_Shipping();
		return $methods;
	}

}
