<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since Skeleton 1.0
 */
?>

<?php get_header(); ?>

	<?php
	// Start the loop.
	while(have_posts()): the_post();

		// Include the single post content template.
		get_template_part('template-parts/content', 'single');

		// If comments are open or we have at least one comment, load up the comment template.
		if(comments_open() || get_comments_number()){
			comments_template();
		}

		if(is_singular('attachment')){
			// Parent post navigation.
			the_post_navigation(array(
				'prev_text' => _x('<span class="meta-nav">Published in</span><span class="post-title">%title</span>', 'Parent post link', 'skeleton'),
			));
		}elseif(is_singular('post')){
			// Previous/next post navigation.
			the_post_navigation(array(
				'next_text' => '<span class="meta-nav" aria-hidden="true">'.__('Next', 'skeleton').'</span> '.
					'<span class="screen-reader-text">'.__('Next post:', 'skeleton').'</span> '.
					'<span class="post-title">%title</span>',
				'prev_text' => '<span class="meta-nav" aria-hidden="true">'.__('Previous', 'skeleton').'</span> '.
					'<span class="screen-reader-text">'.__('Previous post:', 'skeleton').'</span> '.
					'<span class="post-title">%title</span>',
			));
		}
	// End of the loop.
	endwhile; ?>

<?php get_footer(); ?>