<?php
/**
    * Plugin Name:  Addify Shop the Look Pro 
    * Plugin URI:   http://
    * Description: Shop the Look Pro for WooCommerce helps users to display relevant product recommendations to users on the product page.
    * Author:   Addify
    * Author URI: http://
    * Version: 1.0.0
    *  Domain Path:       /languages
    * TextDomain:        af-shop-the-look-pro
    * @package:          Addify-shop-the-look-pro 
**/

if (!defined('ABSPATH')) {
    exit();
}

if ( ! is_multisite() ) { 
    if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) { 
        function ka_sr_checking_module_is_not_deactivate() { 
            // Deactivate the plugin. 
            deactivate_plugins( __FILE__ ); 
            ?> 
            <div id="message" class="error"> 
             <p> 
                 <strong> 
                    <?php esc_html_e( 'Shipping Address plugin is inactive. WooCommerce plugin must be active in order to activate it.', 'af-stlp' ); ?> 
                </strong> 
            </p> 
        </div>; 
        <?php 
    } 
    add_action( 'admin_notices', 'af_sr_checking_module_is_not_deactivate' ); 
} 
}

class Sp_Th_Lk_Pro_Main {

    public function __construct() 
    { 
        $this->af_sp_th_look_pr_global_variables();
        // add_action('wp_enqueue_scripts', array($this, 'af_stlp_main_scripts') );
        add_action( 'init', array($this,'af_stlp_init'));
        add_action( 'init', array($this,'af_stlp_custom_post_type'));
        add_action('admin_menu',array($this,'register_my_custom_submenu_page')); 
        add_action( 'wp_ajax_nopriv_get_popup_data',array($this, 'get_popup_data' ));
        add_action( 'wp_ajax_get_popup_data',array($this, 'get_popup_data' ));
        add_action( 'wp_ajax_get_product_search_bar',array($this, 'get_product_search_bar' ));
        add_action( 'wp_ajax_nopriv_get_product_search_bar',array($this, 'get_product_search_bar' ));
        add_action( 'wp_ajax_get_search_bar_data',array($this, 'get_search_bar_data' ));
        add_action( 'wp_ajax_nopriv_get_search_bar_data',array($this, 'get_search_bar_data' ));
        if (is_admin()) {    
            include_once STLP_PLUGIN_DIR . 'include/admin/af-class-sh-th-lk-pr-admin.php';
            include_once STLP_PLUGIN_DIR . 'include/admin/af-class-sh-th-lk-pr-product.php';
            include_once STLP_PLUGIN_DIR . 'include/admin/af-class-stlp-functions.php';
        }
        else{
            include_once STLP_PLUGIN_DIR . 'include/front/af-class-sh-th-lk-pr-front.php';
        }      
    }
    public function af_sp_th_look_pr_global_variables(){
        if (!defined('STLP_URL') ) {
            define('STLP_URL', plugin_dir_url(__FILE__));
        }

        if (! defined('STLP_PLUGIN_DIR') ) {
            define('STLP_PLUGIN_DIR', plugin_dir_path(__FILE__));
        }
    }

    public function register_my_custom_submenu_page() 
    {
        add_submenu_page( 
            'woocommerce',
            'Shop the Look Pro',
            'Shop the Look Pro',
            'manage_options',
            'my_custom_submenu_page', 
            array($this,'my_custom_submenu_page_callback') 
        ); 
    }
    public function af_stlp_custom_post_type()
    {
        $labels = array(
            'name'               => __( 'Shop the Look Pro','af-stlp'),
            'singular_name'      => __( 'Shop the Look Pro','af-stlp'),
            'all_items'          => __( 'All Data','af-stlp' ),
            'view_item'          => __( 'Data', 'af-stlp' ),
            'search_items'       => __( 'Search Custom  Data', 'af-stlp')
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'menu_position'     => 5,
            'supports'          => array('title'),
            'has_archive'       => false,
            'show_in_admin_bar' => false,
            'show_in_nav_menus' => false,
            'query_var'         => true,
            'show_in_menu'      => false,
        );    
        register_post_type( 'stlp_log', $args );
    }

