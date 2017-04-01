<?php
/**
 * Template for displaying search forms in Skeleton
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since Skeleton 1.0
 */
?>

<form role="search" method="get" class="site-search" action="<?php echo esc_url(home_url('/')); ?>">
	<div class="form-group">
		<label class="screen-reader-text" for="site-search"><?php echo _x('Search for:', 'label', 'skeleton'); ?></label>
		<div class="input-group">
			<span class="input-group-addon"><button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button></span>
			<input type="search" class="form-control" id="site-search" placeholder="<?php echo esc_attr_x('Search &hellip;', 'placeholder', 'skeleton'); ?>" value="<?php echo get_search_query(); ?>" name="s">
		</div>
	</div>
</form>