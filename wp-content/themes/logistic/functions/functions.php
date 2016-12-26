<?php
/**
* Load necessary style and script files
*/
function ozy_enqueue_stylesheets() {

	global $ozyHelper, $ozy_data, $post;
	
	/* The HTML5 Shim is required for older browsers, mainly older versions IE */	
	if($ozyHelper->ielt9()) {
		wp_enqueue_script('html5shim', 'http://html5shim.googlecode.com/svn/trunk/html5.js');
	}
	
	if(!$ozyHelper->ielt9()){
		$ozyHelper->set_footer_style('.ozy-page-model-full #main>.container.no-vc,
		.ozy-page-model-full.ozy-page-model-no-sidebar #content>div>article>div>.wpb_row.ozy-custom-full-row,
		.ozy-page-model-full.ozy-page-model-no-sidebar #content>div>article>div>.wpb_row>.parallax-wrapper,
		.ozy-page-model-has-sidebar #main>.container {
			padding-left: 36px;
			padding-right: 36px;
		}');
	}
	
	if($ozyHelper->isie()) {
		$ozyHelper->set_footer_style('#mc_signup_submit{padding:8px !important;}');
	}

	/*modernizr*/
	wp_enqueue_script('modernizr', OZY_BASE_URL . 'scripts/modernizr.js');
	
	if(is_plugin_active("js_composer/js_composer.php")) {
		wp_enqueue_style('js_composer_front');
	}
	
	wp_enqueue_style('style', OZY_CSS_DIRECTORY_URL . 'style.css');
	
	wp_enqueue_script('logistic-global-plugins', OZY_BASE_URL . 'scripts/logistic-global-plugins.js', array('jquery'), null, true );	
	
	wp_enqueue_style('ozy-fontset', OZY_BASE_URL . 'font/font.min.css');	
	
	/*main script file*/
	wp_enqueue_script('logistic', OZY_BASE_URL . 'scripts/logistic.js', array('jquery'), null, true );

	/*Following variable will be used in logistic.js*/
	wp_localize_script( 'logistic', 'ozy_headerType', array('menu_type' => esc_js($ozy_data->menu_type), 'menu_align' => esc_js($ozy_data->menu_align), 'theme_url' => esc_js(OZY_BASE_URL)) );
	
	/*comment reply*/
	if ( is_singular() && comments_open() && (get_option('thread_comments') == 1)) {
		wp_enqueue_script('comment-reply');
	}

	/*Fancy tabs*/
	wp_register_style('fancy-tabs', OZY_BASE_URL . 'css/fancy-tabs.min.css');	
	wp_register_script('fancy-tabs', OZY_BASE_URL . 'scripts/jquery/fancy-tabs.min.js', array('jquery'), null, true );
	
	/*fancy box*/
	wp_deregister_style('fancybox');
	wp_enqueue_style('fancybox', OZY_BASE_URL . 'scripts/fancybox/jquery.fancybox.css');
	wp_enqueue_script('fancybox', OZY_BASE_URL . 'scripts/fancybox/jquery.fancybox.pack.js', array('jquery'), null, true );
	if(ozy_get_option('fancbox_media') == '1') {
		wp_enqueue_script('fancybox-media', OZY_BASE_URL . 'scripts/fancybox/helpers/jquery.fancybox-media.js', array('jquery'), null, true );
	}
	if(ozy_get_option('fancbox_thumbnail') == '1') {
		wp_enqueue_style('jquery.fancybox-thumbs', OZY_BASE_URL . 'scripts/fancybox/helpers/jquery.fancybox-thumbs.css');
		wp_enqueue_script('fancybox-thumbs', OZY_BASE_URL . 'scripts/fancybox/helpers/jquery.fancybox-thumbs.js', array('jquery'), null, true );
	}	
	
	/*woocommerce*/
	if(is_woocommerce_activated()) {
		wp_enqueue_style('ozy-woocommerce', OZY_BASE_URL . 'css/woocommerce.min.css');
	}
	
	/*font-awesome*/
	wp_enqueue_style('font-awesome', OZY_BASE_URL . 'framework/public/css/vendor/font-awesome.min.css');

	/*search & woocommerce shared library*/
	if(is_search() || ozy_check_is_woocommerce_page() || is_page_template('page-masonry-gallery.php')) {
		wp_enqueue_script('masonry');
	}
	
	/* Supersized BG slider */
	if(ozy_get_metabox('background_group.0.ozy_logistic_meta_page_background_use_slider') == '1') {
		wp_enqueue_style( 'super-sized-css', get_template_directory_uri() . '/css/supersized.min.css');
		wp_enqueue_script('super-sized', get_template_directory_uri() . '/scripts/supersized/js/supersized.3.2.7.min.js', array('jquery'), null, true );
	}
	
	/* Self Hosted Video BG */
	wp_register_script('video-background', OZY_BASE_URL . 'scripts/jquery/videobg.js', array('jquery'), null, true );			
	if(ozy_get_metabox('background_group.0.ozy_logistic_meta_page_background_use_video_self') == '1') {
		wp_enqueue_script('video-background');
	}
	
	/* YouTube Video BG */
	if(ozy_get_metabox('background_group.0.ozy_logistic_meta_page_background_use_video_youtube') == '1') {
		wp_enqueue_script('tubular-youtube', OZY_BASE_URL . '/scripts/jquery/jquery.tubular.1.0.js', array('jquery') );
	}
	
	/* Vimeo Video BG */
	if(ozy_get_metabox('background_group.0.ozy_logistic_meta_page_background_use_video_vimeo') == '1') {
		wp_enqueue_script('ok-video', OZY_BASE_URL . 'scripts/jquery/ok.video.js', array('jquery') );
	}
	
	/* 404 template */
	if(is_404()) {
		wp_enqueue_script('starfield', OZY_BASE_URL . 'scripts/404/starfield.min.js', array('jquery') );
		wp_localize_script('starfield', 'ozy404assets', array('path' => esc_js(OZY_BASE_URL)) );		
	}
	
	/* Masonry Blog template */
	if(is_page_template('page-isotope-blog.php')) {
		wp_enqueue_script('isotope', OZY_BASE_URL . 'scripts/isotope/isotope.pkgd.min.js', array('jquery') );
	}
	
	/* Animsition */
	$ozy_data->is_animsition_active = true;
	if(!ozy_get_option('disable_animsition') == '1') {
		if(is_page()) {
			if(ozy_get_metabox('disable_loader') != '1') {	
				wp_enqueue_style('animsition', OZY_BASE_URL . 'css/animsition.min.css');
			}else{
				$ozy_data->is_animsition_active = false;
			}
		}else{
			wp_enqueue_style('animsition', OZY_BASE_URL . 'css/animsition.min.css');		
		}
	}else{
		$ozy_data->is_animsition_active = false;
	}
	wp_localize_script( 'logistic', 'ozy_Animsition', array(
		'is_active' => esc_js($ozy_data->is_animsition_active))
	);	
	
	/* Countdown template */
	if(is_page_template('page-countdown.php')) {
		wp_enqueue_script('countdown', OZY_BASE_URL . 'scripts/jquery/countdown.js', array('jquery') );		
		wp_enqueue_script('starfield', OZY_BASE_URL . 'scripts/404/starfield.min.js', array('jquery') );

		if(method_exists('DateTime','diff')) {
			$end_year = ozy_get_option('countdown_year');$end_year = (int)$end_year<=0?date('Y'):$end_year;
			$end_month = ozy_get_option('countdown_month');$end_month = (int)$end_month<=0?date('m'):$end_month;
			$end_day = ozy_get_option('countdown_day');$end_day = (int)$end_day<=0?'15':$end_day;
			$end_hour = ozy_get_option('countdown_hour');$end_hour = (int)$end_hour<=0?'23':$end_hour;
			$end_minute = ozy_get_option('countdown_minute');$end_minute = (int)$end_minute<=0?'30':$end_minute;
			$end_second = ozy_get_option('countdown_second');$end_second = (int)$end_second<=0?'30':$end_second;		
			$date1 = new DateTime('now');
			$date2 = new DateTime($end_year."-".$end_month."-".$end_day." ".$end_hour .":" . $end_minute .":" . $end_second);
			$interval = $date1->diff($date2);
			wp_localize_script('starfield', 'ozy404assets', array('path' => esc_js(OZY_BASE_URL), '_days' => esc_js($interval->days), '_hours' => esc_js($interval->h), '_minutes' => esc_js($interval->i), '_seconds' => esc_js($interval->s)) );
		}
	}
	
	return;
}
add_action( 'wp_enqueue_scripts', 'ozy_enqueue_stylesheets', 18 );

