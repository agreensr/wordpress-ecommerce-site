<?php 
/*
 * Virtue Premium Widgets 
 */

class kad_gallery_widget extends WP_Widget{

private static $instance = 0;
    public function __construct() {
        $widget_ops = array('classname' => 'virtue_gallery_widget', 'description' => __('Adds a gallery to any widget area.', 'virtue'));
        parent::__construct('virtue_gallery_widget', __('Virtue: Gallery', 'virtue'), $widget_ops);
    }
     public function widget($args, $instance){ 
        extract( $args ); 
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        if(!empty($instance["ids"])) {$g_ids = $instance["ids"];} else {$g_ids = '';}
        if(!empty($instance["gallery_width"])) {$g_width = 'width='.$instance["gallery_width"];} else {$g_width = '';}
        if(!empty($instance["gallery_height"])) {$g_height = 'height='.$instance["gallery_height"];} else {$g_height = '';}
        if(!empty($instance["gallery_speed"])) {$g_speed = 'speed='.$instance["gallery_speed"];} else {$g_speed = '';}
        if(!empty($instance["gallery_type"])) { $g_type = $instance["gallery_type"]; } else {$g_type = 'standard';}
        if(!empty($instance["lightbox_size"])) { $l_size = 'lightboxsize="'.$instance["lightbox_size"].'"'; } else {$l_size = '';}
        if(!empty($instance["gallery_columns"])) { $g_columns = $instance["gallery_columns"]; } else {$g_columns = '3';}
        if(!empty($instance["gallery_captions"]) && $instance["gallery_captions"] == 'on') { $g_captions = 'caption=true';} else {$g_captions = '';}
        if($g_type == 'masonry') {$masonry = 'true';} else {$masonry = 'false';}

            ?>

          <?php echo $before_widget;
          if ( $title ) echo $before_title . $title . $after_title; 
          echo do_shortcode('[gallery ids='.$g_ids.' type='.$g_type.' '.$g_captions.' masonry='.$masonry.' columns='.$g_columns.' '.$g_speed.' '.$g_height.' '.$l_size.' '.$g_width .']');
          echo $after_widget;?>

    <?php }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['ids'] = $new_instance['ids'];
        $instance['gallery_type'] = $new_instance['gallery_type'];
        $instance['lightbox_size'] = $new_instance['lightbox_size'];
        $instance['gallery_columns'] = $new_instance['gallery_columns']; 
        $instance['gallery_captions'] = $new_instance['gallery_captions'];
        $instance['gallery_width'] = (int) $new_instance['gallery_width'];
        $instance['gallery_height'] = (int) $new_instance['gallery_height'];
        $instance['gallery_speed'] = (int) $new_instance['gallery_speed'];
        $instance['title'] = strip_tags( $new_instance['title'] );
        return $instance;
    }

  public function form($instance){ 
    $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
    $ids = isset($instance['ids']) ? esc_attr($instance['ids']) : '';
    $gallery_width = isset($instance['gallery_width']) ? esc_attr($instance['gallery_width']) : '';
    $gallery_height = isset($instance['gallery_height']) ? esc_attr($instance['gallery_height']) : '';
    $gallery_speed = isset($instance['gallery_speed']) ? esc_attr($instance['gallery_speed']) : '';
    if (isset($instance['gallery_type'])) { $gallery_type = esc_attr($instance['gallery_type']); } else {$gallery_type = 'standard';}
    if (isset($instance['lightbox_size'])) { $lightbox_size = esc_attr($instance['lightbox_size']); } else {$lightbox_size = 'full';}
    if (isset($instance['gallery_columns'])) { $gallery_columns = esc_attr($instance['gallery_columns']); } else {$gallery_columns = '3';}
    if (isset($instance['gallery_captions'])) { $gallery_captions = esc_attr($instance['gallery_captions']); } else {$gallery_captions = 'off';}
    $gallery_type_array = array();
    $lightbox_size_array = array();
    $gallery_columns_array = array();
    $gallery_captions_array = array();
    $gallery_options = array(array("slug" => "standard", "name" => __('Standard', 'virtue')), array("slug" => "masonry", "name" => __('Masonry', 'virtue')), array("slug" => "mosaic", "name" => __('Mosaic', 'virtue')), array( "slug" => "carousel", "name" => __('Carousel', 'virtue')), array( "slug" => "slider", "name" => __('Slider', 'virtue')), array( "slug" => "imagecarousel", "name" => __('Image Carousel', 'virtue')));
    $gallery_columns_options = array(array("slug" => "1", "name" => __('1 Column', 'virtue')), array("slug" => "2", "name" => __('2 Columns', 'virtue')), array("slug" => "3", "name" => __('3 Columns', 'virtue')), array("slug" => "4", "name" => __('4 Columns', 'virtue')), array("slug" => "5", "name" => __('5 Columns', 'virtue')), array("slug" => "6", "name" => __('6 Columns', 'virtue')));
    $gallery_caption_options = array(array("slug" => "off", "name" => __('Off', 'virtue')), array("slug" => "on", "name" => __('On', 'virtue')));
    $lightbox_size_options = array(array("slug" => "full", "name" => __('Full', 'virtue')), array("slug" => "large", "name" => __('Large', 'virtue')), array("slug" => "medium", "name" => __('Medium', 'virtue')), array("slug" => "thumbnail", "name" => __('Thumbnail', 'virtue')));
     foreach ($gallery_caption_options as $gallery_caption_option) {
      if ($gallery_captions == $gallery_caption_option['slug']) { $selected=' selected="selected"';} else { $selected=""; }
      $gallery_captions_array[] = '<option value="' . $gallery_caption_option['slug'] .'"' . $selected . '>' . $gallery_caption_option['name'] . '</option>';
    }
      foreach ($lightbox_size_options as $lightbox_size_option) {
      if ($lightbox_size == $lightbox_size_option['slug']) { $selected=' selected="selected"';} else { $selected=""; }
      $lightbox_size_array[] = '<option value="' . $lightbox_size_option['slug'] .'"' . $selected . '>' . $lightbox_size_option['name'] . '</option>';
    }
    foreach ($gallery_options as $gallery_option) {
      if ($gallery_type == $gallery_option['slug']) { $selected=' selected="selected"';} else { $selected=""; }
      $gallery_type_array[] = '<option value="' . $gallery_option['slug'] .'"' . $selected . '>' . $gallery_option['name'] . '</option>';
    }
    foreach ($gallery_columns_options as $gallery_column_option) {
      if ($gallery_columns == $gallery_column_option['slug']) { $selected=' selected="selected"';} else { $selected=""; }
      $gallery_columns_array[] = '<option value="' . $gallery_column_option['slug'] .'"' . $selected . '>' . $gallery_column_option['name'] . '</option>';
    }?>  

    <div id="virtue_gallery_widget<?php echo esc_attr($this->get_field_id('container')); ?>" class="kad_widget_image_gallery">
        <div class="gallery_images">
            <?php
            $attachments = array_filter( explode( ',', $ids ) );
             if ( $attachments )
            foreach ( $attachments as $attachment_id ) {
                $img = wp_get_attachment_image_src($attachment_id, 'thumbnail');
                $imgfull = wp_get_attachment_image_src($attachment_id, 'full');
                    echo '<a class="of-uploaded-image" target="_blank" rel="external" href="' . $imgfull[0] . '">';
                    echo '<img class="gallery-widget-image" id="gallery_widget_image_'.$attachment_id. '" src="' . $img[0] . '" />';
                    echo '</a>';
                }
?>
        </div>
           <?php  echo '<a href="#" onclick="return false;" id="edit-gallery" class="gallery-attachments button button-primary">' . __('Add/Edit Gallery', 'virtue') . '</a> ';
            echo '<a href="#" onclick="return false;" id="clear-gallery" class="gallery-attachments button">' . __('Clear Gallery', 'virtue') . '</a>';
            echo '<input type="hidden" id="' . esc_attr($this->get_field_id('ids')) . '" class="gallery_values" value="' . $ids . '" name="' . esc_attr($this->get_field_name('ids')) . '" />';
            ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'virtue'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('gallery_type'); ?>"><?php _e('Gallery Type', 'virtue'); ?></label><br />
                <select id="<?php echo $this->get_field_id('gallery_type'); ?>" style="width:100%; max-width:230px" name="<?php echo $this->get_field_name('gallery_type'); ?>"><?php echo implode('', $gallery_type_array);?></select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('gallery_columns'); ?>"><?php _e('Gallery Columns', 'virtue'); ?></label><br />
                <select id="<?php echo $this->get_field_id('gallery_columns'); ?>" style="width:100%; max-width:230px;" name="<?php echo $this->get_field_name('gallery_columns'); ?>"><?php echo implode('', $gallery_columns_array);?></select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('gallery_captions'); ?>"><?php _e('Display Captions', 'virtue'); ?></label><br />
               <select id="<?php echo $this->get_field_id('gallery_captions'); ?>" style="width:100%; max-width:230px" name="<?php echo $this->get_field_name('gallery_captions'); ?>"><?php echo implode('', $gallery_captions_array);?></select>  
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('lightbox_size'); ?>"><?php _e('Lightbox Image Size', 'virtue'); ?></label><br />
                <select id="<?php echo $this->get_field_id('lightbox_size'); ?>" style="width:100%; max-width:230px" name="<?php echo $this->get_field_name('lightbox_size'); ?>"><?php echo implode('', $lightbox_size_array);?></select>
            </p>
            <p style="font-weight:bold;"><?php echo __('If Type Slider', 'virtue'); ?></p>
            <p>
                <label for="<?php echo $this->get_field_id('gallery_width'); ?>"><?php _e('Slider Width (e.g. = 600)', 'virtue'); ?></label><br />
                <input type="text" class="widefat kad_img_widget_link" name="<?php echo $this->get_field_name('gallery_width'); ?>" id="<?php echo $this->get_field_id('gallery_width'); ?>" value="<?php echo $gallery_width; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('gallery_height'); ?>"><?php _e('Slider Height (e.g. = 400)', 'virtue'); ?></label><br />
                <input type="text" class="widefat kad_img_widget_link" name="<?php echo $this->get_field_name('gallery_height'); ?>" id="<?php echo $this->get_field_id('gallery_height'); ?>" value="<?php echo $gallery_height; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('gallery_speed'); ?>"><?php _e('Slider Speed (e.g. = 7000)', 'virtue'); ?></label><br />
                <input type="text" class="widefat kad_img_widget_link" name="<?php echo $this->get_field_name('gallery_speed'); ?>" id="<?php echo $this->get_field_id('gallery_speed'); ?>" value="<?php echo $gallery_speed; ?>">
            </p>
    </div>

    <style type="text/css">.kad_widget_image_gallery {padding-bottom: 10px;}
.kad_widget_image_gallery .gallery_images:after { content: "."; display: block; height: 0; clear: both; visibility: hidden; }
.kad_widget_image_gallery .gallery_images {padding: 5px 5px 0; margin: 10px 0; background: #f2f2f2;}
.kad_widget_image_gallery .gallery_images img {max-width: 80px; height: auto; float: left; padding: 0 5px 5px 0}
</style>

<?php } }

class kad_carousel_widget extends WP_Widget{

private static $instance = 0;
    public function __construct() {
        $widget_ops = array('classname' => 'virtue_carousel_widget', 'description' => __('Adds a carousel to any widget area', 'virtue'));
        parent::__construct('virtue_carousel_widget', __('Virtue: Carousel', 'virtue'), $widget_ops);
    }

