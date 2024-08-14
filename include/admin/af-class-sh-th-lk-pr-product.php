<?php

if (!defined('ABSPATH')) {
    exit();
}
class Sp_Th_Lk_Pro_Product {


    public function __construct() 
    {     
            add_filter( 'woocommerce_product_data_tabs',array($this, 'af_stlp_product_tab' ));
            add_action( 'woocommerce_product_data_panels',array($this, 'af_stlp_product_data_panels' ));
            add_action('woocommerce_process_product_meta',array($this, 'af_stlpe_product_custom_fields_save'));
    }
     
    public function af_stlp_product_tab( $tabs )
     {
    
    // Adds the new tab
    
        $tabs['af_shop_the_look_pro'] = array(
            'label'     => __( 'Shop the Look Pro', 'af-stlp' ),
            'priority'  => 1,
            'target'    => 'af_shop_the_look_pro',
            'class'     => array('af-shop-the-look-pro'),
        );
        return $tabs;

    }
    public function af_stlp_product_data_panels()
    {
      global $woocommerce, $post,$product;

        echo '<div id="af_shop_the_look_pro" class="panel woocommerce_options_panel">';
        echo '<div class="options_group">';

        woocommerce_wp_text_input( array(
        'id'            => 'shop_title', // required, will be used as meta_key
        'label'         =>__('Title', 'af-stlp'), // Text in the label in the editor.
        'desc_tip'      => 'true',
        'description'   => __('Set the Title which will display on Single page', 'af-stlp')
    ) );
        ?>
        <p class="form-field">
            <label for="grouped_products"><?php esc_html_e( 'Select the Product(s)', 'af-stlp' ); ?></label>
            <select class="wc-product-search" multiple="multiple" style="width: 50%;" id="grouped_products" name="shop_the_pro_selected_product[]" data-sortable="true" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'af-stlp' ); ?>" data-action="woocommerce_json_search_products" data-exclude="<?php echo intval( $post->ID ); ?>">
                <?php
                
                $product_ids = (array) get_post_meta($post->ID, 'shop_the_pro_selected_product',true);

                foreach ( $product_ids as $product_id ) {

                    $product = wc_get_product( $product_id );
                    if ($product_id) {
                        ?>
                    <option value="<?php echo $product_id;?>" selected>

                        <?php echo $product->get_name(); ?>
                    </option>
                    <?php
                    }
                }
                ?>
            </select>
        </p>
    </div>
<?php
    }
    public function af_stlpe_product_custom_fields_save( $post_id)
    {
        global $product;
        $search_products = isset($_POST['shop_the_pro_selected_product'])? sanitize_meta('',$_POST['shop_the_pro_selected_product'],'') :array();
        update_post_meta($post_id,'shop_the_pro_selected_product', $search_products);

        $text_input= isset($_POST['shop_title']) ? sanitize_text_field($_POST['shop_title']):'';
         update_post_meta($post_id, 'shop_title', $text_input );
    }
    
}
if (class_exists('Sp_Th_Lk_Pro_Product')) {
    new Sp_Th_Lk_Pro_Product();
}


