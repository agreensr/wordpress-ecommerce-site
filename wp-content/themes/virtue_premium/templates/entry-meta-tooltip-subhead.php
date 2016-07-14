<div class="subhead color_gray">
    <span class="postauthortop" rel="tooltip" data-placement="top" data-original-title="<?php echo get_the_author() ?>">
        <i class="icon-user"></i>
    </span>
    <span class="kad-hidepostauthortop"> | </span>
    <?php $post_category = get_the_category($post->ID); 
    if (!empty($post_category)) { ?> 
        <span class="postedintop" rel="tooltip" data-placement="top" 
        data-original-title="<?php foreach ($post_category as $category)  { echo $category->name .'&nbsp;'; } ?>">
            <i class="icon-folder"></i>
        </span>
    <?php }
    if(comments_open()) { ?>
        <span class="kad-hidepostedin">|</span>
        <span class="postcommentscount" rel="tooltip" data-placement="top" data-original-title="<?php $num_comments = get_comments_number(); echo esc_attr($num_comments); ?>">
            <i class="icon-bubbles"></i>
        </span>
    <?php } ?>
</div>