       public function widget($args, $instance){ 
        extract( $args ); 
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        if(!empty($instance["type"])) {$c_type = $instance["type"];} else {$c_type = 'post';}
        if(!empty($instance["c_order"])) {$c_order = 'orderby='.$instance["c_order"];} else {$c_order = '';}
        if(!empty($instance["autoplay"])) {$autoplay = 'autoplay='.$instance["autoplay"];} else {$autoplay = '';}
        if(!empty($instance["c_items"])) {$c_items = 'items='.$instance["c_items"];} else {$c_items = 'items=6';}
        if(!empty($instance["c_speed"])) {$c_speed = 'speed='.$instance["c_speed"];} else {$c_speed = '';}
         if($c_type == "cat-products" || $c_type == "sale-products") {
            if(!empty($instance["productcat"])) {$c_cat = 'cat='.$instance["productcat"];} else {$c_cat = '';}
        } else if ($c_type == "portfolio") {
            if(!empty($instance["portfoliocat"])) {$c_cat = 'cat='.$instance["portfoliocat"];} else {$c_cat = '';}
        } else {
            if(!empty($instance["postcat"])) {$c_cat = 'cat='.$instance["postcat"];} else {$c_cat = '';}
        }
        if(!empty($instance["c_columns"])) { $c_columns = $instance["c_columns"]; } else {$c_columns = '1';}
        if(!empty($instance["c_scroll"])) { $c_scroll = $instance["c_scroll"]; } else {$c_scroll = '1';}

            ?>


          <?php echo $before_widget;
            if ( $title ) echo $before_title . $title . $after_title; 
           echo do_shortcode('[carousel type='.$c_type.' '.$c_items.' '.$autoplay.' '.$c_order.' columns='.$c_columns.' '.$c_speed.' '.$c_cat.' scroll='.$c_scroll.']');
           echo $after_widget;?>

    <?php }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['type'] = $new_instance['type'];
        $instance['c_items'] = (int) $new_instance['c_items']; 
        $instance['c_columns'] = $new_instance['c_columns'];
        $instance['autoplay'] = $new_instance['autoplay'];
        $instance['c_order'] = $new_instance['c_order'];
        $instance['c_scroll'] = $new_instance['c_scroll'];
        $instance['postcat'] = $new_instance['postcat'];
        $instance['portfoliocat'] = $new_instance['portfoliocat'];
        $instance['productcat'] = $new_instance['productcat'];
        $instance['c_speed'] = (int) $new_instance['c_speed'];
        $instance['title'] = strip_tags( $new_instance['title'] );
        return $instance;
    }

  public function form($instance){ 
    $c_items = isset($instance['c_items']) ? esc_attr($instance['c_items']) : '';
    $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
    $c_speed = isset($instance['c_speed']) ? esc_attr($instance['c_speed']) : '';
    $autoplay = isset($instance['autoplay']) ? esc_attr($instance['autoplay']) : 'true';
    if (isset($instance['type'])) { $c_type = esc_attr($instance['type']); } else {$c_type = 'post';}
    if (isset($instance['c_scroll'])) { $c_scroll = esc_attr($instance['c_scroll']); } else {$c_scroll = '1';}
    if (isset($instance['c_order'])) { $c_order = esc_attr($instance['c_order']); } else {$c_order = 'menu_order';}
    if (isset($instance['c_columns'])) { $c_columns = esc_attr($instance['c_columns']); } else {$c_columns = '1';}
    $carousel_type_array = array();
    $carousel_scroll_array = array();
    $carousel_columns_array = array();
    $carousel_order_array = array();
    $carousel_types = array(
        'post' => array("slug" => "post", "name" => __('Blog Posts', 'virtue')), 
        'portfolio' => array("slug" => "portfolio", "name" => __('Portfolio Posts', 'virtue')), 
        'featured-products' => array( "slug" => "featured-products", "name" => __('Featured Products', 'virtue')), 
        'sale-products' => array( "slug" => "sale-products", "name" => __('Sale Products', 'virtue')), 
        'best-products' => array( "slug" => "best-products", "name" => __('Best Products', 'virtue')),
        'cat-products' => array( "slug" => "cat-products", "name" => __('Category of Products', 'virtue')),
        );
    
    $carousel_types = apply_filters('kadence_widget_carousel_types', $carousel_types);
    $carousel_columns_options = array(array("slug" => "1", "name" => __('1 Column', 'virtue')), array("slug" => "2", "name" => __('2 Columns', 'virtue')), array("slug" => "3", "name" => __('3 Columns', 'virtue')), array("slug" => "4", "name" => __('4 Columns', 'virtue')), array("slug" => "5", "name" => __('5 Columns', 'virtue')));
    $carousel_scroll_options = array(array("slug" => "1", "name" => __('1 item', 'virtue')), array("slug" => "all", "name" => __('All Visible', 'virtue')));
    $carousel_autoplay = array(array("slug" => "true", "name" => __('True', 'virtue')), array("slug" => "false", "name" => __('False', 'virtue')));
    $carousel_order_options = array(array("slug" => "menu_order", "name" => __('Menu Order', 'virtue')), array("slug" => "date", "name" => __('Date', 'virtue')), array("slug" => "rand", "name" => __('Random', 'virtue')));

    if (isset($instance['postcat'])) { $postcat = esc_attr($instance['postcat']); } else {$postcat = '';}
    if (isset($instance['portfoliocat'])) { $portfoliocat = esc_attr($instance['portfoliocat']); } else {$portfoliocat = '';}
    if (isset($instance['productcat'])) { $productcat = esc_attr($instance['productcat']); } else {$productcat = '';}

     $types= get_terms('portfolio-type');
     $type_options = array();
    $type_options[] = '<option value="">All</option>';
    if(!is_wp_error($types) ) {
        foreach ($types as $type) {
          if ($portfoliocat==$type->slug) { $selected=' selected="selected"';} else { $selected=""; }
          $type_options[] = '<option value="' . $type->slug .'"' . $selected . '>' . $type->name . '</option>';
        }
    }
     $categories= get_categories();
    $cat_options = array();
    $cat_options[] = '<option value="">All</option>';
    foreach ($categories as $cat) {
      if ($postcat==$cat->slug) { $selected=' selected="selected"';} else { $selected=""; }
      $cat_options[] = '<option value="' . $cat->slug .'"' . $selected . '>' . $cat->name . '</option>';
    }

    $product_options = array();
    $product_options[] = '<option value="">All</option>';
    if (class_exists('woocommerce')) { 
        $product_categories= get_terms('product_cat');
        foreach ($product_categories as $pcat) {
          if ($productcat==$pcat->slug) { $selected=' selected="selected"';} else { $selected=""; }
          $product_options[] = '<option value="' . $pcat->slug .'"' . $selected . '>' . $pcat->name . '</option>';
        }
    }
    $autoplay_options = array();
    foreach ($carousel_autoplay as $auto) {
        if ($autoplay == $auto['slug']) { $selected=' selected="selected"';} else { $selected=""; }
            $autoplay_options[] = '<option value="' . $auto['slug'] .'"' . $selected . '>' . $auto['name'] . '</option>';
        }


    foreach ($carousel_types as $carousel_type) {
      if ($c_type == $carousel_type['slug']) { $selected=' selected="selected"';} else { $selected=""; }
      $carousel_type_array[] = '<option value="' . $carousel_type['slug'] .'"' . $selected . '>' . $carousel_type['name'] . '</option>';
    }
    foreach ($carousel_scroll_options as $carousel_scroll_option) {
      if ($c_scroll == $carousel_scroll_option['slug']) { $selected=' selected="selected"';} else { $selected=""; }
      $carousel_scroll_array[] = '<option value="' . $carousel_scroll_option['slug'] .'"' . $selected . '>' . $carousel_scroll_option['name'] . '</option>';
    }
    foreach ($carousel_columns_options as $carousel_column_option) {
      if ($c_columns == $carousel_column_option['slug']) { $selected=' selected="selected"';} else { $selected=""; }
      $carousel_columns_array[] = '<option value="' . $carousel_column_option['slug'] .'"' . $selected . '>' . $carousel_column_option['name'] . '</option>';
    }
    foreach ($carousel_order_options as $carousel_order_option) {
      if ($c_order == $carousel_order_option['slug']) { $selected=' selected="selected"';} else { $selected=""; }
      $carousel_order_array[] = '<option value="' . $carousel_order_option['slug'] .'"' . $selected . '>' . $carousel_order_option['name'] . '</option>';
    }?>  

    <div id="virtue_carousel_widget<?php echo esc_attr($this->get_field_id('container')); ?>" class="kad_widget_carousel">
          <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'virtue'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('type'); ?>"><?php _e('Carousel Type', 'virtue'); ?></label><br />
                <select id="<?php echo $this->get_field_id('type'); ?>" style="width:100%; max-width:230px" name="<?php echo $this->get_field_name('type'); ?>"><?php echo implode('', $carousel_type_array);?></select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('c_columns'); ?>"><?php _e('Carousel Columns', 'virtue'); ?></label><br />
                <select id="<?php echo $this->get_field_id('c_columns'); ?>" style="width:100%; max-width:230px;" name="<?php echo $this->get_field_name('c_columns'); ?>"><?php echo implode('', $carousel_columns_array);?></select>
            </p>
             <p>
                <label for="<?php echo $this->get_field_id('c_scroll'); ?>"><?php _e('Scroll Setting', 'virtue'); ?></label><br />
                <select id="<?php echo $this->get_field_id('c_scroll'); ?>" style="width:100%; max-width:230px;" name="<?php echo $this->get_field_name('c_scroll'); ?>"><?php echo implode('', $carousel_scroll_array);?></select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('c_items'); ?>"><?php _e('Items (e.g. = 8)', 'virtue'); ?></label><br />
                <input type="text" class="widefat kad_img_widget_link" name="<?php echo $this->get_field_name('c_items'); ?>" id="<?php echo $this->get_field_id('c_items'); ?>" value="<?php echo $c_items; ?>">
            </p>
             <p>
                <label for="<?php echo $this->get_field_id('c_order'); ?>"><?php _e('Order by', 'virtue'); ?></label><br />
                <select id="<?php echo $this->get_field_id('c_order'); ?>" style="width:100%; max-width:230px;" name="<?php echo $this->get_field_name('c_order'); ?>"><?php echo implode('', $carousel_order_array);?></select>
            </p>
             <p>
                <label for="<?php echo $this->get_field_id('postcat'); ?>"><?php _e('Blog Post Category', 'virtue'); ?></label><br />
                <select id="<?php echo $this->get_field_id('postcat'); ?>" style="width:100%; max-width:230px;" name="<?php echo $this->get_field_name('postcat'); ?>"><?php echo implode('', $cat_options);?></select>
            </p>
             <p>
                <label for="<?php echo $this->get_field_id('portfoliocat'); ?>"><?php _e('Portfolio Category', 'virtue'); ?></label><br />
                <select id="<?php echo $this->get_field_id('portfoliocat'); ?>" style="width:100%; max-width:230px;" name="<?php echo $this->get_field_name('portfoliocat'); ?>"><?php echo implode('', $type_options);?></select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('productcat'); ?>"><?php _e('Product Category', 'virtue'); ?></label><br />
                <select id="<?php echo $this->get_field_id('productcat'); ?>" style="width:100%; max-width:230px;" name="<?php echo $this->get_field_name('productcat'); ?>"><?php echo implode('', $product_options);?></select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('c_speed'); ?>"><?php _e('Carousel Speed (e.g. = 7000)', 'virtue'); ?></label><br />
                <input type="text" class="widefat kad_img_widget_link" name="<?php echo $this->get_field_name('c_speed'); ?>" id="<?php echo $this->get_field_id('c_speed'); ?>" value="<?php echo $c_speed; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('autoplay'); ?>"><?php _e('Auto Play?', 'virtue'); ?></label><br />
                <select id="<?php echo $this->get_field_id('autoplay'); ?>" style="width:100%; max-width:230px;" name="<?php echo $this->get_field_name('autoplay'); ?>"><?php echo implode('', $autoplay_options);?></select>
            </p>
    </div>

<?php } }
class kad_infobox_widget extends WP_Widget{

private static $instance = 0;
    public function __construct() {
        $widget_ops = array('classname' => 'virtue_infobox_widget', 'description' => __('Adds a info box with icon options', 'virtue'));
        parent::__construct('virtue_infobox_widget', __('Virtue: Info Box', 'virtue'), $widget_ops);
    }

