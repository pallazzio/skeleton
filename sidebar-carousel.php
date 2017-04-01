<?php
/**
 * The template for the widget area containing the carousel slides
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since Skeleton 1.0
 */

if( !is_active_sidebar( 'sidebar-carousel' ) || !is_page( 'home' ) ) {
	return;
}

if( get_option( 'skeleton_carousel_home_position' ) == 'full' ) {
	$full = '-full';
} else {
	$full = '';
}

// If we get this far, we have widgets.
?>
<div id="carousel-home-top<?php echo $full; ?>" class="carousel slide hidden-xs" data-ride="carousel"<?php echo !empty( $full ) ? ' style="display:none;"'/*displayed in script.js*/ : ''; ?>>
	<?php $n = count_sidebar_widgets( 'sidebar-carousel' ); ?>
	<?php if ( $n > 1 ) : ?>
		<ol class="carousel-indicators">
			<?php for( $i = 0; $i < $n; $i++ ) { ?>
				<li data-target="#carousel-home-top<?php echo $full; ?>" data-slide-to="<?php echo $i; ?>"<?php echo $i == 0 ? ' class="active"' : ''?>></li>
			<?php } ?>
		</ol>
	<?php endif; ?>
	<ul class="carousel-inner" role="listbox">
		<?php dynamic_sidebar( 'sidebar-carousel' ); ?>
	</ul>
	<?php if ( $n > 1 ) : ?>
		<a class="left carousel-control" href="#carousel-home-top<?php echo $full; ?>" role="button" data-slide="prev">
			<span class="fa fa-chevron-left" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="right carousel-control" href="#carousel-home-top<?php echo $full; ?>" role="button" data-slide="next">
			<span class="fa fa-chevron-right" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
	<?php endif; ?>
</div><!-- .carousel -->