/**
* This function to add action Contact Form 7 plugin to handle date time field
*/
function app_100_handle_cf7_mail_components() {
	wp_enqueue_script('datetimepicker', OZY_BASE_URL . 'scripts/datetimepicker/datetimepicker.js', array('jquery') );
	wp_enqueue_style('datetimepicker', OZY_BASE_URL . '/css/datetimepicker.css');
}
add_action( 'wpcf7_enqueue_scripts', 'app_100_handle_cf7_mail_components');

/**
* This function modifies the main WordPress query to include an array of post types instead of the default 'post' post type.
*
* @param mixed $query The original query
* @return $query The amended query
*/
function ozy_custom_search( $query ) {
	if(!is_admin()) {
		if ( isset($query->is_search) && $query->is_search ) {
			$query->set( 'post_type', array( 'product', 'post', 'page' ) );
		}
	}
	return $query;
};
add_filter( 'pre_get_posts', 'ozy_custom_search' );

function ozy_load_custom_wp_admin_style() {
	global $ozyHelper;
	wp_enqueue_script('ozy-admin', OZY_BASE_URL . 'scripts/admin/admin.js', array('jquery'), null, true );

    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');

	$params = array('ozy_theme_path' => esc_js(OZY_BASE_URL));
	wp_localize_script( 'ozy-admin', 'ozyAdminParams', $params );
	
	wp_enqueue_style( 'ozy-admin', OZY_BASE_URL . 'css/admin.css');	

	wp_enqueue_style('ozy-fontset', OZY_BASE_URL . 'font/ozy/styles.css');
	
	//wp_enqueue_style('ozy-hair-saloon-fontset', OZY_BASE_URL . 'font/hair-saloon/flaticon.css');
	wp_enqueue_style('ozy-logistic1-fontset', OZY_BASE_URL . 'font/logistic1/flaticon.css');
	wp_enqueue_style('ozy-logistic2-fontset', OZY_BASE_URL . 'font/logistic2/flaticon.css');
	wp_enqueue_style('ozy-logistic3-fontset', OZY_BASE_URL . 'font/logistic3/flaticon.css');
	wp_enqueue_style('ozy-logistic4-fontset', OZY_BASE_URL . 'font/logistic4/flaticon.css');
	wp_enqueue_style('ozy-logistic5-fontset', OZY_BASE_URL . 'font/logistic5/flaticon.css');	
	wp_enqueue_style('ozy-logistic6-fontset', OZY_BASE_URL . 'font/logistic6/flaticon.css');	
		
	// Color picker
	wp_enqueue_script('ozy-color-picker', OZY_BASE_URL . 'scripts/admin/color-picker/jquery.minicolors.js', false, '1.0', false);
	wp_enqueue_style('ozy-color-picker', OZY_BASE_URL . 'css/admin/jquery.minicolors.css', false, '1.0', 'all');	
	wp_enqueue_media();
	
}
add_action( 'admin_enqueue_scripts', 'ozy_load_custom_wp_admin_style' );

/**
* Add page model CSS to body dag
*/
add_filter('body_class','ozy_page_model_css');
function ozy_page_model_css($classes) {

	global $post, $ozy_data, $ozyHelper;
	
	$page_model = (ozy_get_option('page_model') ? ozy_get_option('page_model') : 'full');
	if(!is_search()) {
		if(ozy_get_metabox('page_model') && ozy_get_metabox('page_model') !== 'generic') {
			$page_model = ozy_get_metabox('page_model');
		}
	}
	
	$_classes = 'ozy-page-model-' . $page_model;
	$_page_type = 'page';
	if(is_single()) { $_page_type = 'blog'; }
	
	$_post_id = ozy_get_woocommerce_page_id();
	
	$use_custom_sidebar		= ozy_get_metabox('use_sidebar', 0, $_post_id);
	
	if ($_post_id > 0) { $_page_type = 'woocommerce'; }	
	
	$sidebar_position		= ozy_get_option('page_'. $_page_type .'_sidebar_position');
	$sidebar_name			= ozy_get_option('page_'. $_page_type .'_sidebar_id');
	if($use_custom_sidebar == '1') {
		$sidebar_position 	= ozy_get_metabox('sidebar_group.0.ozy_logistic_meta_page_sidebar_position', 0, $_post_id);
		$sidebar_name 		= ozy_get_metabox('sidebar_group.0.ozy_logistic_meta_page_sidebar', 0, $_post_id);
	}

	$_classes.= ' ozy-page-model-'. (($sidebar_position == 'left' || $sidebar_position == 'right') ? 'has' : 'no') .'-sidebar';
	
	// Extras
	$ozy_data->hide_everything_but_content = false;
	if(is_page_template('page-masterslider-full.php') || is_page_template('page-revo-full.php') ||
		is_page_template('page-countdown.php') || is_404()) {
		$ozy_data->hide_everything_but_content = true;
	}else{
		$_classes.= ' ozy-classic';	
	}
	
	// Hide page title?
	if(ozy_get_metabox('hide_title') !== '1' || !is_page()) {
		$_classes.= ' has-page-title';
	}else if(ozy_get_metabox('hide_title') === '1' || !is_page()) {
		$_classes.= ' no-page-title';
	}
	
	$classes[] = $_classes;
	
	return $classes;
}

function ozy_load_custom_wp_admin_stuff() {
	include(OZY_BASE_DIR . 'include/admin-icon-list.php');
	include(OZY_BASE_DIR . 'include/admin-menu-style-editor.php');
}
add_action( 'admin_footer', 'ozy_load_custom_wp_admin_stuff' );