       public function widget($args, $instance){ 
        extract( $args );
        //title
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        if(!empty($title)) { $title = '<h4>'.$title.'</h4>';} else {$title = '';}
        //description & link
        if(!empty($instance['description'])) { $description = $instance['description'];} else {$description = '';}
        if(!empty($instance['link'])) {$link = $instance["link"];} else {$link = '';}
        if(!empty($description)) {$description = '<p>'.$description.'</p>';} else {$description = '';}
        if(!empty($link)) { $link = 'link='.$link; } else {$link = '';}

        if(!empty($instance['image_uri'])) {$imglink = esc_url($instance['image_uri']);} else {$imglink = '';}
        if(!empty($instance["info_icon"])) {$icon = 'icon='.$instance["info_icon"];} else {$icon = '';}
        if(!empty($instance["background"])) {$info_background = 'background='.$instance["background"];} else {$info_background = '';}
        if(!empty($instance["iconbackground"])) {$icon_background = 'iconbackground='.$instance["iconbackground"];} else {$icon_background = '';}
        if(!empty($instance["size"])) {$info_size = 'size='.$instance["size"];} else {$info_size = 'size=48';}
        if(!empty($instance["style"])) { $style = 'style='.$instance["style"]; } else {$style = '';}
        if(!empty($instance["color"])) { $color = 'color='.$instance["color"]; } else {$color = '';}
        if(!empty($instance["tcolor"])) { $tcolor = 'tcolor='.$instance["tcolor"]; } else {$tcolor = '';}
        if(!empty($instance["target"])) { $target = 'target='.$instance["target"]; } else {$target = '';}
        if(!empty($imglink)) {$info_icon = 'image='.$imglink;} else {$info_icon = $icon;}
        if(!empty($instance['image_id'])) {
          $alt = 'alt="'.esc_attr( get_post_meta($instance['image_id'], '_wp_attachment_image_alt', true) ).'"';
        } else {
          $alt = '';
        }

            ?>


          <?php echo $before_widget;
           echo do_shortcode('[infobox '.$link.' '.$target.' '.$info_icon.' '.$tcolor.' '.$info_size.' '.$info_background.' '.$alt.' '.$style .' '.$icon_background.' '.$color.'] '.$title.' '. $description.'[/infobox]');
           echo $after_widget;?>

    <?php }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['info_icon'] = $new_instance['info_icon'];
        $instance['image_uri'] = strip_tags( $new_instance['image_uri'] );
        $instance['background'] = strip_tags( $new_instance['background'] );
        $instance['iconbackground'] = strip_tags($new_instance['iconbackground'] );
        $instance['color'] = strip_tags( $new_instance['color'] );
        $instance['tcolor'] = strip_tags( $new_instance['tcolor'] );
        $instance['size'] = (int) $new_instance['size']; 
        $instance['style'] = $new_instance['style'];
        $instance['target'] = $new_instance['target'];
        $instance['description'] = $new_instance['description'];
        $instance['title'] = $new_instance['title'];
        $instance['image_id'] = $new_instance['image_id'];
        $instance['link'] = $new_instance['link'];
        if ( function_exists( 'icl_register_string' ) ) {
            icl_register_string( 'Widgets', 'info_box_description_' . $this->id_base, $instance['description'] );
            icl_register_string( 'Widgets', 'info_box_link_' . $this->id_base, $instance['link'] ) ;
        }
        return $instance;
    }

  public function form($instance){ 
    $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
    $link = isset($instance['link']) ? esc_attr($instance['link']) : '';
    $background = isset($instance['background']) ? esc_attr($instance['background']) : '';
    $iconbackground = isset($instance['iconbackground']) ? esc_attr($instance['iconbackground']) : '';
    $color = isset($instance['color']) ? esc_attr($instance['color']) : '';
    $tcolor = isset($instance['tcolor']) ? esc_attr($instance['tcolor']) : '';
    $size = isset($instance['size']) ? esc_attr($instance['size']) : '';
    if (isset($instance['target'])) { $target = esc_attr($instance['target']); } else {$target = '_self';}
    if (isset($instance['info_icon'])) { $info_icon = esc_attr($instance['info_icon']); } else {$info_icon = '';}
    $image_uri = isset($instance['image_uri']) ? esc_attr($instance['image_uri']) : '';
    $image_id = isset($instance['image_id']) ? esc_attr($instance['image_id']) : '';
    if (isset($instance['style'])) { $style = esc_attr($instance['style']); } else {$style = 'none';}
    $icon_style_array = array();
    $icon_array = array();
    $target_options = array(array("slug" => "_self", "name" => __('Self', 'virtue')), array("slug" => "_blank", "name" => __('New Window', 'virtue')));
    foreach ($target_options as $target_option) {
      if ($target == $target_option['slug']) { $selected=' selected="selected"';} else { $selected=""; }
      $target_array[] = '<option value="' . $target_option['slug'] .'"' . $selected . '>' . $target_option['name'] . '</option>';
    }
    $icon_style_options = array(array("slug" => "none", "name" => __('None', 'virtue')), array("slug" => "kad-circle-iconclass", "name" => __('Circle', 'virtue')), array("slug" => "kad-square-iconclass", "name" => __('Square', 'virtue')));
    $icons = kad_icon_list();
    foreach ($icons as $icon) {
      if ($info_icon == $icon) { $selected=' selected="selected"';} else { $selected=""; }
      $icon_array[] = '<option value="' . $icon .'"' . $selected . '>' . $icon . '</option>';
    }
    foreach ($icon_style_options as $icon_style_option) {
      if ($style == $icon_style_option['slug']) { $selected=' selected="selected"';} else { $selected=""; }
      $icon_style_array[] = '<option value="' . $icon_style_option['slug'] .'"' . $selected . '>' . $icon_style_option['name'] . '</option>';
    }
    ?>  

    <div id="virtue_infobox_widget<?php echo esc_attr($this->get_field_id('container')); ?>" class="kad_img_upload_widget kad_infobox_widget">
            <p>
                <label for="<?php echo $this->get_field_id('info_icon'); ?>"><?php _e('Choose an Icon', 'virtue'); ?></label><br />
                <select id="<?php echo $this->get_field_id('info_icon'); ?>" class="kad_icomoon" name="<?php echo $this->get_field_name('info_icon'); ?>"><?php echo implode('', $icon_array);?></select>
            </p>
            <p>
            <img class="kad_custom_media_image" src="<?php if(!empty($instance['image_uri'])){echo $instance['image_uri'];} ?>" style="margin:0;padding:0;max-width:100px;display:block" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('image_uri'); ?>"><?php _e('Or upload a custom icon', 'virtue'); ?></label><br />
                <input type="text" class="widefat kad_custom_media_url" name="<?php echo $this->get_field_name('image_uri'); ?>" id="<?php echo $this->get_field_id('image_uri'); ?>" value="<?php echo $image_uri; ?>">
                <input type="hidden" value="<?php echo $image_id; ?>" class="kad_custom_media_id" name="<?php echo $this->get_field_name('image_id'); ?>" id="<?php echo $this->get_field_id('image_id'); ?>" />
                <input type="button" value="<?php _e('Upload', 'virtue'); ?>" class="button kad_custom_media_upload" id="kad_custom_image_uploader" />
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'virtue'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
             <p>
              <label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description', 'virtue'); ?></label><br />
              <textarea name="<?php echo $this->get_field_name('description'); ?>" style="min-height: 100px;" id="<?php echo $this->get_field_id('description'); ?>" class="widefat" ><?php if(!empty($instance['description'])) echo $instance['description']; ?></textarea>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('background'); ?>"><?php _e('Box Background Color (e.g. = #f2f2f2)', 'virtue'); ?></label><br />
                <input type="text" class="widefat kad-widget-colorpicker" style="width: 70px;"  name="<?php echo $this->get_field_name('background'); ?>" id="<?php echo $this->get_field_id('background'); ?>" value="<?php echo $background; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('tcolor'); ?>"><?php _e('Text Color (e.g. = #444444)', 'virtue'); ?></label><br />
                <input type="text" class="widefat kad-widget-colorpicker" style="width: 70px;"  name="<?php echo $this->get_field_name('tcolor'); ?>" id="<?php echo $this->get_field_id('tcolor'); ?>" value="<?php echo $tcolor; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('Icon Size (e.g. = 48)', 'virtue'); ?></label><br />
                <input type="number" class="widefat kad_img_widget_link" name="<?php echo $this->get_field_name('size'); ?>" id="<?php echo $this->get_field_id('size'); ?>" value="<?php echo $size; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('style'); ?>"><?php _e('Icon Style', 'virtue'); ?></label><br />
                <select id="<?php echo $this->get_field_id('style'); ?>" style="width:100%; max-width:230px;" name="<?php echo $this->get_field_name('style'); ?>"><?php echo implode('', $icon_style_array);?></select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('iconbackground'); ?>"><?php _e('Icon Background Color (e.g. = #444444)', 'virtue'); ?></label><br />
                <input type="text" class="widefat kad-widget-colorpicker" style="width: 70px;"  name="<?php echo $this->get_field_name('iconbackground'); ?>" id="<?php echo $this->get_field_id('iconbackground'); ?>" value="<?php echo $iconbackground; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('color'); ?>"><?php _e('Icon Color (e.g. = #f2f2f2)', 'virtue'); ?></label><br />
                <input type="text" class="widefat kad-widget-colorpicker" style="width: 70px;"  name="<?php echo $this->get_field_name('color'); ?>" id="<?php echo $this->get_field_id('color'); ?>" value="<?php echo $color; ?>">
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link:', 'virtue'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo $link; ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('target'); ?>"><?php _e('Link Target', 'virtue'); ?></label><br />
                <select id="<?php echo $this->get_field_id('target'); ?>" style="width:100%; max-width:230px;" name="<?php echo $this->get_field_name('target'); ?>"><?php echo implode('', $target_array);?></select>
            </p>

    </div>

<?php } }

class kad_gmap_widget extends WP_Widget{

private static $instance = 0;
    public function __construct() {
        $widget_ops = array('classname' => 'virtue_gmap_widget', 'description' => __('Adds a google map to a widget area', 'virtue'));
        parent::__construct('virtue_gmap_widget', __('Virtue: Google Map', 'virtue'), $widget_ops);
    }

