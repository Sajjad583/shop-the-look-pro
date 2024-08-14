<?php

if (!defined('ABSPATH')) {
    exit();
}
class Sp_Th_Lk_Pro_Admin {

    public function __construct() 
    {   add_action('admin_enqueue_scripts', array($this, 'af_stlp_admin_scripts') );
        add_action( 'admin_init' , array($this,'add_shop_look_the_Pro_shop_settings'));  
        //add_action( 'admin_footer' , array($this,'stlp_popoup'));  

    }
     


     public function stlp_popoup(){
            include_once STLP_PLUGIN_DIR . 'include/admin/stlp-popoup.php';
     }

    public function add_shop_look_the_Pro_shop_settings()
    {
            add_settings_section( 
            'stlp_general_setting', // ID used to identify this section and with which to register options
            'General Settings',
            array($this,'general_setting_page_clbk'), // Callback used to render the description of the section
            'af_stlp_gnrl_stng_page_do_setting' // Page on which to add this section of options
        );
        
        add_settings_field(  
            'chk_for_stlp_relevent_product',
            esc_html__('Shop the Look Pro','af-stlp'),
            array($this,'chk_for_stlp_relevent_product_clbk'),
            'af_stlp_gnrl_stng_page_do_setting',
            'stlp_general_setting'
        );

        register_setting(

            'af_stlp_gnrl_stng_field',
            'chk_for_stlp_relevent_product'
        );
        
          add_settings_field(  
            'chk_for_stlp_quantity_selector',
            esc_html__('Display Quantity Selector','af-stlp'),
            array($this,'chk_for_stlp_quantity_selector_clbk'),
            'af_stlp_gnrl_stng_page_do_setting',
            'stlp_general_setting'
        );

        register_setting(
            
            'af_stlp_gnrl_stng_field',
            'chk_for_stlp_quantity_selector'
        );

        add_settings_field(  
            'chk_for_stlp_display_stock_details',
            esc_html__('Display Stock Details','af-stlp'),
            array($this,'chk_for_stlp_display_stock_details_clbk'),
            'af_stlp_gnrl_stng_page_do_setting',
            'stlp_general_setting'
        );

        register_setting(
            'af_stlp_gnrl_stng_field',
            'chk_for_stlp_display_stock_details'
        );

        add_settings_field(  
            'chk_for_stlp_display_out_of_stock_pro',
            esc_html__('Display out of Stock Products','af-stlp'),
            array($this,'chk_for_stlp_display_out_of_stock_pro_clbk'),
            'af_stlp_gnrl_stng_page_do_setting',
            'stlp_general_setting'
        );

         register_setting(
            'af_stlp_gnrl_stng_field',
            'chk_for_stlp_display_out_of_stock_pro'
        );

         add_settings_field(  
            'chk_for_stlp_display_dots_navigation_carousel',
            esc_html__('Display Dots Navigation in Carousel','af-stlp'),
            array($this,'chk_for_stlp_display_dots_navigation_carousel_clbk'),
            'af_stlp_gnrl_stng_page_do_setting',
            'stlp_general_setting'
        );

         register_setting(
            'af_stlp_gnrl_stng_field',
            'chk_for_stlp_display_dots_navigation_carousel'
        );

         add_settings_field(  
            'chk_for_stlp_autoplay_carousel',
            esc_html__('Autoplay Carousel','af-stlp'),
            array($this,'chk_for_stlp_autoplay_carousel_clbk'),
            'af_stlp_gnrl_stng_page_do_setting',
            'stlp_general_setting'
        );

        register_setting(
            'af_stlp_gnrl_stng_field',
            'chk_for_stlp_autoplay_carousel'
        );

        add_settings_field(  
            'txt_for_stlp_add_to_cart_button_label',
            esc_html__('Shop & Category Page - Add to Cart Button Label','af-stlp'),
            array($this,'txt_for_stlp_add_to_cart_button_label_clbk'),
            'af_stlp_gnrl_stng_page_do_setting',
            'stlp_general_setting'
        );

        register_setting(
            'af_stlp_gnrl_stng_field',
            'txt_for_stlp_add_to_cart_button_label'
        );

        add_settings_field(  
            'txt_for_stlp_product_page_label',
            esc_html__('Product Page - Linked Products Title','af-stlp'),
            array($this,'txt_for_stlp_product_page_label_clbk'),
            'af_stlp_gnrl_stng_page_do_setting',
            'stlp_general_setting'
        );

        register_setting(
            'af_stlp_gnrl_stng_field',
            'txt_for_stlp_product_page_label'
        );

        add_settings_field(  
            'txt_for_stlp_linked_product_label',
            esc_html__('Linked Products Count Label','af-stlp'),
            array($this,'txt_for_stlp_linked_product_label_clbk'),
            'af_stlp_gnrl_stng_page_do_setting',
            'stlp_general_setting'
        );

        register_setting(
            'af_stlp_gnrl_stng_field',
            'txt_for_stlp_linked_product_label'
        );

        add_settings_field(  
            'txt_for_stlp_total_payable_amount_label',
            esc_html__('Total Payable Amount Label','af-stlp'),
            array($this,'txt_for_stlp_total_payable_amount_label_clbk'),
            'af_stlp_gnrl_stng_page_do_setting',
            'stlp_general_setting'
        );
        
        register_setting(
            'af_stlp_gnrl_stng_field',
            'txt_for_stlp_total_payable_amount_label'
        );

        

        add_settings_section( 
            'stlp_product_list_setting', // ID used to identify this section and with which to register options
            'Products List',
            array($this,'product_list_setting_page_callback'), // Callback used to render the description of the section
            'af_stlp_product_list_page_do_setting' // Page on which to add this section of options
        );
    }
     function chk_for_stlp_relevent_product_clbk() { 
             ?>
             <input type="checkbox" name="chk_for_stlp_relevent_product" value="1" <?php checked( get_option('chk_for_stlp_relevent_product'),'1'); ?>/>
             By enabling this checkbox, you can set and display the relevant product which can be bought togather on the single product page.
             <?php
        }


