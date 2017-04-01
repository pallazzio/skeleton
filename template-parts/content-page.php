<?php
/**
 * The template used for displaying page content
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since Skeleton 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php if(!is_front_page()): ?>
			<?php the_title('<h1 class="entry-title">', '</h1>'); ?>
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php skeleton_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(array(
			'before'      => '<div class="page-links"><span class="page-links-title">'.__('Pages:', 'skeleton').'</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
			'pagelink'    => '<span class="screen-reader-text">'.__('Page', 'skeleton').' </span>%',
			'separator'   => '<span class="screen-reader-text">, </span>',
		));
		?>
	</div><!-- .entry-content -->

	<?php
		edit_post_link(
			sprintf(
				/* %s: Name of current post */
				__('Edit<span class="screen-reader-text"> "%s"</span>', 'skeleton'),
				get_the_title()
			),
			'<footer class="entry-footer"><span class="edit-link">',
			'</span></footer><!-- .entry-footer -->'
		);
	?>
</article><!-- #post-## -->