       public function widget($args, $instance){ 
        extract( $args ); 
        if(!empty($instance["location"])) {$location = $instance["location"];} else {$location = '';}
        if(!empty($instance["locationtitle"])) {$locationtitle = $instance["locationtitle"];} else {$locationtitle = '';}
        if(!empty($instance["location2"])) {$location2 = 'address2="'.$instance["location2"].'"';} else {$location2 = '';}
        if(!empty($instance["locationtitle2"])) {$locationtitle2 = 'title2="'.$instance["locationtitle2"].'"';} else {$locationtitle2 = '';}
        if(!empty($instance["location3"])) {$location3 = 'address3="'.$instance["location3"].'"';} else {$location3 = '';}
        if(!empty($instance["locationtitle3"])) {$locationtitle3 = 'title3="'.$instance["locationtitle3"].'"';} else {$locationtitle3 = '';}
        if(!empty($instance["location4"])) {$location4 = 'address4="'.$instance["location4"].'"';} else {$location4 = '';}
        if(!empty($instance["locationtitle4"])) {$locationtitle4 = 'title4="'.$instance["locationtitle4"].'"';} else {$locationtitle4 = '';}
        if(!empty($instance["center"])) {$center = 'center="'.$instance["center"].'"';} else {$center = '';}
        if(!empty($instance['height'])) {$height = 'height="'.esc_attr($instance['height']).'"';} else {$height = '';}
        if(!empty($instance["maptype"])) {$maptype = 'maptype='.$instance["maptype"];} else {$maptype = '';}
        if(!empty($instance["zoom"])) {$zoom = 'zoom='.$instance["zoom"];} else {$zoom = '';}
        if(!empty($instance["loadscripts"])) {$loadscripts = 'loadscripts='.$instance["loadscripts"];} else {$loadscripts = '';}
            ?>


          <?php echo $before_widget;
           echo do_shortcode('[gmap address="'.$location.'" title="'.$locationtitle.'" '.$height.' '.$maptype.' '.$zoom. ' '.$location2.' '.$location3.' '.$location4.' '.$center.' '.$locationtitle2.' '.$locationtitle3.' '.$locationtitle4.' '.$loadscripts.']');
           echo $after_widget;?>

    <?php }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['locationtitle'] = strip_tags( $new_instance['locationtitle'] );
        $instance['location'] = $new_instance['location'];
        $instance['locationtitle2'] = strip_tags( $new_instance['locationtitle2'] );
        $instance['location2'] = $new_instance['location2'];
        $instance['locationtitle3'] = strip_tags( $new_instance['locationtitle3'] );
        $instance['location3'] = $new_instance['location3'];
        $instance['locationtitle4'] = strip_tags( $new_instance['locationtitle4'] );
        $instance['location4'] = $new_instance['location4'];
        $instance['center'] = $new_instance['center'];
        $instance['height'] = (int) $new_instance['height'];
        $instance['maptype'] = $new_instance['maptype']; 
        $instance['zoom'] = $new_instance['zoom'];
        $instance['loadscripts'] = $new_instance['loadscripts'];
        return $instance;
    }

  public function form($instance){
    $locationtitle = isset($instance['locationtitle']) ? esc_attr($instance['locationtitle']) : '';
    $locationtitle2 = isset($instance['locationtitle2']) ? esc_attr($instance['locationtitle2']) : '';
    $locationtitle3 = isset($instance['locationtitle3']) ? esc_attr($instance['locationtitle3']) : '';
    $locationtitle4 = isset($instance['locationtitle4']) ? esc_attr($instance['locationtitle4']) : '';
    $height = isset($instance['height']) ? esc_attr($instance['height']) : '';
    if (isset($instance['zoom'])) { $zoom = esc_attr($instance['zoom']); } else {$zoom = '15';}
    if (isset($instance['loadscripts'])) { $loadscripts = esc_attr($instance['loadscripts']); } else {$loadscripts = "true";}
    if (isset($instance['maptype'])) { $maptype = esc_attr($instance['maptype']); } else {$maptype = 'ROADMAP';}
    $map_type_array = array();
    $zoom_array = array();
    $loadscripts_array = array();
    $loadscripts_options = array(array("slug" => "true", "name" => __('True', 'virtue')), array("slug" => "false", "name" => __('False', 'virtue')));
    $map_type_options = array(array("slug" => "ROADMAP", "name" => __('ROADMAP', 'virtue')), array("slug" => "HYBRID", "name" => __('HYBRID', 'virtue')), array("slug" => "TERRAIN", "name" => __('TERRAIN', 'virtue')), array("slug" => "SATELLITE", "name" => __('SATELLITE', 'virtue')));
    $zoom_options = array(array("slug" => "1"), array("slug" => "2"), array("slug" => "3"), array("slug" => "4"), array("slug" => "5"), array("slug" => "6"), array("slug" => "7"), array("slug" => "8"), array("slug" => "9"), array("slug" => "10"), array("slug" => "11"), array("slug" => "12"), array("slug" => "13"), array("slug" => "14"), array("slug" => "15"), array("slug" => "16"), array("slug" => "17"), array("slug" => "18"), array("slug" => "19"), array("slug" => "20"), array("slug" => "21"));
    foreach ($zoom_options as $zoom_option) {
      if ($zoom == $zoom_option['slug']) { $selected=' selected="selected"';} else { $selected=""; }
      $zoom_array[] = '<option value="' . $zoom_option['slug'] .'"' . $selected . '>' . $zoom_option['slug'] . '</option>';
    }
    foreach ($map_type_options as $map_type_option) {
      if ($maptype == $map_type_option['slug']) { $selected=' selected="selected"';} else { $selected=""; }
      $map_type_array[] = '<option value="' . $map_type_option['slug'] .'"' . $selected . '>' . $map_type_option['name'] . '</option>';
    }
    foreach ($loadscripts_options as $loadscripts_option) {
      if ($loadscripts == $loadscripts_option['slug']) { $selected=' selected="selected"';} else { $selected=""; }
      $loadscripts_array[] = '<option value="' . $loadscripts_option['slug'] .'"' . $selected . '>' . $loadscripts_option['name'] . '</option>';
    }
    ?>  

    <div id="virtue_gmap_widget<?php echo esc_attr($this->get_field_id('container')); ?>" class="kad_gmap_widget">
            <p>
            <label for="<?php echo $this->get_field_id('locationtitle'); ?>"><?php _e('Marker Title:', 'virtue'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('locationtitle'); ?>" name="<?php echo $this->get_field_name('locationtitle'); ?>" type="text" value="<?php echo $locationtitle; ?>" />
            </p>
            <p>
              <label for="<?php echo $this->get_field_id('location'); ?>"><?php _e('Marker Address', 'virtue'); ?></label><br />
              <textarea name="<?php echo $this->get_field_name('location'); ?>" style="min-height: 50px;" id="<?php echo $this->get_field_id('location'); ?>" class="widefat" ><?php if(!empty($instance['location'])) echo $instance['location']; ?></textarea>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('maptype'); ?>"><?php _e('Map Type', 'virtue'); ?></label><br />
                <select id="<?php echo $this->get_field_id('maptype'); ?>" name="<?php echo $this->get_field_name('maptype'); ?>"><?php echo implode('', $map_type_array);?></select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('zoom'); ?>"><?php _e('Map Zoom', 'virtue'); ?></label><br />
                <select id="<?php echo $this->get_field_id('zoom'); ?>" style="width:100%; max-width:230px;" name="<?php echo $this->get_field_name('zoom'); ?>"><?php echo implode('', $zoom_array);?></select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Map Height (e.g. = 300)', 'virtue'); ?></label><br />
                <input type="text" class="widefat kad_map_widget_height" name="<?php echo $this->get_field_name('height'); ?>" id="<?php echo $this->get_field_id('height'); ?>" value="<?php echo $height; ?>">
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('locationtitle2'); ?>"><?php _e('Marker Title Two:', 'virtue'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('locationtitle2'); ?>" name="<?php echo $this->get_field_name('locationtitle2'); ?>" type="text" value="<?php echo $locationtitle2; ?>" />
            </p>
            <p>
              <label for="<?php echo $this->get_field_id('location2'); ?>"><?php _e('Marker Address Two', 'virtue'); ?></label><br />
              <textarea name="<?php echo $this->get_field_name('location2'); ?>" style="min-height: 50px;" id="<?php echo $this->get_field_id('location2'); ?>" class="widefat" ><?php if(!empty($instance['location2'])) echo $instance['location2']; ?></textarea>
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('locationtitle3'); ?>"><?php _e('Marker Title Three:', 'virtue'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('locationtitle3'); ?>" name="<?php echo $this->get_field_name('locationtitle3'); ?>" type="text" value="<?php echo $locationtitle3; ?>" />
            </p>
            <p>
              <label for="<?php echo $this->get_field_id('location3'); ?>"><?php _e('Marker Address Three', 'virtue'); ?></label><br />
              <textarea name="<?php echo $this->get_field_name('location3'); ?>" style="min-height: 50px;" id="<?php echo $this->get_field_id('location3'); ?>" class="widefat" ><?php if(!empty($instance['location3'])) echo $instance['location3']; ?></textarea>
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('locationtitle4'); ?>"><?php _e('Marker Title Four:', 'virtue'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('locationtitle4'); ?>" name="<?php echo $this->get_field_name('locationtitle4'); ?>" type="text" value="<?php echo $locationtitle4; ?>" />
            </p>
            <p>
              <label for="<?php echo $this->get_field_id('location4'); ?>"><?php _e('Marker Address Four', 'virtue'); ?></label><br />
              <textarea name="<?php echo $this->get_field_name('location4'); ?>" style="min-height: 50px;" id="<?php echo $this->get_field_id('location4'); ?>" class="widefat" ><?php if(!empty($instance['location4'])) echo $instance['location4']; ?></textarea>
            </p>
            <p>
              <label for="<?php echo $this->get_field_id('center'); ?>"><?php _e('Map Center (defauts to first address)', 'virtue'); ?></label><br />
              <textarea name="<?php echo $this->get_field_name('center'); ?>" style="min-height: 50px;" id="<?php echo $this->get_field_id('center'); ?>" class="widefat" ><?php if(!empty($instance['center'])) echo $instance['center']; ?></textarea>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('loadscripts'); ?>"><?php _e('Load Core Google Scripts? (Only turn this off if this is the second+ map on a page)', 'virtue'); ?></label><br />
                <select id="<?php echo $this->get_field_id('loadscripts'); ?>" style="width:100%; max-width:230px;" name="<?php echo $this->get_field_name('loadscripts'); ?>"><?php echo implode('', $loadscripts_array);?></select>
            </p>
    </div>

<?php } }

class kad_calltoaction_widget extends WP_Widget{

private static $instance = 0;
    public function __construct() {
        $widget_ops = array('classname' => 'virtue_calltoaction_widget', 'description' => __('Adds a simple call to action', 'virtue'));
        parent::__construct('virtue_calltoaction_widget', __('Virtue: Call to Action', 'virtue'), $widget_ops);
    }

