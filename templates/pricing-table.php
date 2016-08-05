<div class="eawbs-pricing-table-wrapper" data-instance-id="<?php echo $instance_id;?>">
  <h3><?php _e('Pricing table','ea-weight-based-shipping');?></h3>
  <p><?php _e('Configure shipping costs','ea-weight-based-shipping');?></p>
  <div class="eawbs-pricing-table">
    <div class="eawbs-pricing-table-heading eawbs-clearfix">
      <div class="eawbs-pricing-table-heading-weight">
        <div class="eawbs-pricing-table-label">
          <?php _e('Max weight:','ea-weight-based-shipping');?>
        </div>
      </div>
      <div class="eawbs-pricing-table-heading-cost">
        <div class="eawbs-pricing-table-label">
          <?php _e('Cost:','ea-weight-based-shipping');?>
        </div>
      </div>
    </div>

    <?php
    if( empty($eawbs_pricing_table) ) {
      require_once EAWBS_PLUGIN_PATH . 'templates/pricing-table-row.php';
    }else{
      foreach ($eawbs_pricing_table as $row_element) {
        require EAWBS_PLUGIN_PATH . 'templates/pricing-table-row.php';
      }
    }
    ?>


    <div class="eawbs-pricing-table-actions">
      <button class="button woocommerce-save-button" id="eawbs-button-add-row">
        <span class="dashicons dashicons-plus"></span>
      </button>
      <button class="button woocommerce-save-button" id="eawbs-button-save">
        <?php _e('Save pricing table','ea-weight-based-shipping');?>
      </button>
    </div>
  </div>
</div>
