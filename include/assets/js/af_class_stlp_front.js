jQuery(document).ready(function($){
  var ajax_url= STLP_URL.admin_url;
  var nonce= STLP_URL.nonce;
  var stlp_atpcarousel= STLP_URL.atpcarousel;
  var stlp_nvgdotcarousel = STLP_URL.nvgdotcarousel;
  var auto_play_or_not = '1'== stlp_atpcarousel ? true : false;
  var display_navigation_dots_or_not = '1'== stlp_nvgdotcarousel ? true : false;
  jQuery('.af-stlp-relted-items').slick({
    dots: false,
    infinite: true,
    slidesToShow: 3,
    slidesToScroll: 1,
    autoplaySpeed: 2000,
    arrows: false,
    autoplay : auto_play_or_not,
    dots: display_navigation_dots_or_not,      
   
  });

  $(".stlp_selected_product").on('click',function()
  {
       calcultion($);
  });
  $(".cart-quantity").on('change',function()
  {
       calcultion($);
  });
  $(".qty").on('change',function()
  {
       calcultion($);
  });
});

function calcultion($) 
{ 
        var price=0;                                      // Variable for Price
        var qty=0;                                        // Variable for Quantity
        var total_price = 0;                              // Variable for Total Price
        var select_products =0;                           // Variable for Select Products

    $('.stlp_selected_product' ).each(function() {
      if($(this).is(":checked"))
      {  
        var input_field=$(this).closest(".af-stlp-cart").find(".cart-quantity");
        select_products ++;
        qty +=  parseInt ( input_field.val() );
        price += parseFloat(   input_field.data('product_price') );

        total_price   +=  parseInt ( input_field.val() ) * parseFloat(   input_field.data('product_price') );
      }
    });
    if(qty >= 1)
      {
        total_price += parseInt( $('.qty').val() ) * parseFloat( $('.product_price').val() );

        $(".Stlp-qty-price-text").show();
        $('.no-of-pro-select span').html(select_products);
        $('.total-pro-price span.price').html(total_price);

      } else{

          $(".Stlp-qty-price-text").hide();
      }

    console.log(  select_products );
    console.log(  total_price );    
}