      function chk_for_stlp_quantity_selector_clbk() { 
             ?>
             <input type="checkbox" name="chk_for_stlp_quantity_selector" value="1" <?php checked( get_option('chk_for_stlp_quantity_selector'),'1'); ?>/>
             <?php
        }


        function chk_for_stlp_display_quantity_selector_clbk() { 
             ?>
             <input type="checkbox" name="chk_for_stlp_display_quantity_selector" value="1" <?php checked( get_option('chk_for_stlp_display_quantity_selector'),'1'); ?>/>
             <?php
        }
         function chk_for_stlp_display_stock_details_clbk() { 
                 ?>
                 <input type="checkbox" name="chk_for_stlp_display_stock_details" value="1" <?php checked( get_option('chk_for_stlp_display_stock_details'),'1'); ?>/>
                 <?php
        }
         function chk_for_stlp_display_out_of_stock_pro_clbk() { 
                 ?>
                 <input type="checkbox" name="chk_for_stlp_display_out_of_stock_pro" value="1" <?php checked( get_option('chk_for_stlp_display_out_of_stock_pro'),'1'); ?>/>
                 <?php
        }
         function chk_for_stlp_display_dots_navigation_carousel_clbk() { 
                 ?>
                 <input type="checkbox" name="chk_for_stlp_display_dots_navigation_carousel" value="1" <?php checked( get_option('chk_for_stlp_display_dots_navigation_carousel'),'1'); ?>/>
                 <?php
        }
         function chk_for_stlp_autoplay_carousel_clbk() { 
                 ?>
                 <input type="checkbox" name="chk_for_stlp_autoplay_carousel" value="1" <?php checked( get_option('chk_for_stlp_autoplay_carousel'),'1'); ?>/>
                 <?php
        }


         function txt_for_stlp_add_to_cart_button_label_clbk() { 
                 ?>
                 <input type="text" name="txt_for_stlp_add_to_cart_button_label" style="width: 400px" value="<?php echo( get_option('txt_for_stlp_add_to_cart_button_label')); ?>" />
                 <?php
        }
         function txt_for_stlp_product_page_label_clbk() { 
                 ?>
                 <input type="text" name="txt_for_stlp_product_page_label" style="width: 400px" value="<?php echo( get_option('txt_for_stlp_product_page_label')); ?>" />
                 <?php
        }
         function txt_for_stlp_linked_product_label_clbk() { 
                 ?>
                 <input type="text" name="txt_for_stlp_linked_product_label" style="width: 400px" value="<?php echo( get_option('txt_for_stlp_linked_product_label')); ?>" />
                 <?php
        }
         function txt_for_stlp_total_payable_amount_label_clbk() { 
                 ?>
                 <input type="text" name="txt_for_stlp_total_payable_amount_label" style="width: 400px" value="<?php echo( get_option('txt_for_stlp_total_payable_amount_label')); ?>" />
                 <?php
        }

        
         function general_setting_page_clbk() {
        }
         function product_list_setting_page_callback(){
            ?>
                <form role="search" method="post" class="af_stlp-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <p class="af_stlp-product-search-bar" style="text-align: right;">
                        <label class="screen-reader-text" for="af_stlp_search-search-input">Search Products:</label>
                        <input type="search" id="af_stlp_search-search-input" name="s" value="">
                        <input type="submit" id="af_stlp_search-submit" class="button" value="Search Products">
                    </p>
                         <input type="hidden" id="af_stlp_wpnonce" name="_wpnonce" value="0143946ba5">
                        <input type="hidden" name="af_stlp_wp_http_referer" value="/s2t0wegc4ifkzyu/wp-admin/admin.php?page=stl_settings&amp;tab=products-list">
                </form>
                <?php
            if(file_exists( include_once STLP_PLUGIN_DIR . 'include/admin/af-class-sh-th-lk-pr-product_list.php'))
            {
                 include_once STLP_PLUGIN_DIR . 'include/admin/af-class-sh-th-lk-pr-product_list.php';
            }
        }
        public function af_stlp_admin_scripts() {
        wp_enqueue_style( 'view-detail', STLP_URL .'include/assets/css/af_class_stlp_view_details.css' , false, '1.0.1');
        wp_enqueue_style( 'af-stlp', STLP_URL .'include/assets/css/af_class_stlp_product_list.css' , false, '1.0.1');
        wp_enqueue_script('af-popup', STLP_URL . 'include/assets/js/af_popup.js', array('jquery'), '1.0.1', false);
        wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', false, '4.7.0', false );
        $script_var = array(
            'admin_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wpc-product-nonce'),
        );
        wp_localize_script( 'af-popup', 'STLP_handler', $script_var );
        }

     }    

if (class_exists('Sp_Th_Lk_Pro_Admin')) {
    new Sp_Th_Lk_Pro_Admin();
}