/**
* ozy_init_test
*
* Initialize some early parameters
*/
function ozy_init_test() {
	global $ozy_data;
	
	$d = new Ozy_Mobile_Detect;
	$ozy_data->device_type		= ($d->isMobile() ? ($d->isTablet() ? 'tablet' : 'phone') : 'computer');
	$ozy_data->script_version 	= $d->getScriptVersion();	

	$ozy_data->container_width	= '1212';//'1212';//'1140';
	$ozy_data->content_width 	= '792';
	$ozy_data->sidebar_width 	= '312';
	
	$ozy_data->menu_type = 'classic';
	$ozy_data->menu_align = ozy_get_option('primary_menu_align', 'left');
	
	$ozy_data->custome_primary_menu = false;
	
	if(!isset($ozy_data->_page_content_css_name))
		$ozy_data->_page_content_css_name = '';
}
add_action( 'init', 'ozy_init_test' );

/**
* ozy_footer_stuff
*
* Footer stuffs, like back to top button, side menu etc...
*/	
function ozy_footer_stuff() {
	global $ozy_data,$ozyHelper, $post;
	$page_id = isset($post->ID) ? $post->ID : 0;
	if(ozy_get_option('back_to_top_button') == '1' && !$ozy_data->hide_everything_but_content) {
	?>
	<a href="#top" id="to-top-button" title="<?php _e('Return to Top', 'vp_textdomain') ?>"><span class="icon oic-up-open-mini"></span></a><!--#to-top-button-->
    <?php
	}
}
add_action( 'wp_footer', 'ozy_footer_stuff' );

/**
* Filter for showing attachmend counts on post listing
*/
add_filter('manage_posts_columns', 'ozy_posts_columns_attachment_count', 5);
function ozy_posts_columns_attachment_count($defaults){
    $defaults['wps_post_attachments'] = __('Attached', 'vp_textdomain');
    return $defaults;
}
/**
* Action for showing attachmend counts on post listing
*/
add_action('manage_posts_custom_column', 'ozy_posts_custom_columns_attachment_count', 5, 2);
function ozy_posts_custom_columns_attachment_count($column_name, $id){
	if($column_name === 'wps_post_attachments'){
        $attachments = get_children(array('post_parent'=>$id));
        $count = count($attachments);
        if($count !=0){echo $count;}
    }
}

/**
* ozy_init_metaboxes
*
* Initialize defined meta boxes for desired post types.
*/
function ozy_init_metaboxes()
{
	// Built path to metabox template array file
	$ozy_logistic_meta_page_tmp 		= OZY_BASE_DIR . 'admin/metabox/page.php';
	$ozy_logistic_meta_font_tmp			= OZY_BASE_DIR . 'admin/metabox/ozy_custom_font.php';
	$ozy_logistic_meta_page_blog_tmp 	= OZY_BASE_DIR . 'admin/metabox/page_blog_options.php';
	
	// Initialize the Metabox's object
	$ozy_logistic_meta_page_tmp 		= new VP_Metabox($ozy_logistic_meta_page_tmp);	
	$ozy_logistic_meta_font_tmp 		= new VP_Metabox($ozy_logistic_meta_font_tmp);
	$ozy_logistic_meta_page_blog_tmp	= new VP_Metabox($ozy_logistic_meta_page_blog_tmp);
}
add_action( 'after_setup_theme', 'ozy_init_metaboxes' );

/**
* ozy_print_inline_script
*
* Footer inline script. Prints defined inline script into to the footer.
*/	
function ozy_print_inline_script_style() {
	global $ozyHelper;
	
	$ozyHelper->set_footer_style(ozy_get_option('custom_css'));
	if($ozyHelper->footer_style) {
		echo "<style type=\"text/css\">\r\n";
		echo $ozyHelper->footer_style;
		echo "\r\n</style>\r\n";
	}

	$ozyHelper->set_footer_script(ozy_get_option('custom_script'));
	if($ozyHelper->footer_script) {
		echo "<script type=\"text/javascript\">\r\n";
		echo $ozyHelper->footer_script;
		echo "\r\n</script>\r\n";
	}	
}
add_action( 'wp_footer', 'ozy_print_inline_script_style' );

/**
* ozy_add_query_vars
*
* Adds extra paremeter to existing query vars
*
* @aVars (array) Default return parameter, set by WordPress
*/	
function ozy_add_query_vars($aVars) {
	$aVars[] = "replytocom"; // represents the name of the product category as shown in the URL
	return $aVars;
}
// hook add_query_vars function into query_vars
add_filter('query_vars', 'ozy_add_query_vars');	

/**
* ozy_cwc_rss_post_thumbnail
*
* Adds the post thumbnail to the RSS feed
*
* @content (string) set by WordPress
*/	
function ozy_cwc_rss_post_thumbnail($content) {
	global $post;
	if(isset($post->ID)) {
		if(has_post_thumbnail($post->ID)) {
			$content = '<p>' . get_the_post_thumbnail($post->ID) .
			'</p>' . get_the_content();
		}
	}
	return $content;
}
add_filter('the_excerpt_rss', 'ozy_cwc_rss_post_thumbnail');
add_filter('the_content_feed', 'ozy_cwc_rss_post_thumbnail');

/**
* wb_remove_version
*
* Removes the WordPress version from your header for security
*
* @count (int) Default return parameter, set by WordPress
*/	
function ozy_wb_remove_version() {
	return '';
}
add_filter('the_generator', 'ozy_wb_remove_version');
	
	
/**
* comment_count
*
* Removes Trackbacks from the comment cout
*
* @count (int) Default return parameter, set by WordPress
*/
function ozy_comment_count( $count ) {
	if ( ! is_admin() ) {
		global $post;//global $id;
		if(isset($post->ID)) {
			$comment = get_comments('status=approve&post_id=' . $post->ID);
			$comments_by_type = separate_comments( $comment );
			return count($comments_by_type['comment']);
		}
		return $count;
	} else {
		return $count;
	}
}
add_filter('get_comments_number', 'ozy_comment_count', 0);

/**
* ozy_excerpt_max_charlength
*
* Returns necessary sidebar CSS class definition name
*
* @charlength (int) How many words will be returned
* @cleanurl (bool) Make the returnings raw or not
* @dots (bool) Add ... end of the return
* @exceprt (string) Input string
*/
function ozy_excerpt_max_charlength($charlength, $cleanurl = false, $dots = true, $excerpt = '') {
	if(!$excerpt) {
		$excerpt =  get_the_excerpt();
	}
	$charlength++;
	$r = "";
	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			$r.= mb_substr( $subex, 0, $excut );
		} else {
			$r.= $subex;
		}
		if($dots) $r.= '...';
	} else {
		$r.= $excerpt;
	}
	
	return $cleanurl ?  ozy_cleaner($r) : $r;
}

