<?php
/**
 * Loop Rating
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;

if ( get_option( 'woocommerce_enable_review_rating' ) == 'no' )
	return;
?>
<?php global $virtue_premium; if ($virtue_premium['shop_rating'] == '1') { ?> 
	<?php if ( $rating_html = $product->get_rating_html() ) { ?>
		<a href="<?php the_permalink(); ?>"><?php echo $rating_html; ?></a>
	<?php } else { echo "<span class='notrated'>".__('not rated', 'virtue')."</span>"; ?>
	<?php } ?>
<?php } ?>