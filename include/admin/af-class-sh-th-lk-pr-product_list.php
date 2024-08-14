<?php

if (!defined('ABSPATH')) {
    exit();
}

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class STLP_Product_List_Table extends WP_List_Table
{
    public function prepare_items(){
       $order_by=isset($_GET['orderby']) ? $_GET['orderby'] : '';
       $order=isset($_GET['order']) ? $_GET['order'] : '';
       $search_products=isset($_POST['s']) ? $_POST['s'] : '';
       $this-> items = $this->stlp_product_list_table($order_by, $order, $search_products);
       $stlp_column= $this->get_columns();
       $stlp_hd_column= $this->get_hidden_columns();
       $this->_column_headers = [$stlp_column, $stlp_hd_column];

    }
    public function stlp_product_list_table($order_by ='', $order ='', $search_products =''){
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
      return $post_data_array;
    }

    public function get_hidden_columns()
    {
        return [];
    }

    public function get_columns()
    {
        $column = [
            'stlp_product_name' => __( 'Product Name', 'af-stlp'),
            'stlp_linked_products' => __( 'Linked Products', 'af-stlp'),
            'stlp_most_purchased_products' => __( 'Most Purchased Products', 'af-stlp'),
            'stlp_total_purchase_amount' => __( 'Total Purchase Amount', 'af-stlp'),
            'stlp_created_date' => __( 'Created Date', 'af-stlp'),
            'stlp_last_activity_date' => __( 'Last Activity Date', 'af-stlp'),
            'stlp_action' => __( 'Action', 'af-stlp')
        ];

        return $column;
    }
    public function column_default( $item, $column_name)
    {
        switch( $column_name )
        {
            case 'stlp_product_name':
            return esc_html( $item[$column_name] );
            case 'stlp_linked_products':
            return wp_kses_post( $item[$column_name] );
            case 'stlp_most_purchased_products':
            return wp_kses_post( $item[$column_name] );
            case 'stlp_total_purchase_amount':
            return wp_kses_post( $item[$column_name] );
            case 'stlp_created_date':
            return esc_html( $item[$column_name] );
            case 'stlp_last_activity_date':
            return esc_html( $item[$column_name] );
            case 'stlp_action':
            return wp_kses_post( $item[$column_name] );
            default:
            return 'No product found';
        }
    }
    public function column_cb( $items )
    {
        $checkbox = '<input type="checkbox"  />';
        return $checkbox;
    }
}    
if (class_exists('STLP_Product_List_Table')) {
    $object= new STLP_Product_List_Table();
    $object-> prepare_items();
    $object->display();
}