/**
* ozy_cleaner
*
* Used to make a raw string
*
* @string (string) Input string
*/
function ozy_cleaner($string) {
	return preg_replace('/\b(https?):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $string);		
}
	
function ozy_get_option($opt_name, $default = null) {
	if($default) {
		if(!vp_option('vpt_ozy_logistic_option.ozy_logistic_' . $opt_name)) {
			return $default;
		}else{
			return vp_option('vpt_ozy_logistic_option.ozy_logistic_' . $opt_name);
		}
	}else{
		return vp_option('vpt_ozy_logistic_option.ozy_logistic_' . $opt_name);
	}
}

function ozy_get_metabox($opt_name, $default = null, $post_id = null) {
	return vp_metabox('ozy_logistic_meta_page.ozy_logistic_meta_page_' . $opt_name, $default, $post_id);
}

/**
* ozy_sidebar_check
*
* Returns necessary sidebar CSS class definition name
*
* @sidebar_position (string)
*/
function ozy_sidebar_check($sidebar_position) {
	if(is_search()) return 'no-sidebar ';
	switch($sidebar_position) {
		case 'full':
			return 'no-sidebar ';
		case 'left':
			return 'left-sidebar ';
		case 'right':
			return 'right-sidebar ';
		default:
			return 'no-sidebar ';
	}		
}
	
/** 
* A pagination function 
*
* @param integer $range: The range of the slider, works best with even numbers 
*
* Used WP functions: 
* get_pagenum_link($i) - creates the link, e.g. http://site.com/page/4 
* previous_posts_link('<span class="prev">&nbsp;</span>'); - returns the Previous page link 
* next_posts_link('<span class="next">&nbsp;</span>'); - returns the Next page link 
*/  
function get_pagination($before='',$after='',$range = 4) {  
	// output variable
	$output = "";
	
	$paged = 0;
	if (get_query_var('paged')) {
		$paged = get_query_var('paged');
	}elseif( get_query_var('page') ) {
		$paged = get_query_var('page');
	}else{
		$paged = 1;
	}

	// $paged - number of the current page  
	global $wp_query; 
	// How much pages do we have?  
	if ( !isset($max_page) ) {  
		$max_page = $wp_query->max_num_pages;  
	}  
	// We need the pagination only if there are more than 1 page  
	if($max_page > 1){
	
		$output .= $before;
		
		if(!$paged){  
			$paged = 1;  
		}  
		// On the first page, don't put the First page link  
		if($paged != 1){  
			$output .= ' <a href=' . get_pagenum_link(1) . '><span>&laquo;</span></a>';  		  
		}  
		// To the previous page  
		$output .= get_previous_posts_link('<span>&larr;</span>');  
		// We need the sliding effect only if there are more pages than is the sliding range  
		if($max_page > $range){  
			// When closer to the beginning  
			if($paged < $range){  
				for($i = 1; $i <= ($range + 1); $i++){  
					$output .= "<a href='" . get_pagenum_link($i) ."'";  
					if($i==$paged) $output .= "class='current'";  
					$output .= ">$i</a>";  
				}  
			}  
			// When closer to the end  
			elseif($paged >= ($max_page - ceil(($range/2)))){  
				for($i = $max_page - $range; $i <= $max_page; $i++){  
				$output .= "<a href='" . get_pagenum_link($i) ."'";  
				if($i==$paged) $output .= " class='current'";  
				$output .= ">$i</a>";  
			}  
		}  
		// Somewhere in the middle  
		elseif($paged >= $range && $paged < ($max_page - ceil(($range/2)))){  
			for($i = ($paged - ceil($range/2)); $i <= ($paged + ceil(($range/2))); $i++){  
				$output .= "<a href='" . get_pagenum_link($i) ."'";  
				if($i==$paged) $output .= " class='current'";  
				$output .= ">$i</a>";  
			}  
		}  
	}  
	// Less pages than the range, no sliding effect needed  
	else{  
		for($i = 1; $i <= $max_page; $i++){  
			$output .= "<a href='" . get_pagenum_link($i) ."'";  
			if($i==$paged) $output .= " class='current'";  
			$output .= ">$i</a>";  
		}  
	}  
	// Next page  
	$output .= get_next_posts_link('<span>&rarr;</span>');  
	// On the last page, don't put the Last page link  
	if($paged != $max_page){  
		$output .= ' <a href=' . get_pagenum_link($max_page) . '><span>&raquo;</span></a>';  
	}  

	$output .= $after;
	} 

	return $output;
}	

/**
* ozy_add_extra_page
*
* Category id in body and post class
*
* @classes (array) Exisiting definitions
*/
function category_id_class($classes) {
	global $post;
	if(isset($post->ID)) {
		foreach((get_the_category($post->ID)) as $category) {
			$classes [] = 'cat-' . $category->cat_ID . '-id';			
		}
	}
	return $classes;
}
add_filter('post_class', 'category_id_class');
add_filter('body_class', 'category_id_class');

/**
* ozy_add_extra_page
*
* Adds a class to the post if there is a thumbnail
*
* @classes (array) Exisiting definitions
*/
function has_thumb_class($classes) {
	global $post;
	if(isset($post->ID)){
		if( has_post_thumbnail($post->ID) ) { 
			$classes[] = 'has_thumb'; 
		}
	}
	return $classes;
}
add_filter('post_class', 'has_thumb_class');

/**
* ozy_add_extra_page
*
* We are adding and extra page to include documentation into to the admin.
*/
function ozy_add_extra_page() {
	add_menu_page(
		__('Documentation','vp_textdomain'), 
		__('Documentation','vp_textdomain'), 
		'read',
		'ozy-logistic-documentation', 
		'ozy_logistic_documentation', 
		'dashicons-editor-help' 
	);
}
add_action('admin_menu', 'ozy_add_extra_page');

function ozy_logistic_documentation() {
	echo '<iframe src="http://doc.freevision.me/logistic/" id="ozy-help-iframe" width="100%" height="800px" frameborder="0"></iframe>';
}

/**
* ozy_ajax_like
*
* Like button handling function. Parameters passed by GET
*/
function ozy_ajax_like() {
	
	$id = isset($_GET["vote_post_id"]) ? (sanitize_text_field($_GET["vote_post_id"])) : 0;
	
	if((int)$id <= 0) die( 'Invalid Operation' );
	
	$like_count = (int)get_post_meta((int)$id, "ozy_post_like_count", true);
	
	update_post_meta((int)$id, "ozy_post_like_count", $like_count + 1);
	
	echo $like_count + 1;

	exit();

}
add_action( 'wp_ajax_nopriv_ozy_ajax_like', 'ozy_ajax_like' ); 
add_action( 'wp_ajax_ozy_ajax_like', 'ozy_ajax_like' ); 

/**
* ozy_grab_ids_from_gallery
*
* In some page templates we are only using attachment IDs from gallery shortcode
*/
function ozy_grab_ids_from_gallery() {
	global $post;
	$attachment_ids = array();
	$pattern = get_shortcode_regex();
	$ids = array();
	
	if(isset($post->post_content)) {
		if (preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches ) ) { //finds the     "gallery" shortcode and puts the image ids in an associative array at $matches[3]
			$count=count($matches[3]); //in case there is more than one gallery in the post.
			for ($i = 0; $i < $count; $i++){
				$atts = shortcode_parse_atts( $matches[3][$i] );
				if ( isset( $atts['ids'] ) ){
					$attachment_ids = explode( ',', $atts['ids'] );
					$ids = array_merge($ids, $attachment_ids);
				}
			}
		}
	}
	
	return $ids;
}

