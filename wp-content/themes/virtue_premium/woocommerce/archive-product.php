<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

 global $virtue_premium, $woocommerce_loop;  

 if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

 if(isset($virtue_premium['shop_slider'])) {$shop_slider = $virtue_premium['shop_slider'];} else {$shop_slider = 0;} 
		if (is_shop() and ($shop_slider == '1')) { 
			$choose_shop_slider = $virtue_premium['choose_shop_slider'];
					if ($choose_shop_slider == "rev") {
					get_template_part('templates/shop/rev', 'slider');
					}
					else if ($choose_shop_slider == "flex") {
						get_template_part('templates/shop/flex', 'slider');
					}
					else if ($choose_shop_slider == "fullwidth") {
						get_template_part('templates/shop/flex', 'slider-fullwidth');
					}
					else if ($choose_shop_slider == "cyclone") {
						get_template_part('templates/shop/shortcode', 'slider');
					}
			 } ?>


				
		<div id="content" class="container">
   		<div class="row">
      <div class="main <?php echo kadence_main_class(); ?>" role="main">

		<?php
			/**
			 * woocommerce_before_main_content hook
			 *
			 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
			 * @hooked woocommerce_breadcrumb - 20
			 */
			do_action( 'woocommerce_before_main_content' );
		?>

      	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
			<div class="page-header">
				<div class="row">
					<div class="col-md-6 col-sm-6 woo-archive-pg-title">
						<h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
						<?php woocommerce_result_count(); ?>
					</div>
					<div class="col-md-6 col-sm-6 woo-archive-pg-order">
					<?php if(isset($virtue_premium['shop_toggle']) && $virtue_premium['shop_toggle'] == 1) {
						if(isset($virtue_premium['product_shop_layout']) && $virtue_premium['product_shop_layout'] == '1') { ?>
						<div class="kt_product_toggle_container_list single_to_grid">
							<div title="<?php echo __('List View', 'virtue');?>" class="toggle_list toggle_active" data-toggle="product_list">
							<i class="icon-menu4"></i>
							</div>
							<div title="<?php echo __('Grid View', 'virtue');?>" class="toggle_grid" data-toggle="product_grid">
								<i class="icon-grid5"></i>
							</div>
						</div>
						<?php } else { ?>
						<div class="kt_product_toggle_container">
							<div title="<?php echo __('Grid View', 'virtue');?>" class="toggle_grid toggle_active" data-toggle="product_grid">
								<i class="icon-grid5"></i>
							</div>
							<div title="<?php echo __('List View', 'virtue');?>" class="toggle_list" data-toggle="product_list">
							<i class="icon-menu4"></i>
							</div>
						</div>
					<?php } } ?>
					<?php woocommerce_catalog_ordering(); ?>
					<?php if(kadence_display_shop_breadcrumbs()) { kadence_breadcrumbs(); } ?>
					</div>
				</div>
			</div>
		
		<?php endif; ?>
		<div class="clearfix">
		<?php do_action( 'woocommerce_archive_description' ); ?>
		</div>

		<?php if ( have_posts() ) : ?>

			<?php
				/**
				 * woocommerce_before_shop_loop hook
				 * and ($shop_filter == '1')
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );
			?>
			<?php global $virtue_premium; $shop_filter = $virtue_premium['shop_filter']; 
			 $cat_filter = $virtue_premium['cat_filter']; 
			 if(!empty($virtue_premium['filter_all_text'])) {$alltext = $virtue_premium['filter_all_text'];} else {$alltext = __('All', 'virtue');}
			 if(!empty($virtue_premium['shop_filter_text'])) {$shopfiltertext = $virtue_premium['shop_filter_text'];} else {$shopfiltertext = __('Filter Products', 'virtue');}
	  		if (is_shop() && $shop_filter == 1 && !is_search()) { ?>
      		<section id="options" class="clearfix">
			<?php 
			$categories = get_terms('product_cat');
					$count = count($categories);
						echo '<a class="filter-trigger headerfont" data-toggle="collapse" data-target=".filter-collapse"><i class="icon-tags"></i> '.$shopfiltertext.'</a>';
						echo '<ul id="filters" class="clearfix option-set filter-collapse">';
						echo '<li class="postclass"><a href="#" data-filter="*" title="'.$alltext.'" class="selected"><h5>'.$alltext.'</h5><div class="arrow-up"></div></a></li>';
						 if ( $count > 0 ){
							foreach ($categories as $category){ 
							$termname = strtolower($category->slug);
							$termname = preg_replace("/[^a-zA-Z 0-9]+/", " ", $termname);
							$termname = str_replace(' ', '-', $termname);	
							echo '<li class="postclass"><a href="#" data-filter=".'.$termname.'" title="'.__("Show", "virtue").' '.$category->name.'" rel="'.$termname.'"><h5>'.$category->name.'</h5><div class="arrow-up"></div></a></li>';
								}
				 		}
				 		echo "</ul>"; ?>
			</section>
            <?php } else if (is_product_category() && $cat_filter == 1) { ?>
      		<section id="options" class="clearfix">
			<?php
			global $wp_query;
				// get the query object
					$cat_obj = $wp_query->get_queried_object();
					$product_cat_ID  = $cat_obj->term_id;
					$termtypes = array( 'child_of' => $product_cat_ID,);
					$categories = get_terms('product_cat', $termtypes);
					$count = count($categories);
					if ( $count > 0 ){
						echo '<a class="filter-trigger headerfont" data-toggle="collapse" data-target=".filter-collapse"><i class="icon-tags"></i> '.$shopfiltertext.'</a>';
						echo '<ul id="filters" class="clearfix option-set filter-collapse">';
						echo '<li class="postclass"><a href="#" data-filter="*" title="'.$alltext.'" class="selected"><h5>'.$alltext.'</h5><div class="arrow-up"></div></a></li>';
							foreach ($categories as $category){ 
							$termname = strtolower($category->slug);
							$termname = preg_replace("/[^a-zA-Z 0-9]+/", " ", $termname);
							$termname = str_replace(' ', '-', $termname);
							echo '<li class="postclass"><a href="#" data-filter=".'.$termname.'" title="'.__("Show", "virtue").' '.$category->name.'" rel="'.$termname.'"><h5>'.$category->name.'</h5><div class="arrow-up"></div></a></li>';
								}
						echo "</ul>"; 
				 	} ?>
			</section>
            <?php } ?>

            <div class="clearfix <?php echo kadence_category_layout_css(); ?> rowtight product_category_padding"> 
            <?php woocommerce_product_subcategories(); ?> 
            </div>

			<?php woocommerce_product_loop_start(); ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php woocommerce_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

			<?php
				/**
				 * woocommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			?>

		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php woocommerce_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif; ?>
			<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>
	</div>