       public function widget($args, $instance){ 
        extract( $args );
        //title
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        if(!empty($title)) {$title = $title;} else {$title = '';}
        //description & btn_link
        if(!empty($instance["subtitle"])) { $subtitle = $instance['subtitle'];} else {$subtitle = '';}
        if(!empty($instance["btn_link"])) {$btn_link = $instance["btn_link"];} else {$btn_link = '';}
        if(!empty($instance["btn_text"])) {$btn_text = $instance["btn_text"];} else {$btn_text = '';}
        if(!empty($instance["btn_target"])) {$btn_target = $instance["btn_target"];} else {$btn_target = 'false';}
        if(!empty($instance["tsize"])) {$tsize = 'font-size:'.$instance["tsize"].'px;';} else {$tsize = '';}
        if(!empty($instance["ssize"])) {$ssize = 'font-size:'.$instance["ssize"].'px;';} else {$ssize = '';}
        if(!empty($instance["tlineheight"])) {$tlineheight = 'line-height:'.$instance["tlineheight"].'px;';} else {$tlineheight = '';}
        if(!empty($instance["slineheight"])) {$slineheight = 'line-height:'.$instance["slineheight"].'px;';} else {$slineheight = '';}
        if(!empty($instance["align"])) { $align = $instance["align"];} else {$align = 'center';}
        if(!empty($instance["tcolor"])) { $tcolor = 'color:'.$instance["tcolor"].';'; } else {$tcolor = '';}
        if(!empty($instance["scolor"])) { $scolor = 'color:'.$instance["scolor"].';'; } else {$scolor = '';}
        if(!empty($instance["btn_color"])) { $btn_color = 'tcolor="'.$instance["btn_color"].'"'; } else {$btn_color = '';}
        if(!empty($instance["btn_background"])) { $btn_background = 'bcolor="'.$instance["btn_background"].'"'; } else {$btn_background = '';}
        if(!empty($instance["btn_border_color"])) { $btn_border_color = 'bordercolor="'.$instance["btn_border_color"].'"'; } else {$btn_border_color = '';}
        if(!empty($instance["btn_hover_color"])) { $btn_hover_color = 'thovercolor="'.$instance["btn_hover_color"].'"'; } else {$btn_hover_color = '';}
        if(!empty($instance["btn_hover_background"])) { $btn_hover_background = 'bhovercolor="'.$instance["btn_hover_background"].'"'; } else {$btn_hover_background = '';}
        if(!empty($instance["btn_hover_border_color"])) { $btn_hover_border_color = 'borderhovercolor="'.$instance["btn_hover_border_color"].'"'; } else {$btn_hover_border_color = '';}
        if(!empty($instance["btn_border"])) { $btn_border = 'border="'.$instance["btn_border"].'"'; } else {$btn_border = '';}
        if(!empty($instance["btn_border_radius"])) { $btn_border_radius = 'borderradius="'.$instance["btn_border_radius"].'"'; } else {$btn_border_radius = '';}
        if(!empty($instance["title_html_tag"])) { $title_html_tag = $instance["title_html_tag"]; } else {$title_html_tag = 'h1';}
            ?>


          <?php echo $before_widget;
            echo '<'.$title_html_tag.' style="'.$tcolor.' '.$tsize.' '.$tlineheight.' text-align:'.$align.';">'.$title.'</'.$title_html_tag.'>';
            if($subtitle) { echo '<h5 style="'.$scolor.' '.$ssize.' '.$slineheight.' text-align:'.$align.';">'.$subtitle.'</h5>'; }
            echo '<div style="text-align:'.$align.'">';
            echo do_shortcode('[btn text="'.$btn_text.'" '.$btn_color.' '.$btn_background.' '.$btn_border_color.' '.$btn_hover_color.' '.$btn_hover_background.' '.$btn_hover_border_color.' '.$btn_border.' '.$btn_border_radius.' link="'.$btn_link.'" size="large" target="'.$btn_target.'" font="h1-family"]');
            echo '</div>';
           echo $after_widget;?>

    <?php }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['align'] = $new_instance['align'];
        $instance['btn_target'] = $new_instance['btn_target'];
        $instance['btn_link'] = strip_tags( $new_instance['btn_link'] );
        $instance['btn_text'] = strip_tags( $new_instance['btn_text'] );
        $instance['btn_color'] = strip_tags( $new_instance['btn_color'] );
        $instance['btn_background'] = strip_tags( $new_instance['btn_background'] );
        $instance['btn_border_color'] = strip_tags( $new_instance['btn_border_color'] );
        $instance['btn_border'] = strip_tags( $new_instance['btn_border'] );
        $instance['btn_border_radius'] = strip_tags( $new_instance['btn_border_radius'] );
        $instance['btn_hover_border_color'] = strip_tags( $new_instance['btn_hover_border_color'] );
        $instance['btn_hover_color'] = strip_tags( $new_instance['btn_hover_color'] );
        $instance['btn_hover_background'] = strip_tags( $new_instance['btn_hover_background'] );
        $instance['tcolor'] = strip_tags( $new_instance['tcolor'] );
        $instance['scolor'] = strip_tags( $new_instance['scolor'] );
        $instance['tsize'] = (int) $new_instance['tsize'];
        $instance['ssize'] = (int) $new_instance['ssize']; 
        $instance['tlineheight'] = (int) $new_instance['tlineheight'];
        $instance['slineheight'] = (int) $new_instance['slineheight']; 
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['subtitle'] = strip_tags( $new_instance['subtitle'] );
        $instance['title_html_tag'] = strip_tags( $new_instance['title_html_tag'] );
        return $instance;
    }

  public function form($instance){ 
    $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
    $subtitle = isset($instance['subtitle']) ? esc_attr($instance['subtitle']) : '';
    $tcolor = isset($instance['tcolor']) ? esc_attr($instance['tcolor']) : '';
    $scolor = isset($instance['scolor']) ? esc_attr($instance['scolor']) : '';
    $tsize = isset($instance['tsize']) ? esc_attr($instance['tsize']) : '';
    $ssize = isset($instance['ssize']) ? esc_attr($instance['ssize']) : '';
    $tlineheight = isset($instance['tlineheight']) ? esc_attr($instance['tlineheight']) : '';
    $slineheight = isset($instance['slineheight']) ? esc_attr($instance['slineheight']) : '';
    $btn_link = isset($instance['btn_link']) ? esc_attr($instance['btn_link']) : '';
    $btn_text = isset($instance['btn_text']) ? esc_attr($instance['btn_text']) : '';
    $btn_color = isset($instance['btn_color']) ? esc_attr($instance['btn_color']) : '';
    $btn_background = isset($instance['btn_background']) ? esc_attr($instance['btn_background']) : '';
    $btn_border = isset($instance['btn_border']) ? esc_attr($instance['btn_border']) : '';
    $btn_border_radius = isset($instance['btn_border_radius']) ? esc_attr($instance['btn_border_radius']) : '';
    $btn_border_color = isset($instance['btn_border_color']) ? esc_attr($instance['btn_border_color']) : '';
    $btn_hover_color = isset($instance['btn_hover_color']) ? esc_attr($instance['btn_hover_color']) : '';
    $btn_hover_background = isset($instance['btn_hover_background']) ? esc_attr($instance['btn_hover_background']) : '';
    $btn_hover_border_color = isset($instance['btn_hover_border_color']) ? esc_attr($instance['btn_hover_border_color']) : '';
    $title_html_tag = isset($instance['title_html_tag']) ? esc_attr($instance['title_html_tag']) : 'h1';
    if (isset($instance['align'])) { $align = esc_attr($instance['align']); } else {$align = 'center';}
    if (isset($instance['btn_target'])) { $btn_target = esc_attr($instance['btn_target']); } else {$btn_target = 'false';}
    $align_array = array();
    $btn_target_array = array();
    $html_tag_array = array();
    $html_tag_options = array(array("slug" => "h1", "name" => __('h1', 'virtue')), array("slug" => "h2", "name" => __('h2', 'virtue')), array("slug" => "h3", "name" => __('h3', 'virtue')));
    $align_options = array(array("slug" => "center", "name" => __('Center', 'virtue')), array("slug" => "left", "name" => __('Left', 'virtue')), array("slug" => "right", "name" => __('Right', 'virtue')));
    $btn_target_options = array(array("slug" => "false", "name" => __('Self', 'virtue')), array("slug" => "true", "name" => __('New Window', 'virtue')));
    foreach ($html_tag_options as $html_tag_option) {
      if ($title_html_tag == $html_tag_option['slug']) { $selected=' selected="selected"';} else { $selected=""; }
      $html_tag_array[] = '<option value="' . $html_tag_option['slug'] .'"' . $selected . '>' . $html_tag_option['name'] . '</option>';
    }
    foreach ($align_options as $align_option) {
      if ($align == $align_option['slug']) { $selected=' selected="selected"';} else { $selected=""; }
      $align_array[] = '<option value="' . $align_option['slug'] .'"' . $selected . '>' . $align_option['name'] . '</option>';
    }
    foreach ($btn_target_options as $btn_target_option) {
      if ($btn_target == $btn_target_option['slug']) { $selected=' selected="selected"';} else { $selected=""; }
      $btn_target_array[] = '<option value="' . $btn_target_option['slug'] .'"' . $selected . '>' . $btn_target_option['name'] . '</option>';
    }
    ?>  

    <div id="virtue_calltoaction_widget<?php echo esc_attr($this->get_field_id('container')); ?>" class="kad_calltoaction_widget kad-colorpick">
            <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'virtue'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('tsize'); ?>"><?php _e('Title Size (e.g. = 48)', 'virtue'); ?></label><br />
                <input type="number" class="widefat kad_img_widget_link" name="<?php echo $this->get_field_name('tsize'); ?>" id="<?php echo $this->get_field_id('tsize'); ?>" style="width: 70px;" value="<?php echo $tsize; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('tlineheight'); ?>"><?php _e('Title Line height (e.g. = 48)', 'virtue'); ?></label><br />
                <input type="number" class="widefat kad_img_widget_link" name="<?php echo $this->get_field_name('tlineheight'); ?>" id="<?php echo $this->get_field_id('tlineheight'); ?>" style="width: 70px;" value="<?php echo $tlineheight; ?>">
            </p>
             <p>
                <label for="<?php echo $this->get_field_id('tcolor'); ?>"><?php _e('Title Color (e.g. = #f2f2f2)', 'virtue'); ?></label><br />
                <input type="text" class="kad-widget-colorpicker" name="<?php echo $this->get_field_name('tcolor'); ?>" id="<?php echo $this->get_field_id('tcolor'); ?>" style="width: 70px;" value="<?php echo $tcolor; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('title_html_tag'); ?>"><?php _e('Title html Tag (e.g. = h1)', 'virtue'); ?></label><br />
                 <select id="<?php echo $this->get_field_id('title_html_tag'); ?>" style="width:100%; max-width:230px;" name="<?php echo $this->get_field_name('title_html_tag'); ?>"><?php echo implode('', $html_tag_array);?></select>
            </p>
             <p>
              <label for="<?php echo $this->get_field_id('subtitle'); ?>"><?php _e('Subtitle', 'virtue'); ?></label><br />
              <textarea name="<?php echo $this->get_field_name('subtitle'); ?>" style="min-height: 50px;" id="<?php echo $this->get_field_id('subtitle'); ?>" class="widefat" ><?php echo $subtitle; ?></textarea>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('ssize'); ?>"><?php _e('Subtitle Size (e.g. = 48)', 'virtue'); ?></label><br />
                <input type="number" class="widefat kad_img_widget_link" name="<?php echo $this->get_field_name('ssize'); ?>" id="<?php echo $this->get_field_id('ssize'); ?>" style="width: 70px;" value="<?php echo $ssize; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('slineheight'); ?>"><?php _e('Subtitle Line Height (e.g. = 48)', 'virtue'); ?></label><br />
                <input type="number" class="widefat kad_img_widget_link" name="<?php echo $this->get_field_name('slineheight'); ?>" id="<?php echo $this->get_field_id('slineheight'); ?>" style="width: 70px;" value="<?php echo $slineheight; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('scolor'); ?>"><?php _e('Subtitle Color (e.g. = #f2f2f2)', 'virtue'); ?></label><br />
                <input type="text" class="widefat kad-widget-colorpicker" name="<?php echo $this->get_field_name('scolor'); ?>" id="<?php echo $this->get_field_id('scolor'); ?>" value="<?php echo $scolor; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('align'); ?>"><?php _e('Align', 'virtue'); ?></label><br />
                <select id="<?php echo $this->get_field_id('align'); ?>" style="width:100%; max-width:230px;" name="<?php echo $this->get_field_name('align'); ?>"><?php echo implode('', $align_array);?></select>
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('btn_text'); ?>"><?php _e('Button Text:', 'virtue'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('btn_text'); ?>" name="<?php echo $this->get_field_name('btn_text'); ?>" type="text" value="<?php echo $btn_text; ?>" />
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('btn_link'); ?>"><?php _e('Button Link:', 'virtue'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('btn_link'); ?>" name="<?php echo $this->get_field_name('btn_link'); ?>" type="text" value="<?php echo $btn_link; ?>" />
            </p>
             <p>
                <label for="<?php echo $this->get_field_id('btn_target'); ?>"><?php _e('Link Target', 'virtue'); ?></label><br />
                <select id="<?php echo $this->get_field_id('btn_target'); ?>" style="width:100%; max-width:230px;" name="<?php echo $this->get_field_name('btn_target'); ?>"><?php echo implode('', $btn_target_array);?></select>
            </p>
             <p>
                <label for="<?php echo $this->get_field_id('btn_color'); ?>"><?php _e('Button Color', 'virtue'); ?></label><br />
                <input type="text" class="kad-widget-colorpicker" name="<?php echo $this->get_field_name('btn_color'); ?>" id="<?php echo $this->get_field_id('btn_color'); ?>" style="width: 70px;" value="<?php echo $btn_color; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('btn_background'); ?>"><?php _e('Button Background', 'virtue'); ?></label><br />
                <input type="text" class="kad-widget-colorpicker" name="<?php echo $this->get_field_name('btn_background'); ?>" id="<?php echo $this->get_field_id('btn_background'); ?>" style="width: 70px;" value="<?php echo $btn_background; ?>">
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('btn_border'); ?>"><?php _e('Button Border Size (e.g. = 2px)', 'virtue'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('btn_border'); ?>" name="<?php echo $this->get_field_name('btn_border'); ?>" type="text" value="<?php echo $btn_border; ?>" />
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('btn_border_radius'); ?>"><?php _e('Button Border Radius (e.g. = 6px)', 'virtue'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('btn_border_radius'); ?>" name="<?php echo $this->get_field_name('btn_border_radius'); ?>" type="text" value="<?php echo $btn_border_radius; ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('btn_border_color'); ?>"><?php _e('Button Border Color', 'virtue'); ?></label><br />
                <input type="text" class="kad-widget-colorpicker" name="<?php echo $this->get_field_name('btn_border_color'); ?>" id="<?php echo $this->get_field_id('btn_border_color'); ?>" style="width: 70px;" value="<?php echo $btn_border_color; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('btn_hover_color'); ?>"><?php _e('Button Hover Color', 'virtue'); ?></label><br />
                <input type="text" class="kad-widget-colorpicker" name="<?php echo $this->get_field_name('btn_hover_color'); ?>" id="<?php echo $this->get_field_id('btn_hover_color'); ?>" style="width: 70px;" value="<?php echo $btn_hover_color; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('btn_hover_background'); ?>"><?php _e('Button Hover Background', 'virtue'); ?></label><br />
                <input type="text" class="kad-widget-colorpicker" name="<?php echo $this->get_field_name('btn_hover_background'); ?>" id="<?php echo $this->get_field_id('btn_hover_background'); ?>" style="width: 70px;" value="<?php echo $btn_hover_background; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('btn_hover_border_color'); ?>"><?php _e('Button Hover Border Color', 'virtue'); ?></label><br />
                <input type="text" class="kad-widget-colorpicker" name="<?php echo $this->get_field_name('btn_hover_border_color'); ?>" id="<?php echo $this->get_field_id('btn_hover_border_color'); ?>" style="width: 70px;" value="<?php echo $btn_hover_border_color; ?>">
            </p>
    </div>

<?php } }