/**
* ozy_add_video_embed_title
*
* In regular blog post we are using WordPress embeds as featured media before the title.
*
* @html (string)
* @url (string)
* @attr (string)
*/
function ozy_add_video_embed_title($html, $url, $attr) {
    global $ozy_data, $ozy_temporary_post_format, $ozy_global_params;
	if($ozy_temporary_post_format != '' && ($ozy_data->current_theme_template == 'page-full-blog.php' || $ozy_data->current_theme_template == 'page-regular-blog.php' || $ozy_data->current_theme_template == 'index.php' || is_single())) {
		$ozy_global_params['media_object'] = '<div class="post-' . $ozy_temporary_post_format . '">' . ($ozy_temporary_post_format === 'video' ? '<div class="ozy-video-wrapper">'. $html .'</div>' : $html )  . '</div>';
		return '';
	}
	return $html;
}
//add_filter('embed_oembed_html', 'ozy_add_video_embed_title', 10, 3);
add_filter('embed_oembed_html', 'ozy_add_video_embed_title', 99, 4);

/**
* ozy_template_include
*
* Finds and sets '$ozy_data->current_theme_template' current page template name.
*
* @t (unknown) set by WordPress
*/
function ozy_template_include( $t ){
    global $ozy_data;
	$ozy_data->current_theme_template = basename($t);
    return $t;
}
add_filter( 'template_include', 'ozy_template_include', 1 );

/**
* ozy_theme_add_editor_styles
*
* Add custom style to editor to make content as much same as live site.
*/
function ozy_theme_add_editor_styles() {
    add_editor_style( 'custom-editor-style.css' );
}
add_action( 'init', 'ozy_theme_add_editor_styles' );

/**
* custom_excerpt_length
*
* Set how many words we want on excerpt.
*
* @length (int) required for WordPress
*/
function custom_excerpt_length( $length ) {
	return 30;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

/**
* ozy_enable_more_buttons
*
* Add more buttons to the visual editor
*
* @buttons (array) early defined buttons on editor
*/
function ozy_enable_more_buttons($buttons) {
	$buttons[] = 'hr';
	$buttons[] = 'sub';
	$buttons[] = 'sup';
	$buttons[] = 'fontselect';
	$buttons[] = 'fontsizeselect';
	$buttons[] = 'cleanup';
	$buttons[] = 'charmap';
	return $buttons;
}
add_filter( 'mce_buttons_3', 'ozy_enable_more_buttons' );

/**
* ozy_customize_text_sizes
*
* Add custom text sizes in the font size drop down list of the rich text editor (TinyMCE) in WordPress.
* Value 'theme_advanced_font_sizes' needs to be added, if an overwrite to the default font sizes in the list, is needed.
*
* @initArray (array)  is a variable of type array that contains all default TinyMCE parameters.
*/
function ozy_customize_text_sizes($initArray){
	$initArray['theme_advanced_font_sizes'] = "10px,11px,12px,13px,14px,15px,16px,17px,18px,19px,20px,21px,22px,23px,24px,25px,26px,27px,28px,29px,30px,32px,48px,60px,72px,84px,96px,108px,120px";
	return $initArray;
}
add_filter('tiny_mce_before_init', 'ozy_customize_text_sizes');

/**
 * Extended Walker class for use with the
 * Twitter Bootstrap toolkit Dropdown menus in Wordpress.
 * Edited to support n-levels submenu.
 * @author johnmegahan https://gist.github.com/1597994, Emanuele 'Tex' Tessore https://gist.github.com/3765640
 */
class BootstrapNavMenuWalker extends Walker_Nav_Menu {
	function ozy_custom_menu_style_builder($json, $element_id) {
		if($json) {
			$json_obj = json_decode($json);
			if($json_obj) {
				global $ozyHelper, $ozy_data;
				$style = '';
				$ozy_data->request_a_rate_character_issue = 'no';
				if(isset($json_obj->bg_color) && $json_obj->bg_color){ $style .= 'background-color:'. $json_obj->bg_color .';'; }
				if(isset($json_obj->fn_color) && $json_obj->fn_color){ $style .= 'color:'. $json_obj->fn_color .';'; }
				if(isset($json_obj->border_color) && $json_obj->border_color && isset($json_obj->border_width) && $json_obj->border_width !== '0' ){ 
					$style .= 'border:'. $json_obj->border_width . ' solid ' . $json_obj->border_color .';'; 
				}
				if(isset($json_obj->character_issues) && $json_obj->character_issues==='1'){ $ozy_data->request_a_rate_character_issue = 'yes'; }
				if((isset($json_obj->is_form) && $json_obj->is_form==='1') && (isset($json_obj->html_content) && $json_obj->html_content)) {
					$ozy_data->request_a_rate_form = base64_decode($json_obj->html_content);
					wp_localize_script( 'logistic', 'ozy_requestARate', array('menu_id' => esc_js($element_id)) );
				}
				$ozyHelper->set_footer_style('@media screen and (min-width:1280px){#top-menu>ul>li.menu-item-'. $element_id .'>a{'. $style .';border-radius:22px;}}');
			}
		}
	}	
 
	function start_lvl( &$output, $depth = 0 , $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$submenu = ($depth > 0) ? ' sub-menu' : '';
		$output	   .= "\n$indent<ul class=\"dropdown-menu$submenu depth_$depth\">\n";
	}
 
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) 
	{		 
		if (!is_object($args))
			return false;
		
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
 
		$li_attributes = '';
		$class_names = $value = '';
 
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		
		// managing divider: add divider class to an element to get a divider before it.
		$divider_class_position = array_search('divider', $classes);
		if($divider_class_position !== false){
			$output .= "<li class=\"divider\"></li>\n";
			unset($classes[$divider_class_position]);
		}
		
		$classes[] = ($args->has_children) ? 'dropdown' : '';
		$classes[] = ($item->current || $item->current_item_ancestor) ? 'active' : '';
		$classes[] = 'menu-item-' . $item->ID;
		if($depth && $args->has_children){
			$classes[] = 'dropdown-submenu';
		}
 
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';
 
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';
 
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		$attributes .= ! empty( $item->ID )        ? ' data-id="'   . esc_attr( $item->ID        ) .'"' : '';
		$attributes .= ($args->has_children) 	    ? ' class="dropdown-toggle" data-toggle="dropdown"' : '';
 
 		//Individual Menu Style for Level 0 menu items
		if($depth===0) {
			$this->ozy_custom_menu_style_builder(get_post_meta( $item->ID, 'menu-item-style', true ), $item->ID);
		}
 
		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;
 
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
	
 
	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
		if ( !$element )
			return;
 
		$id_field = $this->db_fields['id'];
 
		//display this element
		if ( is_array( $args[0] ) )
			$args[0]['has_children'] = ! empty( $children_elements[$element->$id_field] );
		else if ( is_object( $args[0] ) )
			$args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
		$cb_args = array_merge( array(&$output, $element, $depth), $args);
		call_user_func_array(array(&$this, 'start_el'), $cb_args);
 
		$id = $element->$id_field;
 
		// descend only when the depth is right and there are childrens for this element
		if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) ) {
 
			foreach( $children_elements[ $id ] as $child ){
 
				if ( !isset($newlevel) ) {
					$newlevel = true;
					//start the child delimiter
					$cb_args = array_merge( array(&$output, $depth), $args);
					call_user_func_array(array(&$this, 'start_lvl'), $cb_args);
				}
				$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
			}
			unset( $children_elements[ $id ] );
		}
 
		if ( isset($newlevel) && $newlevel ){
			//end the child delimiter
			$cb_args = array_merge( array(&$output, $depth), $args);
			call_user_func_array(array(&$this, 'end_lvl'), $cb_args);
		}
 
		//end this element
		$cb_args = array_merge( array(&$output, $element, $depth), $args);
		call_user_func_array(array(&$this, 'end_el'), $cb_args);
	}
}

