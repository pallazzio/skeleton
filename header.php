<?php
/**
 * The template for displaying the header
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since Skeleton 1.0
 */
if ( ! ( isset( $_SERVER['HTTP_X_BARBA'] ) && $_SERVER['HTTP_X_BARBA'] === 'true' ) ) : // begin non-xhr head section

	?><!DOCTYPE html>
	<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<?php if( is_singular() && pings_open( get_queried_object() ) ) : ?>
			<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php endif; ?>
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>
	<header class="header">
		<div class="container">
			<div class="row">
				<div class="col-sm-6">
					<a class="logo" href="<?php bloginfo( 'url' ); ?>" title="<?php bloginfo( 'name' ); ?>" style="background-image:url( '<?php echo get_template_directory_uri(); ?>/images/logo.png' );"><?php bloginfo( 'name' ); ?></a><!-- Logo image as a background image for speed optimization purposes. -->
				</div>
				<div class="col-sm-6 text-right header-contact">
					<?php echo !empty( get_option( 'skeleton_company_phone' ) ) ? '<a class="company-phone" href="tel:' . get_option( 'skeleton_company_phone' ) . '">' . get_option( 'skeleton_company_phone' ) . '</a>' : ''; ?>
					<?php echo !empty( get_option( 'skeleton_company_address' ) ) ? '<address class="company-address">' . get_option( 'skeleton_company_address' ) . '</address>' : ''; ?>
					<ul class="social-links">
						<?php echo !empty( get_option( 'skeleton_facebook_url' ) ) ? '<li class="facebook"><a href="' . get_option('skeleton_facebook_url') . '" target="_blank">Like us on Facebook</a></li>' : ''; ?>
						<?php echo !empty( get_option( 'skeleton_twitter_url' ) ) ? '<li class="twitter"><a href="' . get_option('skeleton_twitter_url') . '" target="_blank">Follow us on Twitter</a></li>' : ''; ?>
						<?php echo !empty( get_option( 'skeleton_instagram_url' ) ) ? '<li class="instagram"><a href="' . get_option('skeleton_instagram_url') . '" target="_blank">Follow us on Instagram</a></li>' : ''; ?>
						<?php echo !empty( get_option( 'skeleton_linkedin_url' ) ) ? '<li class="linkedin"><a href="' . get_option('skeleton_linkedin_url') . '" target="_blank">Join us on LinkedIn</a></li>' : ''; ?>
						<?php echo !empty( get_option( 'skeleton_googleplus_url' ) ) ? '<li class="googleplus"><a href="' . get_option('skeleton_googleplus_url') . '" target="_blank">Follow us on Google Plus</a></li>' : ''; ?>
						<?php echo !empty( get_option( 'skeleton_youtube_url' ) ) ? '<li class="youtube"><a href="' . get_option('skeleton_youtube_url') . '" target="_blank">Subscribe to us on YouTube</a></li>' : ''; ?>
						<?php echo !empty( get_option( 'skeleton_rss_url' ) ) ? '<li class="rss"><a href="' . get_option('skeleton_rss_url') . '" target="_blank">Subscribe to our Blog</a></li>' : ''; ?>
						<?php echo !empty( get_option( 'skeleton_tumblr_url' ) ) ? '<li class="tumblr"><a href="' . get_option('skeleton_tumblr_url') . '" target="_blank">Follow us on Tumblr</a></li>' : ''; ?>
						<?php echo !empty( get_option( 'skeleton_pinterest_url' ) ) ? '<li class="pinterest"><a href="' . get_option('skeleton_pinterest_url') . '" target="_blank">Pin us on Pinterest</a></li>' : ''; ?>
						<?php echo !empty( get_option( 'skeleton_behance_url' ) ) ? '<li class="behance"><a href="' . get_option('skeleton_behance_url') . '" target="_blank">Check us out on Behance</a></li>' : ''; ?>
						<?php echo !empty( get_option( 'skeleton_vimeo_url' ) ) ? '<li class="vimeo"><a href="' . get_option('skeleton_vimeo_url') . '" target="_blank">Watch us on Vimeo</a></li>' : ''; ?>
						<?php echo !empty( get_option( 'skeleton_deviantart_url' ) ) ? '<li class="deviantart"><a href="' . get_option('skeleton_deviantart_url') . '" target="_blank">See us on Deviant Art</a></li>' : ''; ?>
						<?php echo !empty( get_option( 'skeleton_digg_url' ) ) ? '<li class="digg"><a href="' . get_option('skeleton_digg_url') . '" target="_blank">Digg Us</a></li>' : ''; ?>
						<?php echo !empty( get_option( 'skeleton_stumbleupon_url' ) ) ? '<li class="stumbleupon"><a href="' . get_option('skeleton_stumbleupon_url') . '" target="_blank">Stumble Upon Us</a></li>' : ''; ?>
						<?php echo !empty( get_option( 'skeleton_foursquare_url' ) ) ? '<li class="foursquare"><a href="' . get_option('skeleton_foursquare_url') . '" target="_blank">Check in with us on FourSquare</a></li>' : ''; ?>
					</ul>
				</div>
			</div><!-- /.row -->
		</div><!-- /.container -->

		<?php if( has_nav_menu( 'primary' ) ): ?>
			<nav class="navbar navbar-inverse">
				<div class="container">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#nav-primary">
							<span class="screen-reader-text">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
						</button>
					</div>
					<?php
						if( !empty( get_option( 'skeleton_nav_primary_layout' ) ) ) {
							$primary_nav_layout = get_option( 'skeleton_nav_primary_layout' );
						} else {
							$primary_nav_layout = 'right';
						}
						wp_nav_menu( array(
							'menu'              => 'primary',
							'theme_location'    => 'primary',
							'depth'             => 0,
							'container'         => 'div',
							'container_class'   => 'collapse navbar-collapse',
							'container_id'      => 'nav-primary',
							'menu_class'        => 'nav navbar-nav navbar-' . $primary_nav_layout,
							'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
							'walker'            => new WP_Bootstrap_Navwalker()
						) );
					?>
				</div><!-- /.container -->
			</nav>
		<?php endif; ?>
	</header>
		
	<?php if ( ! empty( get_bloginfo( 'description' ) ) ) : ?>
		<div class="container">
			<div class="row">
				<div class="tagline col-xs-12">
					<p><?php echo html_entity_decode( get_bloginfo( 'description' ) ); ?></p>
				</div>
			</div>
		</div>
	<?php endif; ?>
	
<?php endif; // end non-xhr head section ?>

<div id="xhr-wrapper">
	<div class="xhr-container" data-page-title="<?php echo wp_title(); ?>" data-gallery-fullscreen="<?php echo get_option( 'skeleton_gallery_fullscreen' ) == 'false' ? 'false' : 'true' ; ?>">
		<div class="container">
			<div class="row">
				<?php
					// Build page column structure based on the page setting, and which widgets are active.
					switch( get_skeleton_page_layout() ) {
						case 'sidebar_both':
							?>
								<div id="main-wrap" class="col-lg-9 col-sm-8">
									<main class="col-lg-9 col-lg-push-3">
							<?php
							break;
						case 'sidebar_left':
							?>
								<div id="main-wrap">
									<main class="col-lg-9 col-lg-push-3 col-sm-8 col-sm-push-4">
							<?php
							break;
						case 'sidebar_none':
							?>
								<div id="main-wrap">
									<main class="col-xs-12">
							<?php
							break;
						case 'sidebar_right':
						default:
							?>
								<div id="main-wrap">
									<main class="col-lg-9 col-sm-8">
							<?php
					}
					get_sidebar( 'carousel' );
				?>