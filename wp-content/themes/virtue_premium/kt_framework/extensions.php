<?php 
add_action( "after_setup_theme", 'kt_remove_sections', 1);
function kt_remove_sections() {
    if ( ! class_exists( 'Redux' ) ) {
                return;
            }
    if(ReduxFramework::$_version <= '3.5.6') {
        return;
        }
    $options_slug = get_option(OPTIONS_SLUG);
    if(isset($options_slug['kadence_woo_extension']) && $options_slug['kadence_woo_extension'] == '0') {
        //sections
        Redux::removeSection(OPTIONS_SLUG, 'shop_settings');
        Redux::removeSection(OPTIONS_SLUG, 'product_settings');

        //topbar
        Redux::removefield(OPTIONS_SLUG, 'show_cartcount');

        //home layout
        Redux::removefield(OPTIONS_SLUG, 'info_product_feat_settings');
        Redux::removefield(OPTIONS_SLUG, 'product_title');
        Redux::removefield(OPTIONS_SLUG, 'home_product_feat_column');
        Redux::removefield(OPTIONS_SLUG, 'home_product_count');
        Redux::removefield(OPTIONS_SLUG, 'home_product_feat_scroll');
        Redux::removefield(OPTIONS_SLUG, 'home_product_feat_speed');
        Redux::removefield(OPTIONS_SLUG, 'info_product_sale_settings');
        Redux::removefield(OPTIONS_SLUG, 'product_sale_title');
        Redux::removefield(OPTIONS_SLUG, 'home_product_sale_count');
        Redux::removefield(OPTIONS_SLUG, 'home_product_sale_scroll');
        Redux::removefield(OPTIONS_SLUG, 'home_product_sale_speed');
        Redux::removefield(OPTIONS_SLUG, 'info_product_best_settings');
        Redux::removefield(OPTIONS_SLUG, 'product_best_title');
        Redux::removefield(OPTIONS_SLUG, 'home_product_best_column');
        Redux::removefield(OPTIONS_SLUG, 'home_product_best_count');
        Redux::removefield(OPTIONS_SLUG, 'home_product_best_scroll');
        Redux::removefield(OPTIONS_SLUG, 'home_product_best_speed');

        // Breadcrumbs
        Redux::removefield(OPTIONS_SLUG, 'show_breadcrumbs_shop');
        Redux::removefield(OPTIONS_SLUG, 'show_breadcrumbs_product');
        Redux::removefield(OPTIONS_SLUG, 'shop_breadcrumbs');

        // Menu
        Redux::removefield(OPTIONS_SLUG, 'menu_cart');
        Redux::removefield(OPTIONS_SLUG, 'menu_search_woo');

        // language
        Redux::removefield(OPTIONS_SLUG, 'cart_placeholder_text');
        Redux::removefield(OPTIONS_SLUG, 'sold_placeholder_text');
        Redux::removefield(OPTIONS_SLUG, 'sale_placeholder_text');
        Redux::removefield(OPTIONS_SLUG, 'shop_filter_text');
        Redux::removefield(OPTIONS_SLUG, 'description_tab_text');
        Redux::removefield(OPTIONS_SLUG, 'description_header_text');
        Redux::removefield(OPTIONS_SLUG, 'additional_information_tab_text');
        Redux::removefield(OPTIONS_SLUG, 'additional_information_header_text');
        Redux::removefield(OPTIONS_SLUG, 'video_tab_text');
        Redux::removefield(OPTIONS_SLUG, 'video_title_text');
        Redux::removefield(OPTIONS_SLUG, 'reviews_tab_text');
        Redux::removefield(OPTIONS_SLUG, 'related_products_text');
        

        //update feild
        $field = Redux::getField(OPTIONS_SLUG, 'homepage_layout');
        unset($field['options']['disabled']['block_three']);
        unset($field['options']['disabled']['block_nine']);
        unset($field['options']['disabled']['block_ten']);
        Redux::setField(OPTIONS_SLUG,$field);

        add_filter('kadence_widget_carousel_types', 'kadence_unset_product_from_carousel');
        function kadence_unset_product_from_carousel($types) {
            unset($types['featured-products']);
            unset($types['sale-products']);
            unset($types['best-products']);
            unset($types['cat-products']);
            return $types;
        }
    }
    if(isset($options_slug['kadence_header_footer_extension']) && $options_slug['kadence_header_footer_extension'] == '1') {
        add_action('wp_head', 'kt_wp_head_script_output');
        function kt_wp_head_script_output() {
            global $virtue_premium;
            if(isset($virtue_premium['kt_header_script']) && !empty($virtue_premium['kt_header_script']) ){
                echo $virtue_premium['kt_header_script'];
            }
        }
        add_action('wp_footer', 'kt_wp_fotoer_script_output');
        function kt_wp_fotoer_script_output() {
            global $virtue_premium;
            if(isset($virtue_premium['kt_footer_script']) && !empty($virtue_premium['kt_footer_script']) ){
                echo $virtue_premium['kt_footer_script'];
            }
        }
    } else {
        if(isset($options_slug['kadence_header_footer_extension'])) {
            Redux::removeSection(OPTIONS_SLUG, 'header_footer_scripts');
        }
    }
    if(isset($options_slug['kadence_portfolio_extension']) && $options_slug['kadence_portfolio_extension'] == '0') {
        Redux::removeSection(OPTIONS_SLUG, 'portfolio_options');

        Redux::removefield(OPTIONS_SLUG, 'info_portfolio_settings');
        Redux::removefield(OPTIONS_SLUG, 'portfolio_title');
        Redux::removefield(OPTIONS_SLUG, 'portfolio_type');
        Redux::removefield(OPTIONS_SLUG, 'home_portfolio_carousel_column');
        Redux::removefield(OPTIONS_SLUG, 'home_port_car_layoutstyle');
        Redux::removefield(OPTIONS_SLUG, 'home_port_car_hoverstyle');
        Redux::removefield(OPTIONS_SLUG, 'home_port_car_imageratio');
        Redux::removefield(OPTIONS_SLUG, 'home_portfolio_carousel_count');
        Redux::removefield(OPTIONS_SLUG, 'home_portfolio_carousel_speed');
        Redux::removefield(OPTIONS_SLUG, 'home_portfolio_carousel_scroll');
        Redux::removefield(OPTIONS_SLUG, 'home_portfolio_order');
        Redux::removefield(OPTIONS_SLUG, 'portfolio_car_fullwidth');
        Redux::removefield(OPTIONS_SLUG, 'portfolio_car_lightbox');
        Redux::removefield(OPTIONS_SLUG, 'portfolio_show_type');
        Redux::removefield(OPTIONS_SLUG, 'portfolio_show_excerpt');
        Redux::removefield(OPTIONS_SLUG, 'info_portfolio_full_settings');
        Redux::removefield(OPTIONS_SLUG, 'portfolio_full_title');
        Redux::removefield(OPTIONS_SLUG, 'portfolio_full_type');
        Redux::removefield(OPTIONS_SLUG, 'home_port_count');
        Redux::removefield(OPTIONS_SLUG, 'home_portfolio_full_order');
        Redux::removefield(OPTIONS_SLUG, 'home_port_columns');

        Redux::removefield(OPTIONS_SLUG, 'home_port_layoutstyle');
        Redux::removefield(OPTIONS_SLUG, 'home_port_hoverstyle');
        Redux::removefield(OPTIONS_SLUG, 'home_port_imageratio');
        Redux::removefield(OPTIONS_SLUG, 'portfolio_full_masonry');
        Redux::removefield(OPTIONS_SLUG, 'home_portfolio_full_height');
        Redux::removefield(OPTIONS_SLUG, 'portfolio_full_filter');
        Redux::removefield(OPTIONS_SLUG, 'portfolio_full_show_filter');
        Redux::removefield(OPTIONS_SLUG, 'portfolio_full_fullwidth');
        Redux::removefield(OPTIONS_SLUG, 'home_portfolio_lightbox');
        Redux::removefield(OPTIONS_SLUG, 'portfolio_full_show_type');
        Redux::removefield(OPTIONS_SLUG, 'portfolio_full_show_excerpt');
        Redux::removefield(OPTIONS_SLUG, 'portfolio_full_fullwidth');
        


        remove_action( 'init', 'kadence_portfolio_post_init', 1 );

        $field = Redux::getField(OPTIONS_SLUG, 'homepage_layout');
        unset($field['options']['disabled']['block_eight']);
        unset($field['options']['disabled']['block_six']);
        Redux::setField(OPTIONS_SLUG,$field);

        add_filter('kadence_widget_carousel_types', 'kadence_unset_portfolio_from_carousel');
        function kadence_unset_portfolio_from_carousel($types) {
            unset($types['portfolio']);
            return $types;
        }
        add_filter( 'kadence_portfolio_post_removal', 'kadence_portfolio_post_removal_function');
        function kadence_portfolio_post_removal_function() { 
            return false;
        }
    }
    if(isset($options_slug['kadence_staff_extension']) && $options_slug['kadence_staff_extension'] == '0') {
      remove_action( 'init', 'staff_post_init');
    }
    if(isset($options_slug['kadence_testimonial_extension']) && $options_slug['kadence_testimonial_extension'] == '0') {
      remove_action( 'init', 'testimonial_post_init');
        add_filter( 'kadence_testimonial_post_removal', 'kadence_testimonial_post_removal_function');
        function kadence_testimonial_post_removal_function() { 
            return false;
        }
        add_filter('kadence_shortcodes', 'kadence_unset_test_from_shortcode_popup');
        function kadence_unset_test_from_shortcode_popup($virtue_shortcodes) {
            unset($virtue_shortcodes['kad_testimonial_form']);
            return $virtue_shortcodes;
        }
    }
}


function kadence_portfolio_post_on() {
    return apply_filters( 'kadence_portfolio_post_removal', true );
}
function kadence_testimonial_post_on() {
    return apply_filters( 'kadence_testimonial_post_removal', true );
}