/**
* Header slider check
*/
function ozy_check_header_slider() {
	if(is_search()) return array('','');
	
	$slider_type = $slider_alias = '';
	if ( have_posts() && 
		!is_page_template('page-revo-full.php') )
	{
		wp_reset_postdata();
		global $post;
		$post_id = isset($post->ID) ? $post->ID : 0;
		if(ozy_check_is_woocommerce_shop_page()) { $post_id = ozy_get_woocommerce_page_id(); }
		
		/*Revolution slider*/
		$revo_slider_alias = ozy_get_metabox('revolution_slider', null, $post_id);
		if( $revo_slider_alias != '-1' && $revo_slider_alias != '' && function_exists('putRevSlider') ) {
			$slider_type 	= 'revo';
			$slider_alias 	= $revo_slider_alias;
		}

		/*Master slider*/
		$master_slider_alias = ozy_get_metabox('master_slider', null, $post_id);
		if( $master_slider_alias != '-1' && $master_slider_alias != '' && function_exists('masterslider') ) {
			$slider_type 	= 'master';
			$slider_alias 	= $master_slider_alias;
		}
	}
	return array($slider_type, $slider_alias);			
}

/**
* Adds header slider if defined on metaboxes
*/
function ozy_put_header_slider($args) {
	if(!is_page_template('page-revo-full.php') && !is_page_template('page-masterslider-full.php')) {	
		if(is_array($args) && isset($args[0]) && $args[0]) {
			echo '<div class="ozy-header-slider">';
			if($args[0] == 'revo') {
				if(function_exists('putRevSlider')) putRevSlider( $args[1] );
			} else if($args[0] == 'master') {
				if(function_exists('masterslider')) masterslider( $args[1] );
			}		
			echo '</div><!--#header-slider-->';
		}
	}
}		

/**
* Footer slider check
*/
function ozy_check_footer_slider() {
	if(is_search()) return array('','');
		
	$slider_type = $slider_alias = '';
	if ( have_posts() && 
		!is_page_template('page-revo-full.php') && 
		ozy_get_metabox('use_footer_slider') == '1' )
	{
		/*Revolution slider*/
		$revo_slider_alias = ozy_get_metabox('use_footer_slider_group.0.ozy_logistic_meta_page_revolution_footer_slider');
		if( $revo_slider_alias != '-1' && $revo_slider_alias != '' && function_exists('putRevSlider') ) {
			$slider_type 	= 'revo';
			$slider_alias 	= $revo_slider_alias;
		}
		
		/*Master slider*/
		$master_slider_alias = ozy_get_metabox('use_footer_slider_group.0.ozy_logistic_meta_page_master_footer_slider');
		if( $master_slider_alias != '-1' && $master_slider_alias != '' && function_exists('masterslider') ) {
			$slider_type 	= 'master';
			$slider_alias 	= $master_slider_alias;
		}		
	}
	return array($slider_type, $slider_alias);
}

/**
* Add footer slider to page if defined on metaboxes
*/
function ozy_put_footer_slider($args) {
	if(is_array($args) && isset($args[0]) && $args[0]) {
		echo '<div class="ozy-footer-slider">';
		if($args[0] == 'revo') {
			putRevSlider( $args[1] );
		} else if($args[0] == 'master') {
			masterslider( $args[1] );
		}
		echo '</div><!--#footer-slider-->';
	}
}

/**
* Load theme options generic metabox parameters for blog
*/
function ozy_blog_meta_params() {
	/*post per load*/
	$post_per_load 			= (int)vp_metabox('ozy_logistic_meta_page_blog.ozy_logistic_meta_page_blog_count');
	
	/*order & order by*/
	$order = 'ASC'; $orderby = 'date';
	$order_orderby			= vp_metabox('ozy_logistic_meta_page_blog.ozy_logistic_meta_page_blog_order');
	$order_orderby			= explode('-', $order_orderby);
	if(is_array($order_orderby) && isset($order_orderby[0]) && isset($order_orderby[1])) {
		$order = $order_orderby[1]; $orderby = $order_orderby[0];
	}
	
	/*category filter*/
	$category_filter		= vp_metabox('ozy_logistic_meta_page_blog.ozy_logistic_meta_page_blog_filter');
	
	/*check if category filter set for blog page*/
	$include_categories = vp_metabox('ozy_logistic_meta_page_blog.ozy_logistic_meta_page_blog_category');
	if(is_array($include_categories) && isset($include_categories[0]) && $include_categories[0] != '-1') {
		//user not choosed to show all categories
		$include_categories = join(',', $include_categories);
	}else{
		$include_categories = '';
	}
	
	$category_filter		= vp_metabox('ozy_logistic_meta_page_blog.ozy_logistic_meta_page_blog_filter');
	
	global $ozy_data;
	$ozy_data->_blog_order 				= $order;
	$ozy_data->_blog_orderby 			= $orderby;
	$ozy_data->_blog_include_categories = $include_categories;
	$ozy_data->_blog_post_per_load 		= $post_per_load;
	$ozy_data->_blog_category_filter	= $category_filter;
}

