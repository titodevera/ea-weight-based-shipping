<?php
namespace EA_Weight_Based_Shipping;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class EA_Weight_Based_Shipping extends \WC_Shipping_Method {

	/** @var string cost passed to [fee] shortcode */
	protected $fee_cost = '';

	public function __construct( $instance_id = 0 ) {
		$this->id                    = 'ea_weight_based_shipping';
		$this->instance_id 			 = absint( $instance_id );
		$this->method_title          = __( 'Weight based', 'ea-weight-based-shipping' );
		$this->method_description    = __( 'Weight based shipping.', 'ea-weight-based-shipping' );
		$this->supports              = array(
			'shipping-zones',
			'instance-settings',
			'instance-settings-modal',
		);
		$this->init();

		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	/**
	 * init user set variables.
	 */
	public function init() {
		$this->instance_form_fields = $this->get_settings();
		$this->title                = $this->get_option( 'title' );
		$this->packing_weight       = $this->get_option( 'packing_weight' );
		$this->tax_percentage       = $this->get_option( 'tax_percentage' );
		$this->extra_kg       			= $this->get_option( 'extra_kg' );
	}

	public function get_settings(){
		$settings = array(
			'enabled' => array(
				'title'     => __( 'Enable/Disable', 'ea-weight-based-shipping' ),
				'type'       => 'checkbox',
				'label'     => __( 'Enable Weight Based Shipping', 'ea-weight-based-shipping' ),
				'default'     => 'yes'
			),
			'title' => array(
				'title' 		=> __( 'Method Title', 'ea-weight-based-shipping' ),
				'type' 			=> 'text',
				'description' 	=> __( 'This controls the title which the user sees during checkout.', 'ea-weight-based-shipping' ),
				'default'		=> __( 'Weight based', 'ea-weight-based-shipping' ),
				'desc_tip'		=> true
			),
			'packing_weight' => array(
				'title' 		=> __( 'Packing Weight', 'ea-weight-based-shipping' ),
				'type' 			=> 'text',
				'description' 	=> __( 'The weight of the packaging will be added to the total weight of the order.', 'ea-weight-based-shipping' ),
				'default'		=> 0,
				'desc_tip'		=> true
			),
			'extra_kg' => array(
				'title' 		=> __( 'Extra KG', 'ea-weight-based-shipping' ),
				'type' 			=> 'text',
				'description' 	=> __( 'Extra KG cost.', 'ea-weight-based-shipping' ),
				'default'		=> 0,
				'desc_tip'		=> true
			),
			'tax_percentage' => array(
				'title' 		=> __( 'Tax', 'ea-weight-based-shipping' ),
				'type' 			=> 'text',
				'description' 	=> __( 'Tax percentage.', 'ea-weight-based-shipping' ),
				'default'		=> 0,
				'desc_tip'		=> true
			)
		);

		return $settings;
	}

	public function admin_options(){
		if ( ! $this->instance_id ) {
				echo '<h3>' . esc_html( $this->get_method_title() ) . '</h3>';
		}
		echo wp_kses_post( wpautop( $this->get_method_description() ) );
		echo $this->get_admin_options_html();

		ob_start();

		$instance_id = -1;
		if(isset($_GET['instance_id'])){
			$instance_id = $_GET['instance_id'];
		}
		$eawbs_pricing_table = json_decode(get_option('eawbs-pricing-table-'.$instance_id));
		if( $instance_id>-1 ){
			require_once EAWBS_PLUGIN_PATH . 'templates/pricing-table.php';
		}

		echo ob_get_clean();
	}

	public function is_available( $package ){
		$package_weight = 0;
		foreach ( $package['contents'] as $item_id => $values ) {
			$product = $values['data'];
			$product_weight =  $product->get_weight();
			$quantity = $values["quantity"];
			$product_weight_total = $product_weight*$quantity;
			$package_weight += $product_weight_total;
		}
		if($package_weight>0){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Calculate package weight
	 *
	 * @param array $package
	 */
	private function calculate_package_weight($package){
		$package_weight = 0;
		foreach ( $package['contents'] as $item_id => $values ) {
			$product = $values['data'];
			$product_weight =  $product->get_weight();
			$quantity = $values["quantity"];
			$product_weight_total = $product_weight*$quantity;
			$package_weight += $product_weight_total;
		}

		return $package_weight + $this->packing_weight;
	}


	/**
	 * calculate_shipping function.
	 *
	 * @param array $package (default: array())
	 */
	public function calculate_shipping( $package = array() ) {

		$package_weight = $this->calculate_package_weight($package);

		$current_pricing_table = get_option( 'eawbs-pricing-table-'.$this->instance_id);
		$current_pricing_table = json_decode($current_pricing_table);
		$shipping_cost = 0;

		foreach ($current_pricing_table as $pricing_row) {
			if($pricing_row->weight > $package_weight){
				$shipping_cost = $pricing_row->cost;
				break;
			}
			if($pricing_row === end($current_pricing_table)){
				$heaviest = end($current_pricing_table);
				$extra = ($package_weight - $heaviest->weight) * $this->extra_kg;
				$shipping_cost = $heaviest->cost + $extra;
			}
		}

		$tax_percentage = $this->tax_percentage/100;
		$tax = $tax_percentage * $shipping_cost;

		$rate = array(
			'id' => $this->id,
			'label' => $this->title ,
			'cost' => $tax + $shipping_cost,
			'calc_tax' => 'per_item'
		);

		$this->add_rate( $rate );

	}

}
