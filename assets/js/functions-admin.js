jQuery.noConflict();
jQuery(document).ready(function($){

  $('.eawbs-pricing-table-row .eawbs-button-remove').not(':first').css('visibility','visible');
  $('.eawbs-pricing-table-row .eawbs-button-remove').on('click', function(e){
    e.preventDefault();
    $(this).closest('.eawbs-pricing-table-row').remove();
  });

  $('button#eawbs-button-add-row').on('click', function(e){
    e.preventDefault();
    var $newRow = $('.eawbs-pricing-table-row').first().clone();
    $('.eawbs-button-remove',$newRow).css('visibility','visible');
    $('input[type="text"]',$newRow).val('');
    $('.eawbs-pricing-table-actions').before($newRow);

    $('span.eawbs-button-remove',$newRow).on('click', function(e){
      e.preventDefault();
      $(this).closest('.eawbs-pricing-table-row').remove();
    });

  });

  $('#eawbs-button-save').on('click', function(e){
    e.preventDefault();

    //get all values
    var pricing_rows = [];
    $('.eawbs-pricing-table-row').each(function(){
      var weight = $('.eawbs-pricing-table-col-weight input',this).val();
      var cost = $('.eawbs-pricing-table-col-cost input',this).val();

      pricing_rows.push({weight:weight, cost:cost});
    });

    //save values
    var data = {
      'action': 'eawbs_pricing_table_save',
      'pricing_rows': pricing_rows,
      'instance_id': $(this).closest('.eawbs-pricing-table-wrapper').data('instance-id')
    };
    jQuery.post(ajax_object.ajax_url, data, function(response) {
      if(response=='ok'){
        alert('OK');
      }else{
        alert('ERROR');
      }
    });
  });



});