class kad_imgmenu_widget extends WP_Widget{

private static $instance = 0;
    public function __construct() {
        $widget_ops = array('classname' => 'virtue_imgmenu_widget', 'description' => __('Adds an image background with text, link and hover effect.', 'virtue'));
        parent::__construct('virtue_imgmenu_widget', __('Virtue: Image Menu Item', 'virtue'), $widget_ops);
    }

       public function widget($args, $instance){ 
        extract( $args ); 
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        if(!empty($instance["description"])) {$description = $instance["description"];} else {$description = '';}
        if(!empty($instance['image_uri'])) {$image = esc_url($instance['image_uri']);} else {$image = virtue_img_placeholder();}
        if(!empty($instance["height"])) { $height = $instance["height"];} else {$height = '210';}
        if(!empty($instance["link"])) { $link = $instance["link"];} else {$link = '#';}
        if(!empty($instance["height_setting"])) { $height_setting = $instance["height_setting"];} else {$height_setting = 'normal';}
        if(!empty($instance["target"]) && $instance["target"] == 'true') { $linktarget = 'target="_blank"';} else {$linktarget = '';}
            ?>

                <?php echo $before_widget; ?>
                <?php if($height_setting == 'imgsize') { ?>

                            <div class="kad-animation image-menu-image-size" data-animation="fade-in" data-delay="150">
                                    <?php if(!empty($link)) {echo '<a href="'.esc_attr($link).'" class="homepromolink" target="'.esc_attr($linktarget).'">';} ?>
                                        <div class="image_menu_hover_class"></div>
                                        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title);?>" />
                                        <div class="image_menu_content">
                                            <div class="image_menu_message">    
                                                <?php if (!empty($title)) {echo '<h4>'.$title.'</h4>';} ?>
                                                <?php if (!empty($description)) {echo '<h5>'.$description.'</h5>';}?>
                                            </div>
                                        </div>
                                    <?php if(!empty($link)) {echo '</a>'; }?>
                                </div>
                 <?php } else { ?>
                <div class="kad-animation" data-animation="fade-in" data-delay="150">
                    <?php if(!empty($link)) echo '<a href="'.esc_url($link).'" '.esc_attr($linktarget).' class="homepromolink">'; ?>
                        <div class="infobanner" style="background: url(<?php echo esc_url($image); ?>) center center no-repeat; height:<?php echo esc_attr($height) ?>px; <?php echo 'background-size:cover;';?>">
                            <div class="home-message" style="height:<?php echo esc_attr($height) ?>px;">
                                <?php if (!empty($title)) echo '<h4>'.$title.'</h4>'; ?>
                                <?php if (!empty($description)) echo '<h5>'.$description.'</h5>';?>
                            </div>
                        </div>
                    <?php if(!empty($link)) echo '</a>'; ?>
                </div>
                <?php } ?>
                <?php echo $after_widget;?>

    <?php }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['image_uri'] = strip_tags( $new_instance['image_uri'] );
        $instance['description'] = strip_tags( $new_instance['description'] );
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['link'] = $new_instance['link'];
        $instance['height'] = (int) $new_instance['height'];
        $instance['target'] = $new_instance['target'];
        $instance['height_setting'] = $new_instance['height_setting'];
        return $instance;
    }
  public function form($instance){ 
    $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
    $link = isset($instance['link']) ? esc_attr($instance['link']) : '';
    $height = isset($instance['height']) ? esc_attr($instance['height']) : '';
    $image_uri = isset($instance['image_uri']) ? esc_attr($instance['image_uri']) : '';
    if (isset($instance['target'])) { $target = esc_attr($instance['target']); } else {$target = 'false';}
    if (isset($instance['height_setting'])) { $height_setting = esc_attr($instance['height_setting']); } else {$height_setting = 'normal';}
    $height_options = array(array("slug" => "normal", "name" => __('Height setting Above', 'virtue')), array("slug" => "imgsize", "name" => __('Image Size', 'virtue')));
    $target_options = array(array("slug" => "false", "name" => __('Self', 'virtue')), array("slug" => "true", "name" => __('New Window', 'virtue')));
    foreach ($target_options as $target_option) {
      if ($target == $target_option['slug']) { $selected=' selected="selected"';} else { $selected=""; }
      $target_array[] = '<option value="' . $target_option['slug'] .'"' . $selected . '>' . $target_option['name'] . '</option>';
    }
    foreach ($height_options as $height_option) {
      if ($height_setting == $height_option['slug']) { $selected=' selected="selected"';} else { $selected=""; }
      $height_array[] = '<option value="' . $height_option['slug'] .'"' . $selected . '>' . $height_option['name'] . '</option>';
    }
    ?>  

    <div id="virtue_imgmenu_widget<?php echo esc_attr($this->get_field_id('container')); ?>" class="kad_img_upload_widget kad_infobox_widget">
            <p>
            <img class="kad_custom_media_image" src="<?php if(!empty($instance['image_uri'])){echo $instance['image_uri'];} ?>" style="margin:0;padding:0;max-width:100px;display:block" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('image_uri'); ?>"><?php _e('Upload an image', 'virtue'); ?></label><br />
                <input type="text" class="widefat kad_custom_media_url" name="<?php echo $this->get_field_name('image_uri'); ?>" id="<?php echo $this->get_field_id('image_uri'); ?>" value="<?php echo $image_uri; ?>">
                <input type="button" value="<?php _e('Upload', 'virtue'); ?>" class="button kad_custom_media_upload" id="kad_custom_image_uploader" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Item Height (e.g. = 220)', 'virtue'); ?></label><br />
                <input type="number" class="widefat kad_img_widget_link" name="<?php echo $this->get_field_name('height'); ?>" id="<?php echo $this->get_field_id('height'); ?>" style="width: 70px;" value="<?php echo $height; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('height_setting'); ?>"><?php _e('Height set by:', 'virtue'); ?></label><br />
                <select id="<?php echo $this->get_field_id('height_setting'); ?>" style="width:100%; max-width:230px;" name="<?php echo $this->get_field_name('height_setting'); ?>"><?php echo implode('', $height_array);?></select>
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'virtue'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
             <p>
              <label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description', 'virtue'); ?></label><br />
              <textarea name="<?php echo $this->get_field_name('description'); ?>" style="min-height: 20px;" id="<?php echo $this->get_field_id('description'); ?>" class="widefat" ><?php if(!empty($instance['description'])) echo $instance['description']; ?></textarea>
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link:', 'virtue'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo $link; ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('target'); ?>"><?php _e('Link Target', 'virtue'); ?></label><br />
                <select id="<?php echo $this->get_field_id('target'); ?>" style="width:100%; max-width:230px;" name="<?php echo $this->get_field_name('target'); ?>"><?php echo implode('', $target_array);?></select>
            </p>

    </div>

<?php } }

class kad_split_content_widget extends WP_Widget{
private static $instance = 0;
    public function __construct() {
        $widget_ops = array('classname' => 'virtue_split_content_widget', 'description' => __('Adds an column with an image beside a content field.', 'virtue'));
        parent::__construct('virtue_split_content_widget', __('Virtue: Split Content', 'virtue'), $widget_ops);
    }

       public function widget($args, $instance){ 
        extract( $args ); 
        if(!empty($instance["title"])) {$title = $instance["title"];} else {$title = '';}
        if(!empty($instance["description"])) {$description = $instance["description"];} else {$description = '';}
        if(!empty($instance['image_url'])) {$image = esc_url($instance['image_url']);} else {$image = '';}
        if(!empty($instance["img_link"])) { $img_link = 'image_link="'.$instance["img_link"].'" ';} else {$img_link = '';}
        if(!empty($instance["img_align"])) { $img_align = 'imageside="'.$instance["img_align"].'" ';} else {$img_align = '';}
        if(!empty($instance['img_background_color'])) {$img_background_color = 'img_background="'.$instance['img_background_color'].'" ';} else {$img_background_color = '';}
        if(!empty($instance['content_background_color'])) {$content_background_color = 'content_background="'.$instance['content_background_color'].'" ';} else {$content_background_color = '';}
        if(!empty($instance["height"])) { $height = $instance["height"];} else {$height = '500';}
        if(!empty($instance["btn_text"])) { $btn_text = $instance["btn_text"];} else {$btn_text = '';}
        if(!empty($instance["btn_link"])) { $btn_link = $instance["btn_link"];} else {$btn_link = '#';}
        if(!empty($instance["link_target"])) { $linktarget = 'target="'.$instance["link_target"].'"';} else {$linktarget = '';}
        if(!empty($instance['filter'])){ $description = wpautop( $description );} else {$description = $description;}
        if(!empty($instance['img_cover'])){ $cover = 'image_cover="true" ';} else {$cover = '';}
            ?>

                <?php echo $before_widget; ?>
                <?php $output = '[kt_imgsplit image="'.$image.'" height="'.$height.'" '.$img_align.' '.$cover.' '.$img_background_color.' '.$img_link.' '.$linktarget.' '.$content_background_color .']';
                if(!empty($title)) { $output .= '<h2 class="kt_imgsplit_title">'.$title.'</h2>';}
                if(!empty($description)) {$output .= '<div class="kt_imgsplit_content">'.$description.'</div>';}
                if(!empty($btn_text)) {$output .= '<a href="'.$btn_link.'" class="kt_imgsplit_btn kad-btn kad-btn-primary">'.$btn_text.'</a>';}
                $output .= '[/kt_imgsplit]'; 
                echo do_shortcode($output); ?>

                <?php echo $after_widget;?>

    <?php }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['image_url'] = strip_tags( $new_instance['image_url'] );
        $instance['description'] = $new_instance['description'];
        $instance['title'] = $new_instance['title'];
        $instance['btn_link'] = $new_instance['btn_link'];
        $instance['btn_text'] = $new_instance['btn_text'];
        $instance['img_link'] = $new_instance['img_link'];
        $instance['height'] = (int) $new_instance['height'];
        $instance['link_target'] = $new_instance['link_target'];
        $instance['img_align'] = $new_instance['img_align'];
        $instance['filter'] = ! empty( $new_instance['filter'] );
        $instance['img_cover'] = ! empty( $new_instance['img_cover'] );
        $instance['img_background_color'] = $new_instance['img_background_color'];
        $instance['content_background_color'] = $new_instance['content_background_color'];

