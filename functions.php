<?php
/**
 * Skeleton functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Skeleton
 * @since Skeleton 0.0.1
 */

require_once 'includes/pallazzio-wpghu/pallazzio-wpghu.php';
new Pallazzio_WPGHU( __FILE__, 'pallazzio' );

if(!function_exists('skeleton_setup')) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * Create your own skeleton_setup() function to override in a child theme.
 *
 * @since Skeleton 0.0.1
 */
add_action('after_setup_theme', 'skeleton_setup');
function skeleton_setup(){

	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support('title-tag');

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support('post-thumbnails');
	set_post_thumbnail_size(150, 150);

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus(array(
		'primary' => __('Primary Menu', 'skeleton'),
		'footer' => __('Footer Menu', 'skeleton')
	));

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support('html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	));

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support('post-formats', array(
		'image'
	));
}
endif; // skeleton_setup

// Register Custom Navigation Walker
require_once( 'wp-bootstrap-navwalker.php' );

/**
 * Registers a widget area.
 *
 * @link https://developer.wordpress.org/reference/functions/register_sidebar/
 *
 * @since Skeleton 0.0.1
 */
add_action('widgets_init', 'skeleton_widgets_init');
function skeleton_widgets_init(){
	register_sidebar(array(
		'name'          => __('Right Sidebar', 'skeleton'),
		'id'            => 'sidebar-right',
		'description'   => __('Add widgets here to appear in your right sidebar.', 'skeleton'),
		'before_widget' => '<section id="%1$s" class="%2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="section-title">',
		'after_title'   => '</h3>'
	));
	register_sidebar(array(
		'name'          => __('Left Sidebar', 'skeleton'),
		'id'            => 'sidebar-left',
		'description'   => __('Add widgets here to appear in your left sidebar.', 'skeleton'),
		'before_widget' => '<section id="%1$s" class="%2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="section-title">',
		'after_title'   => '</h3>'
	));
	register_sidebar(array(
		'name'          => __('Footer Widgets', 'skeleton'),
		'id'            => 'sidebar-footer',
		'description'   => __('Add widgets here to appear in your footer.', 'skeleton'),
		'before_widget' => '<div id="%1$s" class="col-md-3 col-sm-6 %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="foot-title">',
		'after_title'   => '</h3>'
	));
	register_sidebar(array(
		'name'          => __('Carousel Slides', 'skeleton'),
		'id'            => 'sidebar-carousel',
		'description'   => __('Add "Carousel Slide" widgets here to appear in your carousel.', 'skeleton'),
		'before_widget' => '<li class="item">',
		'after_widget'  => '</li>',
		'before_title'  => '<h3 class="slide-title">',
		'after_title'   => '</h3>'
	));
}

/**
 * Counts number of active widgets in a given sidebar
 *
 * @since Skeleton 0.0.1
 */
function count_sidebar_widgets($sidebar_id, $echo = false){
	$the_sidebars = wp_get_sidebars_widgets();
	if(!isset($the_sidebars[$sidebar_id])){
		return __('Invalid sidebar ID');
	}
	if($echo){
		echo count($the_sidebars[$sidebar_id]);
	}else{
		return count($the_sidebars[$sidebar_id]);
	}
}

/**
 * Adds "active" CSS class to dynamic sidebar widgets. Also adds numeric index class for each widget (widget-1, widget-2, etc.)
 *
 * @since Skeleton 0.0.1
 */
add_filter('dynamic_sidebar_params', 'widget_active_class');
function widget_active_class($params) {
	global $my_widget_num; // Global a counter array
	$this_id = $params[0]['id']; // Get the id for the current sidebar we're processing
	$arr_registered_widgets = wp_get_sidebars_widgets(); // Get an array of ALL registered widgets	

	if(!$my_widget_num){ // If the counter array doesn't exist, create it
		$my_widget_num = array();
	}

	if(!isset($arr_registered_widgets[$this_id]) || !is_array($arr_registered_widgets[$this_id])) { // Check if the current sidebar has no widgets
		return $params; // No widgets in this sidebar... bail early.
	}

	if(isset($my_widget_num[$this_id])){ // See if the counter array has an entry for this sidebar
		$my_widget_num[$this_id]++;
	}else{ // If not, create it starting with 1
		$my_widget_num[$this_id] = 1;
	}
	
	$class = 'class="';

	if($my_widget_num[$this_id] == 1){ // If this is the first widget
		$class .= 'active ';
	}

	$params[0]['before_widget'] = str_replace('class="', $class, $params[0]['before_widget']); // Insert our new classes into "before widget"

	return $params;
}

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Skeleton 0.0.1
 */
add_action('wp_head', 'skeleton_javascript_detection', 0);
function skeleton_javascript_detection(){
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}

// Remove Emojis
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// Remove jQuery version included with WordPress. It is included below.
add_filter('wp_enqueue_scripts', 'skeleton_unqueue', PHP_INT_MAX);
function skeleton_unqueue(){
	wp_dequeue_script('jquery');
	wp_deregister_script('jquery');
}

// Remove certain widgets that cause 404 errors when Yoast SEO is installed
add_action( 'widgets_init', 'skeleton_unregister_default_widgets', 11 );
function skeleton_unregister_default_widgets() {
	//unregister_widget('WP_Widget_Pages');
	//unregister_widget('WP_Widget_Calendar');
	//unregister_widget('WP_Widget_Archives');
	//unregister_widget('WP_Widget_Links');
	//unregister_widget('WP_Widget_Meta');
	//unregister_widget('WP_Widget_Search');
	//unregister_widget('WP_Widget_Text');
	unregister_widget('WP_Widget_Categories');
	//unregister_widget('WP_Widget_Recent_Posts');
	//unregister_widget('WP_Widget_Recent_Comments');
	//unregister_widget('WP_Widget_RSS');
	//unregister_widget('WP_Widget_Tag_Cloud');
	//unregister_widget('WP_Nav_Menu_Widget');
}

/**
 * Upon inserting an image into a post, replace 'src' with 'data-src' for lazy loading.
 *
 * @since Skeleton 0.0.1
 */
add_filter('get_image_tag', 'skeleton_image_tag', 10, 1);
function skeleton_image_tag($img){
	return str_replace(array('src="', 'class="'), array('src="'.get_template_directory_uri().'/images/placeholder.png" data-src="', 'class="img-responsive lazy thumbnail '), $img);
}

/**
 * Enqueue scripts and styles.
 *
 * @since Skeleton 0.0.1
 */
add_action('wp_enqueue_scripts', 'skeleton_enqueue');
function skeleton_enqueue() {
	wp_enqueue_style( 'skeleton-bootstrap', get_template_directory_uri() . '/css/bootstrap.css', array(), '20170101', 'all' );
	wp_enqueue_style( 'skeleton-smartmenus-bootstrap', get_template_directory_uri() . '/css/jquery.smartmenus.bootstrap.css', array(), '20170101', 'all' );
	wp_enqueue_style( 'skeleton-font-awesome', get_template_directory_uri() . '/css/font-awesome.css', array(), '20170101', 'all' );
	wp_enqueue_style( 'skeleton-gallery', get_template_directory_uri() . '/css/blueimp-gallery.min.css', array(), '20170101', 'all' );
	wp_enqueue_style( 'skeleton-style', get_template_directory_uri() . '/css/style.css', array(), '20170101', 'all' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script( 'skeleton-jquery', get_template_directory_uri() . '/js/jquery.js', array(), '20170101', true );
	wp_enqueue_script( 'skeleton-bootstrap', get_template_directory_uri() . '/js/bootstrap.js', array(), '20170101', true );
	wp_enqueue_script( 'skeleton-smartmenus', get_template_directory_uri() . '/js/jquery.smartmenus.js', array(), '20170101', true );
	wp_enqueue_script( 'skeleton-smartmenus-bootstrap', get_template_directory_uri() . '/js/jquery.smartmenus.bootstrap.js', array(), '20170101', true );
	wp_enqueue_script( 'skeleton-lazyload', get_template_directory_uri() . '/js/lazyload.js', array(), '20170101', true );
	wp_enqueue_script( 'skeleton-gallery', get_template_directory_uri() . '/js/blueimp-gallery.min.js', array(), '20170101', true );
	
	if ( get_option( 'skeleton_page_transition' ) != 'disabled' ) {
		wp_enqueue_script( 'skeleton-barba', get_template_directory_uri().'/js/barba.js', array(), '20170101', true );
		$transition = get_option( 'skeleton_page_transition' );
		if ( empty( $transition ) ) {
			// Set default to 'fade' if it hasn't been set to anything yet.
			$transition = 'fade';
		}
		wp_enqueue_script( 'skeleton-barba-'.$transition, get_template_directory_uri().'/js/barba-'.$transition.'.js', array(), '20170101', true );
	}
	
	wp_enqueue_script( 'skeleton-script', get_template_directory_uri().'/js/script.js', array(), '20170101', true );
}

/**
 * Enqueue styles for asynchronous delivery.
 *
 * @since Skeleton 0.0.1
 */
/*
add_action('wp_footer', 'skeleton_enqueue_async_style', 999);
function skeleton_enqueue_async_style(){ ?>
	<script type="text/javascript">
		// defer additional styles for page speed purposes
		$(document).ready(function(){
			c = ['gallery'];
			c.forEach(function(i){
				d = document.createElement('link');
				d.rel = 'stylesheet';
				d.href = '<?php echo get_template_directory_uri(); ?>/css/'+i+'.css';
				document.getElementsByTagName('head')[0].appendChild(d);
			})
		});
	</script>
<?php }
*/

/**
 * Enqueue scripts for asynchronous delivery.
 *
 * @since Skeleton 0.0.1
 */
/*
add_action('wp_footer', 'skeleton_enqueue_async_script', 999);
function skeleton_enqueue_async_script(){ ?>
	<script type="text/javascript">
		// defer additional javascript for page speed purposes
		$(document).ready(){function(){
			s = ['bootstrap', 'lazyload', 'gallery', 'barba', 'script'];
			s.forEach(function(i){
				d = document.createElement('script');
				d.src = '<?php echo get_template_directory_uri(); ?>/js/'+i+'.js';
				document.getElementsByTagName('body')[0].appendChild(d);
			})
		}};
	</script>
<?php }
*/

/* Stop WordPress from ammoyoingly adding <br /> and <p></p> tags. If I want them... I WILL ADD THEM! */
//remove_filter('the_content','wpautop');

/**
 * Replace comment form fields with bootstrap compliant markup
 *
 * @since Skeleton 0.0.1
 */
add_filter('comment_form_default_fields', 'skeleton_comment_form_fields');
function skeleton_comment_form_fields($fields){
	$commenter = wp_get_current_commenter();
	
	$req = get_option('require_name_email');
	$aria_req = ($req ? ' aria-required="true"' : '');
	$html5 = current_theme_supports('html5', 'comment-form') ? 1 : 0;
	
	$fields = array(
		'author' => '<div class="form-group comment-form-author">'.'<label for="author">'.__('Name').($req ? ' <span class="required">*</span>' : '').'</label> '.
		'<input class="form-control" id="author" name="author" type="text" value="'.esc_attr($commenter['comment_author']).'" size="30"'.$aria_req.' /></div>',
		'email' => '<div class="form-group comment-form-email"><label for="email">'.__('Email').($req ? ' <span class="required">*</span>' : '' ).'</label> '.
		'<input class="form-control" id="email" name="email" '.($html5 ? 'type="email"' : 'type="text"').' value="'.esc_attr($commenter['comment_author_email']).'" size="30"'.$aria_req.' /></div>',
		'url' => '<div class="form-group comment-form-url"><label for="url">'.__('Website').'</label> '.
		'<input class="form-control" id="url" name="url" '.($html5 ? 'type="url"' : 'type="text"').' value="'.esc_attr($commenter['comment_author_url']).'" size="30" /></div>'
	);
	
	return $fields;
}

/**
 * Also replace comment form textarea with bootstrap compliant markup
 *
 * @since Skeleton 0.0.1
 */
add_filter('comment_form_defaults', 'skeleton_comment_form');
function skeleton_comment_form( $args ) {
	$args['comment_field'] = '<div class="form-group comment-form-comment">'.
		'<label for="comment">'._x('Comment', 'noun').'</label>'.
		'<textarea class="form-control" id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></div>';
	$args['class_submit'] = 'btn btn-primary pull-right';
	
	return $args;
}

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images
 *
 * @since Skeleton 0.0.1
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
/*
add_filter('wp_calculate_image_sizes', 'skeleton_content_image_sizes_attr', 10 , 2);
function skeleton_content_image_sizes_attr($sizes, $size){
	$width = $size[0];

	840 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 62vw, 840px';

	if('page' === get_post_type()){
		840 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	}else{
		840 > $width && 600 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 61vw, (max-width: 1362px) 45vw, 600px';
		600 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	}
	return $sizes;
}
*/

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails
 *
 * @since Skeleton 0.0.1
 *
 * @param array $attr Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size Registered image size or flat array of height and width dimensions.
 * @return string A source size value for use in a post thumbnail 'sizes' attribute.
 */
/*
add_filter('wp_get_attachment_image_attributes', 'skeleton_post_thumbnail_sizes_attr', 10 , 3);
function skeleton_post_thumbnail_sizes_attr($attr, $attachment, $size){
	if ('post-thumbnail' === $size){
		is_active_sidebar('sidebar-right') && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 60vw, (max-width: 1362px) 62vw, 840px';
		!is_active_sidebar('sidebar-right') && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 88vw, 1200px';
	}
	return $attr;
}
*/

/**
 * Get image information.
 *
 * @since Skeleton 0.0.1
 */
function skeleton_get_attachment($attachment_id){
	$attachment = get_post($attachment_id);
	return array(
		'alt' => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
		'caption' => $attachment->post_excerpt,
		'description' => $attachment->post_content,
		'href' => get_permalink($attachment->ID),
		'src' => $attachment->guid,
		'title' => $attachment->post_title
	);
}

/**
 * Replace default gallery HTML with Bootstrap compliant markup.
 *
 * @since Skeleton 0.0.1
 */
add_filter( 'post_gallery', 'skeleton_gallery', 10, 2 );
function skeleton_gallery ( $output, $attr ) {
	global $post;
	
	if ( isset ( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}
	
	extract( shortcode_atts( array(
		'order' => 'ASC',
		'orderby' => 'menu_order ID',
		'id' => $post->ID,
		'itemtag' => 'dl',
		'icontag' => 'dt',
		'captiontag' => 'dd',
		'columns' => 3,
		'size' => 'thumbnail',
		'include' => '',
		'exclude' => ''
	), $attr) );
	
	$id = intval( $id );
	if ( 'RAND' == $order ) $orderby = 'none';
	
	if ( !empty( $include ) ) {
		$include = preg_replace( '/[^0-9,]+/', '', $include );
		$_attachments = get_posts( array(
			'include' => $include,
			'post_status' => 'inherit',
			'post_type' => 'attachment',
			'post_mime_type' => 'image',
			'order' => $order,
			'orderby' => $orderby)
		);
		
		$attachments = array();
		foreach( $_attachments as $k => $v ) {
			$attachments[$v->ID] = $_attachments[$k];
		}
	}
	
	if ( empty ( $attachments ) ) return '';
	
	// HTML output.
	$output = '<ul class="gallery row">';
	
	// Loop through each attachment.
	foreach ( $attachments as $id => $attachment ) {
		// Specify which image size to use.
		$sizes = wp_get_attachment_metadata( $id );
		$meta = skeleton_get_attachment( $id );
		
		$dir = substr( $meta['src'], 0, strrpos( $meta['src'], '/' ) ) . '/';
		write_log($dir);
		
		$title = $meta['title'];
		$alt = $meta['alt'];
		
		if ( isset( $sizes['sizes']['fullscreen'] ) ) {
			$full = $sizes['sizes']['fullscreen']['file'];
		} else {
			$full = substr( $sizes['file'], strrpos( $sizes['file'], '/' ) );
		}
		$full = $dir . $full;
		
		$output .= 	'<li class="col-md-4 col-xs-6">' .
									'<a href="' . $full . '" title="' . $title . '">' .
										'<img class="img-responsive lazy thumbnail" src="' . get_template_directory_uri() . '/images/placeholder.png" ' .
										'data-src="' . $dir . $sizes['sizes']['thumbnail']['file'] . '" width="' . $sizes['sizes']['thumbnail']['width'] . '" height="' . $sizes['sizes']['thumbnail']['height'] . '" alt="' . $alt . '" />' .
									'</a>' .
								'</li>';
	} // /foreach
	
	$output .= '</ul>';
	
	return $output;
}

// add largest allowable image size
add_image_size( 'fullscreen', 1600, 1600, false );

// set jpg compression quality level
add_filter('jpeg_quality', function($arg){return 60;});

/**
 * Deletes the original uploaded image and uses the large format in its place.
 *
 * @since Skeleton 0.0.1
 */
add_filter( 'wp_generate_attachment_metadata', 'skeleton_replace_uploaded_image', 10, 1 );
function skeleton_replace_uploaded_image ( $image_data ) {
	// if there is no large image : return
	if ( !isset ( $image_data['sizes']['large'] ) ) return $image_data;

	// paths to the uploaded image and the large image
	$upload_dir = wp_upload_dir();
	$uploaded_image_location = $upload_dir['basedir'] . '/' . $image_data['file'];
	$current_subdir = substr( $image_data['file'], 0, strrpos($image_data['file'], '/' ) );
	$large_image_location = $upload_dir['basedir'] . '/' . $current_subdir . '/' . $image_data['sizes']['large']['file'];

	// delete the uploaded image
	unlink( $uploaded_image_location );

	// rename the large image
	rename( $large_image_location, $uploaded_image_location );

	// update image metadata and return them
	$image_data['width'] = $image_data['sizes']['large']['width'];
	$image_data['height'] = $image_data['sizes']['large']['height'];
	unset( $image_data['sizes']['large'] );

	return $image_data;
}

// for debugging. write to error log.
if ( !function_exists( 'write_log' ) ) {
	function write_log ( $log ) {
		if ( true === WP_DEBUG ) {
			error_log( '****************************' );
			if ( is_array( $log ) || is_object( $log ) ) {
				error_log( print_r( $log, true ) );
			} else {
				error_log( $log );
			}
		}
	}
}

// Company settings admin page
function skeleton_company_settings_page() { ?>
<div class="wrap">
	<h1>Company Info</h1>
	<form method="post" action="options.php">
		<?php
			do_settings_sections( 'company-options' );
			settings_fields( 'company-settings' );
			submit_button();
		?>          
	</form>
</div>
<?php	}

// Theme settings admin page
function skeleton_theme_settings_page() { ?>
<div class="wrap">
	<h1>Theme Settings</h1>
	<form method="post" action="options.php">
		<?php
			do_settings_sections( 'theme-options' );
			settings_fields( 'theme-settings' );
			submit_button();
		?>          
	</form>
</div>
<?php	}

// Add pages for settings under "Settings" in admin menu
add_action( 'admin_menu', 'skeleton_add_submenu_items' );
function skeleton_add_submenu_items() {
	add_submenu_page( 'options-general.php', 'Company', 'Company', 'manage_options', 'company-options', 'skeleton_company_settings_page', null, 99 );
	add_submenu_page( 'options-general.php', 'Theme', 'Theme', 'manage_options', 'theme-options', 'skeleton_theme_settings_page', null, 99 );
}

function skeleton_display_page_transition_element() { ?>
	<select name="skeleton_page_transition" id="skeleton_page_transition">
		<option value="fade" <?php selected( 'fade', get_option( 'skeleton_page_transition' ), true ); ?>>Fade</option>
		<option value="slide" <?php selected( 'slide', get_option( 'skeleton_page_transition' ), true ); ?>>Slide</option>
		<option value="none" <?php selected( 'none', get_option( 'skeleton_page_transition' ), true ); ?>>No Effect</option>
		<option value="disabled" <?php selected( 'disabled', get_option( 'skeleton_page_transition' ), true ); ?>>Disable XHR</option>
	</select>
<?php }

function skeleton_display_gallery_fullscreen_element() { ?>
	<select name="skeleton_gallery_fullscreen" id="skeleton_gallery_fullscreen">
		<option value="true" <?php selected( 'true', get_option( 'skeleton_gallery_fullscreen' ), true ); ?>>Yes</option>
		<option value="false" <?php selected( 'false', get_option( 'skeleton_gallery_fullscreen' ), true ); ?>>No</option>
	</select>
<?php }

function skeleton_display_nav_primary_layout_element() { ?>
	<select name="skeleton_nav_primary_layout" id="skeleton_nav_primary_layout">
		<option value="right" <?php selected( 'right', get_option( 'skeleton_nav_primary_layout' ), true ); ?>>Right</option>
		<option value="center" <?php selected( 'center', get_option( 'skeleton_nav_primary_layout' ), true ); ?>>Center</option>
		<option value="left" <?php selected( 'left', get_option( 'skeleton_nav_primary_layout' ), true ); ?>>Left</option>
	</select>
<?php }

function skeleton_display_carousel_home_position_element() { ?>
	<select name="skeleton_carousel_home_position" id="skeleton_carousel_home_position">
		<option value="full" <?php selected( 'full', get_option( 'skeleton_carousel_home_position' ), true ); ?>>Full Width (Above Content)</option>
		<option value="content" <?php selected( 'content', get_option( 'skeleton_carousel_home_position' ), true ); ?>>Inside Content Column</option>
	</select>
<?php }

function skeleton_display_nav_primary_dropdowns_linked_element() { ?>
	<select name="skeleton_nav_primary_dropdowns_linked" id="skeleton_nav_primary_dropdowns_linked">
		<option value="false" <?php selected( 'false', get_option( 'skeleton_nav_primary_dropdowns_linked' ), true ); ?>>No</option>
		<option value="true" <?php selected( 'true', get_option( 'skeleton_nav_primary_dropdowns_linked' ), true ); ?>>Yes</option>
	</select>
<?php }

function skeleton_display_company_phone_element() { ?>
	<input type="tel" name="skeleton_company_phone" id="skeleton_company_phone" value="<?php echo get_option( 'skeleton_company_phone' ); ?>" />
<?php }

function skeleton_display_company_address_element() { ?>
	<textarea class="regular-text" name="skeleton_company_address" id="skeleton_company_address"><?php echo get_option('skeleton_company_address'); ?></textarea>
<?php }

function skeleton_display_google_analytics_element() { ?>
	<input type="text" name="skeleton_google_analytics" id="skeleton_google_analytics" value="<?php echo get_option( 'skeleton_google_analytics' ); ?>" />
<?php }

function skeleton_display_google_domain_validation_element() { ?>
	<input class="regular-text" type="text" name="skeleton_google_domain_validation" id="skeleton_google_domain_validation" value="<?php echo get_option( 'skeleton_google_domain_validation' ); ?>" />
<?php }

function skeleton_display_bing_domain_validation_element() { ?>
	<input class="regular-text" type="text" name="skeleton_bing_domain_validation" id="skeleton_bing_domain_validation" value="<?php echo get_option( 'skeleton_bing_domain_validation' ); ?>" />
<?php }

function skeleton_display_yahoo_domain_validation_element() { ?>
	<input class="regular-text" type="text" name="skeleton_yahoo_domain_validation" id="skeleton_yahoo_domain_validation" value="<?php echo get_option( 'skeleton_yahoo_domain_validation' ); ?>" />
<?php }

function skeleton_display_facebook_element() { ?>
	<input class="regular-text" type="text" name="skeleton_facebook_url" id="skeleton_facebook_url" value="<?php echo get_option('skeleton_facebook_url'); ?>" />
<?php }

function skeleton_display_twitter_element() { ?>
	<input class="regular-text" type="text" name="skeleton_twitter_url" id="skeleton_twitter_url" value="<?php echo get_option('skeleton_twitter_url'); ?>" />
<?php }

function skeleton_display_linkedin_element() { ?>
	<input class="regular-text" type="text" name="skeleton_linkedin_url" id="skeleton_linkedin_url" value="<?php echo get_option('skeleton_linkedin_url'); ?>" />
<?php }

function skeleton_display_googleplus_element() { ?>
	<input class="regular-text" type="text" name="skeleton_googleplus_url" id="skeleton_googleplus_url" value="<?php echo get_option('skeleton_googleplus_url'); ?>" />
<?php }

function skeleton_display_youtube_element() { ?>
	<input class="regular-text" type="text" name="skeleton_youtube_url" id="skeleton_youtube_url" value="<?php echo get_option('skeleton_youtube_url'); ?>" />
<?php }

function skeleton_display_rss_element() { ?>
	<input class="regular-text" type="text" name="skeleton_rss_url" id="skeleton_rss_url" value="<?php echo get_option('skeleton_rss_url'); ?>" />
<?php }

function skeleton_display_tumblr_element() { ?>
	<input class="regular-text" type="text" name="skeleton_tumblr_url" id="skeleton_tumblr_url" value="<?php echo get_option('skeleton_tumblr_url'); ?>" />
<?php }

function skeleton_display_instagram_element() { ?>
	<input class="regular-text" type="text" name="skeleton_instagram_url" id="skeleton_instagram_url" value="<?php echo get_option('skeleton_instagram_url'); ?>" />
<?php }

function skeleton_display_pinterest_element() { ?>
	<input class="regular-text" type="text" name="skeleton_pinterest_url" id="skeleton_pinterest_url" value="<?php echo get_option('skeleton_pinterest_url'); ?>" />
<?php }

function skeleton_display_behance_element() { ?>
	<input class="regular-text" type="text" name="skeleton_behance_url" id="skeleton_behance_url" value="<?php echo get_option('skeleton_behance_url'); ?>" />
<?php }

function skeleton_display_vimeo_element() { ?>
	<input class="regular-text" type="text" name="skeleton_vimeo_url" id="skeleton_vimeo_url" value="<?php echo get_option('skeleton_vimeo_url'); ?>" />
<?php }

function skeleton_display_deviantart_element() { ?>
	<input class="regular-text" type="text" name="skeleton_deviantart_url" id="skeleton_deviantart_url" value="<?php echo get_option('skeleton_deviantart_url'); ?>" />
<?php }

function skeleton_display_digg_element() { ?>
	<input class="regular-text" type="text" name="skeleton_digg_url" id="skeleton_digg_url" value="<?php echo get_option('skeleton_digg_url'); ?>" />
<?php }

function skeleton_display_stumbleupon_element() { ?>
	<input class="regular-text" type="text" name="skeleton_stumbleupon_url" id="skeleton_stumbleupon_url" value="<?php echo get_option('skeleton_stumbleupon_url'); ?>" />
<?php }

function skeleton_display_foursquare_element() { ?>
	<input class="regular-text" type="text" name="skeleton_foursquare_url" id="skeleton_foursquare_url" value="<?php echo get_option('skeleton_foursquare_url'); ?>" />
<?php }

add_action( 'admin_init', 'skeleton_display_theme_effects_fields' );
function skeleton_display_theme_effects_fields() {
	add_settings_section( 'theme-effects-settings', 'Effects', null, 'theme-options' );
	
	add_settings_field( 'skeleton_page_transition', 'Page Transition', 'skeleton_display_page_transition_element', 'theme-options', 'theme-effects-settings' );
	add_settings_field( 'skeleton_gallery_fullscreen', 'Gallery Image Fullscreen', 'skeleton_display_gallery_fullscreen_element', 'theme-options', 'theme-effects-settings' );
	
	register_setting( 'theme-settings', 'skeleton_page_transition' );
	register_setting( 'theme-settings', 'skeleton_gallery_fullscreen' );
}

add_action( 'admin_init', 'skeleton_display_theme_layout_fields' );
function skeleton_display_theme_layout_fields() {
	add_settings_section( 'theme-layout-settings', 'Layout', null, 'theme-options' );
	
	add_settings_field( 'skeleton_nav_primary_layout', 'Primary Menu Layout', 'skeleton_display_nav_primary_layout_element', 'theme-options', 'theme-layout-settings' );
	add_settings_field( 'skeleton_carousel_home_position', 'Homepage Carousel Position', 'skeleton_display_carousel_home_position_element', 'theme-options', 'theme-layout-settings' );
	add_settings_field( 'skeleton_nav_primary_dropdowns_linked', 'Primary Menu Dropdowns Linked', 'skeleton_display_nav_primary_dropdowns_linked_element', 'theme-options', 'theme-layout-settings' );
	
	register_setting( 'theme-settings', 'skeleton_nav_primary_layout' );
	register_setting( 'theme-settings', 'skeleton_carousel_home_position' );
	register_setting( 'theme-settings', 'skeleton_nav_primary_dropdowns_linked' );
}

add_action( 'admin_init', 'skeleton_display_company_basic_info_fields' );
function skeleton_display_company_basic_info_fields() {
	add_settings_section( 'company-basic-info', 'Basic Info', null, 'company-options' );

	add_settings_field( 'skeleton_company_phone', 'Company Phone', 'skeleton_display_company_phone_element', 'company-options', 'company-basic-info' );
	add_settings_field( 'skeleton_company_address', 'Company Address', 'skeleton_display_company_address_element', 'company-options', 'company-basic-info' );
	
	register_setting( 'company-settings', 'skeleton_company_phone' );
	register_setting( 'company-settings', 'skeleton_company_address' );
}

add_action( 'admin_init', 'skeleton_display_company_technical_info_fields' );
function skeleton_display_company_technical_info_fields() {
	add_settings_section( 'company-technical-info', 'Technical Info', null, 'company-options' );

	add_settings_field( 'skeleton_google_analytics', 'Google Analytics Tracking ID<br /><small style="font-size: 0.8em;">e.g. (UA-XXXXXX-Y)</small>', 'skeleton_display_google_analytics_element', 'company-options', 'company-technical-info' );
	add_settings_field( 'skeleton_google_domain_validation', 'Google Domain Validation', 'skeleton_display_google_domain_validation_element', 'company-options', 'company-technical-info' );
	add_settings_field( 'skeleton_bing_domain_validation', 'Bing Domain Validation', 'skeleton_display_bing_domain_validation_element', 'company-options', 'company-technical-info' );
	add_settings_field( 'skeleton_yahoo_domain_validation', 'Yahoo Domain Validation', 'skeleton_display_yahoo_domain_validation_element', 'company-options', 'company-technical-info' );
	
	register_setting( 'company-settings', 'skeleton_google_analytics' );
	register_setting( 'company-settings', 'skeleton_google_domain_validation' );
	register_setting( 'company-settings', 'skeleton_bing_domain_validation' );
	register_setting( 'company-settings', 'skeleton_yahoo_domain_validation' );
}

add_action( 'admin_init', 'skeleton_display_company_social_info_fields' );
function skeleton_display_company_social_info_fields() {
	add_settings_section( 'company-social-info', 'Social Info', null, 'company-options' );

	add_settings_field( 'skeleton_facebook_url', 'Facebook URL', 'skeleton_display_facebook_element', 'company-options', 'company-social-info' );
	add_settings_field( 'skeleton_twitter_url', 'Twitter URL', 'skeleton_display_twitter_element', 'company-options', 'company-social-info' );
	add_settings_field( 'skeleton_linkedin_url', 'LinkedIn URL', 'skeleton_display_linkedin_element', 'company-options', 'company-social-info' );
	add_settings_field( 'skeleton_googleplus_url', 'Google Plus URL', 'skeleton_display_googleplus_element', 'company-options', 'company-social-info' );
	add_settings_field( 'skeleton_youtube_url', 'YouTube URL', 'skeleton_display_youtube_element', 'company-options', 'company-social-info' );
	add_settings_field( 'skeleton_rss_url', 'RSS Feed URL<small style="display: block; font-size: 0.8em;">Usually "/feed/" or "/blog/"</small>', 'skeleton_display_rss_element', 'company-options', 'company-social-info' );
	add_settings_field( 'skeleton_tumblr_url', 'Tumblr URL', 'skeleton_display_tumblr_element', 'company-options', 'company-social-info' );
	add_settings_field( 'skeleton_instagram_url', 'Instagram URL', 'skeleton_display_instagram_element', 'company-options', 'company-social-info' );
	add_settings_field( 'skeleton_pinterest_url', 'Pinterest URL', 'skeleton_display_pinterest_element', 'company-options', 'company-social-info' );
	add_settings_field( 'skeleton_behance_url', 'Behance URL', 'skeleton_display_behance_element', 'company-options', 'company-social-info' );
	add_settings_field( 'skeleton_vimeo_url', 'Vimeo URL', 'skeleton_display_vimeo_element', 'company-options', 'company-social-info' );
	add_settings_field( 'skeleton_deviantart_url', 'Deviant Art URL', 'skeleton_display_deviantart_element', 'company-options', 'company-social-info' );
	add_settings_field( 'skeleton_digg_url', 'Digg URL', 'skeleton_display_digg_element', 'company-options', 'company-social-info' );
	add_settings_field( 'skeleton_stumbleupon_url', 'StumbleUpon URL', 'skeleton_display_stumbleupon_element', 'company-options', 'company-social-info' );
	add_settings_field( 'skeleton_foursquare_url', 'FourSquare URL', 'skeleton_display_foursquare_element', 'company-options', 'company-social-info' );
	
	register_setting( 'company-settings', 'skeleton_facebook_url' );
	register_setting( 'company-settings', 'skeleton_twitter_url' );
	register_setting( 'company-settings', 'skeleton_linkedin_url' );
	register_setting( 'company-settings', 'skeleton_googleplus_url' );
	register_setting( 'company-settings', 'skeleton_youtube_url' );
	register_setting( 'company-settings', 'skeleton_rss_url' );
	register_setting( 'company-settings', 'skeleton_tumblr_url' );
	register_setting( 'company-settings', 'skeleton_instagram_url' );
	register_setting( 'company-settings', 'skeleton_pinterest_url' );
	register_setting( 'company-settings', 'skeleton_behance_url' );
	register_setting( 'company-settings', 'skeleton_vimeo_url' );
	register_setting( 'company-settings', 'skeleton_deviantart_url' );
	register_setting( 'company-settings', 'skeleton_digg_url' );
	register_setting( 'company-settings', 'skeleton_stumbleupon_url' );
	register_setting( 'company-settings', 'skeleton_foursquare_url' );
}

/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 *
 * Create your own skeleton_post_thumbnail() function to override in a child theme.
 *
 * @since Skeleton 0.0.1
 */
function skeleton_post_thumbnail(){
	if(post_password_required() || is_attachment() || !has_post_thumbnail()){
		return;
	}

	if(is_singular()):
	?>

	<div class="post-thumbnail">
		<?php the_post_thumbnail(); ?>
	</div><!-- .post-thumbnail -->

	<?php else: ?>

	<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
		<?php the_post_thumbnail('post-thumbnail', array('alt' => the_title_attribute('echo=0'))); ?>
	</a>

	<?php endif;
}

/**
 * Displays the optional excerpt.
 *
 * Wraps the excerpt in a div element.
 *
 * Create your own skeleton_excerpt() function to override in a child theme.
 *
 * @since Skeleton 0.0.1
 *
 * @param string $class Optional. Class string of the div element. Defaults to 'entry-summary'.
 */
add_filter('excerpt', 'skeleton_excerpt');
function skeleton_excerpt($class = 'entry-summary'){
	$class = esc_attr($class);
		if(has_excerpt() || is_search()): ?>
		<div class="<?php echo $class; ?>">
			<?php the_excerpt(); ?>
		</div><!-- .<?php echo $class; ?> -->
	<?php endif;
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... and
 * a 'Continue reading' link.
 *
 * Create your own skeleton_excerpt_more() function to override in a child theme.
 *
 * @since Skeleton 0.0.1
 *
 * @return string 'Continue reading' link prepended with an ellipsis.
 */
add_filter('excerpt_more', 'skeleton_excerpt_more');
function skeleton_excerpt_more(){
	$link = sprintf('<a href="%1$s" class="more-link">%2$s</a>',
		esc_url(get_permalink( get_the_ID())),
		/* %s: Name of current post */
		sprintf(__('Continue reading<span class="screen-reader-text"> "%s"</span>', 'skeleton'), get_the_title(get_the_ID()))
	);
	return ' &hellip; '.$link;
}

/**
 * Determines whether blog/site has more than one category.
 *
 * Create your own skeleton_categorized_blog() function to override in a child theme.
 *
 * @since Skeleton 0.0.1
 *
 * @return bool True if there is more than one category, false otherwise.
 */
function skeleton_categorized_blog(){
	if(false === ($cats = get_transient('skeleton_categories'))){
		// Create an array of all the categories that are attached to posts.
		$cats = get_categories(array(
			'fields'     => 'ids',
			// We only need to know if there is more than one category.
			'number'     => 2,
		));

		// Count the number of categories that are attached to the posts.
		$cats = count($cats);

		set_transient('skeleton_categories', $cats);
	}

	if($cats > 1){
		// This blog has more than 1 category so skeleton_categorized_blog should return true.
		return true;
	}else{
		// This blog has only 1 category so skeleton_categorized_blog should return false.
		return false;
	}
}

/**
 * Prints HTML with meta information for the categories, tags.
 *
 * Create your own skeleton_entry_meta() function to override in a child theme.
 *
 * @since Skeleton 0.0.1
 */
function skeleton_entry_meta(){
	if('post' === get_post_type()){
		$author_avatar_size = apply_filters('skeleton_author_avatar_size', 49);
		printf('<span class="byline"><span class="author vcard">%1$s<span class="screen-reader-text">%2$s </span> <a class="url fn n" href="%3$s">%4$s</a></span></span>',
			get_avatar(get_the_author_meta('user_email'), $author_avatar_size),
			_x('Author', 'Used before post author name.', 'skeleton'),
			esc_url(get_author_posts_url(get_the_author_meta('ID'))),
			get_the_author()
		);
	}

	if(in_array(get_post_type(), array('post', 'attachment'))){
		skeleton_entry_date();
	}

	$format = get_post_format();
	if(current_theme_supports('post-formats', $format)){
		printf('<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
			sprintf('<span class="screen-reader-text">%s </span>', _x('Format', 'Used before post format.', 'skeleton')),
			esc_url(get_post_format_link($format)),
			get_post_format_string($format)
		);
	}

	if('post' === get_post_type()){
		skeleton_entry_taxonomies();
	}

	if(!is_singular() && !post_password_required() && (comments_open() || get_comments_number())){
		echo '<span class="comments-link">';
		comments_popup_link(sprintf(__('Leave a comment<span class="screen-reader-text"> on %s</span>', 'skeleton'), get_the_title()));
		echo '</span>';
	}
}

/**
 * Prints HTML with date information for current post.
 *
 * Create your own skeleton_entry_date() function to override in a child theme.
 *
 * @since Skeleton 0.0.1
 */
function skeleton_entry_date(){
	$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';

	$time_string = sprintf($time_string,
		esc_attr(get_the_date('c')),
		get_the_date()
	);

	printf('<span class="posted-on"><span class="screen-reader-text">%1$s </span><a href="%2$s" rel="bookmark">%3$s</a></span>',
		_x('Posted on', 'Used before publish date.', 'skeleton'),
		esc_url(get_permalink()),
		$time_string
	);
}

/**
 * Prints HTML with category and tags for current post.
 *
 * Create your own skeleton_entry_taxonomies() function to override in a child theme.
 *
 * @since Skeleton 0.0.1
 */
function skeleton_entry_taxonomies(){
	$categories_list = get_the_category_list(_x(', ', 'Used between list items, there is a space after the comma.', 'skeleton'));
	if($categories_list && skeleton_categorized_blog()){
		printf('<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
			_x('Categories', 'Used before category names.', 'skeleton'),
			$categories_list
		);
	}

	$tags_list = get_the_tag_list('', _x(', ', 'Used between list items, there is a space after the comma.', 'skeleton'));
	if($tags_list){
		printf('<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
			_x('Tags', 'Used before tag names.', 'skeleton'),
			$tags_list
		);
	}
}

/**
 * Get rid of some unnecessary WordPress stuff.
 *
 * @since Skeleton 0.0.1
 */
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'wp_generator');

/**
 * Check page settings for page layout.
 *
 * @since Skeleton 0.0.1
 */
function get_skeleton_page_layout(){
	$skeleton_page_layout = get_post_meta( get_the_ID(), '_skeleton_page_layout', 1, true );
	// Find out what the page setting is, and which sidebars actually have active widgets as well. Check all possiblities.
	//echo(get_the_ID());
	//echo('<pre>');print_r(get_post_meta(get_the_ID()));echo('</pre>');
	if( !is_active_sidebar( 'sidebar-right' ) && !is_active_sidebar( 'sidebar-left' ) ):
		$skeleton_page_layout = 'sidebar_none';
	
	elseif( ! $skeleton_page_layout || ( $skeleton_page_layout == 'sidebar_right' && is_active_sidebar( 'sidebar-right' ) ) ):
		$skeleton_page_layout = 'sidebar_right';
		
	elseif( $skeleton_page_layout == 'sidebar_left' && is_active_sidebar( 'sidebar-left' ) ):
		$skeleton_page_layout = 'sidebar_left';
		
	elseif( $skeleton_page_layout == 'sidebar_both' && is_active_sidebar( 'sidebar-right' ) && is_active_sidebar( 'sidebar-left' ) ):
		$skeleton_page_layout = 'sidebar_both';
		
	elseif( $skeleton_page_layout == 'sidebar_both' && is_active_sidebar( 'sidebar-right' ) && !is_active_sidebar( 'sidebar-left' ) ):
		$skeleton_page_layout = 'sidebar_right';
		
	elseif( $skeleton_page_layout == 'sidebar_both' && !is_active_sidebar( 'sidebar-right' ) && is_active_sidebar( 'sidebar-left' ) ):
		$skeleton_page_layout = 'sidebar_left';
		
	else:
		$skeleton_page_layout = 'sidebar_none';
		
	endif;
	
	return $skeleton_page_layout;
}

add_action('load-post.php', 'skeleton_post_meta_boxes_setup');
add_action('load-post-new.php', 'skeleton_post_meta_boxes_setup');
function skeleton_post_meta_boxes_setup(){
  /* Add meta boxes on the 'add_meta_boxes' hook. */
  add_action('add_meta_boxes', 'skeleton_add_post_meta_boxes');
	
	/* Save post meta on the 'save_post' hook. */
	add_action('save_post', 'skeleton_save_page_layout_meta', 10, 2);
}

/* Create meta boxes on the post editor screen. */
function skeleton_add_post_meta_boxes(){
  add_meta_box(
    'skeleton-page-layout', // Unique ID
    esc_html__('Page Layout', 'example'), // Title
    'skeleton_page_layout_meta_box', // Callback function
    array('post', 'page'), // Admin page (or post type)
    'side', // Context
    'default' // Priority
  );
}

/* Display the post meta box. */
function skeleton_page_layout_meta_box($object, $box){ 
  wp_nonce_field(basename( __FILE__ ), 'skeleton_page_layout_nonce');
	?>
  <p>
		<?php $sidebar = esc_attr(get_post_meta($object->ID, '_skeleton_page_layout', true)); ?>
		<select class="widefat" name="skeleton-page-layout" id="skeleton-page-layout">
			<option value="sidebar_none"<?php echo $sidebar == 'sidebar_none' ? ' selected' : ''; ?>>No Sidebar</option>
			<option value="sidebar_left"<?php echo $sidebar == 'sidebar_left' ? ' selected' : ''; ?>>One Sidebar - Left</option>
			<option value="sidebar_right"<?php echo ($sidebar != 'sidebar_none' && $sidebar != 'sidebar_left' && $sidebar != 'sidebar_both') ? ' selected' : ''; ?>>One Sidebar - Right</option>
			<option value="sidebar_both"<?php echo $sidebar == 'sidebar_both' ? ' selected' : ''; ?>>Both Sidebars</option>
		</select>
  </p>
	<?php
}

/* Save the meta box's post metadata. */
function skeleton_save_page_layout_meta($post_id, $post){
  /* Verify the nonce before proceeding. */
  if(!isset($_POST['skeleton_page_layout_nonce']) || !wp_verify_nonce($_POST['skeleton_page_layout_nonce'], basename( __FILE__ )))
    return $post_id;

  /* Get the post type object. */
  $post_type = get_post_type_object($post->post_type);

  /* Check if the current user has permission to edit the post. */
  if(!current_user_can($post_type->cap->edit_post, $post_id))
    return $post_id;

  /* Get the posted data and sanitize it for use as an HTML class. */
  $new_meta_value = (isset($_POST['skeleton-page-layout']) ? sanitize_html_class($_POST['skeleton-page-layout']) : '');

  /* Get the meta key. */
  $meta_key = '_skeleton_page_layout';

  /* Get the meta value of the custom field key. */
  $meta_value = get_post_meta($post_id, $meta_key, true);

  /* If a new meta value was added and there was no previous value, add it. */
  if($new_meta_value && '' == $meta_value)
    add_post_meta($post_id, $meta_key, $new_meta_value, true);

  /* If the new meta value does not match the old value, update it. */
  elseif($new_meta_value && $new_meta_value != $meta_value)
    update_post_meta($post_id, $meta_key, $new_meta_value);

  /* If there is no new meta value but an old value exists, delete it. */
  elseif('' == $new_meta_value && $meta_value)
    delete_post_meta($post_id, $meta_key, $meta_value);
}

/**
 * Add custom user type and extra user meta fields for employees.
 *
 * @since Skeleton 0.0.1
 */
remove_role('employee');
$result = add_role('employee', 'Employee', array('read' => true));

add_action( 'show_user_profile', 'show_employee_profile_fields' );
add_action( 'edit_user_profile', 'show_employee_profile_fields' );
function show_employee_profile_fields($user){ ?>
	<h3>Employee Information</h3>
	<table class="form-table">
		<tr>
			<th><label for="phone">Phone Number</label></th>
			<td>
				<input type="text" name="phone" id="phone" value="<?php echo esc_attr(get_the_author_meta('phone', $user->ID)); ?>" class="regular-text" /><br />
				<span class="description">Please enter your contact number.</span>
			</td>
		</tr>
		<tr>
			<th><label for="address">Address</label></th>
			<td>
				<input type="text" name="address" id="address" value="<?php echo esc_attr(get_the_author_meta('address', $user->ID)); ?>" class="regular-text" placeholder="Street Address" /><br />
				<input type="text" name="city" id="city" value="<?php echo esc_attr(get_the_author_meta('city', $user->ID)); ?>" class="regular-text" placeholder="City" /><br />
				<input type="text" name="state" id="state" value="<?php echo esc_attr(get_the_author_meta('state', $user->ID)); ?>" class="regular-text" placeholder="State" /><br />
				<input type="text" name="zip" id="zip" value="<?php echo esc_attr(get_the_author_meta('zip', $user->ID)); ?>" class="regular-text" placeholder="Zip" /><br />
				<span class="description">Please enter your current address.</span>
			</td>
		</tr>
	</table>
<?php }

add_action('personal_options_update', 'save_employee_profile_fields');
add_action('edit_user_profile_update', 'save_employee_profile_fields');
function save_employee_profile_fields($user_id){
	if(!current_user_can('edit_user', $user_id)){
		return false;
	}
	update_user_meta($user_id, 'phone', $_POST['phone']);
	update_user_meta($user_id, 'address', $_POST['address']);
	update_user_meta($user_id, 'city', $_POST['city']);
	update_user_meta($user_id, 'state', $_POST['state']);
	update_user_meta($user_id, 'zip', $_POST['zip']);
}

/**
 * Redirect custom user "employee" after successful login.
 *
 * @param string $redirect_to URL to redirect to.
 * @param string $request URL the user is coming from.
 * @param object $user Logged user's data.
 * @return string
 */
/*
add_filter('login_redirect', 'skeleton_login_redirect', 10, 3);
function skeleton_login_redirect($redirect_to, $request, $user){
	if(isset($user->roles) && is_array($user->roles)){
		if(in_array('employee', $user->roles)){
			return home_url();
		}else{
			return admin_url();
		}
	}
}
*/






