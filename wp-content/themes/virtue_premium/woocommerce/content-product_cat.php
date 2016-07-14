<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product_cat.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $woocommerce_loop, $virtue_premium;


// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

if(is_shop() || is_product_category() || is_product_tag()) {
	if(isset($virtue_premium['product_cat_layout']) && !empty($virtue_premium['product_cat_layout'])) {
		$product_cat_column = $virtue_premium['product_cat_layout'];
	} else {
		$product_cat_column = 4;
	}
	$woocommerce_loop['columns'] = $product_cat_column;
} else {
	if ( empty( $woocommerce_loop['columns'] ) ) {
		$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
	}
	$product_cat_column = $woocommerce_loop['columns'];
}

if ($product_cat_column == '1') {$itemsize = 'tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12'; $catimgwidth = 600;} 
else if ($product_cat_column == '2') {$itemsize = 'tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12'; $catimgwidth = 600;} 
else if ($product_cat_column == '3'){ $itemsize = 'tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $catimgwidth = 400;} 
else if ($product_cat_column == '6'){ $itemsize = 'tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $catimgwidth = 240;} 
else if ($product_cat_column == '5'){ $itemsize = 'tcol-md-25 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $catimgwidth = 240;} 
else {$itemsize = 'tcol-md-3 tcol-sm-4 tcol-xs-4 tcol-ss-6'; $catimgwidth = 300;}

if(!is_shop() && !is_product_category() && !is_product_tag()) {
		$woocommerce_loop['columns'] = $product_cat_column;
}
if(isset($virtue_premium['product_cat_img_ratio'])) {
	$img_ratio = $virtue_premium['product_cat_img_ratio'];
} else {
	$img_ratio = 'widelandscape';
}

	if($img_ratio == 'portrait') {
			$tempcatimgheight = $catimgwidth * 1.35;
			$catimgheight = floor($tempcatimgheight);
	} else if($img_ratio == 'landscape') {
			$tempcatimgheight = $catimgwidth / 1.35;
			$catimgheight = floor($tempcatimgheight);
	} else if($img_ratio == 'square') {
			$catimgheight = $catimgwidth;
	} else {
			$tempcatimgheight = $catimgwidth / 2;
			$catimgheight = floor($tempcatimgheight);
	}

	// Increase loop count
$woocommerce_loop['loop']++;
	?> 

<div class="<?php echo esc_attr($itemsize); ?> kad_product">
<div <?php wc_product_cat_class('product-category grid_item', $category ); ?>>

	<?php 
	/**
	 * woocommerce_before_subcategory hook.
	 *
	 * @hooked woocommerce_template_loop_category_link_open - 10
	 */
	do_action( 'woocommerce_before_subcategory', $category );

	/**
	 * woocommerce_before_subcategory_title hook.
	 *
	 * @hooked woocommerce_subcategory_thumbnail - 10
	 */
	do_action( 'woocommerce_before_subcategory_title', $category );
	if($img_ratio == 'off') {
					woocommerce_subcategory_thumbnail($category);
	} else {
		$thumbnail_id = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true  );
		if ( $thumbnail_id ) {
			$image_cat_url = wp_get_attachment_image_src( $thumbnail_id, 'full');
			$image_cat_url = $image_cat_url[0];
			$cat_image = aq_resize($image_cat_url, $catimgwidth, $catimgheight, true);
				if(empty($cat_image)) {
					$cat_image = $image_cat_url;}
			    } else {
			        $cat_image = virtue_img_placeholder_cat();
			   	}
			 	if ( $cat_image ) {
			            echo '<img src="' . esc_url($cat_image) . '" width="'.esc_attr($catimgwidth).'" height="'.esc_attr($catimgheight).'" alt="' . esc_attr($category->name) . '" />';
			    }
    	}
    /**
	 * woocommerce_shop_loop_subcategory_title hook.
	 *
	 * @hooked woocommerce_template_loop_category_title - 10
	 */
	do_action( 'woocommerce_shop_loop_subcategory_title', $category );

	/**
	 * woocommerce_after_subcategory_title hook.
	 */
	do_action( 'woocommerce_after_subcategory_title', $category );

	/**
	 * woocommerce_after_subcategory hook.
	 *
	 * @hooked woocommerce_template_loop_category_link_close - 10
	 */
	do_action( 'woocommerce_after_subcategory', $category ); ?>

</div>
</div>