        return $instance;
    }

  public function form($instance){ 
    $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
    $description = isset($instance['description']) ? $instance['description'] : '';
    $filter = isset( $instance['filter'] ) ? $instance['filter'] : 0;
    $btn_text = isset($instance['btn_text']) ? esc_attr($instance['btn_text']) : '';
    $btn_link = isset($instance['btn_link']) ? esc_attr($instance['btn_link']) : '';
    $img_link = isset($instance['img_link']) ? esc_attr($instance['img_link']) : '';
    $height = isset($instance['height']) ? esc_attr($instance['height']) : '500';
    $cover = isset( $instance['img_cover'] ) ? $instance['img_cover'] : 0;
    $image_url = isset($instance['image_url']) ? esc_attr($instance['image_url']) : '';
    $img_background_color = isset($instance['img_background_color']) ? esc_attr($instance['img_background_color']) : '';
    $content_background_color = isset($instance['content_background_color']) ? esc_attr($instance['content_background_color']) : '';
    $img_align = isset($instance['img_align']) ? esc_attr($instance['img_align']) : 'left';
    $link_target = isset($instance['link_target']) ? esc_attr($instance['link_target']) : '_self';
    $target_options = array(array("slug" => "_self", "name" => __('Self', 'virtue')), array("slug" => "_blank", "name" => __('New Window', 'virtue')));
    $align_options = array(array("slug" => "left", "name" => __('Left', 'virtue')), array("slug" => "right", "name" => __('Right', 'virtue')));
    foreach ($target_options as $target_option) {
      if ($link_target == $target_option['slug']) { $selected=' selected="selected"';} else { $selected=""; }
      $target_array[] = '<option value="' . $target_option['slug'] .'"' . $selected . '>' . $target_option['name'] . '</option>';
    }
    foreach ($align_options as $align_option) {
      if ($img_align == $align_option['slug']) { $selected=' selected="selected"';} else { $selected=""; }
      $align_array[] = '<option value="' . $align_option['slug'] .'"' . $selected . '>' . $align_option['name'] . '</option>';
    }
    ?>  

    <div id="virtue_split_content_widget<?php echo esc_attr($this->get_field_id('container')); ?>" class="kad_img_upload_widget kad_infobox_widget">
            <p>
                <label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height', 'virtue'); ?></label><br />
                <input type="number" class="widefat kad_img_widget_link" name="<?php echo $this->get_field_name('height'); ?>" id="<?php echo $this->get_field_id('height'); ?>" style="width: 70px;" value="<?php echo $height; ?>">
            </p>
            <h4><?php _e('Image content', 'virtue');?></h4>
            <p>
            <img class="kad_custom_media_image" src="<?php if(!empty($instance['image_url'])){echo $instance['image_url'];} ?>" style="margin:0;padding:0;max-width:100px;display:block" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('image_url'); ?>"><?php _e('Upload an image', 'virtue'); ?></label><br />
                <input type="text" class="widefat kad_custom_media_url" name="<?php echo $this->get_field_name('image_url'); ?>" id="<?php echo $this->get_field_id('image_url'); ?>" value="<?php echo $image_url; ?>">
                <input type="button" value="<?php _e('Upload', 'virtue'); ?>" class="button kad_custom_media_upload" id="kad_custom_image_uploader" />
            </p>
            <p><input id="<?php echo $this->get_field_id('img_cover'); ?>" name="<?php echo $this->get_field_name('img_cover'); ?>" type="checkbox"<?php checked( $cover ); ?> />&nbsp;<label for="<?php echo $this->get_field_id('img_cover'); ?>"><?php _e('Force image to cover whole area', 'virtue'); ?></label></p>
           
            <p>
                <label for="<?php echo $this->get_field_id('img_link'); ?>"><?php _e('Image Link (optional)', 'virtue'); ?></label><br />
                <input type="text" class="widefat" name="<?php echo $this->get_field_name('img_link'); ?>" id="<?php echo $this->get_field_id('img_link'); ?>"value="<?php echo $img_link; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('img_background_color'); ?>"><?php _e('Image Background Color (optional)', 'virtue'); ?></label><br />
                <input type="text" class="widefat kad-widget-colorpicker" name="<?php echo $this->get_field_name('img_background_color'); ?>" id="<?php echo $this->get_field_id('img_background_color'); ?>" value="<?php echo $img_background_color; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('img_align'); ?>"><?php _e('Image align:', 'virtue'); ?></label><br />
                <select id="<?php echo $this->get_field_id('img_align'); ?>" style="width:100%; max-width:230px;" name="<?php echo $this->get_field_name('img_align'); ?>"><?php echo implode('', $align_array);?></select>
            </p>
            <h4><?php _e('Text content', 'virtue');?></h4>
            <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'virtue'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
             <p>
              <label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description', 'virtue'); ?></label><br />
              <textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" ><?php echo esc_textarea( $description ); ?></textarea>
            </p>
            <p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox"<?php checked( $filter ); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs', 'virtue'); ?></label></p>
            <p>
            <label for="<?php echo $this->get_field_id('btn_text'); ?>"><?php _e('Button Text (optional)', 'virtue'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('btn_text'); ?>" name="<?php echo $this->get_field_name('btn_text'); ?>" type="text" value="<?php echo $btn_text; ?>" />
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('btn_link'); ?>"><?php _e('Button Link (optional)', 'virtue'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('btn_link'); ?>" name="<?php echo $this->get_field_name('btn_link'); ?>" type="text" value="<?php echo $btn_link; ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('link_target'); ?>"><?php _e('Link link_Target (optional)', 'virtue'); ?></label><br />
                <select id="<?php echo $this->get_field_id('link_target'); ?>" style="width:100%; max-width:230px;" name="<?php echo $this->get_field_name('link_target'); ?>"><?php echo implode('', $target_array);?></select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('content_background_color'); ?>"><?php _e('Text Content Background Color (optional)', 'virtue'); ?></label><br />
                <input type="text" class="widefat kad-widget-colorpicker" name="<?php echo $this->get_field_name('content_background_color'); ?>" id="<?php echo $this->get_field_id('content_background_color'); ?>" value="<?php echo $content_background_color; ?>">
            </p>

    </div>

<?php } }

class kad_icon_flip_box_widget extends WP_Widget{
private static $instance = 0;
    public function __construct() {
        $widget_ops = array('classname' => 'virtue_icon_flip_box_widget', 'description' => __('Adds an box that flips to show more content.', 'virtue'));
        parent::__construct('virtue_icon_flip_box_widget', __('Virtue: Icon Flip Box', 'virtue'), $widget_ops);
    }

       public function widget($args, $instance){ 
        extract( $args ); 
        if(!empty($instance["title"])) {$title = 'title="'.$instance["title"].'"';} else {$title = '';}
        if(!empty($instance["description"])) {$description = 'description="'.$instance["description"].'"';} else {$description = '';}
        if(!empty($instance['icon'])) {$icon = 'icon="'.$instance['icon'].'"';} else {$icon = '';}
        if(!empty($instance['iconcolor'])) {$iconcolor = 'iconcolor="'.$instance['iconcolor'].'"';} else {$iconcolor = '';}
        if(!empty($instance['titlecolor'])) {$titlecolor = 'titlecolor="'.$instance['titlecolor'].'"';} else {$titlecolor = '';}
        if(!empty($instance['fcolor'])) {$fcolor = 'fcolor="'.$instance['fcolor'].'"';} else {$fcolor = '';}
        if(!empty($instance['titlesize'])) {$titlesize = 'titlesize="'.$instance['titlesize'].'px"';} else {$titlesize = '';}
        if(!empty($instance['image'])) {$image = 'image="'.$instance['image'].'"';} else {$image = '';}
        if(!empty($instance['height'])) {$height = 'height="'.$instance['height'].'px"';} else {$height = '';}
        if(!empty($instance["iconsize"])) { $iconsize = 'iconsize="'.$instance["iconsize"].'px" ';} else {$iconsize = '';}
        if(!empty($instance["flip_content"])) { $flip_content = 'flip_content="'.$instance["flip_content"].'" ';} else {$flip_content = '';}
        if(!empty($instance["fbtn_text"])) { $fbtn_text = 'fbtn_text="'.$instance["fbtn_text"].'" ';} else {$fbtn_text = '';}
        if(!empty($instance["fbtn_link"])) { $fbtn_link = 'fbtn_link="'.$instance["fbtn_link"].'" ';} else {$fbtn_link = '';}
        if(!empty($instance["fbtn_color"])) { $fbtn_color = 'fbtn_color="'.$instance["fbtn_color"].'"';} else {$fbtn_color = '';}
        if(!empty($instance["fbtn_icon"])) { $fbtn_icon = 'fbtn_icon="'.$instance["fbtn_icon"].'"';} else {$fbtn_icon = '';}
        if(!empty($instance["fbtn_background"])) { $fbtn_background = 'fbtn_background="'.$instance["fbtn_background"].'"';} else {$fbtn_background = '';}
        if(!empty($instance["fbtn_border"])) { $fbtn_border = 'fbtn_border="'.$instance["fbtn_border"].'"';} else {$fbtn_border = '';}
        if(!empty($instance["fbtn_border_radius"])) { $fbtn_border_radius = 'fbtn_border_radius="'.$instance["fbtn_border_radius"].'px"';} else {$fbtn_border_radius = '';}
        if(!empty($instance["background"])) { $background = 'background="'.$instance["background"].'"';} else {$background = '';}
        if(!empty($instance["bcolor"])) { $bcolor = 'bcolor="'.$instance["bcolor"].'"';} else {$bcolor = '';}
        if(!empty($instance["bbackground"])) { $bbackground = 'bbackground="'.$instance["bbackground"].'"';} else {$bbackground = '';}
        if(!empty($instance["fbtn_target"])) { $fbtn_target = 'fbtn_target="'.$instance["fbtn_target"].'"';} else {$fbtn_target = '';}
            ?>

                <?php echo $before_widget; ?>
                <?php $output = '[kt_flip_box '.$icon.' '.$height.' '.$iconsize.' '.$iconcolor.' '.$titlecolor.' '.$fcolor.' '.$title.' '.$description.' '.$titlesize.' '.$image.' '.$flip_content.' '.$fbtn_text.' '.$fbtn_color.' '.$fbtn_icon.' '.$fbtn_background.' '.$fbtn_border.' '.$fbtn_border_radius.' '.$background.' '.$bcolor.' '.$bbackground.' '.$fbtn_target.' '.$fbtn_link.']';
                echo do_shortcode($output); ?>

                <?php echo $after_widget;?>

    <?php }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['description'] = $new_instance['description'];
        $instance['icon'] = $new_instance['icon'];
        $instance['iconcolor'] = $new_instance['iconcolor'];
        $instance['titlecolor'] = $new_instance['titlecolor'];
        $instance['fcolor'] = $new_instance['fcolor'];
        $instance['image'] = $new_instance['image'];
        $instance['flip_content'] = $new_instance['flip_content'];
        $instance['fbtn_text'] = $new_instance['fbtn_text'];

