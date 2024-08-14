<?php

if (!defined('ABSPATH')) {
    exit();
}
class Sp_Th_Lk_Pro_Function {
	public static function get_child_name($product_id){
	    $child_name=array();
	    $stlp_post_meta=get_post_meta($product_id,'shop_the_pro_selected_product',true);
	    foreach($stlp_post_meta as $post_meta){
	        $child_product=wc_get_product($post_meta);
	        $child_product_name=$child_product->get_name() ;
	        $child_name[]="<a href='".get_edit_post_link( $child_product->get_id())."'>" .$child_product_name. '</a>';
	    }
	    return implode(', ', $child_name);
	}
	public static function get_products_price($product_id){
	    $total_price=0;
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

	            $metadata =(array) get_post_meta($stlp_post,'stlpdata',true);	        
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
	    foreach($stlp_data as $pro_id => $pro_quantity)
	    {
	        if($pro_quantity >= 1){
	           $stlp_product=wc_get_product($pro_id);
	           $pro_quantity=$pro_quantity;
	           $pro_price=$stlp_product->get_price();
	           $pro_total_price=$pro_quantity*$pro_price;
	           $total_price +=$pro_total_price;
	       }
	   }
	   return $total_price;
	}
	public static function get_purchase_child_name($product_id){
	    $stlp_data=array();
	    $product_name=array();
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
	            $metadata = (array)get_post_meta($stlp_post,'stlpdata',true);
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
	    foreach($stlp_data as $pro_id => $pro_quantity)
        {
            if($pro_quantity >= 1){
               $stlp_product=wc_get_product($pro_id);
               $pro_name= $stlp_product->get_name();
               $product_name[]="<a href='".get_edit_post_link( $stlp_product->get_id())."'>" .$pro_name. '</a>';
            }
        }
        return implode(', ', $product_name);  
	}
}
new Sp_Th_Lk_Pro_Function();