 <div id="pageheader" class="titleclass">
    <div class="container">
      <?php get_template_part('templates/page', 'header');  ?>
    </div><!--container-->
  </div><!--titleclass-->
  <?php global $virtue_premium; if(kadence_display_sidebar()) {$display_sidebar = true; $fullclass = '';} else {$display_sidebar = false; $fullclass = 'fullwidth';} ?>
    <div id="content" class="container">
      <div class="row">
      <div class="main <?php echo kadence_main_class(); ?>  <?php echo esc_attr($fullclass);?> postlist" id="ktmain" role="main">

<?php if (!have_posts()) : ?>
  <div class="alert">
    <?php _e('Sorry, no results were found.', 'virtue'); ?>
  </div>
  <?php get_search_form(); ?>
<?php endif; ?>

<?php
if(isset($virtue_premium['search_layout']) && $virtue_premium['search_layout'] == 'singlecolumn') {
  if($display_sidebar){
               while (have_posts()) : the_post();
                    get_template_part('templates/content', get_post_format());
               endwhile;
             } else {
                while (have_posts()) : the_post(); 
                    get_template_part('templates/content', 'fullwidth');
                endwhile;
             }
} else if(isset($virtue_premium['search_layout']) && $virtue_premium['search_layout'] == 'simple_grid') { 
  if(isset($virtue_premium['virtue_animate_in']) && $virtue_premium['virtue_animate_in'] == 1) {$animate = 1;} else {$animate = 0;} ?>
  <div id="kad-blog-grid" class="clearfix init-isotope"  data-fade-in="<?php echo esc_attr($animate);?>"  data-iso-selector=".b_item" data-iso-style="masonry">
  <?php $itemsize = 'tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12';?>
<?php while (have_posts()) : the_post(); ?>
  <div class="<?php echo esc_attr($itemsize);?> b_item search_item">
  <?php get_template_part('templates/content', 'loop-searchresults'); ?>
  </div>
<?php endwhile; ?>
</div> <!-- Blog Grid -->
<?php } else { 
   if(isset($virtue_premium['virtue_animate_in']) && $virtue_premium['virtue_animate_in'] == 1) {$animate = 1;} else {$animate = 0;} ?>
<div id="kad-blog-grid" class="clearfix init-isotope"  data-fade-in="<?php echo esc_attr($animate);?>"  data-iso-selector=".b_item" data-iso-style="masonry">
  <?php $itemsize = 'tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12';?>
<?php while (have_posts()) : the_post(); ?>
  <div class="<?php echo esc_attr($itemsize);?> b_item search_item">
  <?php get_template_part('templates/content', 'searchresults'); ?>
  </div>
<?php endwhile; ?>
</div> <!-- Blog Grid -->
<?php }?>
<?php if ($wp_query->max_num_pages > 1) : ?>
              <?php kad_wp_pagenavi(); ?>   
        <?php endif; ?>
</div><!-- /.main -->
