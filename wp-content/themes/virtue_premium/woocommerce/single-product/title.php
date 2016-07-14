<?php
/**
 * Single Product title
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $virtue_premium;
if(isset($virtue_premium['product_nav']) && $virtue_premium['product_nav'] == '1') {?>
		<div class="productnav">
				<?php previous_post_link_plus( array('order_by' => 'menu_order', 'loop' => true, 'in_same_tax' => true, 'format' => '%link', 'link' => '<i class="icon-arrow-left"></i>') ); ?>
				<?php next_post_link_plus( array('order_by' => 'menu_order', 'loop' => true, 'in_same_tax' => true, 'format' => '%link', 'link' => '<i class="icon-arrow-right"></i>') ); ?>
		</div>
		<?php } ?>
<h1 itemprop="name" class="product_title entry-title"><?php the_title(); ?></h1>