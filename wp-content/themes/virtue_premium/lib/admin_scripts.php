<?php

/**
 * Enqueue CSS & JS
 */
function kadence_admin_scripts($hook) {

	wp_register_style('kad_adminstyles', get_template_directory_uri() . '/assets/css/kad_adminstyles.css', false, 358);
	  wp_enqueue_style('kad_adminstyles');

	if( $hook != 'edit.php' && $hook != 'post.php' && $hook != 'post-new.php' && $hook != 'widgets.php' ) 
		return;

	  wp_register_script('kad_adminscripts', get_template_directory_uri() . '/assets/js/min/kad_adminscripts-min.js', false, 358, false);
	  wp_enqueue_script('kad_adminscripts');

}

add_action('admin_enqueue_scripts', 'kadence_admin_scripts');

add_action('print_media_templates', 'kadence_media_gallery_extras');
function kadence_media_gallery_extras(){
?>
<script type="text/html" id="tmpl-custom-gallery-setting">
    <hr style="clear: both;">
    <h3 style="margin-top:10px;"><?php echo __('KT Extra Gallery Settings', 'virtue');?></h3>
    <label class="setting">
      <span><?php _e('Type', 'virtue'); ?></span>
      <select data-setting="type">
        <option value="default"><?php _e('Default', 'virtue');?></option>
        <option value="slider"><?php _e('Slider', 'virtue');?></option>
        <option value="carousel"><?php _e('Carousel', 'virtue');?></option>
        <option value="mosaic"><?php _e('Mosaic', 'virtue');?></option>
        <option value="grid"><?php _e('Custom Grid', 'virtue');?></option>
      </select>
    </label>
    <label class="setting">
      <span><?php _e('Show Captions', 'virtue'); ?></span>
      <select data-setting="caption">
      <option value="default"><?php _e('Default', 'virtue');?></option>
        <option value="false"><?php _e('False', 'virtue');?></option>
        <option value="true"><?php _e('True', 'virtue');?></option>
      </select>
    </label>
    <label class="setting">
      <span><?php _e('Masonry', 'virtue'); ?></span>
      <select data-setting="masonry">
        <option value="default"><?php _e('Default', 'virtue');?></option>
        <option value="true"><?php _e('False', 'virtue');?></option>
        <option value="false"><?php _e('True', 'virtue');?></option>
      </select>
    </label>
    <h4><?php echo __('Slider Option - Settings', 'virtue');?></h4>
    <label class="setting">
        <span style="min-width: 50px;"><?php _e('Width', 'virtue'); ?></span>
        <input type="text" value="" data-setting="width" style="float:left;">
    </label>
    <label class="setting">
        <span style="min-width: 50px;"><?php _e('Height', 'virtue'); ?></span>
        <input type="text" value="" data-setting="height" style="float:left;">
    </label>
    <hr style="clear: both;">
</script>

<script>

    jQuery(document).ready(function()
    {
        _.extend(wp.media.gallery.defaults, {
        type: 'default',
        caption: 'default',
        masonry: 'default',
        width: '',
        height: '',
        });

        wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
        template: function(view){
          return wp.media.template('gallery-settings')(view)
               + wp.media.template('custom-gallery-setting')(view);
        }
        });

    });

</script>
<?php

}