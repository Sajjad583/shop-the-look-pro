<?php

if (!defined('ABSPATH')) {
    exit();
}

class Sp_Th_Lk_Pro_Front {

    public function __construct() 
    {
        add_action('wp_enqueue_scripts', array($this, 'af_stlp_front_scripts') );

        if('1'== get_option('chk_for_stlp_relevent_product')){
            add_filter( 'woocommerce_after_add_to_cart_quantity',array($this, 'af_stlp_add_products' ));
            add_filter( "wp_footer",array($this, 'wp_footer_function' ), 10,2 );
            add_filter( "woocommerce_add_cart_item_data",array($this, 'add_product_to_cart' ), 10,2 );
            add_action( "woocommerce_checkout_create_order_line_item",array($this, 'store_cart_item_to_order_item_data' ), 10,4 );
            add_action( 'woocommerce_checkout_update_order_meta',array($this, 'checkout_field_update_order_meta' ));
            if('1'== get_option('chk_for_stlp_display_stock_details')){
                add_filter('woocommerce_before_add_to_cart_quantity', array( $this, 'af_stlp_show_quantity')); }
                add_filter( 'woocommerce_product_add_to_cart_text', array( $this,'shop_page_add_to_cart_btnfunction'));
                add_filter( 'woocommerce_loop_add_to_cart_link', array( $this,'replacing_add_to_cart_button'), 10, 2);
            }
        }

        public function af_stlp_show_quantity(){
            global $product;
            $prod_obj = wc_get_product(get_the_ID());
            ?>
            <p><?php echo esc_attr('Remaining Stock quantity is  '. $prod_obj->get_stock_quantity()); ?></p>
            <?php
        }

