<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since Skeleton 1.0
 */
?>
				<?php
					// Build page column structure based on the page setting, and which widgets are active.
					switch( get_skeleton_page_layout() ) {
						case 'sidebar_both':
							?>
									</main>
									<aside class="col-lg-3 col-lg-pull-9">
										<?php dynamic_sidebar( 'sidebar-left' ); ?>
									</aside>
								</div>
								<aside class="col-lg-3 col-sm-4">
									<?php dynamic_sidebar( 'sidebar-right' ); ?>
								</aside>
							<?php
							break;
						case 'sidebar_left':
							?>
									</main>
								</div>
								<aside class="col-lg-3 col-lg-pull-9 col-sm-4 col-sm-pull-8">
									<?php dynamic_sidebar( 'sidebar-left' ); ?>
								</aside>
							<?php
							break;
						case 'sidebar_none':
							?>
									</main>
								</div>
							<?php
							break;
						case 'sidebar_right':
						default:
							?>
									</main>
								</div>
								<aside class="col-lg-3 col-sm-4">
									<?php dynamic_sidebar( 'sidebar-right' ); ?>
								</aside>
							<?php
					}
				?>
			</div><!-- /.row -->
		</div><!-- /.container -->
	</div><!-- /.xhr-container -->
</div><!-- /.xhr-wrapper -->

<?php if ( ! ( isset( $_SERVER['HTTP_X_BARBA'] ) && $_SERVER['HTTP_X_BARBA'] === 'true' ) ) : // begin non-xhr footer section ?>

	<?php if( is_active_sidebar( 'sidebar-footer' ) ) : ?>
		<div class="container">
			<aside id="footer-widgets" class="row footer-widgets">
				<?php dynamic_sidebar( 'sidebar-footer' ); ?>
			</aside><!-- .footer-widgets -->
		</div><!-- /.container -->
	<?php endif; ?>
	
	<footer class="colophon">
		<div class="container">
			<div class="row">
				<div class="col-md-6 pull-left">
					<p>&copy; <?php echo date( 'Y' ); ?> <a title="<?php bloginfo( 'name' ); ?>" href="<?php bloginfo( 'url' ); ?>"><?php bloginfo( 'name' ); ?></a></p>
				</div>
				<div class="col-md-6 pull-right">
					<p class="text-right">Designed and Hosted by <a href="https://www.lmgnow.com/">Lifestyles Media Group</a> in the <span class="usa">U.S.A.</span></p>
				</div>
			</div><!-- /.row -->
		</div><!-- /.container -->
	</footer>
	<?php if ( !empty ( get_option ( 'skeleton_google_analytics' ) ) ) : ?>
		<script>
			function googleAnalyze(url){
				(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
				})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
				ga('create', '<?php echo get_option( 'skeleton_google_analytics' ); ?>', 'auto');
				ga('send', 'pageview', url);
			}
		</script>
	<?php endif; ?>
	<?php wp_footer(); ?>
	</body>
	</html>

<?php endif; // end non-xhr footer section ?>