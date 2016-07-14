<?php 
  global $post, $kt_feat_width;
    if(kadence_display_sidebar()) {
      $kt_feat_width = 848;
    } else {
      $kt_feat_width = 1170;
    }
    /**
    * @hooked virtue_single_post_upper_headcontent - 10
    */
    do_action( 'kadence_single_post_begin' ); 
    ?>
<div id="content" class="container">
    <div id="post-<?php the_ID(); ?>" class="row single-article" itemscope="" itemtype="http://schema.org/BlogPosting">

      <div class="main <?php echo kadence_main_class(); ?>" id="ktmain" role="main">
        <?php while (have_posts()) : the_post(); 
         
         do_action( 'kadence_single_post_before' ); 

         ?>
          <article <?php post_class(); ?>>
          <?php
          /**
          * @hooked virtue_single_post_headcontent - 10
          * @hooked virtue_single_post_meta_date - 20
          */
          do_action( 'kadence_single_post_before_header' );
          ?>
    <header>
         <?php 
            /**
            * @hooked virtue_post_header_breadcrumbs - 10
            * @hooked virtue_post_header_title - 20
            * @hooked virtue_post_header_meta - 30
            */
            do_action( 'kadence_single_post_header' );
            ?>
    </header>
    <div class="entry-content clearfix" itemprop="description articleBody">
      <?php
      do_action( 'kadence_single_post_content_before' );
        
        the_content(); 
      
      do_action( 'kadence_single_post_content_after' );
      ?>
    </div>
    <footer class="single-footer">
    <?php 
      /**
      * @hooked virtue_post_footer_pagination - 10
      * @hooked virtue_post_footer_tags - 20
      * @hooked virtue_post_footer_meta - 30
      * @hooked virtue_post_nav - 40
      */
      do_action( 'kadence_single_post_footer' );
      ?>
    </footer>
  </article>
<?php
      /**
      * @hooked virtue_post_authorbox - 20
      * @hooked virtue_post_bottom_carousel - 30
      * @hooked virtue_post_comments - 40
      */
      do_action( 'kadence_single_post_after' );

      endwhile; ?>
</div>
<?php 
do_action( 'kadence_single_post_end' ); 