    public function my_custom_submenu_page_callback() 
    {
        if(isset($_GET['tab']))
        {
            $active_tab = sanitize_text_field(wp_unslash($_GET['tab']));
        } 
        else 
        {
            $active_tab = 'settings';
        }
        
        ?>
        <div class="wrap"> 
            <h2 class="nav-tab-wrapper"> 
                <!-- Setting Tab --> 
                <a href="?page=my_custom_submenu_page&tab=settings" class="nav-tab <?php echo esc_attr( $active_tab ) === 'settings' ? ' nav-tab-active' : ''; ?>" > <?php esc_html_e( 'Settings'); ?> </a> 
                <!-- Products List --> 
                <a href="?page=my_custom_submenu_page&tab=products_list" class="nav-tab <?php echo esc_attr( $active_tab ) === 'products_list' ? ' nav-tab-active' : ''; ?>" > <?php esc_html_e( 'Products List'); ?> </a> 
            </h2> 
        </div>
        <form  method="POST" action="options.php" >  <?php

            if($active_tab == 'settings'){        
                settings_fields( 'af_stlp_gnrl_stng_field' ); 
                do_settings_sections( 'af_stlp_gnrl_stng_page_do_setting' );
                submit_button( __( 'Submit', 'af-stlp' )); 
            }
            if($active_tab == 'products_list'){
                if(isset($_GET['product_id'])){
                    $this->list_products_view_details();
                }
                else{
                    settings_fields( 'af_stlp_product_list_field' ); 
                    do_settings_sections( 'af_stlp_product_list_page_do_setting' );
                } 
            }         ?>
        </form>  <?php
    }

