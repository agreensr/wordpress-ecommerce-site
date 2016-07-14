  <div id="pageheader" class="titleclass">
    <div class="container">
      <?php get_template_part('templates/page', 'header'); ?>
    </div><!--container-->
  </div><!--titleclass-->
  
    <div id="content" class="container">
      <div class="row">
      <div class="main <?php echo kadence_main_class(); ?>  postlist" role="main">
      <div class="entry-content" itemprop="mainContentOfPage">

<?php if (!have_posts()) : ?>
  <div class="alert">
    <?php _e('Sorry, no results were found.', 'virtue'); ?>
  </div>
  <?php get_search_form(); 
    endif;

    while (have_posts()) : the_post(); 
      get_template_part('templates/content', get_post_format()); 
    endwhile; 

    if ($wp_query->max_num_pages > 1) : 
      kad_wp_pagenavi();   
    endif; ?>
    </div>

</div><!-- /.main -->