        $instance['fbtn_link'] = $new_instance['fbtn_link'];
        $instance['fbtn_color'] = $new_instance['fbtn_color'];
        $instance['fbtn_icon'] = $new_instance['fbtn_icon'];
        $instance['fbtn_background'] = $new_instance['fbtn_background'];
        $instance['fbtn_border'] = $new_instance['fbtn_border'];
        $instance['background'] = $new_instance['background'];
        $instance['bcolor'] = $new_instance['bcolor'];
        $instance['bbackground'] = $new_instance['bbackground'];
        $instance['fbtn_target'] = $new_instance['fbtn_target'];

        $instance['height'] = (int) $new_instance['height'];
        $instance['titlesize'] = (int) $new_instance['titlesize'];
        $instance['iconsize'] = (int) $new_instance['iconsize'];
        $instance['fbtn_border_radius'] = (int) $new_instance['fbtn_border_radius'];

        return $instance;
    }

  public function form($instance){ 
    $title = isset($instance['title']) ? $instance['title'] : '';
    $description = isset($instance['description']) ? $instance['description'] : '';
    $icon = isset($instance['icon']) ? $instance['icon'] : '';
    $iconcolor = isset($instance['iconcolor']) ? $instance['iconcolor'] : '';
    $titlecolor = isset($instance['titlecolor']) ? $instance['titlecolor'] : '';
    $fcolor = isset($instance['fcolor']) ? $instance['fcolor'] : '';
    $image = isset($instance['image']) ? $instance['image'] : '';
    $flip_content = isset($instance['flip_content']) ? $instance['flip_content'] : '';
    $fbtn_text = isset($instance['fbtn_text']) ? $instance['fbtn_text'] : '';
    $fbtn_color = isset($instance['fbtn_color']) ? $instance['fbtn_color'] : '';
    $fbtn_border = isset($instance['fbtn_border']) ? $instance['fbtn_border'] : '2px solid #ffffff';
    $fbtn_icon = isset($instance['fbtn_icon']) ? $instance['fbtn_icon'] : '';
    $fbtn_background = isset($instance['fbtn_background']) ? $instance['fbtn_background'] : '';
    $background = isset($instance['background']) ? $instance['background'] : '';
    $bcolor = isset($instance['bcolor']) ? $instance['bcolor'] : '';
    $bbackground = isset($instance['bbackground']) ? $instance['bbackground'] : '';
    $iconsize = isset($instance['iconsize']) ? $instance['iconsize'] : '48';
    $titlesize = isset( $instance['titlesize'] ) ? $instance['titlesize'] : '24';
    $height = isset( $instance['height'] ) ? $instance['height'] : '';
    $fbtn_border_radius = isset( $instance['fbtn_border_radius'] ) ? $instance['fbtn_border_radius'] : '0';
   
    $image = isset($instance['image']) ? esc_url($instance['image']) : '';
    $fbtn_link = isset($instance['fbtn_link']) ? esc_url($instance['fbtn_link']) : '';
    $fbtn_target = isset($instance['fbtn_target']) ? esc_attr($instance['fbtn_target']) : '_self';
    $target_options = array(array("slug" => "_self", "name" => __('Self', 'virtue')), array("slug" => "_blank", "name" => __('New Window', 'virtue')));
    foreach ($target_options as $target_option) {
      if ($fbtn_target == $target_option['slug']) { $selected=' selected="selected"';} else { $selected=""; }
      $target_array[] = '<option value="' . $target_option['slug'] .'"' . $selected . '>' . $target_option['name'] . '</option>';
    }
    $icons = kad_icon_list();
    foreach ($icons as $ico) {
      if ($icon == $ico) { $selected=' selected="selected"';} else { $selected=""; }
      $icon_array[] = '<option value="' . $ico .'"' . $selected . '>' . $ico . '</option>';
    }
    $icon_btn_array[] = '<option value="">' . __('None', 'virtue') . '</option>';
    foreach ($icons as $ico) {
      if ($fbtn_icon == $ico) { $selected=' selected="selected"';} else { $selected=""; }
      $icon_btn_array[] = '<option value="' . $ico .'"' . $selected . '>' . $ico . '</option>';
    }
    ?>  

    <div id="virtue_icon_flip_box_widget<?php echo esc_attr($this->get_field_id('container')); ?>" class="kad_img_upload_widget kad_infobox_widget">
            <h4><?php _e('Front Side', 'virtue');?></h4>
            <p>
                <label for="<?php echo $this->get_field_id('icon'); ?>"><?php _e('Choose an Icon', 'virtue'); ?></label><br />
                <select id="<?php echo $this->get_field_id('icon'); ?>" class="kad_icomoon" name="<?php echo $this->get_field_name('icon'); ?>"><?php echo implode('', $icon_array);?></select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('iconsize'); ?>"><?php _e('Icon Size', 'virtue'); ?></label><br />
                <input type="number" class="widefat kad_img_widget_link" name="<?php echo $this->get_field_name('iconsize'); ?>" id="<?php echo $this->get_field_id('iconsize'); ?>" style="width: 70px;" value="<?php echo $iconsize; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('iconcolor'); ?>"><?php _e('Icon Color', 'virtue'); ?></label><br />
                <input type="text" class="kad-widget-colorpicker" name="<?php echo $this->get_field_name('iconcolor'); ?>" id="<?php echo $this->get_field_id('iconcolor'); ?>" style="width: 70px;" value="<?php echo $iconcolor; ?>">
            </p>
            <p>
            <img class="kad_custom_media_image" src="<?php if(!empty($instance['image'])){echo $instance['image'];} ?>" style="margin:0;padding:0;max-width:100px;display:block" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('Optional Image instead of icon', 'virtue'); ?></label><br />
                <input type="text" class="widefat kad_custom_media_url" name="<?php echo $this->get_field_name('image'); ?>" id="<?php echo $this->get_field_id('image'); ?>" value="<?php echo $image; ?>">
                <input type="button" value="<?php _e('Upload', 'virtue'); ?>" class="button kad_custom_media_upload" id="kad_custom_image_uploader" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'virtue'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('titlesize'); ?>"><?php _e('Title Size', 'virtue'); ?></label><br />
                <input type="number" class="widefat kad_img_widget_link" name="<?php echo $this->get_field_name('titlesize'); ?>" id="<?php echo $this->get_field_id('titlesize'); ?>" style="width: 70px;" value="<?php echo $titlesize; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('titlecolor'); ?>"><?php _e('Title Color', 'virtue'); ?></label><br />
                <input type="text" class="kad-widget-colorpicker" name="<?php echo $this->get_field_name('titlecolor'); ?>" id="<?php echo $this->get_field_id('titlecolor'); ?>" style="width: 70px;" value="<?php echo $titlecolor; ?>">
            </p>
           <p>
              <label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description', 'virtue'); ?></label><br />
              <textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" ><?php echo esc_textarea( $description ); ?></textarea>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('fcolor'); ?>"><?php _e('Description Color', 'virtue'); ?></label><br />
                <input type="text" class="kad-widget-colorpicker" name="<?php echo $this->get_field_name('fcolor'); ?>" id="<?php echo $this->get_field_id('fcolor'); ?>" style="width: 70px;" value="<?php echo $fcolor; ?>">
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('background'); ?>"><?php _e('Front Side Background', 'virtue'); ?></label><br />
                <input type="text" class="kad-widget-colorpicker" name="<?php echo $this->get_field_name('background'); ?>" id="<?php echo $this->get_field_id('background'); ?>" style="width: 70px;" value="<?php echo $background; ?>">
            </p>
            <h4><?php _e('Back Side', 'virtue');?></h4>
            <p>
              <label for="<?php echo $this->get_field_id('flip_content'); ?>"><?php _e('Back Side Description', 'virtue'); ?></label><br />
              <textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id('flip_content'); ?>" name="<?php echo $this->get_field_name('flip_content'); ?>" ><?php echo esc_textarea( $flip_content ); ?></textarea>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('bcolor'); ?>"><?php _e('Back Side Description Color', 'virtue'); ?></label><br />
                <input type="text" class="kad-widget-colorpicker" name="<?php echo $this->get_field_name('bcolor'); ?>" id="<?php echo $this->get_field_id('bcolor'); ?>" style="width: 70px;" value="<?php echo $bcolor; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('fbtn_text'); ?>"><?php _e('Button Text', 'virtue'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('fbtn_text'); ?>" name="<?php echo $this->get_field_name('fbtn_text'); ?>" type="text" value="<?php echo $fbtn_text; ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('fbtn_link'); ?>"><?php _e('Button Link', 'virtue'); ?></label><br />
                <input type="text" class="widefat" name="<?php echo $this->get_field_name('fbtn_link'); ?>" id="<?php echo $this->get_field_id('fbtn_link'); ?>"value="<?php echo $fbtn_link; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('fbtn_color'); ?>"><?php _e('Button Text Color', 'virtue'); ?></label><br />
                <input type="text" class="kad-widget-colorpicker" name="<?php echo $this->get_field_name('fbtn_color'); ?>" id="<?php echo $this->get_field_id('fbtn_color'); ?>" style="width: 70px;" value="<?php echo $fbtn_color; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('fbtn_background'); ?>"><?php _e('Button Background Color', 'virtue'); ?></label><br />
                <input type="text" class="kad-widget-colorpicker" name="<?php echo $this->get_field_name('fbtn_background'); ?>" id="<?php echo $this->get_field_id('fbtn_background'); ?>" style="width: 70px;" value="<?php echo $fbtn_background; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('fbtn_border'); ?>"><?php _e('Button Border (example: 2px solid #ffffff)', 'virtue'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('fbtn_border'); ?>" name="<?php echo $this->get_field_name('fbtn_border'); ?>" type="text" value="<?php echo $fbtn_border; ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('fbtn_border_radius'); ?>"><?php _e('Button Border Radius (example: 6)', 'virtue'); ?></label><br />
                <input type="number" class="widefat kad_img_widget_link" name="<?php echo $this->get_field_name('fbtn_border_radius'); ?>" id="<?php echo $this->get_field_id('fbtn_border_radius'); ?>" style="width: 70px;" value="<?php echo $fbtn_border_radius; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('fbtn_icon'); ?>"><?php _e('Button Icon (optional)', 'virtue'); ?></label><br />
                <select id="<?php echo $this->get_field_id('fbtn_icon'); ?>" class="kad_icomoon" name="<?php echo $this->get_field_name('fbtn_icon'); ?>"><?php echo implode('', $icon_btn_array);?></select>
            </p>
             <p>
                <label for="<?php echo $this->get_field_id('fbtn_target'); ?>"><?php _e('Button Link Target', 'virtue'); ?></label><br />
                <select id="<?php echo $this->get_field_id('fbtn_target'); ?>" style="width:100%; max-width:230px;" name="<?php echo $this->get_field_name('fbtn_target'); ?>"><?php echo implode('', $target_array);?></select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('bbackground'); ?>"><?php _e('Back Side Background', 'virtue'); ?></label><br />
                <input type="text" class="kad-widget-colorpicker" name="<?php echo $this->get_field_name('bbackground'); ?>" id="<?php echo $this->get_field_id('bbackground'); ?>" style="width: 70px;" value="<?php echo $bbackground; ?>">
            </p>


            <h4><?php _e('Box Height', 'virtue');?></h4>
            <p>
                <label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height (example: 280)', 'virtue'); ?></label><br />
                <input type="number" class="widefat kad_img_widget_link" name="<?php echo $this->get_field_name('height'); ?>" id="<?php echo $this->get_field_id('height'); ?>" style="width: 70px;" value="<?php echo $height; ?>">
            </p>
    </div>

<?php } }