        public function shop_page_add_to_cart_btnfunction() {
            if (is_shop() || is_product_category()) {
                return __( get_option('txt_for_stlp_add_to_cart_button_label'), 'af-stlp' );
            }   
        }
        public function replacing_add_to_cart_button( $button, $product ) 
        {
            if (is_product_category() || is_shop()) {
                $button_text = __( get_option('txt_for_stlp_add_to_cart_button_label'), 'af-stlp' );
                $button_link = $product->get_permalink();
                $button = '<a class="button" href="' . $button_link . '">' . 
                $button_text . '</a>';

            }
            return $button;
        }
        public function wp_footer_function()
        {
       //  $order = new WC_Order( 461 );
       //  //$meta_data= $order->get_meta_data();  
       //  //print_r($meta_data);
       //  // echo "<pre>";
       //  $stlp_products=array();

       //  foreach( $order->get_items() as $item_id => $item)
       // {    

       //  $variation_id = $item->get_variation_id();
       //  $product_id   = $item->get_product_id();
       //  $quantity = $item->get_quantity();
       //  $product      = $item->get_product();

       //   if($variation_id >= 1)
       //  {
       //      $product_id = $variation_id;
       //  }
       //  else
       //  {
       //      $product_id=$product_id;
       //  }

       //  $stlp_products[$product_id]=$quantity;
       // } 
        // $scdt_arr= array('first' =>  '1st',
        //                  'second' => '2nd',
        //                  'third' =>  '3rd');
        // echo '<pre>';

            //  global $woocommerce;
            //  $items = $woocommerce->cart->get_cart();
            //  $stlp_data=array();

            // foreach($items as $item => $values) { 

            //     if(in_array('product_link_data', array_keys( $values ) ,true ) ){

            //         //print_r( $values['product_link_data']);

            //         $product_id1=$values['product_id'];
            //         echo $product_id1;
            //         foreach($items as $item => $item_values){
            //             if(in_array($item_values['key'], $values['product_link_data'] ,true )){
            //                 $product_id2=$item_values['product_id'];
            //                 $quantity = $item_values['quantity'];
            //                 $stlp_data[$product_id1][$product_id2]=$quantity;       
            //             }
            //         }
            //      }
            // }
            // print_r($stlp_data);
            //print_r( $cart_item_data['product_link_data'] );
            //  $stlp_postmeta=get_post_meta(412,'shop_the_pro_selected_product',true);

            //            echo '<pre>';
            // print_r($stlp_postmeta);

            // $stlp_data=array_fill_keys($stlp_postmeta,0);
            // print_r($stlp_data);
    //     global $post;
    //     $product_id = 411;
    //     $total_price=0;
    //     $stlp_data=array();
    //     $stlp_postmeta=get_post_meta($product_id,'shop_the_pro_selected_product',true);
    //     $stlp_data=array_fill_keys($stlp_postmeta,0); 
    //     $stlp_post = array(
    //         'post_type'  => 'stlp_log',    
    //         'post_title' => '',
    //         'post_content' => '',
    //         'post_status' => 'publish',
    //         'fields'      =>'ids',
    //     );
    //     $stlp_posts= new WP_Query($stlp_post);

    //     if( $stlp_posts -> have_posts()){ 
    //         foreach($stlp_posts->posts as $stlp_post){ 
    //             $metadata = get_post_meta($stlp_post,'stlpdata',true);
    //             // echo "<pre>";
    //             foreach($metadata as $key =>$data){
    //                 //print_r($key);
    //                 if($key==$product_id){
    //     // print_r($metadata);
    //                     foreach($data as $child_pro_id => $quantity){
    //                         if(array_key_exists($child_pro_id,$stlp_data)){
    //                             $stlp_data[$child_pro_id] += $quantity;

    //                         }
    //                     }
    //                 }       
    //             }    
    //         }   
    //     }
    //     foreach($stlp_data as $pro_id => $pro_quantity)
    //                     {
    //                         if($pro_quantity >= 1){
    //                            $stlp_product=wc_get_product($pro_id);
    //                            $pro_name=$stlp_product->get_name();
    //                            $pro_quantity=$pro_quantity;
    //                            $pro_price=$stlp_product->get_price();
    //                            $pro_total_price=$pro_quantity*$pro_price;
    //                             $total_price +=$pro_total_price;
    //                         }         
        }
        public function af_stlp_add_products(){
          global $post,$product,$woocommerce;

          if(!empty(get_post_meta($post->ID, 'shop_title',true))){
            $title=get_post_meta($post->ID, 'shop_title',true);
        }else{
            $title= get_option('txt_for_stlp_product_page_label');
        }
        $meta_data = (array) get_post_meta($post->ID,'shop_the_pro_selected_product',true);     
        $meta_data  = array_filter($meta_data);
        $product_id= get_the_ID();
        $prod_obj= wc_get_product($product_id);
        $prod_price= $prod_obj->get_price();
        ?>
        <input type="hidden" name="product_price" class="product_price" value="<?php echo esc_attr($prod_price); ?>">
        <?php 
        if (( $meta_data)) 
        {
            ?>
            <div class="af-stlp-front">
                <h1> <?php print($title) ?> </div> <!-- <pre> -->
                    <section class="af_stlp_main_slider">
                        <ul class="af-stlp-relted-items">
                            <?php 
                            foreach ( $meta_data as $product_id ) {
                              if (empty($product_id)) {
                                continue;
                            }
                            $product2 = wc_get_product( $product_id );

                            if('1'!= get_option('chk_for_stlp_display_out_of_stock_pro') && !$product2->is_in_stock())
                            {
                                continue;
                            } 
                            ?>
                            <li class="af-stlp-cart"> 
                                <div class="af-stlp-cart-checkbox" >
                                    <input type="checkbox" id="stlp_selected_product.<?php echo $product_id?>" class="stlp_selected_product"  name="stlp_selected_product<?php echo $product_id;?>">
                                    </div> <?php    

                                    echo $product2->get_image();?>
                                    <div class="af-stlp-cart-text">
                                        <p class="pro_name"> <?php  echo $product2->get_name(); ?> </p><p class="pro_price"> <?php
                                        echo $product2->get_price_html();
                                    ?></p>
                                    <div class="af-stlp-cart-quantity">
                                        <?php  if('1'== get_option('chk_for_stlp_quantity_selector')){ ?>
                                            <input type="number" id="af_stlp_quantity" data-product_price="<?php echo $product2->get_price();  ?>" class="cart-quantity" step="1" min="1" max="9" name="af_stlp_quantity<?php echo $product_id; ?>" value="1" title="Qty" size="4" placeholder="" inputmode="numeric" autocomplete="off">
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="stlp_stock_check">
                                    <?php  
                                    if('1'== get_option('chk_for_stlp_display_stock_details'))
                                    {
                                     ?> <p class="stock_qty"> <?php   echo esc_attr('Remaining Stock quantity is  '. $product2->get_stock_quantity()); ?> </p> <?php
                                 }        
                                 ?>
                                </div>
                            </li> <?php
                     }  
                     ?>
                 </ul>
             </section><br> 
             <input type="hidden" name="product_id" class="product_id" value="<?php echo $product_id?>">
             <div class="Stlp-qty-price-text" style="display:none;">
                 <label style="color: blue; font-size:20px;" class="no-of-pro-select"><?php echo get_option('txt_for_stlp_linked_product_label'); ?> :<span></span></label><br>
                 <label style="color: blue; font-size:20px;" class="total-pro-price"><?php echo get_option('txt_for_stlp_total_payable_amount_label'); ?>  :
                    <span class="af-currencySymbol" ><?php echo get_woocommerce_currency_symbol(); ?></span>
                    <span class="price"></span>
                </label>
                </div><?php
            }   
        }

        public function add_product_to_cart($cart_item_data, $product_id)
        {        
            remove_filter('woocommerce_add_cart_item_data',array($this,'add_product_to_cart'),10,2);
            $meta_product = (array) get_post_meta( $product_id,'shop_the_pro_selected_product',true);

            $cart_key_arr = array();

            $meta_product  = array_filter($meta_product);

            foreach ( $meta_product as $product_id ) 
            {
                if ( ! empty($product_id) && isset( $_POST['stlp_selected_product'.$product_id] ) ) {

                    $qunatity = $_POST['af_stlp_quantity'.$product_id];

                    $cart_key   = WC()->cart->add_to_cart($product_id,$qunatity,$variation_id = 0, $variation = array(),array('af-stlp-parent-id' => $product_id) );      
                    $cart_key_arr[] = $cart_key;
                }
            }

            add_filter('woocommerce_add_cart_item_data',array($this,'add_product_to_cart'),10,2);

            $cart_item_data['product_link_data'] = $cart_key_arr; 


            return $cart_item_data;
        }

        public function store_cart_item_to_order_item_data($item, $cart_item_key, $cart_item_data, $order)
        {
            $stlp_data=array();
            if(isset($cart_item_data['product_link_data']) ){

                $product_id1=$cart_item_data['product_id'];
                $items = WC()->cart->get_cart();
                foreach($items as $key => $values){
                    if(in_array($values['key'], $cart_item_data['product_link_data'] ,true )){
                        $product_id2=$values['product_id'];
                        $quantity = $values['quantity'];
                        $stlp_data[$product_id1][$product_id2]=$quantity;
                    }
                }
            }
            $stlp_data = array_filter($stlp_data);
            
            if( count($stlp_data) >= 1 ){
                $item->add_meta_data('product_data',$stlp_data);
            }
        }
        public function checkout_field_update_order_meta( $order_id ) {

            $order= wc_get_order($order_id);
            $items=$order->get_items();
            // echo '<pre>';
            // print_r($order);
            foreach ($items as $item_id => $item) {
                $item_meta = (array) $item->get_meta('product_data'); 

                if( count($item_meta) >= 1 ){
                    // print_r($item_meta);
                    $stlp_post = array(
                        'post_type'  => 'stlp_log',    
                        'post_title' => '',
                        'post_content' => '',
                        'post_status' => 'publish',
                        'post_parent'=> $order_id,
                    );
                    $post_id=wp_insert_post( $stlp_post );
                    update_post_meta($post_id,'stlpdata',$item_meta );
                }

            }
        }

        public function af_stlp_front_scripts() {
            wp_enqueue_style( 'af-stlp', STLP_URL .'include/assets/css/af_class_stlp_front.css' , false, '1.0.1');
            wp_enqueue_script( 'af-stlp', STLP_URL .'include/assets/js/af_class_stlp_front.js',array('jquery'), '1.0.1');
            wp_enqueue_style( 'af-stlpss','https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.8/slick.min.css');
            wp_enqueue_style( 'af-stlpssss','https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.8/slick-theme.min.css');
            wp_enqueue_script( 'af-stlpjs','https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.8/slick.min.js');

            $script_var = array(
                'admin_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('wpc-product-nonce'),
                'atpcarousel' => get_option('chk_for_stlp_autoplay_carousel'),
                'nvgdotcarousel' => get_option('chk_for_stlp_display_dots_navigation_carousel'),
            );
            wp_localize_script( 'af-stlp', 'STLP_URL', $script_var );
        }


    }
    if (class_exists('Sp_Th_Lk_Pro_Front')) {
        new Sp_Th_Lk_Pro_Front();
    }
