jQuery(document).ready(function($){
var admin_url=STLP_handler.admin_url;
var nonce=STLP_handler.nonce;

	$(document).on('click','.closs', function(){
		$(".stlp-popup-section").remove();  
    });

	var pro_id;

	$('.stlp_view_product').on('click',function (e) {
        e.preventDefault();
		product_class=$(this);
		pro_id=$(this).data('product_id');
		jQuery.ajax({
			type: "post",
			dataType: "json",
			url: admin_url,
			delay: 1,
			data: {
				action: 'get_emp_data',
				product_id: pro_id,
				nonce:nonce,
			},
			success: function(response){
				if(response['popup_html']){
					console.log(response['popup_html']);
					product_class.closest('.product_popup_data').append(response['popup_html']);
					product_class.closest('.product_popup_data').find('section.stlp-popup-section').show();
				}
			}
		});

	});

	$('.search_button').on('click',function (e){
		e.preventDefault();	
        product_id=$(this).data('sproduct_id');
		order_id=$('#stlp_search-search-input').val();
		if(!(order_id)){
			$('stlp_search-search-input').focus();
			return;
		}
		jQuery.ajax({
			type: "post",
			dataType: "json",
			url: admin_url,
			delay: 1,
			data: {
				action: 'get_search_bar_data',
				order_id: order_id,
				product_id: product_id,
				nonce:nonce,
			},
			success: function(response){

				if(response['order_search_html'] != undefined){

					jQuery('table.order_table').find('tbody').html(response['order_search_html']);S
				}
			}
		});
	});	
});