    public function list_products_view_details()
    {
        if(isset($_GET['product_id'])){
            $pro_id=($_GET['product_id']);
            $Edit_link=get_edit_post_link($pro_id);
            $Perma_link=get_the_permalink($pro_id);
            $product=wc_get_product($pro_id); 
            $created_date=$product->get_date_created();
            $gmt_pro_created_date = gmdate('M d, Y H:i:s A', strtotime($created_date));
            $modified_date=$product->get_date_modified();
            $gmt_pro_modified_date = gmdate('M d, Y H:i:s A', strtotime($modified_date));
            $stlp_pro_view_detail=Sp_Th_Lk_Pro_Function::get_products_price($pro_id);
            if($stlp_pro_view_detail>=0.1){
                $most_view_products_btn='<div class="product_popup_data"><button data-product_id='.$pro_id.' id="btn" class="stlp_view_product"> View Products </button><div class="stlp_popup_data"></div></div>';
            }else{
                $most_view_products_btn= '';
            }
            $stlp_data=array();
            $stlp_postmeta=get_post_meta($pro_id,'shop_the_pro_selected_product',true);
            $stlp_data=array_fill_keys($stlp_postmeta,0); 
            $stlp_post = array(
                'post_type'  => 'stlp_log',    
                'post_status' => 'publish',
                'fields'      =>'ids',
            );
            $stlp_posts= new WP_Query($stlp_post);
            $stlp_pro_posts=$stlp_posts->posts;
            ?> 
            <div class="page_view_details">
                <div class="page_heading">
                    <h1><?php echo esc_html__('Product Name - T-Shirt with Logo','af-stlp') ?></h1>
                </div>
                <div class="btn_back">
                    <a href="?page=my_custom_submenu_page&tab=products_list"><?php echo esc_html__('Go back','af-stlp') ?></a>
                </div>
                <div class="btn_edit_product">
                   <a href="<?php echo $Edit_link ?> "><?php echo esc_html__('Edit Product','af-stlp') ?></a>
               </div>
               <div class="btn_view_product">
                <a href="<?php echo $Perma_link ?>"><?php echo esc_html__('View Product','af-stlp') ?></a>
            </div>
        </div>
        <div class="pro_config">
            <h3><?php echo esc_html__('Product Configuration','af-stlp') ?></h3>
            <hr class="config_border">
            <pre><h4 class="config-link-pro">Linked Products        : <?php echo Sp_Th_Lk_Pro_Function::get_child_name($pro_id); ?></h4></pre>
            <pre><h4>Created Date           : <?php echo $gmt_pro_created_date; ?></h4></pre>
            <pre><h4>Last Activity Date     : <?php echo $gmt_pro_modified_date; ?></h4></pre>
        </div>
        <div class="stlp-report-heading">
            <h1><?php echo esc_html__('Reports','af-stlp') ?></h1>
        </div>
        
        <div class="pro_status">

            <h3><?php echo esc_html__('Product Status','af-stlp') ?></h3>
            <hr class="status_border">
            <h4>Number of Purchases   :<?php echo $most_view_products_btn; ?></h4>
            <pre><h4>Most Purchased Products   :<?php echo Sp_Th_Lk_Pro_Function::get_purchase_child_name($pro_id) ?></h4></pre>
            <pre><h4>Total Purchase Amount     :<?php echo wc_price(Sp_Th_Lk_Pro_Function::get_products_price($pro_id)) ?></h4></pre>
        </div>
        <div class="stlp-linked-products-order-heading">
            <h1 class="heading-inline"><?php echo esc_html__('Order Details','af-stlp') ?></h1>
        </div>
        <div>
            <p class="search-box">
                <label class="screen-reader-text" for="stlp_search-search-input"><?php echo esc_html__('Search Orders:','af-stlp') ?></label>
                <input type="number" id="stlp_search-search-input" name="s" value="">
                <input type="submit" data-sproduct_id='<?php echo $pro_id; ?>' id="search-submit" class="search_button" value="Search Orders">
            </p>
        </div>
        <div class="stlp_order_table_container">
            <table class="order_table">
                <thead class="order_table_heading">
                    <tr class="order_table_head_row">
                        <th style="color: #2271b1;"><?php echo esc_html__('Order ID','af-stlp') ?></th>
                        <th><?php echo esc_html__('Username','af-stlp') ?></th>
                        <th><?php echo esc_html__('Product Name','af-stlp') ?></th>
                        <th><?php echo esc_html__('Order Status','af-stlp') ?></th>
                        <th><?php echo esc_html__('Ordered Date','af-stlp') ?></th>
                        <th><?php echo esc_html__('Total Purchase Amount','af-stlp') ?></th>
                    </tr>
                </thead>
                <tbody class="order_table_body">
                    <?php
                    $all_orders_id=array();
                    foreach($stlp_pro_posts as $key=>$post_id){
                        $pro_post=get_post($post_id); 
                        $order_id=$pro_post->post_parent;
                        if(!empty($order_id))
                        {
                            $all_orders_id[]=$order_id;
                        }   
                    }

                    $all_orders_id=array_unique($all_orders_id);
                    foreach($all_orders_id as $order_id){

                        $order= wc_get_order($order_id);
                        if (!empty($order)){
                            $order_products_name=array();
                            $product_total_price=0;

                            $order_pro_status=$order->get_status();
                            $order_pro_date=$order->get_date_modified();
                            $gmt_order_pro_date = gmdate('M d, Y H:i:s A', strtotime($order_pro_date));
                        ///////// Order Created Date  ///////////
                            $order_items=$order->get_items(); 
                            foreach ($order_items as $order_key => $item) {

                                $pro_name=$item->get_name();
                                $item_meta=(array)$item->get_meta('product_data');
                                $item_meta = array_filter($item_meta);

                                if (count($item_meta) <1 ) {
                                    continue;
                                }
                                foreach ($item_meta as $item_key => $meta){
                                    if($item_key==$pro_id){
                                        foreach ($meta as $current_pro => $pro_qty) {
                                            $current_product=wc_get_product($current_pro);
                                            $order_pro_name=$current_product->get_name();
                                            $order_products_name[]="<a href='".get_edit_post_link( $current_product->get_id())."'>" .$order_pro_name. '</a>';
                                            $order_pro_qty= (int) $pro_qty;
                                            $order_pro_price=$current_product->get_price();
                                            $order_pro_total_price=$order_pro_price*$order_pro_qty;
                                            $product_total_price +=$order_pro_total_price;
                                        }
                                    }
                                }
                            }
                            $linked_product_name=implode(', ', $order_products_name);
                            if(empty($linked_product_name)){
                                continue;
                            } ?>
                            <tr class="order_table_body_row">
                                <th><a href='<?php echo get_edit_post_link( $order_id ); ?>'><?php echo $order_id; ?></a></th>
                                <th> <?php echo $order->get_billing_first_name(); echo '<br>'; echo $order->get_billing_email(); ?> </th>
                                <th> <?php echo $linked_product_name; ?> </th>
                                <th  class="stlp_order_status"> <?php echo $order_pro_status;?> </th>
                                <th> <?php echo  $gmt_order_pro_date; ?> </th>
                                <th> <?php echo wc_price($product_total_price); ?> </th>
                            </tr>
                            <?php
                        }
                    }      ?>  
                </tbody>
                <tfoot class="order_table_foot">
                    <tr class="order_table_foot_row">
                        <th style="color: #2271b1;"><?php echo esc_html__('Order ID','af-stlp') ?></th>
                        <th><?php echo esc_html__('Username','af-stlp') ?></th>
                        <th><?php echo esc_html__('Product Name','af-stlp') ?></th>
                        <th><?php echo esc_html__('Order Status','af-stlp') ?></th>
                        <th><?php echo esc_html__('Ordered Date','af-stlp') ?></th>
                        <th><?php echo esc_html__('Total Purchase Amount','af-stlp') ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php 
    }
}

public function af_stlp_init() {
    if ( function_exists( 'load_plugin_textdomain' ) ) { 
        load_plugin_textdomain( 'af-stlp', false, dirname( plugin_basename(__FILE__) ) . '/languages' ); 
    } 
}

public function af_stlp_main_scripts() {

    wp_enqueue_script( 'af-stlp', STLP_URL .'include/assets/js/af_class_stlp_front.js',array('jquery'), '1.0.1');
    $script_var = array(
        'admin_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('wpc-product-nonce'),
    );
    wp_localize_script( 'af-stlp', 'STLP_URL', $script_var );
} 

public function get_product_search_bar(){
    if(isset($_POST['product_name'])){
        $product_name=($_POST['product_name']);
    
    $stlp_post_meta =array();
    $post_data_array =[];
    $args = [ 
        'post_type' => array('product','product_variation'),
        'posts_per_page' => -1,
        'fields'         =>'ids'
    ];
    $posts_data = new Wp_Query( $args );

    if(!empty( $posts_data -> have_posts() )){  
        foreach($posts_data->posts as $post_data){

            $stlp_post_meta=get_post_meta($post_data,'shop_the_pro_selected_product',true);

            if(!empty($stlp_post_meta)){           
                $product=wc_get_product($post_data);
                $product_id= $product->get_id();
                $created_date=$product->get_date_created();
                $gmt_pro_created_date = gmdate('M d, Y H:i:s', strtotime($created_date));
                $modified_date=$product->get_date_modified();
                $gmt_pro_modified_date = gmdate('M d, Y H:i:s', strtotime($modified_date));
                $stlp_pro_view_detail=Sp_Th_Lk_Pro_Function::get_products_price($product_id);
                if($stlp_pro_view_detail>=0.1){
                    $product_view_details_btn='<div class="product_popup_data"><button data-product_id='.$product_id.' id="btn" class="stlp_view_product"> View Products </button><div class="stlp_popup_data"></div></div>';
                }else{
                    $product_view_details_btn= '';
                }
                $post_data_array[] = [
                  'stlp_product_name' => $product->get_name(),
                  'stlp_linked_products' =>Sp_Th_Lk_Pro_Function::get_child_name($product_id),
                  'stlp_most_purchased_products' =>$product_view_details_btn,
                  'stlp_total_purchase_amount' =>wc_price($stlp_pro_view_detail),
                  'stlp_created_date' =>$gmt_pro_created_date,
                  'stlp_last_activity_date' => $gmt_pro_modified_date,
                  'stlp_action' => '<a href="?page=my_custom_submenu_page&tab=products_list&product_id='.$product_id.'">View Details</a>',
                ];
            }
        }
    }
    }
}

public function get_search_bar_data(){
    if(isset($_POST['nonce'])){
        $nonce=$_POST['nonce'];
    }else{
        $nonce=0;
    } 
    if(!wp_verify_nonce($nonce, 'wpc-product-nonce')){
       die(__( 'Failed Ajax Security Check', 'af-stlp' ));
    }  
    if(isset($_POST['product_id']) && isset($_POST['order_id'])){
        $sorder_id=($_POST['order_id']);
        $pro_id=($_POST['product_id']);
         
        $stlp_data=array();
        $stlp_postmeta=get_post_meta($pro_id,'shop_the_pro_selected_product',true);
        $stlp_data=array_fill_keys($stlp_postmeta,0); 
        $stlp_post = array(
            'post_type'  => 'stlp_log',    
            'post_status' => 'publish',
            'fields'      =>'ids',
        );
        $stlp_posts= new WP_Query($stlp_post);
        $stlp_pro_posts=$stlp_posts->posts;
         
                $all_orders_id=array();
                foreach($stlp_pro_posts as $key=>$post_id){
                    $pro_post=get_post($post_id); 
                    $order_id=$pro_post->post_parent;
                    if(!empty($order_id))
                    {
                        $all_orders_id[]=$order_id;
                    }   
                }

                $all_orders_id=array_unique($all_orders_id);

                if (!in_array($sorder_id, $all_orders_id)){
                    ?> 
                    <tr class="order_table_body_row2">
                        <th><?php echo esc_html__('No items found.','af-stlp') ?></th>
                    </tr> 
                    <?php
                    $order_search_data= ob_get_clean();
                    wp_send_json(array('order_search_html'=>$order_search_data));
                    return;
                }
                
        foreach($all_orders_id as $order_id){
            $order= wc_get_order($order_id);
            if($sorder_id != $order_id){
                continue;
                }
            if (!empty($order)){
                $order_products_name=array();
                $product_total_price=0;
                $order_pro_status=$order->get_status();
                $order_pro_date=$order->get_date_modified();
                $gmt_order_pro_date = gmdate('M d, Y H:i:s A', strtotime($order_pro_date));
                        ///////// Order Created Date  ///////////
                $order_items=$order->get_items(); 
                foreach ($order_items as $order_key => $item) {

                    $pro_name=$item->get_name();
                    $item_meta=(array)$item->get_meta('product_data');
                    $item_meta = array_filter($item_meta);

                    if (count($item_meta) <1 ) {
                        continue;
                    }
                    foreach ($item_meta as $item_key => $meta){
                        if($item_key==$pro_id){
                            foreach ($meta as $current_pro => $pro_qty) {
                                $current_product=wc_get_product($current_pro);
                                $order_pro_name=$current_product->get_name();
                                $order_products_name[]="<a href='".get_edit_post_link( $current_product->get_id())."'>" .$order_pro_name. '</a>';
                                $order_pro_qty= (int) $pro_qty;
                                $order_pro_price=$current_product->get_price();
                                $order_pro_total_price=$order_pro_price*$order_pro_qty;
                                $product_total_price +=$order_pro_total_price;
                            }
                        }
                    }
                }
                $linked_product_name=implode(', ', $order_products_name);
                if(empty($linked_product_name)){
                    continue;
                }
             ?>
                <tr class="order_table_body_row">
                    <th><a href='<?php echo get_edit_post_link( $order_id ); ?>'><?php echo $order_id; ?></a></th>
                    <th> <?php echo $order->get_billing_first_name(); echo '<br>'; echo $order->get_billing_email(); ?> </th>
                    <th> <?php echo $linked_product_name; ?> </th>
                    <th  class="stlp_order_status"> <?php echo $order_pro_status;?> </th>
                    <th> <?php echo  $gmt_order_pro_date; ?> </th>
                    <th> <?php echo wc_price($product_total_price); ?> </th>
                </tr>
                    <?php
                }
            }      
        $order_search_data = ob_get_clean();
        wp_send_json(array("order_search_html"=> $order_search_data));
    }
    wp_die();
}

public function get_popup_data(){
    if(isset($_POST['nonce'])){
        $nonce=$_POST['nonce'];
    }else{
        $nonce=0;
    } 
    if(!wp_verify_nonce($nonce, 'wpc-product-nonce')){
       die(__( 'Failed Ajax Security Check', 'af-stlp' ));
    }  
    if(isset($_POST['product_id'])){
        $product_id= $_POST['product_id'];
        $stlp_data=array();
        $stlp_postmeta=get_post_meta($product_id,'shop_the_pro_selected_product',true);
        $stlp_data=array_fill_keys($stlp_postmeta,0); 
        $stlp_post = array(
            'post_type'  => 'stlp_log',    
            'post_title' => '',
            'post_content' => '',
            'post_status' => 'publish',
            'fields'      =>'ids',
        );
        $stlp_posts= new WP_Query($stlp_post);

        if( $stlp_posts -> have_posts()){ 
            foreach($stlp_posts->posts as $stlp_post){ 
                $metadata = get_post_meta($stlp_post,'stlpdata',true);
                foreach($metadata as $key =>$data){
                    if($key==$product_id){
                        foreach($data as $child_pro_id => $quantity){
                            if(array_key_exists($child_pro_id,$stlp_data)){
                                $stlp_data[$child_pro_id] += $quantity;
                            }
                        }
                    }       
                }    
            }   
        } 
        ob_start(); ?>
        <div class="backdrop">
            <section class="stlp-popup-section"> 
                <div class="popup-main">
                    <div class="popup-header">
                        <h1><?php echo esc_html__('Most Purchased Products','af-stlp') ?></h1>
                        <button class="closs"><i class="fa fa-times" aria-hidden="true"></i></button>
                    </div>
                    <div class="popup-product-data">
                      <table>
                        <thead class="table_heading">
                            <tr class="table_tr_heading">
                                <th><?php echo esc_html__('Product Name','af-stlp') ?></th>
                                <th><?php echo esc_html__('Purchased Quantity','af-stlp') ?></th>
                                <th><?php echo esc_html__('Total Purache Amount','af-stlp') ?></th>
                            </tr>
                        </thead>
                        <?php
                        foreach($stlp_data as $pro_id => $pro_quantity)
                        {
                            if($pro_quantity >= 1){
                               $stlp_product=wc_get_product($pro_id);
                               $pro_name=$stlp_product->get_name();
                               $pro_quantity=$pro_quantity;
                               $pro_price=$stlp_product->get_price();
                               $pro_total_price=$pro_quantity*$pro_price;
                               ?>
                               <tr>
                                <td class="stlp_pro_name"><?php echo $pro_name ?></td>
                                <td><?php   echo $pro_quantity ?></td>
                                <td><?php echo wc_price($pro_total_price) ?></td>
                                </tr> <?php
                            }
                        }  ?>
                    </table>
                </div>
                <div class="popup-footer">
                </div>
            </div>
            </section> </div><?php
            $pro_popup_data = ob_get_clean();
            wp_send_json(array("popup_html"=> $pro_popup_data));
        }
        wp_die();
    }    
}
if (class_exists('Sp_Th_Lk_Pro_Main')) {
    new Sp_Th_Lk_Pro_Main();
}