/**
* Load theme options generic metabox parameters for pages / posts
*/
function ozy_page_meta_params($opt_param = "page") {
	global $ozyHelper;

	/*background slider*/
	$background_use_slider = ozy_get_metabox('background_group.0.ozy_logistic_meta_page_background_use_slider');
	if($background_use_slider == '1') {
		$ozyHelper->fullscreen_slide_show();
	}	
	/*custom page options*/
	$hide_page_title		= ozy_get_metabox('hide_title');
	$hide_page_content 		= ozy_get_metabox('hide_content');
	$custom_page_title		= ozy_get_metabox('use_custom_title') == '1' ? ozy_get_metabox('custom_title') : '';
	$use_custom_sidebar		= ozy_get_metabox('use_sidebar');
	
	/*generic sidebar options*/
	$sidebar_position		= ozy_get_option('page_'.$opt_param.'_sidebar_position');
	$sidebar_name			= ozy_get_option('page_'.$opt_param.'_sidebar_id');
	
	/*custom sidebar used?*/
	if($use_custom_sidebar == '1') {
		$sidebar_position 	= ozy_get_metabox('sidebar_group.0.ozy_logistic_meta_page_sidebar_position');
		$sidebar_name 		= ozy_get_metabox('sidebar_group.0.ozy_logistic_meta_page_sidebar');
	}
	
	/*sidebar check*/
	$content_css_name = ozy_sidebar_check($sidebar_position);
	
	if($hide_page_title !== '1') {
		$content_css_name.= ' has-title ';
	}

	if(!$ozyHelper->has_shortcode('vc_row') || !function_exists('wpb_map') && (!is_single())) {
		$content_css_name.= ' no-vc ';
	}

	global $ozy_data;	
	$ozy_data->_page_background_use_slider	= $background_use_slider;
	$ozy_data->_page_hide_page_title		= $hide_page_title;
	$ozy_data->_page_hide_page_content		= $hide_page_content;
	$ozy_data->_page_custom_page_title		= $custom_page_title;
	$ozy_data->_page_use_custom_sidebar		= $use_custom_sidebar;
	$ozy_data->_page_sidebar_position		= $sidebar_position;
	$ozy_data->_page_sidebar_name			= $sidebar_name . $ozy_data->wpml_current_language_;
	if(!isset($ozy_data->_page_content_css_name)) 
		$ozy_data->_page_content_css_name = '';
	$ozy_data->_page_content_css_name		.= $content_css_name;
	
}

/**
* Load theme options and metabox parameters for woocommerce pages
*/
function ozy_woocommerce_meta_params() {
	global $ozy_data,$ozyHelper;

	/*generic sidebar options*/
	$sidebar_position		= ozy_get_option('page_woocommerce_sidebar_position');
	$sidebar_name			= ozy_get_option('page_woocommerce_sidebar_id');
	
	$post_id = ozy_get_woocommerce_page_id();
	
	$use_custom_sidebar		= ozy_get_metabox('use_sidebar', 0, $post_id);	
	
	/*custom sidebar used?*/
	if($use_custom_sidebar == '1') {
		$sidebar_position 	= ozy_get_metabox('sidebar_group.0.ozy_logistic_meta_page_sidebar_position', 0, $post_id);
		$sidebar_name 		= ozy_get_metabox('sidebar_group.0.ozy_logistic_meta_page_sidebar', 0, $post_id);
	}
	
	/*sidebar check*/
	$content_css_name = ozy_sidebar_check($sidebar_position);
	
	if(!$ozyHelper->has_shortcode('vc_row')) {
		$content_css_name.= ' no-vc ';
	}	
	
	$ozy_data->_woocommerce_use_custom_sidebar		= $use_custom_sidebar;
	$ozy_data->_woocommerce_sidebar_position		= $sidebar_position;
	$ozy_data->_woocommerce_sidebar_name			= $sidebar_name;
	$ozy_data->_woocommerce_content_css_name		= $content_css_name;
}

/**
* Load theme options generic metabox parameters for pages
*/
function ozy_page_master_meta_params() {
	wp_reset_postdata();
	global $ozyHelper;
	// background slider
	if(ozy_get_metabox('use_custom_background') == '1') {
		$meta_opt_path = 'ozy_logistic_meta_page.ozy_logistic_meta_page_background_group.0.ozy_logistic_meta_page_background_video';
		if(ozy_get_metabox('background_group.0.ozy_logistic_meta_page_background_use_slider') == '1') {
			$ozyHelper->fullscreen_slide_show();
		}
		if(ozy_get_metabox('background_group.0.ozy_logistic_meta_page_background_use_video_self') == '1') {
			$ozyHelper->fullscreen_video_show(
				vp_metabox($meta_opt_path . '_self_group.0.ozy_logistic_meta_page_background_video_self_image'),
				vp_metabox($meta_opt_path . '_self_group.0.ozy_logistic_meta_page_background_video_self_mp4'),
				vp_metabox($meta_opt_path . '_self_group.0.ozy_logistic_meta_page_background_video_self_webm'),
				vp_metabox($meta_opt_path . '_self_group.0.ozy_logistic_meta_page_background_video_self_ogv')
			);
		}
		if(ozy_get_metabox('background_group.0.ozy_logistic_meta_page_background_use_video_youtube') == '1') {
			$ozyHelper->fullscreen_youtube_video_show(
				vp_metabox($meta_opt_path . '_youtube_group.0.ozy_logistic_meta_page_background_video_youtube_image'),
				vp_metabox($meta_opt_path . '_youtube_group.0.ozy_logistic_meta_page_background_video_youtube_id')
			);
		}
		if(ozy_get_metabox('background_group.0.ozy_logistic_meta_page_background_use_video_vimeo') == '1') {
			$ozyHelper->fullscreen_vimeo_video_show(
				vp_metabox($meta_opt_path . '_vimeo_group.0.ozy_logistic_meta_page_background_video_vimeo_image'),
				vp_metabox($meta_opt_path . '_vimeo_group.0.ozy_logistic_meta_page_background_video_vimeo_id')
			);
		}	
	}
	
	// custom page options
	$hide_page_title		= ozy_get_metabox('hide_title');
	$hide_page_content 		= ozy_get_metabox('hide_content');//ozy_logistic_meta_page.ozy_logistic_meta_page_
	$custom_page_title		= ozy_get_metabox('use_custom_title') == '1' ? ozy_get_metabox('use_custom_title_group.0.ozy_logistic_meta_page_custom_title') : '';
	$custom_page_sub_title	= ozy_get_metabox('use_custom_title') == '1' ? ozy_get_metabox('use_custom_title_group.0.ozy_logistic_meta_page_custom_sub_title') : '';
	$use_custom_sidebar		= ozy_get_metabox('use_sidebar');
	
	// generic sidebar options
	// absolute
	$_page_type = 'page';
	if(is_single()) { $_page_type = 'blog'; }	
	$_post_id = ozy_get_woocommerce_page_id();	
	if ($_post_id > 0) { $_page_type = 'woocommerce'; }	
	$sidebar_position		= ozy_get_option('page_'. $_page_type .'_sidebar_position');
	$sidebar_name			= ozy_get_option('page_'. $_page_type .'_sidebar_id');
		
	// custom sidebar used?
	if($use_custom_sidebar == '1') {
		$sidebar_position 	= ozy_get_metabox('sidebar_group.0.ozy_logistic_meta_page_sidebar_position');
		$sidebar_name 		= ozy_get_metabox('sidebar_group.0.ozy_logistic_meta_page_sidebar');
	}
	
	// sidebar check
	$content_css_name = ozy_sidebar_check($sidebar_position);

	if(!$ozyHelper->has_shortcode('vc_row') || is_search()) {
		$content_css_name.= ' no-vc ';
	}
	
	global $ozy_data;
	$ozy_data->_page_hide_page_title		= $hide_page_title;
	$ozy_data->_page_hide_page_content		= $hide_page_content;
	$ozy_data->_page_custom_page_title		= $custom_page_title;
	$ozy_data->_page_custom_page_sub_title	= $custom_page_sub_title;
	$ozy_data->_page_use_custom_sidebar		= $use_custom_sidebar;
	$ozy_data->_page_sidebar_position		= $sidebar_position;
	$ozy_data->_page_sidebar_name			= $sidebar_name . $ozy_data->wpml_current_language_;
	if(!isset($ozy_data->_page_content_css_name)) 
		$ozy_data->_page_content_css_name = '';	
	$ozy_data->_page_content_css_name		.= $content_css_name;
	
	$hide_page_title_arr = array(
		'page-classic-gallery', 
		'page-horizontal-gallery', 
		'page-thumbnail-gallery',
		'page-nearby-gallery',
		'page-row-slider'
	);
	foreach($hide_page_title_arr as $p) {
		if(is_page_template($p . '.php')) {
			$ozy_data->_page_hide_page_title = 1;
			break;
		}
	}	
}

