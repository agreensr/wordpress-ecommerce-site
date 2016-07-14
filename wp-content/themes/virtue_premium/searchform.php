<?php global $virtue_premium; if(!empty($virtue_premium['search_placeholder_text'])) {$searchtext = $virtue_premium['search_placeholder_text'];} else {$searchtext = __('Search', 'virtue');} ?>
<form role="search" method="get" class="form-search" action="<?php echo home_url('/'); ?>">
  <label class="hide" for="s"><?php _e('Search for:', 'virtue'); ?></label>
  <input type="text" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" class="search-query" placeholder="<?php echo $searchtext; ?>">
  <button type="submit" class="search-icon"><i class="icon-search"></i></button>
</form>