/**
* WooCommerce check and check functions
*/
if ( ! function_exists( 'is_woocommerce_activated' ) ) {
	function is_woocommerce_activated() {
		if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
	}
}

function ozy_check_is_woocommerce_page() {
	if(is_woocommerce_activated()) {
		if(is_cart() || is_checkout() || is_account_page() || is_woocommerce()) {
			return true;
		}
	}
	return false;
}

function ozy_is_product_page() {
	if(is_woocommerce_activated()) {
		if(is_cart()) {
			return true;
		}
		return false;
	}
	return false;
}

function ozy_get_woocommerce_page_id() {
	if(ozy_check_is_woocommerce_page()) {
		if(is_cart()) {
			return woocommerce_get_page_id('cart');
		}else if(is_checkout()) {
			return woocommerce_get_page_id('checkout');
		}else if(is_shop() || is_product_category() || is_product_tag()) {
			return woocommerce_get_page_id('shop');
		}else if(is_account_page()) {
			return woocommerce_get_page_id('account_page');
		}else if(is_product() ) { //|| is_product_category() || is_product_tag() to show sidebar on those pages move to is_shop() check
			global $post;
			if(isset($post->ID))
				return $post->ID;
			return null;
		}else{
			return null;
		}
	}
	return null;
}

function ozy_check_is_woocommerce_shop_page() {
	if(is_woocommerce_activated()) {
		if(is_shop() || is_product_category()) {
			return true;
		}
	}
	return false;
}

if(is_woocommerce_activated()) {
	include_once('woocommerce.php');
}

/**
* ozy_convert_classic_gallery
*
* Catches [gallery] shortcode fromt content, removes it and turns into array
*/
function ozy_convert_classic_gallery() {
	echo apply_filters('the_content', preg_replace('/\[gallery ids=[^\]]+\]/', '',  get_the_content()));
}

/**
* ozy_add_search_to_header_menu
*
* Adds search icon into classic and mega menu options
*/
function ozy_add_search_to_header_menu ( $items, $args ) {
	global $ozy_data;
	$woo_output = $search_output = $sidemenu_output = $wpml_output = "";
	if('logged-in-menu' === $args -> theme_location || 'header-menu' === $args -> theme_location || $ozy_data->custome_primary_menu) {
		if(ozy_get_option('primary_menu_search') !== '-1') {
			$search_output .= '<li class="menu-item menu-item-search">
					<a href="#top-search">
						<span>
							<i class="oic-search-1">&nbsp;</i><span>'. __('Search', 'vp_textdomain') .'</span>
						</span>
					</a>
				</li>';
		}
	}
	
	if('logged-in-menu' === $args -> theme_location || 'header-menu' === $args -> theme_location) {
		if(function_exists("icl_get_languages") && function_exists("icl_disp_language") && defined("ICL_LANGUAGE_CODE") && defined("ICL_LANGUAGE_NAME")){
			$languages = icl_get_languages('skip_missing=0&orderby=code');
			$wpml_output .= '<li class="menu-item menu-item-wpml"><a href="javascript:void(0);"><span><img src="'. $languages[ICL_LANGUAGE_CODE]['country_flag_url'] .'" alt="'. ICL_LANGUAGE_CODE .'"/><span>'. ICL_LANGUAGE_NAME .'</span></span></a>';
			$wpml_output .= '<ul>';
			if(!empty($languages)){
				foreach($languages as $l){
					$wpml_output .= '<li>';
					$wpml_output .= '<a href="' . $l['url'] . '">';
					if($l['country_flag_url']){
						$wpml_output .= '<img src="' . $l['country_flag_url'] . '" height="12" alt="' . $l['language_code'] . '" width="18" />';
					}
					$wpml_output .= icl_disp_language($l['native_name'], '');
					$wpml_output .= '</a>';
					$wpml_output .= '</li>';
				}
			}
			$wpml_output .= '</ul>';		
			$wpml_output .= '</li>';
		}	
	}
	
	if($ozy_data->menu_align === 'left') {
		$items = $search_output . $wpml_output . $items;
	}else{
		$items.= $search_output . $wpml_output;
	}
	
	return $items;
}
add_filter('wp_nav_menu_items','ozy_add_search_to_header_menu',10,2);

/**
* wp_title Filter to avoid theme check errors
*/
function ozy_filter_wp_title( $title ) {
	$filtered_title = get_bloginfo( 'name' );
	if ( is_category() ) {
		$filtered_title = 'Category Archive for &quot;' . single_cat_title('', false) . '&quot; | ' . get_bloginfo( 'name' );
	} elseif ( is_tag() ) {
		$filtered_title = 'Tag Archive for &quot;' . single_tag_title('', false) . '&quot; | ' . get_bloginfo( 'name' );
	} elseif ( is_archive() ) {
		$filtered_title = $title . ' Archive | ' . get_bloginfo( 'name' );
	} elseif ( is_search()) {
		$filtered_title = 'Search for &quot;'. esc_html(get_search_query()) .'&quot; | ' . get_bloginfo( 'name' );
	} elseif ( is_home() ) {
		$filtered_title = get_bloginfo( 'name' ) .  ' | ' . get_bloginfo( 'description' );
	}  elseif ( is_404() ) {
		$filtered_title = 'Error 404 Not Found | ' . get_bloginfo( 'name' );
	} elseif ( is_single() ) {
		$filtered_title = $title;
	} else {
		$filtered_title = get_bloginfo( 'name' );
		if($title) {
			$filtered_title .= $title;
		}		
	}	
    return $filtered_title;
}
add_filter( 'wp_title', 'ozy_filter_wp_title' );
/**
* To enable font upload, adding file mime types
*/
function ozy_custom_upload_mimes ( $existing_mimes=array() ) {
	// add your extension to the array
	$existing_mimes['eot'] 	= 'application/vnd.ms-fontobject';
	$existing_mimes['ttf'] 	= 'application/octet-stream';
	$existing_mimes['woff'] = 'application/x-woff';
	$existing_mimes['svg'] 	= 'image/svg+xml';
	
	return $existing_mimes;
}
add_filter('upload_mimes', 'ozy_custom_upload_mimes');

function ozy_custom_nextpage_links($defaults) {
	$args = array(
		'before' => '<div class="pagination">' . __('Pages: ', 'vp_textdomain'),
		'after' => '</div>',
	);
	
	$r = wp_parse_args($args, $defaults);
	
	return $r;
}
add_filter('wp_link_pages_args','ozy_custom_nextpage_links');