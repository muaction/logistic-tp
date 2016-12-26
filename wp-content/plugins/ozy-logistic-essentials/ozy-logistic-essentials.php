<?php
/**
 * Plugin Name: ozythemes Logistic Theme Essentials
 * Plugin URI: http://themeforest.net/user/freevision/portfolio
 * Description: This plugin will enable Extended Visual Shortcodes for Visual Composer, Custom Fonts, Unlimited Custom Sidebars and few other features for your LOGISTIC theme.
 * Version: 2.5
 * Author: freevision
 */

define( 'OZY_LOGISTIC_ESSENTIALS_ACTIVATED', 1 );

/**
 * Custom post types
 */
function ozy_plugin_create_post_types() {
	
	load_plugin_textdomain('logistic-textdomain', false, basename( dirname( __FILE__ ) ) . '/translate');
	
	$essentials_options = get_option('ozy_logistic_essentials');
	
	//User managaged sidebars
	register_post_type( 'ozy_sidebars',
		array(
			'labels' => array(
				'name' => __( 'Sidebars', 'logistic-textdomain'),
				'singular_name' => __( 'Sidebars', 'logistic-textdomain'),
				'add_new' => 'Add Sidebar',
				'add_new_item' => 'Add Sidebar',
				'edit_item' => 'Edit Sidebar',
				'new_item' => 'New Sidebar',
				'view_item' => 'View Sidebars',
				'search_items' => 'Search Sidebar',
				'not_found' => 'No Sidebar found',
				'not_found_in_trash' => 'No Sidebar found in Trash'				
			),
			'can_export' => true,
			'public' => true,
			'exclude_from_search' => true,
			'publicly_queryable' => false,				
			'has_archive' => false,
			'rewrite' => false,
			'supports' => array('title'),
			'taxonomies' => array(''),
			'menu_icon' => 'dashicons-align-left'
		)
	);
	
	//User managaged fonts
	register_post_type( 'ozy_fonts',
		array(
			'labels' => array(
				'name' => __( 'Custom Fonts', 'logistic-textdomain'),
				'singular_name' => __( 'Custom Fonts', 'logistic-textdomain'),
				'add_new' => 'Add Font',
				'add_new_item' => 'Add Font',
				'edit_item' => 'Edit Font',
				'new_item' => 'New Font',
				'view_item' => 'View Font',
				'search_items' => 'Search Font',
				'not_found' => 'No Font found',
				'not_found_in_trash' => 'No Font found in Trash'				
			),
			'can_export' => true,
			'public' => true,
			'exclude_from_search' => true,
			'publicly_queryable' => false,				
			'has_archive' => false,
			'rewrite' => false,
			'supports' => array('title'),
			'taxonomies' => array(''),
			'menu_icon' => 'dashicons-editor-textcolor'
		)
	);	
}
add_action( 'init', 'ozy_plugin_create_post_types', 0 );

/**
 * Options panel for this plugin
 */
class OzyEssentialsOptionsPage_Logistic
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'ozy Essentials', 
            'manage_options', 
            'ozy-logistic-essentials-setting-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'ozy_logistic_essentials' );
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>ozy Essentials Options</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'ozy_logistic_essentials_option_group' );
                do_settings_sections( 'ozy-logistic-essentials-setting-admin' );
				do_settings_sections( 'ozy-logistic-essentials-setting-admin-twitter' );
				do_settings_sections( 'ozy-logistic-essentials-setting-admin-instagram' );
			
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'ozy_logistic_essentials_option_group', // Option group
            'ozy_logistic_essentials', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'ozy-logistic-essentials-setting-admin-twitter', 
            'Twitter Parameters', 
            array( $this, 'print_twitter_section_info' ),
            'ozy-logistic-essentials-setting-admin-twitter'
        );		

        add_settings_section(
            'ozy-logistic-essentials-setting-admin-instagram', 
            'Instagram Parameters', 
            array( $this, 'print_instagram_section_info' ),
            'ozy-logistic-essentials-setting-admin-instagram'
        );				
		
        add_settings_field(
            'twitter_consumer_key', 
            'Consumer Key', 
            array( $this, 'field_callback_twitter_consumer_key' ), 
            'ozy-logistic-essentials-setting-admin-twitter', 
            'ozy-logistic-essentials-setting-admin-twitter'
        );

		add_settings_field(
            'twitter_secret_key', 
            'Secret Key', 
            array( $this, 'field_callback_twitter_secret_key' ), 
            'ozy-logistic-essentials-setting-admin-twitter', 
            'ozy-logistic-essentials-setting-admin-twitter'
        );
		
		add_settings_field(
            'twitter_token_key', 
            'Access Token Key', 
            array( $this, 'field_callback_twitter_token_key' ), 
            'ozy-logistic-essentials-setting-admin-twitter', 
            'ozy-logistic-essentials-setting-admin-twitter'
        );
		
		add_settings_field(
            'twitter_token_secret_key', 
            'Access Token Secret Key', 
            array( $this, 'field_callback_twitter_token_secret_key' ), 
            'ozy-logistic-essentials-setting-admin-twitter', 
            'ozy-logistic-essentials-setting-admin-twitter'
        );

		add_settings_field(
            'instagram_access_token_key', 
            'Access Token Key', 
            array( $this, 'field_callback_instagram_access_token_key' ), 
            'ozy-logistic-essentials-setting-admin-instagram', 
            'ozy-logistic-essentials-setting-admin-instagram'
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
		if( !empty( $input['twitter_consumer_key'] ) )
            $input['twitter_consumer_key'] = sanitize_text_field( $input['twitter_consumer_key'] );

		if( !empty( $input['twitter_secret_key'] ) )
            $input['twitter_secret_key'] = sanitize_text_field( $input['twitter_secret_key'] );

        if( !empty( $input['twitter_token_key'] ) )
            $input['twitter_token_key'] = sanitize_text_field( $input['twitter_token_key'] );

        if( !empty( $input['twitter_token_secret_key'] ) )
            $input['twitter_token_secret_key'] = sanitize_text_field( $input['twitter_token_secret_key'] );			

		if( !empty( $input['instagram_access_token_key'] ) )
            $input['instagram_access_token_key'] = sanitize_text_field( $input['instagram_access_token_key'] );			

        return $input;
    }

    /** 
     * Print the Section text
     */
    public function print_twitter_section_info()
    {
        print 'Enter required parameters of your Twitter Dev. account <a href="https://dev.twitter.com/apps" target="_blank">https://dev.twitter.com/apps</a>';
    }	

    public function print_instagram_section_info()
    {
        print 'Enter required parameters of your Instagram  API. Get your Access Token and User ID <a href="https://api.instagram.com/oauth/authorize/?client_id=ab103e54c54747ada9e807137db52d77&redirect_uri=http://blueprintinteractive.com/tutorials/instagram/uri.php&response_type=code" target="_blank">here</a>.';
    }	

    /** 
     * Get the settings option array and print one of its values : Twitter Consumer Key
     */	
    public function field_callback_twitter_consumer_key()
    {
        printf(
            '<input type="text" id="twitter_consumer_key" name="ozy_logistic_essentials[twitter_consumer_key]" value="%s" />',
            (!isset($this->options['twitter_consumer_key']) ? '' : esc_attr( $this->options['twitter_consumer_key']))
        );
    }

    /** 
     * Get the settings option array and print one of its values : Twitter Secret Key
     */	
    public function field_callback_twitter_secret_key()
    {
        printf(
            '<input type="text" id="twitter_secret_key" name="ozy_logistic_essentials[twitter_secret_key]" value="%s" />',
            (!isset($this->options['twitter_secret_key']) ? '' : esc_attr( $this->options['twitter_secret_key']))
        );		
    }

    /** 
     * Get the settings option array and print one of its values : Twitter Token Key
     */	
    public function field_callback_twitter_token_key()
    {
        printf(
            '<input type="text" id="twitter_token_key" name="ozy_logistic_essentials[twitter_token_key]" value="%s" />',
            (!isset($this->options['twitter_token_key']) ? '' : esc_attr( $this->options['twitter_token_key']))
        );		
    }

    /** 
     * Get the settings option array and print one of its values : Twitter Token Secret Key
     */
    public function field_callback_twitter_token_secret_key()
    {
        printf(
            '<input type="text" id="twitter_token_secret_key" name="ozy_logistic_essentials[twitter_token_secret_key]" value="%s" />',
            (!isset($this->options['twitter_token_secret_key']) ? '' : esc_attr( $this->options['twitter_token_secret_key']))
        );		
    }
	
    /** 
     * Get the settings option array and print one of its values : Instagram Access Token Key
     */
    public function field_callback_instagram_access_token_key()
    {
        printf(
            '<input type="text" id="instagram_access_token_key" name="ozy_logistic_essentials[instagram_access_token_key]" value="%s" />',
            (!isset($this->options['instagram_access_token_key']) ? '' : esc_attr( $this->options['instagram_access_token_key']))
        );		
    }	

}

/** 
 * Register activation redirection
 */
register_activation_hook(__FILE__, 'ozy_essentials_plugin_activate');
add_action('admin_init', 'ozy_essentials_plugin_activate_redirect');

function ozy_essentials_plugin_activate() {
    add_option('ozy_essentials_plugin_activate_redirect', true);
}

function ozy_essentials_plugin_activate_redirect() {
    if (get_option('ozy_essentials_plugin_activate_redirect', false)) {
        delete_option('ozy_essentials_plugin_activate_redirect');
        wp_redirect('options-general.php?page=ozy-logistic-essentials-setting-admin');
    }
}

/**
 * We need this plugin to work only on admin side
 */

if( is_admin() ) {
    $ozy_essentials_options_page = new OzyEssentialsOptionsPage_Logistic();
}

/**
 * Shortcodes
 */

if ( ! function_exists( 'is_plugin_active' ) ) require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
if(is_plugin_active("js_composer/js_composer.php") && function_exists('vc_map') && function_exists('vc_set_as_theme')) {

	//Icon Selector Attribute Type
	function ozy_select_an_icon_settings_field($settings, $value) {
	   //$dependency = vc_generate_dependencies_attributes($settings);
	   return '<div class="select_an_icon">'
				 .'<input name="'.$settings['param_name']
				 .'" id="field_'.$settings['param_name']
				 .'_select" class="wpb_vc_param_value wpb-textinput '
				 .$settings['param_name'].' '.$settings['type'].'_field" type="text" value="'
				 .$value.'"/>'
			 .'</div>';
	}

	vc_add_shortcode_param('select_an_icon', 'ozy_select_an_icon_settings_field', get_template_directory_uri() .'/scripts/admin/admin.js');

	$add_css_animation = array(
		"type" => "dropdown",
		"heading" => __("CSS Animation", "logistic-textdomain"),
		"param_name" => "css_animation",
		"admin_label" => true,
		"value" => array(__("No", "logistic-textdomain") => '', __("Top to bottom", "logistic-textdomain") => "top-to-bottom", __("Bottom to top", "logistic-textdomain") => "bottom-to-top", __("Left to right", "logistic-textdomain") => "left-to-right", __("Right to left", "logistic-textdomain") => "right-to-left", __("Appear from center", "logistic-textdomain") => "appear"),
		"description" => __("Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.", "logistic-textdomain")
	);

	$animate_css_effects = array("flash","bounce","shake","tada","swing","wobble","pulse","flip","flipInX","flipOutX","flipInY","flipOutY","fadeIn","fadeInUp","fadeInDown","fadeInLeft","fadeInRight","fadeInUpBig","fadeInDownBig","fadeInLeftBig","fadeInRightBig","fadeOut","fadeOutUp","fadeOutDown","fadeOutLeft","fadeOutRight","fadeOutUpBig","fadeOutDownBig","fadeOutLeftBig","fadeOutRightBig","bounceIn","bounceInDown","bounceInUp","bounceInLeft","bounceInRight","bounceOut","bounceOutDown","bounceOutUp","bounceOutLeft","bounceOutRight","rotateIn","rotateInDownLeft","rotateInDownRight","rotateInUpLeft","rotateInUpRight","rotateOut","rotateOutDownLeft","rotateOutDownRight","rotateOutUpLeft","rotateOutUpRight","hinge","rollIn","rollOut");
	
	/**
	* Fancy Tab
	*/
	if (!function_exists('ozy_vc_fancytab_wrapper')) {
		function ozy_vc_fancytab_wrapper( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_fancytab_wrapper', $atts);
			extract(shortcode_atts(array(
				'model'			=> 'tabs-style-underline',
				'color1'		=> '',
				'color2'		=> '',
				'color3'		=> '',
				'color4'		=> '',
				'extra_css'		=> '',
				'css_animation' => ''
			), $atts));
			
			if($css_animation) {
				wp_enqueue_script('waypoints');
				$css_animation = " wpb_animate_when_almost_visible wpb_" . esc_attr($css_animation) . " ";
			}

			wp_enqueue_script('fancy-tabs');
			wp_enqueue_style('fancy-tabs');
			
			$GLOBALS['OZY_FANCY_TAB_NAV'] 		= '';
			$GLOBALS['OZY_FANCY_TAB_CONTENT'] 	= '';
			$GLOBALS['OZY_FANCY_TAB_MODEL']		= $model;
			$GLOBALS['OZY_FANCY_TAB_COUNTER']	= 1;
			
			do_shortcode($content);
			
			$output = '<nav><ul>'. $GLOBALS['OZY_FANCY_TAB_NAV'] .'</ul></nav>';
			$output.= '<div class="content-wrap">'. $GLOBALS['OZY_FANCY_TAB_CONTENT'] .'</div>';
			
			global $ozyHelper;
			$ozyHelper->set_footer_style('.tabs nav{background-color:'. esc_attr($color1) .'!important}');
			$ozyHelper->set_footer_style('.tabs nav ul li a{background-color:'. esc_attr($color2) .'!important;color:'. esc_attr($color3) .'!important}');
			$ozyHelper->set_footer_style('.tabs nav ul li:not(.tab-current) a:hover,.tabs-style-bar nav ul li a:focus{color:'. esc_attr($color4) .'!important}');
			$ozyHelper->set_footer_style('.tabs nav ul li.tab-current a{background-color:'. esc_attr($color4) .'!important;color:'. esc_attr($color2) .'!important}');

			switch($model) {
				case 'tabs-style-iconbox':
					$ozyHelper->set_footer_style('.tabs-style-iconbox nav ul li.tab-current a::after{border-top-color:'. esc_attr($color4) .'!important}');
					$ozyHelper->set_footer_style('.tabs-style-iconbox nav ul li:first-child::before, .tabs-style-iconbox nav ul li::after{background:'. esc_attr($color1) .'!important}');					
					break;
				case 'tabs-style-underline':
					$ozyHelper->set_footer_style('.tabs nav ul li a{color:'. esc_attr($color4) .'!important}');
					$ozyHelper->set_footer_style('.tabs nav ul li.tab-current a{background-color:'. esc_attr($color2) .'!important;color:'. esc_attr($color3) .'!important}');
					$ozyHelper->set_footer_style('.tabs-style-underline nav li a::after{background:'. esc_attr($color4) .'!important}');					
					break;
				case 'tabs-style-linetriangle':
					$ozyHelper->set_footer_style('.tabs nav,.tabs nav ul li.tab-current a{background-color:transparent!important;color:'. esc_attr($color3) .'!important}');
					$ozyHelper->set_footer_style('.tabs nav ul li a{background-color:transparent!important;color:'. esc_attr($color4) .'!important}');
					$ozyHelper->set_footer_style('.tabs nav ul li.tab-current a:after{border-top-color:'. esc_attr($color1) .'!important}');
					break;
				case 'tabs-style-topline':
					$ozyHelper->set_footer_style('.tabs-style-topline nav ul li a{background-color:'. esc_attr($color1) .'!important;color:'. esc_attr($color3) .'!important}');
					$ozyHelper->set_footer_style('.tabs-style-topline nav ul li.tab-current a{background-color:'. esc_attr($color2) .'!important;color:'. esc_attr($color4) .'!important}');
					$ozyHelper->set_footer_style('.tabs-style-topline nav li.tab-current a{box-shadow:inset 0 3px 0 '. esc_attr($color4) .'!important}');
					$ozyHelper->set_footer_style('.tabs-style-topline nav li.tab-current{border-top-color:'. esc_attr($color4) .'!important;}');
					$ozyHelper->set_footer_style('.tabs-style-topline nav li{border-bottom:none!important}');
					break;
				case 'tabs-style-iconfall':
					$ozyHelper->set_footer_style('.tabs-style-iconfall nav ul li a{color:'. esc_attr($color3) .'!important}');
					$ozyHelper->set_footer_style('.tabs-style-iconfall nav ul li.tab-current a{color:'. esc_attr($color4) .'!important}');
					$ozyHelper->set_footer_style('.tabs-style-iconfall nav ul li.tab-current a,.tabs-style-iconfall nav ul li a{background-color:transparent!important}');
					$ozyHelper->set_footer_style('.tabs-style-iconfall nav li::before{background-color:'. esc_attr($color4) .'!important}');
					break;
			}

		 	unset($GLOBALS['OZY_FANCY_TAB_NAV']);
			unset($GLOBALS['OZY_FANCY_TAB_CONTENT']);
			unset($GLOBALS['OZY_FANCY_TAB_MODEL']);
			unset($GLOBALS['OZY_FANCY_TAB_COUNTER']);
			
			return '<div class="tabs '. esc_attr($model) .'">'. $output .'</div>';
		}
		
		add_shortcode('ozy_vc_fancytab_wrapper', 'ozy_vc_fancytab_wrapper');
		
		vc_map( array(
			"name" => __("Fancy Tab", "logistic-textdomain"),
			"base" => "ozy_vc_fancytab_wrapper",
			"as_parent" => array('only' => 'ozy_vc_fancytab'),
			"content_element" => true,
			"show_settings_on_create" => false,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "dropdown",
					"heading" => __("Model", "logistic-textdomain"),
					"param_name" => "model",
					"value" => array("tabs-style-bar","tabs-style-iconbox","tabs-style-underline","tabs-style-linetriangle","tabs-style-topline","tabs-style-iconfall"),
					"admin_label" => true
				),array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Color #1", "logistic-textdomain"),
					"param_name" => "color1",
					"admin_label" => true,
					"value" => "rgba(40,44,42,0.05)"
				),array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Color #2", "logistic-textdomain"),
					"param_name" => "color2",
					"admin_label" => true,
					"value" => "#ffffff"
				),array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Color #3", "logistic-textdomain"),
					"param_name" => "color3",
					"admin_label" => true,
					"value" => "#000000"
				),array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Color #4", "logistic-textdomain"),
					"param_name" => "color4",
					"admin_label" => true,
					"value" => "#34ccff"
				),
				$add_css_animation,
				array(
					"type" => "textfield",
					"heading" => __("Extra class name", "logistic-textdomain"),
					"param_name" => "el_class",
					"description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "logistic-textdomain")
				)			
		   ),
		   "js_view" => 'VcColumnView'
		) );
	}

	if (!function_exists('ozy_vc_fancytab')) {
		function ozy_vc_fancytab( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_fancytab', $atts);
			extract(shortcode_atts(array(
				'icon'		=> '',
				'title'		=> ''
			), $atts));

			$rand_id = 'tab-section-' . $GLOBALS['OZY_FANCY_TAB_COUNTER'];

			$GLOBALS['OZY_FANCY_TAB_NAV'] 		.= '<li><a href="#'. esc_attr($rand_id) .'" class="'. ($icon && $GLOBALS['OZY_FANCY_TAB_MODEL'] != 'tabs-style-linetriangle' ? 'icon ' . esc_attr($icon) : '') .'"><span>'.esc_attr($title) .'</span></a></li>';
			$GLOBALS['OZY_FANCY_TAB_CONTENT'] 	.= '<section id="'. esc_attr($rand_id) .'">'. $content .'</section>';
			$GLOBALS['OZY_FANCY_TAB_COUNTER']	= $GLOBALS['OZY_FANCY_TAB_COUNTER'] + 1;
			return null;
		}
		
		add_shortcode('ozy_vc_fancytab', 'ozy_vc_fancytab');

		vc_map( array(
			"name" => __("Fancy Tab - Item", "logistic-textdomain"),
			"base" => "ozy_vc_fancytab",
			"content_element" => true,
			"as_child" => array('only' => 'ozy_vc_fancytab_wrapper'),
			"icon" => "icon-wpb-ozy-el",
			"params" => array(
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Title", "logistic-textdomain"),
					"param_name" => "title",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "select_an_icon",
					"heading" => __("Icon", "logistic-textdomain"),
					"param_name" => "icon",
					"value" => '',
					"admin_label" => false,
					"description" => __("Once you select an Icon, title will not be shown on overlay.", "logistic-textdomain")
				),array(
					"type" => "textarea_html",
					"class" => "",
					"heading" => __("Content", "logistic-textdomain"),
					"param_name" => "content",
					"admin_label" => false,
					"value" => ""
				)
		   )
		) );
	}

	class WPBakeryShortCode_Ozy_Vc_Fancytab_Wrapper extends WPBakeryShortCodesContainer{}
	class WPBakeryShortCode_Ozy_Vc_Fancytab extends WPBakeryShortCode{}	
	
	/*
	* Knockout Text
	*/
	if (!function_exists('ozy_vc_knockout_text')) {
		function ozy_vc_knockout_text( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_knockout_text', $atts);
			extract(shortcode_atts(array(
				'image' => '',
				'title' => '',
				'font_size' => ''
			), $atts));
			
			$image = wp_get_attachment_image_src($image, 'full'); $image_src = '';
			if(isset($image[0])) {
				$image_src = $image[0];
			}			

			return '<div class="ozy-knockout-text" style="background-image:url('. esc_url($image_src) .');"><div><h2 class="masked-text" style="font-size:'. esc_attr($font_size) .'!important">'. $title .'</h2></div></div>';
		}
		
		add_shortcode( 'ozy_vc_knockout_text', 'ozy_vc_knockout_text' );
		
		vc_map( array(
			"name" => __("Knockout Text", "logistic-textdomain"),
			"base" => "ozy_vc_knockout_text",
			"content_element" => true,
			"icon" => "icon-wpb-ozy-el",
			"params" => array(
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __("Image", "logistic-textdomain"),
					"param_name" => "image",
					"admin_label" => false,
					"value" => ""
				),		
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Title", "logistic-textdomain"),
					"param_name" => "title",
					"admin_label" => true,
					"value" => ""
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Size", "logistic-textdomain"),
					"param_name" => "font_size",
					"admin_label" => true,
					"value" => "14vw"
				)				
		   )
		) );	
	}
	
	/*
	* Service Box
	*/
	if (!function_exists('ozy_vc_service_box')) {
		function ozy_vc_service_box( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_service_box', $atts);
			extract(shortcode_atts(array(				
				'icon' => '',
				'link' => ''
			), $atts));

			$output = '';
			$link = vc_build_link($link);
			if(is_array($link) && isset($link['url']) && isset($link['title'])) {
				$output .= '<a href="'. esc_url($link['url']) .'" class="ozy-service-box shared-border-color" '. (isset($link['target']) ? ' target="'. esc_attr($link['target']) .'"' : '') .'>';			
				if($icon) $output .= '<i class="oic f '. esc_attr($icon) .'"></i>';
				$output .= '<span class="content-color">' . $link['title'] . '</span>';
				$output .= '<i class="oic s oic-up-open-mini content-color"></i>';
				$output .= '</a>';
			}
			return $output;
		}
		
		add_shortcode( 'ozy_vc_service_box', 'ozy_vc_service_box' );
		
		vc_map( array(
			"name" => __("Service Box", "logistic-textdomain"),
			"base" => "ozy_vc_service_box",
			"content_element" => true,
			"icon" => "icon-wpb-ozy-el",
			"params" => array(
				array(
					"type" => "select_an_icon",
					"heading" => __("Icon", "logistic-textdomain"),
					"param_name" => "icon",
					"value" => '',
					"admin_label" => false,
					"description" => __("<br><br><br><br><br><br>", "logistic-textdomain")
				),array(
					"type" => "vc_link",
					"class" => "",
					"heading" => __("Link", "logistic-textdomain"),
					"param_name" => "link",
					"admin_label" => true,
					"value" => ""
				)
		   )
		) );	
	}
	
	/*
	* Hover Card
	*/
	if (!function_exists('ozy_vc_hover_card')) {
		function ozy_vc_hover_card( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_hover_card', $atts);
			extract(shortcode_atts(array(
				'small_image' => '',
				'icon' => '',
				'icon_color' => '#000000',
				'title' => '',
				'title_color' => '#000000',
				'excerpt' => '',
				'link' => ''
			), $atts));
					
			$small_image = wp_get_attachment_image_src($small_image, 'medium'); $small_image_src = '';
			if(isset($small_image[0])) {
				$small_image_src = $small_image[0];
			}
			
			$element_id = 'ohwc-' . rand(1, 100000);			
			
			$output = '<div id="'. $element_id .'" class="ozy-hover-card">';
			$output .= '	<div class="f">';
			if($small_image_src) $output .= '<figure><img src="'. esc_url($small_image_src) .'" alt=""/></figure>';
			$output .= '		<div>';
			if($icon) $output .= '<i class="oic '. esc_attr($icon) .'">&nbsp;</i>';
			if($title) $output .= '<h3>'. $title .'</h3>';
			if($excerpt) $output .= '<p>'. $excerpt .'</p>';
			$link = vc_build_link($link);
			if(is_array($link) && isset($link['url']) && isset($link['title']) && $link['url'] && $link['title']) {
				$output .= '<a href="'. esc_url($link['url']) .'" class="content-color" '. (isset($link['target']) ? ' target="'. esc_attr($link['target']) .'"' : '') .'><span>' . $link['title'] . '</span> &rarr;</a>';
			}
			$output .= '		</div>';
			$output .= '	</div>';

			$output .= '	<div class="s">';
			$output .= '		<div>';
			if($icon) $output .= '<i class="oic '. esc_attr($icon) .'">&nbsp;</i>';
			if($title) $output .= '<h3>'. $title .'</h3>';
			$output .= '		</div>';
			$output .= '	</div>';
			
			$output .= '</div>';

			global $ozyHelper;
			$ozyHelper->set_footer_style('#'. $element_id . '.ozy-hover-card i.oic{color: '. esc_attr($icon_color) .' !important;}');
			$ozyHelper->set_footer_style('#'. $element_id . '.ozy-hover-card>div>div>h3,#'. $element_id . '.ozy-hover-card>div>div>p,#'. $element_id . '.ozy-hover-card>div.f a{color: '. esc_attr($title_color) .' !important;}');
			$ozyHelper->set_footer_style('#'. $element_id . '.ozy-hover-card:hover>div.f a:hover>span{border-color: '. esc_attr($title_color) .' !important;}');
						
			return $output;
		}
		
		add_shortcode( 'ozy_vc_hover_card', 'ozy_vc_hover_card' );
		
		vc_map( array(
			"name" => __("Hover Card", "logistic-textdomain"),
			"base" => "ozy_vc_hover_card",
			"content_element" => true,
			"icon" => "icon-wpb-ozy-el",
			"params" => array(
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __("Small Image", "logistic-textdomain"),
					"param_name" => "small_image",
					"admin_label" => false,
					"value" => ""
				),array(
					"type" => "select_an_icon",
					"heading" => __("Icon", "logistic-textdomain"),
					"param_name" => "icon",
					"value" => '',
					"admin_label" => false
				),array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Icon Color", "logistic-textdomain"),
					"param_name" => "icon_color",
					"admin_label" => false,
					"value" => "#000000"
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Title", "logistic-textdomain"),
					"param_name" => "title",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Title Color", "logistic-textdomain"),
					"param_name" => "title_color",
					"admin_label" => false,
					"value" => "#000000"
				),array(
					"type" => "textarea",
					"class" => "",
					"heading" => __("Excerpt", "logistic-textdomain"),
					"param_name" => "excerpt",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "vc_link",
					"class" => "",
					"heading" => __("Link", "logistic-textdomain"),
					"param_name" => "link",
					"admin_label" => false,
					"value" => ""
				)
		   )
		) );	
	}	
	
	/**
	* Wide Call to Action
	*/
	if (!function_exists('ozy_vc_wide_call_to_action')) {
		function ozy_vc_wide_call_to_action( $atts, $content = null ) {
			$atts = vc_map_get_attributes('ozy_vc_wide_call_to_action', $atts);			
			extract( shortcode_atts( array(
				  'title' => '',
				  'gr_start' => '#0076ff',
				  'gr_end' => '#1fd87c',
				  'face_color' => '#ffffff',
				  'bar_color' => '#1d212d',
				  'button_caption' => esc_attr('REQUEST A RATE', 'logistic-textdomain'),
				  'content_part1' => '',
				  'content_part2' => ''
				), $atts ) 
			);
			
			$content_part1 = rawurldecode(base64_decode($content_part1));
			$content_part2 = rawurldecode(base64_decode($content_part2));
			
			$element_id = 'owcta-' . rand(1, 100000);
			
			$output = '<div id="'. esc_attr($element_id) .'" class="ozy-wide-call-to-action">';
			
			$output.= '<div class="bar"><div><i class="oic-flaticon5-time">&nbsp;</i><div class="part1">'. $content_part2 .'</div><div class="part2"><a href="#request-a-rate" class="comp-request-a-rate">'. esc_attr($button_caption) .'</a></div></div></div>';
			$output.= '<div class="gradient"><div>'. $content_part1 .'</div></div>';
			
			$output.= '</div>';

			global $ozyHelper;
			$ozyHelper->set_footer_style('#'. $element_id . '.ozy-wide-call-to-action>div.gradient{background: '. esc_attr($gr_start) .';background: -moz-linear-gradient(120deg, '. esc_attr($gr_start) .' 0%, '. esc_attr($gr_end) .' 100%);	background: -webkit-linear-gradient(120deg, '. esc_attr($gr_start) .' 0%,'. esc_attr($gr_end) .' 100%);	background: linear-gradient(120deg, '. esc_attr($gr_start) .' 0%,'. esc_attr($gr_end) .' 100%);filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="'. esc_attr($gr_start) .'", endColorstr="'. esc_attr($gr_end) .'",GradientType=1 );}');
			$ozyHelper->set_footer_style('#'. $element_id . '.ozy-wide-call-to-action *{color:'. esc_attr($face_color) .'!important;}');
			$ozyHelper->set_footer_style('#'. $element_id . '.ozy-wide-call-to-action>div.bar>div>div>a{background-color:'. esc_attr($face_color) .' !important;color:'. esc_attr($bar_color) .' !important;}');
			$ozyHelper->set_footer_style('#'. $element_id . '.ozy-wide-call-to-action>div.bar{background-color:'. esc_attr($bar_color) .' !important;}');
			$ozyHelper->set_footer_style('#'. $element_id . '.ozy-wide-call-to-action>div.bar h5>span{color:'. esc_attr($gr_end) .'!important;}');
			
			return $output;
		}

		add_shortcode( 'ozy_vc_wide_call_to_action', 'ozy_vc_wide_call_to_action' );

		vc_map( array(
		   "name" => __("Wide Call to Action", "logistic-textdomain"),
		   "base" => "ozy_vc_wide_call_to_action",
		   "icon" => "icon-wpb-ozy-el",
		   'category' => 'by OZY',
		   "params" => array(
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Title", "logistic-textdomain"),
					"param_name" => "title",
					"value" => "",
					"admin_label" => true,
					"description" => __("Only a place holder", "logistic-textdomain")
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Bar Color", "logistic-textdomain"),
					"param_name" => "bar_color",
					"admin_label" => false,
					"value" => "#1d212d"
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Gradient Start", "logistic-textdomain"),
					"param_name" => "gr_start",
					"admin_label" => false,
					"value" => "#0076ff"
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Gradient End", "logistic-textdomain"),
					"param_name" => "gr_end",
					"admin_label" => false,
					"value" => "#1fd87c"
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Face Color", "logistic-textdomain"),
					"param_name" => "face_coor",
					"admin_label" => false,
					"value" => "#ffffff"
				),
				array(
					"type" => "textarea_raw_html",
					"class" => "",
					"heading" => __("Part #1", "logistic-textdomain"),
					"param_name" => "content_part1",
					"value" => base64_encode("<h3>INTERNATIONAL<h3><h3>FREIGHT SERVICES</h3><h5>Multimodal Transportation</h5>"),
					"admin_label" => false,
					"description" => __("Text in gradient section", "logistic-textdomain")
				),
				array(
					"type" => "textarea_raw_html",
					"class" => "",
					"heading" => __("Part #2", "logistic-textdomain"),
					"param_name" => "content_part2",
					"value" => base64_encode("<h5>Your enquiry, our service</br>Call Now : <span>+39 0541 647087</span></h5>"),
					"admin_label" => false,
					"description" => __("Text in generic bar", "logistic-textdomain")
				),				
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Button Caption", "logistic-textdomain"),
					"param_name" => "button_caption",
					"value" => esc_attr__('REQUEST A RATE', 'logistic-textdomain'),
					"admin_label" => false,
					"description" => __("Caption on request a rate button", "logistic-textdomain")
				),				
		   )
		) );	
	}	
	
	/**
	* Blog Latest Headers
	*/
	if (!function_exists('ozy_vc_blog_latest_posts')) {
		function ozy_vc_blog_latest_posts( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_blog_latest_posts', $atts);
			extract( shortcode_atts( array(
				  'title' => '',
				  'item_count' => '',
				  'category_name' => '',
				  'author' => '',
				  'tag' => '',
				  'order_by' => 'date',
				  'order' => 'DESC',
				  'post_status' => 'published'
				), $atts ) 
			);
			
			global $ozyHelper;		
			
			if(!function_exists('ozy_get_option') || !is_object($ozyHelper)) return null;

			$output = "";
			$args = array( 
				'tax_query' => array(
					array(
						'taxonomy' => 'post_format',
						'field' => 'slug',
						'terms' => array('post-format-quote', 'post-format-aside'),
						'operator' => 'NOT IN'
					)
				),
				'post_type'			=> 'post',
				'posts_per_page' 	=> esc_attr(( (int)$item_count <= 0 ? get_option("posts_per_page") : ((int)$item_count > 0 ? $item_count : 6) )),
				'tag' 				=> ( !empty($tag) ? $tag : $tag ),
				'author_name'		=> ( !empty($author) ? $author : NULL ),
				'category_name'		=> ( !empty($category_name) ? $category_name : NULL ),
				'cat'				=> isset($cat) ? $cat : NULL,
				'author'			=> isset($author) ? $author : NULL,
				'orderby'			=> ( !empty($orderby) ? $orderby : 'date' ),
				'post_status'		=> ( !empty($post_status) ? $post_status : 'publish' ),
				'order'				=> ( !empty($order) ? $order : 'DESC' )
			);

			$loop = new WP_Query( $args );
			$counter = 0;			
			$output .= '<ul class="blog-listing-latest">' . PHP_EOL;
			while ( $loop->have_posts() ) : $loop->the_post();
				$output .= '<li>';
				$output .= '<div class="box-date"><span class="d">' . get_the_date('d') .'</span><span class="m">' . get_the_date('M') .'</span></div>' . PHP_EOL;
				$output .= '<div class="box-wrapper">' . PHP_EOL;
				$output .= '<h4>' . get_the_title() . '</h4>' . PHP_EOL;
				$output .= '<a href="' . get_permalink() . '">' . __('Read More &rarr;', 'logistic-textdomain') . '</a>' . PHP_EOL;
				$output .= '</div>' . PHP_EOL;
				$output .= '</li>' . PHP_EOL;			
			endwhile;
			wp_reset_postdata();
		
			$output .= '</ul>' . PHP_EOL;
			
			$style = 'ul.blog-listing-latest>li div.box-date>span.d{color:'. esc_attr($ozyHelper->rgba2rgb(ozy_get_option('form_button_font_color'))) .' !important;background-color:'. esc_attr(ozy_get_option('form_button_background_color')) .';}';
			$style.= 'ul.blog-listing-latest>li div.box-date>span.m{color:'. esc_attr($ozyHelper->rgba2rgb(ozy_get_option('form_button_font_color'))) .' !important;background-color:'. esc_attr(ozy_get_option('form_button_background_color_hover')) .';}';
			$ozyHelper->set_footer_style($style);
			
			return '<div class="wpb_wrapper">' . ( trim($title) ? '<h2 class="wpb_heading">' . esc_attr($title) . '</h2>' : '' ) . $output . '</div>';
		}

		add_shortcode( 'ozy_vc_blog_latest_posts', 'ozy_vc_blog_latest_posts' );

		vc_map( array(
		   "name" => __("Latest Blog Posts", "logistic-textdomain"),
		   "base" => "ozy_vc_blog_latest_posts",
		   "icon" => "icon-wpb-ozy-el",
		   'category' => 'by OZY',
		   "params" => array(
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Title", "logistic-textdomain"),
					"param_name" => "title",
					"value" => "",
					"admin_label" => true,
					"description" => __("Component title", "logistic-textdomain")
				),   
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Post Count", "logistic-textdomain"),
					"param_name" => "item_count",
					"value" => "6",
					"admin_label" => false,
					"description" => __("How many post will be listed on one page?", "logistic-textdomain")
				),
				array(
					"type" => "textfield",
					"heading" => __("Categories", "logistic-textdomain"),
					"param_name" => "category_name",
					"description" => __("If you want to narrow output, enter category slug names here. Display posts that have this category (and any children of that category), use category slug (NOT name). Split names with ','. More information; <a href='http://codex.wordpress.org/Class_Reference/WP_Query#Category_Parameters' target='_blank'>http://codex.wordpress.org/Class_Reference/WP_Query#Category_Parameters</a>", "logistic-textdomain")
				),
				array(
					"type" => "textfield",
					"heading" => __("Tags", "logistic-textdomain"),
					"param_name" => "tag",
					"description" => __("If you want to narrow output, enter tag slug names here. Display posts that have this tag, use tag slug (NOT name). Split names with ','. More information; <a href='http://codex.wordpress.org/Class_Reference/WP_Query#Author_Parameters' target='_blank'>http://codex.wordpress.org/Class_Reference/WP_Query#Author_Parameters</a>", "logistic-textdomain")
				),
				array(
					"type" => "textfield",
					"heading" => __("Author", "logistic-textdomain"),
					"param_name" => "author",
					"description" => __("If you want to narrow output, enter author slug name here. Display posts that belongs to author, use 'user_nicename' (NOT name). More information; <a href='http://codex.wordpress.org/Class_Reference/WP_Query#Tag_Parameters' target='_blank'>http://codex.wordpress.org/Class_Reference/WP_Query#Tag_Parameters</a>", "logistic-textdomain")
				),		
				array(
					"type" => "dropdown",
					"heading" => __("Order by", "logistic-textdomain"),
					"param_name" => "orderby",
					"value" => array(__("Date", "logistic-textdomain") => "date", __("ID", "logistic-textdomain") => "ID", __("Author", "logistic-textdomain") => "author", __("Title", "logistic-textdomain") => "title", __("Modified", "logistic-textdomain") => "modified", __("Random", "logistic-textdomain") => "rand", __("Comment count", "logistic-textdomain") => "comment_count", __("Menu order", "logistic-textdomain") => "menu_order" ),
					"description" => __('Select how to sort retrieved posts. More at <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>.', 'js_composer')
				),
				array(
					"type" => "dropdown",
					"heading" => __("Order way", "logistic-textdomain"),
					"param_name" => "order",
					"value" => array( __("Descending", "logistic-textdomain") => "DESC", __("Ascending", "logistic-textdomain") => "ASC" ),
					"description" => __('Designates the ascending or descending order. More at <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>.', "logistic-textdomain")
				),
				array(
					"type" => "dropdown",
					"heading" => __("Post Status", "logistic-textdomain"),
					"param_name" => "post_status",
					"value" => array("publish", "pending", "draft", "auto-draft", "future", "private", "inherit", "trash", "any"),
					"admin_label" => false,
					"description" => __("Show posts associated with certain status.", "logistic-textdomain")
				)
		   )
		) );	
	}
	
	/**
	* Timeline
	*/
	if (!function_exists('ozy_vc_timelinewrapper')) {
		function ozy_vc_timelinewrapper( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_timelinewrapper', $atts);
			extract(shortcode_atts(array(
				'title' => '',
				'bg_color' => '#ffffff'
			), $atts));
			
			$element_id = 'ozy-timeline-wrapper_' . rand(100,10000); 
			
			$output = '';
			$output.= ($title ? '<div id="'. $element_id .'" class="ozy-timeline-wrapper"><h4 class="timeline-caption"><span>'. esc_attr($title) .'</span></h4>' : '');
			$output.= '<ul class="timeline">'. do_shortcode($content) .'</ul>';
			$output.= ($title ? '</div>' : '');
			
			if($bg_color) {
				global $ozyHelper;
				$ozyHelper->set_footer_style('#'. $element_id . ' .timeline-panel{background-color:'. esc_attr($bg_color) .';}#'. $element_id . ' .timeline-panel:after{border-left-color:'. esc_attr($bg_color) .';}');
			}
			
			return $output;
		}
		
		add_shortcode('ozy_vc_timelinewrapper', 'ozy_vc_timelinewrapper');
		
		vc_map( array(
			"name" => __("Timeline Wrapper", "logistic-textdomain"),
			"base" => "ozy_vc_timelinewrapper",
			"as_parent" => array('only' => 'ozy_vc_timeline'),
			"content_element" => true,
			"show_settings_on_create" => false,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "textfield",
					"heading" => __("Title", "logistic-textdomain"),
					"param_name" => "title",
					"value" => "",
					"decription" => __("Only place holder", "logistic-textdomain"),
					"admin_label" => true
				),array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Item Background / Arrow Color", "logistic-textdomain"),
					"param_name" => "bg_color",
					"admin_label" => true,
					"value" => "#ffffff"
				)
		   ),
		   "js_view" => 'VcColumnView'
		) );
	}

	class WPBakeryShortCode_Ozy_Vc_Timelinewrapper extends WPBakeryShortCodesContainer{}

	if (!function_exists('ozy_vc_timeline')) {
		function ozy_vc_timeline( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_timeline', $atts);
			extract(shortcode_atts(array(
				'position' => 'left',
				'title' => '',
				'excerpt' => '',
				'icon' => '',
				'bg_color' => '',
				'text_color' => '',
				'icon_bg_color' => '',
				'icon_color' => '',
				'css_animation' => ''
			), $atts));
			
			if($css_animation) {
				wp_enqueue_script('waypoints');
				$css_animation = " wpb_animate_when_almost_visible wpb_" . esc_attr($css_animation) . " ";
			}
			
			if($content) $excerpt = $content; //this line covers new Content field after 2.0 version
			
			$css_text_color = ($text_color ? ' style="color:'. esc_attr($text_color) .'"':'');
			
			$output = '
			 <li class="'. ($position === 'right' ? 'timeline-inverted':'') . $css_animation .'">
			  <div class="timeline-badge" style="'. ('color:'. esc_attr($icon_color) . ';background-color:' . esc_attr($icon_bg_color)) .'"><i class="'. esc_attr($icon) .'"></i></div>
			  <div class="timeline-panel">
				<div class="timeline-heading">
				  <h4 class="timeline-title"'. $css_text_color .'>'. esc_attr($title) .'</h4>
				</div>
				<div class="timeline-body"'. $css_text_color .'>'. do_shortcode($excerpt) .'</div>
			  </div>
			</li>';

			return $output;
		}
		
		add_shortcode( 'ozy_vc_timeline', 'ozy_vc_timeline' );
		
		vc_map( array(
			"name" => __("Timeline Item", "logistic-textdomain"),
			"base" => "ozy_vc_timeline",
			"content_element" => true,
			"as_child" => array('only' => 'ozy_vc_timelinewrapper'),
			"icon" => "icon-wpb-ozy-el",
			"params" => array(
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Position", "logistic-textdomain"),
					"param_name" => "position",
					"admin_label" => true,
					"value" => array("left", "right")
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Title", "logistic-textdomain"),
					"param_name" => "title",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "textarea",
					"class" => "",
					"heading" => __("Content (OLD)", "logistic-textdomain"),
					"param_name" => "excerpt",
					"admin_label" => false,
					"description" => __("Please do not use this field, only to cover old versions. Copy your content into next Content field.", "logistic-textdomain"),
					"value" => ""
				),array(
					"type" => "textarea_html",
					"class" => "",
					"heading" => __("Content", "logistic-textdomain"),
					"param_name" => "content",
					"admin_label" => false,
					"value" => ""
				),array(
					"type" => "select_an_icon",
					"heading" => __("Icon", "logistic-textdomain"),
					"param_name" => "icon",
					"value" => '',
					"admin_label" => false,
					"description" => __("Once you select an Icon, title will not be shown on overlay.", "logistic-textdomain")
				),array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Text Color", "logistic-textdomain"),
					"param_name" => "text_color",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Icon Background Color", "logistic-textdomain"),
					"param_name" => "icon_bg_color",
					"admin_label" => true,
					"value" => "#222222"
				),array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Icon Color", "logistic-textdomain"),
					"param_name" => "icon_color",
					"admin_label" => true,
					"value" => "#ffffff"
				),$add_css_animation
		   )
		) );	
	}

	class WPBakeryShortCode_Ozy_Vc_Timeline extends WPBakeryShortCode{}	
	
	/**
	* Interactive Horizontal Boxes
	*/
	if (!function_exists('ozy_vc_iaboxes')) {
		function ozy_vc_iaboxes( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_iaboxes', $atts);
			extract(shortcode_atts(array(
				'title' => ''
			), $atts));
			
			return '<div class="ozy-iabox-wrapper">'. do_shortcode($content) .'</div>';
		}
		
		add_shortcode('ozy_vc_iaboxes', 'ozy_vc_iaboxes');
		
		vc_map( array(
			"name" => __("Interactive Horizontal Boxes", "logistic-textdomain"),
			"base" => "ozy_vc_iaboxes",
			"as_parent" => array('only' => 'ozy_vc_iabox,ozy_vc_flipbox'),
			"content_element" => true,
			"show_settings_on_create" => false,	
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "textfield",
					"heading" => __("Title", "logistic-textdomain"),
					"param_name" => "width",
					"value" => "Title",
					"decription" => __("Only place holder", "logistic-textdomain"),
					"admin_label" => true
				)		
		   ),
		   "js_view" => 'VcColumnView'
		) );
	}

	class WPBakeryShortCode_Ozy_Vc_Iaboxes extends WPBakeryShortCodesContainer{}

	if (!function_exists('ozy_vc_iabox')) {
		function ozy_vc_iabox( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_iabox', $atts);
			extract(shortcode_atts(array(
				'box_size' => '6',
				'title_size' => 'h2',
				'bg_color' => '#222222',
				'title_color' => '#ffffff',
				'excerpt_color' => '#ffffff',
				'use_hover' => '0',
				'bg_hover_color' => '',
				'title_hover_color' => '#ffffff',
				'excerpt_hover_color' => '#ffffff',
				'title_hover' => '',
				'excerpt_hover' => '',			
				'bg_image' => '',
				'title' => '',
				'excerpt' => '',
				'min_height' => '',
				'bg_video' => '0',
				'bg_video_mp4' => '',
				'bg_video_webm' => '',
				'bg_video_ogv' => '',
				'icon' => '',
				'link' => '',
				'text_align' => '',
				'link_target' => '_self'
			), $atts));
			
			$box_size = 'vc_col-sm-' . $box_size;
			
			$bg_image = wp_get_attachment_image_src($bg_image, 'full');
			$style = ' style="';
			if(isset($bg_image[0])) {
				$style.= 'background-image:url('. $bg_image[0] .')';
			}
			if($bg_color) {
				$style.= ';background-color:'. esc_attr($bg_color);
			}
			if((int)$min_height>0) {
				$style.= ';min-height:'. $min_height .'px;';
			}
			$style.= ';text-align:'. esc_attr($text_align) .';';
			$style.= '"';
			
			$output = '<div class="ozy-iabox '. esc_attr($box_size) .'" '. $style .'>';
			
			if($bg_video == 'on') { 
				$output .= '<video preload="auto" loop="true" autoplay="true" src="'.esc_url($bg_video_mp4).'">';
				if($bg_video_ogv) $output .='<source type="video/ogv" src="'. esc_url($bg_video_ogv) .'">';
				if($bg_video_mp4) $output .='<source type="video/mp4" src="'. esc_url($bg_video_mp4) .'">';	
				if($bg_video_webm) $output .='<source type="video/webm" src="'. esc_url($bg_video_webm) .'">';
				$output .= '</video>';
			}
			
			$title_hover 	= !$title_hover ? $title : $title_hover;
			$excerpt_hover 	= !$excerpt_hover ? $excerpt : $excerpt_hover;		
			
			$output.= esc_attr($title) ? '<'. esc_attr($title_size) .' style="color:'. esc_attr($title_color) .';" class="heading">'. esc_attr($title) .'</'. esc_attr($title_size) .'>' : '';
			$output.= esc_attr($excerpt) ? '<div style="color:'. esc_attr($excerpt_color) .';font-size:120%;line-height:120%">'. nl2br($excerpt) .'</div>' : '';
			$output.= esc_attr($icon) ? '<i class="'. esc_attr($icon) .'" style="color:'. esc_attr($title_color) .';"></i>' : '';
			if(esc_attr($use_hover) === 'on') {
				$output.= '<a href="'. esc_attr($link) .'" target="'. esc_attr($link_target) .'" style="background-color:'. esc_attr($bg_hover_color) .'">';
				$output.= esc_attr($title) ? '<'. esc_attr($title_size) .' style="color:'. esc_attr($title_hover_color) .';" class="heading">'. esc_attr($title_hover) .'</'. esc_attr($title_size) .'>' : '';
				$output.= esc_attr($excerpt) ? '<div style="color:'. esc_attr($excerpt_hover_color) .';font-size:120%;line-height:120%">'. nl2br($excerpt_hover) .'</div>' : '';
				$output.= esc_attr($icon) ? '<i class="'. esc_attr($icon) .'" style="color:'. esc_attr($title_hover_color) .';"></i>' : '';
				$output.= '</a>';
			}else{
				$output.= '<a href="'. esc_attr($link) .'" target="'. esc_attr($link_target) .'">';
				$output.= '&nbsp;';
				$output.= '</a>';
			}
			$output.= '</div>';
			
			return $output;
		}
		
		add_shortcode( 'ozy_vc_iabox', 'ozy_vc_iabox' );
		
		vc_map( array(
			"name" => __("Interactive Box", "logistic-textdomain"),
			"base" => "ozy_vc_iabox",
			"content_element" => true,
			"as_child" => array('only' => 'ozy_vc_iaboxes'),
			"icon" => "icon-wpb-ozy-el",
			"params" => array(
				array(
					"type" => "dropdown",
					"heading" => __("Size", "logistic-textdomain"),
					"param_name" => "box_size",
					"value" => array("1/2" => "6", "2/3" => "8", "1/3" => "4", "1/4" => "3"),
					"admin_label" => true
				),array(
					"type" => 'dropdown',
					"heading" => __("Title Size", "logistic-textdomain"),
					"param_name" => "title_size",
					"value" => array("h2", "h1", "h3", "h4", "h5", "h6"),
				),array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __("Background Image", "logistic-textdomain"),
					"param_name" => "bg_image",
					"admin_label" => false,
					"value" => ""
				),array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Background Color", "logistic-textdomain"),
					"param_name" => "bg_color",
					"admin_label" => true,
					"value" => "#222222"
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Title", "logistic-textdomain"),
					"param_name" => "title",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "textarea",
					"class" => "",
					"heading" => __("Excerpt", "logistic-textdomain"),
					"param_name" => "excerpt",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "dropdown",
					"heading" => __("Text Align", "logistic-textdomain"),
					"param_name" => "text_align",
					"value" => array("left", "center", "right"),
					"admin_label" => false
				),array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Title Color", "logistic-textdomain"),
					"param_name" => "title_color",
					"admin_label" => true,
					"value" => "#ffffff"
				),array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Excerpt Color", "logistic-textdomain"),
					"param_name" => "excerpt_color",
					"admin_label" => true,
					"value" => "#ffffff"
				),array(
					"type" => 'dropdown',
					"heading" => __("Hover", "logistic-textdomain"),
					"param_name" => "use_hover",
					"description" => __("If selected, you can set background, title and excerpt color for hover mode.", "logistic-textdomain"),
					"value" => array("off", "on"),
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Title", "logistic-textdomain"),
					"param_name" => "title_hover",
					"admin_label" => false,
					"value" => "",
					"dependency" => Array('element' => "use_hover", 'value' => 'on')
				),array(
					"type" => "textarea",
					"class" => "",
					"heading" => __("Excerpt", "logistic-textdomain"),
					"param_name" => "excerpt_hover",
					"admin_label" => false,
					"value" => "",
					"dependency" => Array('element' => "use_hover", 'value' => 'on')
				),array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Hover Background Color", "logistic-textdomain"),
					"param_name" => "bg_hover_color",
					"admin_label" => true,
					"value" => "#222222",
					"dependency" => Array('element' => "use_hover", 'value' => 'on')
				),array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Hover Title Color", "logistic-textdomain"),
					"param_name" => "title_hover_color",
					"admin_label" => true,
					"value" => "#ffffff",
					"dependency" => Array('element' => "use_hover", 'value' => 'on')
				),array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Hover Excerpt Color", "logistic-textdomain"),
					"param_name" => "excerpt_hover_color",
					"admin_label" => true,
					"value" => "#ffffff",
					"dependency" => Array('element' => "use_hover", 'value' => 'on')
				),array(
					"type" => "textfield",
					"heading" => __("Minimum Height", "logistic-textdomain"),
					"param_name" => "min_height",
					"description" => __("Set minimum height of your row in pixels. Not required", "logistic-textdomain")
				),array(
					"type" => 'dropdown',
					"heading" => __("Video Background", "logistic-textdomain"),
					"param_name" => "bg_video",
					"description" => __("If selected, you can set background of your box as video.", "logistic-textdomain"),
					"value" => array("off", "on"),
				),array(
					"type" => "textfield",
					"heading" => __("MP4 File", "logistic-textdomain"),
					"param_name" => "bg_video_mp4",
					"description" => __("MP4 Video file path", "logistic-textdomain"),
					"dependency" => Array('element' => "bg_video", 'value' => 'on')
				),array(
					"type" => "textfield",
					"heading" => __("WEBM File", "logistic-textdomain"),
					"param_name" => "bg_video_webm",
					"description" => __("WEBM Video file path", "logistic-textdomain"),
					"dependency" => Array('element' => "bg_video", 'value' => 'on')	  
				),array(
					"type" => "textfield",
					"heading" => __("OGV File", "logistic-textdomain"),
					"param_name" => "bg_video_ogv",
					"description" => __("OGV Video file path", "logistic-textdomain"),
					"dependency" => Array('element' => "bg_video", 'value' => 'on')
				),array(
					"type" => "select_an_icon",
					"heading" => __("Icon", "logistic-textdomain"),
					"param_name" => "icon",
					"value" => '',
					"admin_label" => false,
					"description" => __("Once you select an Icon, title will not be shown on overlay.", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Link", "logistic-textdomain"),
					"param_name" => "link",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "dropdown",
					"heading" => __("Link Target", "logistic-textdomain"),
					"param_name" => "link_target",
					"value" => array("_self", "_blank", "_parent"),
					"admin_label" => false,
					"description" => __("Select link target window.", "logistic-textdomain")
				)
		   )
		) );	
	}

	class WPBakeryShortCode_Ozy_Vc_Iabox extends WPBakeryShortCode{}
	
	/**
	* Spinner List
	*/
	if (!function_exists('ozy_vc_spinnerlist')) {
		function ozy_vc_spinnerlist( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_spinnerlist', $atts);
			extract(shortcode_atts(array(
				'title' => ''
			), $atts));
			
			$output = '<div class="ozy-spinner-list">';
			$output .= '<ul>'. do_shortcode($content) .'</div>';
			$output .= '</div>';
			
			return $output;
		}
		
		add_shortcode('ozy_vc_spinnerlist', 'ozy_vc_spinnerlist');
		
		vc_map( array(
			"name" => __("Spinner List", "logistic-textdomain"),
			"base" => "ozy_vc_spinnerlist",
			"as_parent" => array('only' => 'ozy_vc_spinnerlist_item'),
			"content_element" => true,
			"show_settings_on_create" => false,	
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "textfield",
					"heading" => __("Title", "logistic-textdomain"),
					"param_name" => "width",
					"value" => "Title",
					"decription" => __("Only place holder", "logistic-textdomain"),
					"admin_label" => true
				)		
		   ),
		   "js_view" => 'VcColumnView'
		) );
	}

	class WPBakeryShortCode_Ozy_Vc_Spinnerlist extends WPBakeryShortCodesContainer{}

	if (!function_exists('ozy_vc_spinnerlist_item')) {
		function ozy_vc_spinnerlist_item( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_spinnerlist_item', $atts);
			extract(shortcode_atts(array(
				'bg_image' => '',
				'bg_color' => '#000000',
				'fn_color' => '#ffffff',
				'title' => '',
				'sub_title' => '',
				'link' => '',
				'link_target' => '_self'
			), $atts));
			
			$rand_classname = 'spinner-' . rand(0,10000);
			
			$style = '';
			
			if($bg_color && !$bg_image)
				$style.='background-color:'. esc_attr($bg_color).';';

			if($bg_image) {
				$bg_image = wp_get_attachment_image_src($bg_image, 'full');
				if(isset($bg_image[0])) {
					$style.= 'background:'. esc_attr($bg_color) .' url('. esc_url($bg_image[0]) .') no-repeat center center';
				}
			}
				
			$output = '<li class="'. $rand_classname .'">';
			$output .= '<div><a href="'. esc_url($link) .'" target="'. esc_attr($link_target) .'">'. esc_attr($title) . '<span>'. esc_attr($sub_title) .'</span></a></div>';
			$output .= '</li>';
			
			global $ozyHelper;
			$ozyHelper->set_footer_style('.'. $rand_classname .'{'. esc_attr($style) .'}');
			$ozyHelper->set_footer_style('.'. $rand_classname .'>div>a{color:'. esc_attr($fn_color) .' !important}');
			
			return $output;
		}
		
		add_shortcode( 'ozy_vc_spinnerlist_item', 'ozy_vc_spinnerlist_item' );
		
		vc_map( array(
			"name" => __("Spinner List Item", "logistic-textdomain"),
			"base" => "ozy_vc_spinnerlist_item",
			"content_element" => true,
			"as_child" => array('only' => 'ozy_vc_spinnerlist'),
			"icon" => "icon-wpb-ozy-el",
			"params" => array(
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __("Background Image", "logistic-textdomain"),
					"param_name" => "bg_image",
					"admin_label" => false,
					"value" => ""
				),array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Background Color", "logistic-textdomain"),
					"param_name" => "bg_color",
					"admin_label" => true,
					"value" => "#000000"
				),array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Foreground Color", "logistic-textdomain"),
					"param_name" => "fn_color",
					"admin_label" => true,
					"value" => "#ffffff"
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Title", "logistic-textdomain"),
					"param_name" => "title",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Sub Title", "logistic-textdomain"),
					"param_name" => "sub_title",
					"admin_label" => true,
					"value" => __("CLICK HERE FOR MORE INFO &gt;", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Link", "logistic-textdomain"),
					"param_name" => "link",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "dropdown",
					"heading" => __("Link Target", "logistic-textdomain"),
					"param_name" => "link_target",
					"value" => array("_self", "_blank", "_parent"),
					"admin_label" => false,
					"description" => __("Select link target window.", "logistic-textdomain")
				)
		   )
		) );	
	}

	class WPBakeryShortCode_Ozy_Vc_Spinnerlist_item extends WPBakeryShortCode{}	
	
	/**
	* Multi Location Pretty Map
	*/
	if (!function_exists('ozy_vc_prettymap_multi')) {
		function ozy_vc_prettymap_multi( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_prettymap_multi', $atts);
			extract(shortcode_atts(array(
				'zoom'			=> '13',
				'hue'			=> '#ff0000',
				'saturation'	=> '-30',
				'lightness' 	=> '0',
				'height'		=> '350px',
				'api_key'		=> ''
			), $atts));
			
			$rand_id = 'map_data_' . rand(1, 10000);
			$GLOBALS['OZY_CUSTOM_MAP'] = array();
			
			//wp_enqueue_script('googlemaps', '//maps.google.com/maps/api/js?'. esc_url($api_key?'key=' . $api_key .'&':'') .'sensor=false&language=en', array('jquery'), null, true );
			wp_enqueue_script('googlemaps', '//maps.google.com/maps/api/js?'. ($api_key?'key=' . $api_key .'&':'') .'sensor=false&language=en', array('jquery'), null, true );
			
			do_shortcode($content);
			
			wp_localize_script( 'logistic', 'ozyMapData', array($rand_id =>  json_encode($GLOBALS['OZY_CUSTOM_MAP'])) );
			
			unset($GLOBALS['OZY_CUSTOM_MAP']);
			
			return '<div class="ozy-multi-google-map" 
			data-path="'. esc_attr($rand_id) .'" 
			data-zoom="'. esc_attr($zoom) .'" 
			data-hue="'. esc_attr($hue) .'" 
			data-saturation="'. esc_attr($saturation) .'" 
			data-lightness="'. esc_attr($lightness) .'" style="height:'. esc_attr($height) .'"></div>';
		}
		
		add_shortcode('ozy_vc_prettymap_multi', 'ozy_vc_prettymap_multi');
		
		vc_map( array(
			"name" => __("Multi Location Google Map", "logistic-textdomain"),
			"base" => "ozy_vc_prettymap_multi",
			"as_parent" => array('only' => 'ozy_vc_prettymap_multi_location'),			
			"content_element" => true,
			"show_settings_on_create" => true,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Google Maps API Key", "logistic-textdomain"),
					"param_name" => "api_key",
					"admin_label" => false,
					"value" => "",
					'description' => wp_kses(__('<a href="http://freevision.me/google-maps-key/" target="_blank">Learn how to get an API Key.</a>', 'logistic-textdomain'),array('a' => array('href' => array(), 'target' => array()))),					
				),			
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Height", "logistic-textdomain"),
					"param_name" => "height",
					"admin_label" => true,
					"value" => "350px"
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Zoom Level", "logistic-textdomain"),
					"param_name" => "zoom",
					"admin_label" => true,
					"value" => "13"
				),
				array(
					"type" => "colorpicker",
					"heading" => __("Hue Color", "logistic-textdomain"),
					"param_name" => "hue",
					"admin_label" => false,
					"value" => "#FF0000"
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Saturation", "logistic-textdomain"),
					"param_name" => "saturation",
					"admin_label" => true,
					"value" => "-30"
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Lightness", "logistic-textdomain"),
					"param_name" => "lightness",
					"admin_label" => true,
					"value" => "0"
				)		
		   ),
		   "js_view" => 'VcColumnView'		   
		) );
	}
	
	if (!function_exists('ozy_vc_prettymap_multi_location')) {
		function ozy_vc_prettymap_multi_location( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_prettymap_multi_location', $atts);
			extract(shortcode_atts(array(
				'caption'		=> '',
				'address'		=> '',
				'custom_icon'	=> ''
			), $atts));

			$custom_icon = wp_get_attachment_image_src($custom_icon, 'full');
			if(isset($custom_icon[0])) {
				$custom_icon = $custom_icon[0];
			}else{
				$custom_icon = null;
			}
			
			$GLOBALS['OZY_CUSTOM_MAP'][] = array(esc_attr($caption), esc_attr($address), $custom_icon);
			
			return null;
		}
		
		add_shortcode('ozy_vc_prettymap_multi_location', 'ozy_vc_prettymap_multi_location');

		vc_map( array(
			"name" => __("Location", "logistic-textdomain"),
			"base" => "ozy_vc_prettymap_multi_location",
			"content_element" => true,
			"as_child" => array('only' => 'ozy_vc_prettymap_multi'),
			"icon" => "icon-wpb-ozy-el",
			"params" => array(
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Info Box Caption", "logistic-textdomain"),
					"param_name" => "caption",
					"admin_label" => true,
					"value" => __("Head Quarter", "logistic-textdomain")
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Address", "logistic-textdomain"),
					"param_name" => "address",
					"admin_label" => true,
					"value" => __("Melbourne, Australia", "logistic-textdomain")
				),
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __("Custom Icon", "logistic-textdomain"),
					"param_name" => "custom_icon",
					"description" => __("You can select a custom icon for your pin on the map", "logistic-textdomain"),
					"admin_label" => false,
					"value" => ""
				)				
		   )
		) );
	}

	class WPBakeryShortCode_Ozy_Vc_Prettymap_Multi extends WPBakeryShortCodesContainer{}
	class WPBakeryShortCode_Ozy_Vc_Prettymap_Multi_Location extends WPBakeryShortCode{}			
	
	/**
	* Pretty Map
	*/
	if (!function_exists('ozy_vc_prettymap')) {
		function ozy_vc_prettymap( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_prettymap', $atts);
			extract(shortcode_atts(array(
				'address'		=> '',
				'zoom'			=> '13',
				'custom_icon'	=> '',
				'hue'			=> '#ff0000',
				'saturation'	=> '-30',
				'lightness' 	=> '0',
				'height'		=> '350px',
				'api_key'		=> ''
			), $atts));
			
			$custom_icon = wp_get_attachment_image_src($custom_icon, 'full');
			if(isset($custom_icon[0])) {
				$custom_icon = $custom_icon[0];
			}
			
			//wp_enqueue_script('googlemaps', '//maps.google.com/maps/api/js?sensor=false&language=en', array('jquery'), null, true );
			wp_enqueue_script('googlemaps', '//maps.google.com/maps/api/js?'. ($api_key?'key=' . $api_key .'&':'') .'sensor=false&language=en', array('jquery'), null, true );
			
			return '<div class="ozy-google-map" 
			data-address="'. esc_attr($address) .'" 
			data-zoom="'. esc_attr($zoom) .'" 
			data-hue="'. esc_attr($hue) .'" 
			data-saturation="'. esc_attr($saturation) .'" 
			data-lightness="'. esc_attr($lightness) .'" 
			data-icon="'. esc_url($custom_icon) .'" style="height:'. esc_attr($height) .'"></div>';
		}
		
		add_shortcode('ozy_vc_prettymap', 'ozy_vc_prettymap');
		
		vc_map( array(
			"name" => __("Custom Google Map", "logistic-textdomain"),
			"base" => "ozy_vc_prettymap",
			"content_element" => true,
			"show_settings_on_create" => true,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Google Maps API Key", "logistic-textdomain"),
					"param_name" => "api_key",
					"admin_label" => false,
					"value" => "",
					'description' => wp_kses(__('<a href="http://freevision.me/google-maps-key/" target="_blank">Learn how to get an API Key.</a>', 'logistic-textdomain'),array('a' => array('href' => array(), 'target' => array()))),					
				),			
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Height", "logistic-textdomain"),
					"param_name" => "height",
					"admin_label" => true,
					"value" => "350px"
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Address", "logistic-textdomain"),
					"param_name" => "address",
					"admin_label" => true,
					"value" => __("Melbourne, Australia", "logistic-textdomain")
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Zoom Level", "logistic-textdomain"),
					"param_name" => "zoom",
					"admin_label" => true,
					"value" => "13"
				),			
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __("Custom Icon", "logistic-textdomain"),
					"param_name" => "custom_icon",
					"description" => __("You can select a custom icon for your pin on the map", "logistic-textdomain"),
					"admin_label" => false,
					"value" => ""
				),
				array(
					"type" => "colorpicker",
					"heading" => __("Hue Color", "logistic-textdomain"),
					"param_name" => "hue",
					"admin_label" => false,
					"value" => "#FF0000"
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Saturation", "logistic-textdomain"),
					"param_name" => "saturation",
					"admin_label" => true,
					"value" => "-30"
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Lightness", "logistic-textdomain"),
					"param_name" => "lightness",
					"admin_label" => true,
					"value" => "0"
				)		
		   )
		) );
	}

	/**
	* Simple Info Box
	*/
	if (!function_exists('ozy_vc_simpleinfobox')) {
		function ozy_vc_simpleinfobox( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_simpleinfobox', $atts);
			extract(shortcode_atts(array(
				'image'			=> '',
				'caption'		=> '',
				'title'			=> '',
				'excerpt'		=> '',
				'fn_color'		=> '#ffffff',
				'bg_color'		=> 'transparent',
				'link_caption'	=> 'LEARN MORE',
				'link'			=> '',
				'link_target'	=> '_self',
				'extra_css'		=> '',
				'css_animation' => ''
			), $atts));
			
			if($css_animation) {
				wp_enqueue_script('waypoints');
				$css_animation = " wpb_animate_when_almost_visible wpb_" . esc_attr($css_animation) . " ";
			}		
			
			$rand_id = "simple-info-box-" . rand(1,10000);
			
			$image = wp_get_attachment_image_src($image, 'full');
			
			$style = '';
			if(isset($image[0])) {
				//$output .= '<img src="'. esc_url($image[0]) .'" alt="' . esc_attr($caption) . '"/>';
				$style = ' style="background:url('. esc_url($image[0]) .')"';
			}
			$output = '<div class="ozy-simlple-info-box" id="'. $rand_id .'" '. $style .'>';
			
			$output .= '<section>';
			$output .= '<h5>'. esc_attr($caption) .'</h5>';
			$output .= '<h2>'. esc_attr($title) .'</h2>';
			if($excerpt) $output .= '<p>'. $excerpt .'</p>';
			$output .= '<a href="'. esc_url($link) .'" target="'. esc_attr($link_target) .'">'. esc_attr($link_caption) .'</a>' . PHP_EOL;
			$output .= '</section>';
				
			$output .= PHP_EOL .'</div>';
			
			global $ozyHelper;
			$ozyHelper->set_footer_style("#$rand_id>section>a,#$rand_id h2,#$rand_id h5,#$rand_id {color:". esc_attr($fn_color) ." !important;}");
			$ozyHelper->set_footer_style("#$rand_id>section>a{border-color:". esc_attr($fn_color) ."}");
			if($bg_color && $bg_color!='transparent') {
				$ozyHelper->set_footer_style("#$rand_id{background-color:". esc_attr($bg_color) ."}");
				$ozyHelper->set_footer_style("#$rand_id>section{background:none !important}");
			}			
			
			return $output;
		}
		
		add_shortcode('ozy_vc_simpleinfobox', 'ozy_vc_simpleinfobox');
		
		vc_map( array(
			"name" => __("Simple Info Box", "logistic-textdomain"),
			"base" => "ozy_vc_simpleinfobox",
			"content_element" => true,
			"show_settings_on_create" => true,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __("Image", "logistic-textdomain"),
					"param_name" => "image",
					"admin_label" => false,
					"value" => ""
				),		
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Caption", "logistic-textdomain"),
					"param_name" => "caption",
					"admin_label" => true,
					"value" => __("Caption Over Title", "logistic-textdomain")
				),				
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Title", "logistic-textdomain"),
					"param_name" => "title",
					"admin_label" => true,
					"value" => __("Enter Title Here", "logistic-textdomain")
				),
				array(
					"type" => "textarea",
					"class" => "",
					"heading" => __("Excerpt", "logistic-textdomain"),
					"param_name" => "excerpt",
					"admin_label" => true,
					"value" => ""
				),		
				array(
					"type" => "colorpicker",
					"heading" => __("Foreground Color", "logistic-textdomain"),
					"param_name" => "fn_color",
					"admin_label" => false,
					"value" => "#ffffff"
				),
				array(
					"type" => "colorpicker",
					"heading" => __("Background Color", "logistic-textdomain"),
					"param_name" => "bg_color",
					"admin_label" => false,
					"value" => "transparent"
				),				
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Link Caption", "logistic-textdomain"),
					"param_name" => "link_caption",
					"value" => __("LEARN MORE", "logistic-textdomain"),
					"admin_label" => true
				),				
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Link", "logistic-textdomain"),
					"param_name" => "link",
					"admin_label" => true
				),
				array(
					"type" => "dropdown",
					"heading" => __("Link Target", "logistic-textdomain"),
					"param_name" => "link_target",
					"value" => array("_self", "_blank", "_parent"),
					"admin_label" => false,
					"description" => __("Select link target window.", "logistic-textdomain")
				),	
				$add_css_animation,
				array(
					"type" => "textfield",
					"heading" => __("Extra class name", "logistic-textdomain"),
					"param_name" => "el_class",
					"description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "logistic-textdomain")
				)			
		   )
		) );
	}	
	
	/**
	* Image Box With Caption Wrapper
	*/
	if (!function_exists('ozy_vc_imageboxwithcaption_wrapper')) {
		function ozy_vc_imageboxwithcaption_wrapper( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_imageboxwithcaption_wrapper', $atts);
			extract(shortcode_atts(array(
				'item_count' => '4',
				'item_space' => '1px'
			), $atts));
			
			$rand_id = 'ozy-image-with-caption-wrapper-' . rand(100, 1000);
			
			global $ozyHelper;
			$ozyHelper->set_footer_style("#$rand_id>a:not(first-child) {padding:0 0 ". esc_attr($item_space) ." ". esc_attr($item_space) .";}");
			
			return '<div id="'. esc_attr($rand_id) .'" class="ozy-image-with-caption-wrapper column-'. esc_attr($item_count) .'">'. do_shortcode($content) .'</div>';
		}
		
		add_shortcode('ozy_vc_imageboxwithcaption_wrapper', 'ozy_vc_imageboxwithcaption_wrapper');
		
		vc_map( array(
			"name" => __("Image Box With Caption Wrapper", "logistic-textdomain"),
			"base" => "ozy_vc_imageboxwithcaption_wrapper",
			"as_parent" => array('only' => 'ozy_vc_imageboxwithcaption'),
			"content_element" => true,
			"show_settings_on_create" => false,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Item Count Per Row", "logistic-textdomain"),
					"param_name" => "item_count",
					"admin_label" => true,
					"value" => array("4","1","2","3")
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Item Spacing", "logistic-textdomain"),
					"param_name" => "item_space",
					"admin_label" => true,
					"value" => array("1px","0","2px","3px","4px","5px","6px","7px","8px","9px","10px","11px","12px","13px","14px","15px","16px","17px","18px","19px","20px")
				)									
		   ),
		   "js_view" => 'VcColumnView'
		) );
	}

	class WPBakeryShortCode_Ozy_Vc_Imageboxwithcaption_wrapper extends WPBakeryShortCodesContainer{}	
	
	/**
	* Image Box With Caption
	*/
	if (!function_exists('ozy_vc_imageboxwithcaption')) {
		function ozy_vc_imageboxwithcaption( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_imageboxwithcaption', $atts);
			extract(shortcode_atts(array(
				'image'			=> '',
				'caption'		=> '',
				'icon'			=> '',
				'fn_color'		=> '#ffffff',
				'bg_color'		=> '#000000',
				'tag' 			=> 'SEE MORE',
				'link'			=> '',
				'link_target'	=> '_self',
				'extra_css'		=> '',
				'css_animation' => ''
			), $atts));
			
			if($css_animation) {
				wp_enqueue_script('waypoints');
				$css_animation = " wpb_animate_when_almost_visible wpb_" . esc_attr($css_animation) . " ";
			}		
			
			$rand_id = "image-with-caption-" . rand(1,10000);
			
			$output = '<a href="'. esc_url($link) .'" target="'. esc_attr($link_target) .'" class="ozy-image-with-caption" id="'. $rand_id .'">' . PHP_EOL;

			$image = wp_get_attachment_image_src($image, 'full');
			if(isset($image[0])) {
				$output .= '<figure>';
				if($icon) {
					$output .= '<span><i class="'. esc_attr($icon) .'"></i></span>';
				}
				$output .= '<img src="'. esc_url($image[0]) .'" alt="' . esc_attr($caption) . '"/>';
				$output .= '</figure>';
			}
			
			$output .= '<section>';
			$output .= '<h5>'. esc_attr($caption) .'</h5>';
			if(esc_attr($tag)) {
				$output .= '<span class="tag">'. esc_attr($tag) .'</span>';
			}	
			$output .= '</section>';
				
			//$output .= do_shortcode( $content );
			$output .= PHP_EOL .'</a>';
			
			global $ozyHelper;
			$ozyHelper->set_footer_style("#$rand_id h5,#$rand_id {color:". esc_attr($fn_color) ." !important;}");
			$ozyHelper->set_footer_style("#$rand_id span.tag{background-color:". esc_attr($fn_color) .";color:". esc_attr($bg_color) .";}");
			$ozyHelper->set_footer_style("#$rand_id figure>span{background-color:". $ozyHelper->hex2rgba($bg_color, '0.7') .";}");		
			$ozyHelper->set_footer_style("#$rand_id>section{background-color:". esc_attr($bg_color) .";}");
			
			return $output;
		}
		
		add_shortcode('ozy_vc_imageboxwithcaption', 'ozy_vc_imageboxwithcaption');
		
		vc_map( array(
			"name" => __("Image Box With Caption", "logistic-textdomain"),
			"base" => "ozy_vc_imageboxwithcaption",
			"content_element" => true,
			"show_settings_on_create" => true,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __("Image", "logistic-textdomain"),
					"param_name" => "image",
					"admin_label" => false,
					"value" => ""
				),		
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Caption", "logistic-textdomain"),
					"param_name" => "caption",
					"admin_label" => true,
					"value" => __("Box Title", "logistic-textdomain")
				),
				array(
					"type" => "select_an_icon",
					"heading" => __("Hover Icon", "logistic-textdomain"),
					"param_name" => "icon",
					"value" => '',
					"admin_label" => false
				),			
				array(
					"type" => "colorpicker",
					"heading" => __("Foreground Color", "logistic-textdomain"),
					"param_name" => "fn_color",
					"admin_label" => false,
					"value" => "#ffffff"
				),
				array(
					"type" => "colorpicker",
					"heading" => __("Tag Background Color", "logistic-textdomain"),
					"param_name" => "bg_color",
					"admin_label" => false,
					"value" => "#000000"
				),					
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Tag", "logistic-textdomain"),
					"param_name" => "tag",
					"admin_label" => true,
					"value" => __("SEE MORE", "logistic-textdomain")
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Link", "logistic-textdomain"),
					"param_name" => "link",
					"admin_label" => true
				),
				array(
					"type" => "dropdown",
					"heading" => __("Link Target", "logistic-textdomain"),
					"param_name" => "link_target",
					"value" => array("_self", "_blank", "_parent"),
					"admin_label" => false,
					"description" => __("Select link target window.", "logistic-textdomain")
				),	
				$add_css_animation,
				array(
					"type" => "textfield",
					"heading" => __("Extra class name", "logistic-textdomain"),
					"param_name" => "el_class",
					"description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "logistic-textdomain")
				)			
		   )
		) );
	}

	/**
	* Colored Content Box
	*/
	if (!function_exists('ozy_vc_coloredcontentbox')) {
		function ozy_vc_coloredcontentbox( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_coloredcontentbox', $atts);
			extract(shortcode_atts(array(
				'image'			=> '',
				'caption'		=> 'Caption Goes Here',
				'hover_caption' => 'SHOP NOW',
				'fn_color'		=> '#ffffff',
				'bg_color'		=> '#e42039',
				'tag' 			=> 'SAY SOMETHING',
				'link'			=> '',
				'link_target'	=> '_self',
				'extra_css'		=> '',
				'css_animation' => ''
			), $atts));
			
			if($css_animation) {
				wp_enqueue_script('waypoints');
				$css_animation = " wpb_animate_when_almost_visible wpb_" . esc_attr($css_animation) . " ";
			}		
			
			$rand_id = "fancybox-" . rand(1,10000);
			
			$output = '<a href="'. esc_attr($link) .'" target="'. esc_attr($link_target) .'" class="ozy-colored-content-box" id="'. $rand_id .'">' . PHP_EOL;

			$image = wp_get_attachment_image_src($image, 'full');
			if(isset($image[0])) {
				$output .= '<img src="'. esc_url($image[0]) .'" alt="' . esc_attr($caption) . '"/>';
			}
			
			$output .= '<section class="overlay">';
			$output .= '<span class="caption heading-font">'. esc_attr($caption) .'</span>';
			if(esc_attr($tag)) {
				$output .= '<span class="tag">'. esc_attr($tag) .'</span>';
			}	
			$output .= '</section>';
			$output .= '<section class="overlay-two">';
			$output .= '<h3 class="ozy-vertical-centered-element">'. esc_attr($hover_caption) .'</h3>';
			$output .= '</section>';
				
			//$output .= do_shortcode( $content );
			$output .= PHP_EOL .'</a>';
			
			global $ozyHelper;
			$ozyHelper->set_footer_style("#$rand_id>section>span,#$rand_id>section>h3{color:". esc_attr($fn_color) .";}");
			$ozyHelper->set_footer_style("#$rand_id>section>span.caption{background-color:". esc_attr($bg_color) .";color:". esc_attr($fn_color) .";}");
			$ozyHelper->set_footer_style("#$rand_id>section.overlay-two{background-color:". esc_attr($bg_color) .";}");		
			$ozyHelper->set_footer_style("#$rand_id:hover>section.overlay-two{background-color:". $ozyHelper->hex2rgba($bg_color, '0.7') .";}");
			
			return $output;
		}
		
		add_shortcode('ozy_vc_coloredcontentbox', 'ozy_vc_coloredcontentbox');
		
		vc_map( array(
			"name" => __("Coloured Content Box", "logistic-textdomain"),
			"base" => "ozy_vc_coloredcontentbox",
			"content_element" => true,
			"show_settings_on_create" => true,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __("Image", "logistic-textdomain"),
					"param_name" => "image",
					"admin_label" => false,
					"value" => ""
				),		
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Caption", "logistic-textdomain"),
					"param_name" => "caption",
					"admin_label" => true,
					"value" => __("Caption Goes Here", "logistic-textdomain")
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Hover Caption", "logistic-textdomain"),
					"param_name" => "hover_caption",
					"admin_label" => false,
					"value" => __("SHOP NOW", "logistic-textdomain")
				),			
				array(
					"type" => "colorpicker",
					"heading" => __("Foreground Color", "logistic-textdomain"),
					"param_name" => "fn_color",
					"admin_label" => false,
					"value" => "#ffffff"
				),
				array(
					"type" => "colorpicker",
					"heading" => __("Tag Background Color", "logistic-textdomain"),
					"param_name" => "bg_color",
					"admin_label" => false,
					"value" => "#e42039"
				),					
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Tag", "logistic-textdomain"),
					"param_name" => "tag",
					"admin_label" => true,
					"value" => __("SAY SOMETHING", "logistic-textdomain")
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Link", "logistic-textdomain"),
					"param_name" => "link",
					"admin_label" => true
				),
				array(
					"type" => "dropdown",
					"heading" => __("Link Target", "logistic-textdomain"),
					"param_name" => "link_target",
					"value" => array("_self", "_blank", "_parent"),
					"admin_label" => false,
					"description" => __("Select link target window.", "logistic-textdomain")
				),	
				$add_css_animation,
				array(
					"type" => "textfield",
					"heading" => __("Extra class name", "logistic-textdomain"),
					"param_name" => "el_class",
					"description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "logistic-textdomain")
				)			
		   )
		) );
	}

	/**
	* Simple Hover Image Box
	*/
	if (!function_exists('ozy_vc_simplehoverimagebox')) {
		function ozy_vc_simplehoverimagebox( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_simplehoverimagebox', $atts);
			extract(shortcode_atts(array(
				'image'			=> '',
				'title'			=> 'TITLE GOES HERE',
				'hover_caption' => '',
				'fn_color'		=> '#ffffff',
				'main_color'	=> '#0094f9',
				'link'			=> '',
				'link_target'	=> '_self',
				'extra_css'		=> '',
				'css_animation' => ''
			), $atts));
			
			if($css_animation) {
				wp_enqueue_script('waypoints');
				$css_animation = " wpb_animate_when_almost_visible wpb_" . esc_attr($css_animation) . " ";
			}		
			
			$rand_id = "simple-hove-box-" . rand(1,10000);
			$output = '<div class="ozy-simple-hove-box" id="'. $rand_id .'">';
			$output .= '<h5><span class="cbox"></span>'. esc_attr($title) .'</h5>';
			$output .= '<a href="'. esc_url($link) .'" target="'. esc_attr($link_target) .'" id="'. $rand_id .'">' . PHP_EOL;

			$image = wp_get_attachment_image_src($image, 'full');
			if(isset($image[0])) {
				$output .= '<img src="'. esc_url($image[0]) .'" alt="' . esc_attr($title) . '"/>';
			}
			$output .= '<section>';
			$output .= '<p class="ozy-vertical-centered-element">'. esc_attr($hover_caption) .'</p>';
			$output .= '</section>';
			
			$output .= PHP_EOL .'</a>';			
			$output .= PHP_EOL .'</div>';
			
			global $ozyHelper;
			$ozyHelper->set_footer_style("#$rand_id>h5>span.cbox{background-color:". esc_attr($main_color) ."}");
			$ozyHelper->set_footer_style("#$rand_id>a>section{background-color:". $ozyHelper->hex2rgba(esc_attr($main_color), '0.7') ."}");
			$ozyHelper->set_footer_style("#$rand_id>a>section>p{color:". esc_attr($fn_color) ."}");
			
			return $output;
		}
		
		add_shortcode('ozy_vc_simplehoverimagebox', 'ozy_vc_simplehoverimagebox');
		
		vc_map( array(
			"name" => __("Simple Hover Image Box", "logistic-textdomain"),
			"base" => "ozy_vc_simplehoverimagebox",
			"content_element" => true,
			"show_settings_on_create" => true,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Title", "logistic-textdomain"),
					"param_name" => "title",
					"admin_label" => true
				),
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __("Image", "logistic-textdomain"),
					"param_name" => "image",
					"admin_label" => false,
					"value" => ""
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Hover Caption", "logistic-textdomain"),
					"param_name" => "hover_caption",
					"admin_label" => false
				),
				array(
					"type" => "colorpicker",
					"heading" => __("Main Color", "logistic-textdomain"),
					"param_name" => "main_color",
					"admin_label" => false,
					"value" => "#0094f9"
				),
				array(
					"type" => "colorpicker",
					"heading" => __("Foreground Color", "logistic-textdomain"),
					"param_name" => "fn_color",
					"admin_label" => false,
					"value" => "#ffffff"
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Link", "logistic-textdomain"),
					"param_name" => "link",
					"admin_label" => true
				),
				array(
					"type" => "dropdown",
					"heading" => __("Link Target", "logistic-textdomain"),
					"param_name" => "link_target",
					"value" => array("_self", "_blank", "_parent"),
					"admin_label" => false,
					"description" => __("Select link target window.", "logistic-textdomain")
				),	
				$add_css_animation,
				array(
					"type" => "textfield",
					"heading" => __("Extra class name", "logistic-textdomain"),
					"param_name" => "el_class",
					"description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "logistic-textdomain")
				)			
		   )
		) );
	}	
	
	/**
	* Fancy Image Box
	*/
	if (!function_exists('ozy_vc_fancyimagebox')) {
		function ozy_vc_fancyimagebox( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_fancyimagebox', $atts);
			extract(shortcode_atts(array(
				'image'			=> '',
				'caption'		=> '',
				'excerpt'		=> '',
				'fn_color'		=> '#ffffff',
				'bg_color'		=> '#000000',
				'tag' 			=> 'MORE',
				'link'			=> '',
				'link_target'	=> '_self',
				'extra_css'		=> '',
				'css_animation' => ''
			), $atts));
			
			if($css_animation) {
				wp_enqueue_script('waypoints');
				$css_animation = " wpb_animate_when_almost_visible wpb_" . esc_attr($css_animation) . " ";
			}		
			
			$rand_id = "fancybox-" . rand(1,10000);
			
			$output = '<a href="'. esc_url($link) .'" target="'. esc_attr($link_target) .'" class="ozy-fancyimagebox" id="'. $rand_id .'">' . PHP_EOL;

			$image = wp_get_attachment_image_src($image, 'full');
			if(isset($image[0])) {
				$output .= '<img src="'. esc_url($image[0]) .'" alt="' . esc_attr($caption) . '"/>';
			}
			
			$output .= '<section>';
			$output .= '<h2 style="display:none">'. esc_attr($caption) .'</h2>';
			$output .= '<section>';	
			if(esc_attr($tag)) {
				$output .= '<span class="tag">'. esc_attr($tag) .'</span>';
			}	
			$output .= '<h2>'. esc_attr($caption) .'</h2>';
			if(esc_attr($excerpt)) {
				$output .= '<span class="line"></span>';
				$output .= '<p>'. esc_attr($excerpt) .'</p>';
			}
			$output .= '</section>';
			$output .= '</section>';
				
			//$output .= do_shortcode( $content );
			$output .= PHP_EOL .'</a>';
			
			global $ozyHelper;
			$ozyHelper->set_footer_style("#$rand_id h2,#$rand_id p,#$rand_id {color:". esc_attr($fn_color) ." !important;}");
			$ozyHelper->set_footer_style("#$rand_id>section>section>span{border-color:". esc_attr($fn_color) .";}");
			$ozyHelper->set_footer_style("#$rand_id>section>section>span.tag{background-color:". $ozyHelper->hex2rgba($bg_color, '0.4') .";}");		
			
			return $output;
		}
		
		add_shortcode('ozy_vc_fancyimagebox', 'ozy_vc_fancyimagebox');
		
		vc_map( array(
			"name" => __("Fancy Image Box", "logistic-textdomain"),
			"base" => "ozy_vc_fancyimagebox",
			"content_element" => true,
			"show_settings_on_create" => true,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __("Image", "logistic-textdomain"),
					"param_name" => "image",
					"admin_label" => false,
					"value" => ""
				),		
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Caption", "logistic-textdomain"),
					"param_name" => "caption",
					"admin_label" => true,
					"value" => __("Box Title", "logistic-textdomain")
				),
				array(
					"type" => "textarea",
					"class" => "",
					"heading" => __("Excerpt", "logistic-textdomain"),
					"param_name" => "excerpt",
					"admin_label" => false
				),
				array(
					"type" => "colorpicker",
					"heading" => __("Foreground Color", "logistic-textdomain"),
					"param_name" => "fn_color",
					"admin_label" => false,
					"value" => "#ffffff"
				),
				array(
					"type" => "colorpicker",
					"heading" => __("Tag Background Color", "logistic-textdomain"),
					"param_name" => "bg_color",
					"admin_label" => false,
					"value" => "#000000"
				),					
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Tag", "logistic-textdomain"),
					"param_name" => "tag",
					"admin_label" => true,
					"value" => __("MORE", "logistic-textdomain")
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Link", "logistic-textdomain"),
					"param_name" => "link",
					"admin_label" => true
				),
				array(
					"type" => "dropdown",
					"heading" => __("Link Target", "logistic-textdomain"),
					"param_name" => "link_target",
					"value" => array("_self", "_blank", "_parent"),
					"admin_label" => false,
					"description" => __("Select link target window.", "logistic-textdomain")
				),	
				$add_css_animation,
				array(
					"type" => "textfield",
					"heading" => __("Extra class name", "logistic-textdomain"),
					"param_name" => "el_class",
					"description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "logistic-textdomain")
				)			
		   )
		) );
	}

	/**
	* News Bar
	*/
	if (!function_exists('ozy_vc_newsbar')) {
		function ozy_vc_newsbar( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_newsbar', $atts);
			extract(shortcode_atts(array(
				'title'				=> 'NEWS',
				'sub_title'			=> 'STAY TUNED',
				'link'				=> '',
				'link_caption' 		=> 'VIEW ALL',
				'link_target'		=> '_self',
				'css_animation' 	=> ''
			), $atts));
			
			if($css_animation) {
				wp_enqueue_script('waypoints');
				$css_animation = " wpb_animate_when_almost_visible wpb_" . esc_attr($css_animation) . " ";
			}		

			$output = '<div class="ozy-news-bar">' . PHP_EOL;

			$args = array(
				'post_type' 			=> 'post',
				'posts_per_page'		=> 3,//esc_attr($posts_per_page),
				'orderby' 				=> 'date',
				'order' 				=> 'DESC',
				'ignore_sticky_posts' 	=> 1,
			);

			$the_query = new WP_Query( $args );

			$output	.= '<ul>';
			
			$output .= '<li><h1 class="content-color-alternate">'. esc_attr($title) .'</h1><h2 class="content-color">'. esc_attr($sub_title) .'</h2><a href="'. esc_url($link) .'" target="'. esc_attr($link_target) .'" class="generic-button">'. esc_attr($link_caption) .'<i class="oic-outlined-iconset-140">&nbsp;</i></a></li>';

			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				
				$output .= '<li>';
				$output .= '<h1 class="t">'. get_the_title() .'</h1>';
				$output .= '<p>' . ozy_excerpt_max_charlength(200, true, false) . '</p>';
				$output .= '<a href="'. get_permalink() .'">'. __('READ MORE &nbsp;&nbsp;&gt;', 'logistic-textdomain') .'</a>';				
				$output .= '</li>';
			}
			wp_reset_postdata();
			
			$output.= do_shortcode( $content );
			$output.= PHP_EOL .'</ul>';
			$output.= PHP_EOL .'</div>';
			
			return $output;
		}
		
		add_shortcode('ozy_vc_newsbar', 'ozy_vc_newsbar');
		
		vc_map( array(
			"name" => __("News Bar", "logistic-textdomain"),
			"base" => "ozy_vc_newsbar",
			"content_element" => true,
			"show_settings_on_create" => true,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Title", "logistic-textdomain"),
					"param_name" => "title",
					"admin_label" => true,
					"value" => __("NEWS", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Sub Title", "logistic-textdomain"),
					"param_name" => "sub_title",
					"admin_label" => true,
					"value" => __("STAY TUNED", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Link", "logistic-textdomain"),
					"param_name" => "link",
					"admin_label" => true,
					"value" => '',
					"description" => __("Link to blog / news page", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Link Caption", "logistic-textdomain"),
					"param_name" => "link_caption",
					"admin_label" => true,
					"value" => __("VIEW ALL", "logistic-textdomain")
				),array(
					"type" => "dropdown",
					"heading" => __("Link Target", "logistic-textdomain"),
					"param_name" => "link_target",
					"value" => array("_self", "_blank", "_parent"),
					"admin_label" => false,
					"description" => __("Select link target window.", "logistic-textdomain")
				),
				$add_css_animation
		   )
		) );
	}	
	
	/**
	* Fancy Post Accordion
	*/
	if (!function_exists('ozy_vc_fancypostaccordion_feed')) {
		function ozy_vc_fancypostaccordion_feed( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_fancypostaccordion_feed', $atts);
			extract(shortcode_atts(array(
				'link_caption' 		=> 'Find out more →',
				'link_target'		=> '_self',
				'posts_per_page'	=> '6',
				'css_animation' 	=> ''
			), $atts));
			
			if($css_animation) {
				wp_enqueue_script('waypoints');
				$css_animation = " wpb_animate_when_almost_visible wpb_" . esc_attr($css_animation) . " ";
			}		

			$output = '<div class="ozy-fancyaccordion-feed">' . PHP_EOL;

			$args = array(
				'post_type' 			=> 'post',
				'posts_per_page'		=> esc_attr($posts_per_page),
				'orderby' 				=> 'date',
				'order' 				=> 'DESC',
				'ignore_sticky_posts' 	=> 1,
			);

			$the_query = new WP_Query( $args );
				
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$output .= '<a href="'. get_permalink() .'" target="'. esc_attr($link_target) .'" class="ozy-border-color"><span>';
				$output .= '<div class="d ozy-border-color"><h3>'. get_the_date('d.m.Y') .'</h3></div>';
				$output .= '<h3 class="t">'. get_the_title() .'</h3>';
				
				$categories = get_the_terms(get_the_ID(), 'category');
				if(is_array($categories)) {
					$output .= '<span class="category generic-button">';
					$comma = '';			
					foreach ($categories as $cat) {
						$output .= $comma . $cat->name;
						$comma = ', ';
					}
					$output .= '</span>';
				}
				
				$output .= '<span class="plus-icon"><span class="h"></span><span class="v"></span></span>';
				$output .= '</span></a>';
				$output .= '<div class="panel ozy-border-color"><div>' . ozy_excerpt_max_charlength(200, true, false) . '<p>';
				$output .= '<a href="'. get_permalink() .'">'. esc_attr($link_caption) .'</a>';
				$output .= '</p></div></div>';
			}
			wp_reset_postdata();
			
			$output.= do_shortcode( $content );
			$output.= PHP_EOL .'</div>';
			
			return $output;
		}
		
		add_shortcode('ozy_vc_fancypostaccordion_feed', 'ozy_vc_fancypostaccordion_feed');
		
		vc_map( array(
			"name" => __("Fancy Post List", "logistic-textdomain"),
			"base" => "ozy_vc_fancypostaccordion_feed",
			"content_element" => true,
			"show_settings_on_create" => true,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Link Caption", "logistic-textdomain"),
					"param_name" => "link_caption",
					"admin_label" => true,
					"value" => __("Find out more →", "logistic-textdomain")
				),array(
					"type" => "dropdown",
					"heading" => __("Link Target", "logistic-textdomain"),
					"param_name" => "link_target",
					"value" => array("_self", "_blank", "_parent"),
					"admin_label" => false,
					"description" => __("Select link target window.", "logistic-textdomain")
				),					
				array(
					"type" => "dropdown",
					"heading" => __("Item Count", "logistic-textdomain"),
					"param_name" => "posts_per_page",
					"value" => array("6", "1", "2", "3", "4", "5", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16"),
					"admin_label" => true,
					"description" => __("How many post will be shown on the list.", "logistic-textdomain")
				),
				$add_css_animation
		   )
		) );
	}

	/**
	* Post List With Title
	*/
	if (!function_exists('ozy_vc_postlistwithtitle_feed')) {
		function ozy_vc_postlistwithtitle_feed( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_postlistwithtitle_feed', $atts);
			extract(shortcode_atts(array(
				'posts_per_page'	=> '8',
				'extra_css'			=> '',
				'css_animation' 	=> ''
			), $atts));
			
			if($css_animation) {
				wp_enqueue_script('waypoints');
				$css_animation = " wpb_animate_when_almost_visible wpb_" . esc_attr($css_animation) . " ";
			}		

			$output = '<div class="ozy-postlistwithtitle-feed">' . PHP_EOL;

			$args = array(
				'post_type' 			=> 'post',
				'posts_per_page'		=> esc_attr($posts_per_page),
				'orderby' 				=> 'date',
				'order' 				=> 'DESC',
				'ignore_sticky_posts' 	=> 1,							
				'meta_key' 				=> '_thumbnail_id'
			);

			$the_query = new WP_Query( $args );
				
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$output .= '<a href="'. get_permalink() .'" class="ozy-border-color">';
				$output .= '<h2>'. get_the_title() .'</h2>';
				$output .= '<p>'. get_the_date() .'</p>';
				$output .= '<p>';
				$output .= strip_tags(get_the_category_list(', '));
				$output .= '</p>';
				$output .= '</a>';
			}
			wp_reset_postdata();
			
			$output.= do_shortcode( $content );
			$output.= PHP_EOL .'</div>';
			
			return $output;
		}
		
		add_shortcode('ozy_vc_postlistwithtitle_feed', 'ozy_vc_postlistwithtitle_feed');
		
		vc_map( array(
			"name" => __("Post List With Title", "logistic-textdomain"),
			"base" => "ozy_vc_postlistwithtitle_feed",
			"content_element" => true,
			"show_settings_on_create" => true,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "textfield",
					"heading" => __("Post Count", "logistic-textdomain"),
					"param_name" => "posts_per_page",
					"value" => "8",
					"admin_label" => true
				),		
				$add_css_animation,
				array(
					"type" => "textfield",
					"heading" => __("Extra class name", "logistic-textdomain"),
					"param_name" => "el_class",
					"description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "logistic-textdomain")
				)			
		   )
		) );
	}

	/**
	* Mail Chimp
	*/
	if (!function_exists('ozy_vc_mailchimp') && function_exists('mailchimpSF_signup_form')) {
		function ozy_vc_mailchimp( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_', $atts);
			extract(shortcode_atts(array(
				'css_animation' => ''
				), $atts ) 
			);
			
			if($css_animation) {
				wp_enqueue_script('waypoints');
				$css_animation = " wpb_animate_when_almost_visible wpb_" . esc_attr($css_animation) . " ";
			}
			
			return '<div class="wpb_content_element">'. do_shortcode('[mailchimpsf_form]') .'</div>';		
		}
		
		add_shortcode('ozy_vc_mailchimp', 'ozy_vc_mailchimp');
		
		vc_map( array(
			"name" => __("Mail Chimp", "logistic-textdomain"),
			"base" => "ozy_vc_mailchimp",
			"content_element" => true,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				$add_css_animation	
		   )
		) );
	}

	/**
	* Anything Wrapper 2
	*/
	if (!function_exists('ozy_vc_anywrapper2')) {
		function ozy_vc_anywrapper2( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_anywrapper2', $atts);
			extract(shortcode_atts(array(
				'vertical_position' => 'center',
				'horizontal_position' => 'center',
				'width' => '50%',
				'text_align' => 'left',
				'padding_top' => '30px',
				'padding_right' => '30px',
				'padding_bottom' => '30px',
				'padding_left' => '30px'
			), $atts));
			
			$style = 'ozy-anything-wrapper-x';
			$style .= ' v-' . $vertical_position;
			$style .= ' h-' . $horizontal_position;
			
			return '<div class="'. esc_attr($style) .'" style="text-align:'. esc_attr($text_align) .';width:'. esc_attr($width) .';padding:'. esc_attr($padding_top) .' '. esc_attr($padding_right) .' '. esc_attr($padding_bottom) .' '. esc_attr($padding_left) .'">'. do_shortcode($content) .'</div>';
		}
		
		add_shortcode('ozy_vc_anywrapper2', 'ozy_vc_anywrapper2');
		
		vc_map( array(
			"name" => __("Anything Wrapper 2", "logistic-textdomain"),
			"base" => "ozy_vc_anywrapper2",
			"as_parent" => array('except' => 'ozy_vc_iabox,ozy_vc_flipbox'),
			"content_element" => true,
			"show_settings_on_create" => false,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Vertical Position", "logistic-textdomain"),
					"param_name" => "vertical_position",
					"admin_label" => true,
					"value" => array("center","top","bottom")
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Horizontal Position", "logistic-textdomain"),
					"param_name" => "horizontal_position",
					"admin_label" => true,
					"value" => array("center","left","right")
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Width", "logistic-textdomain"),
					"param_name" => "width",
					"admin_label" => true,
					"value" => "50%"
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Text Align", "logistic-textdomain"),
					"param_name" => "text_align",
					"admin_label" => true,
					"value" => array("left","center","right")
				),						
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Padding Top", "logistic-textdomain"),
					"param_name" => "padding_top",
					"admin_label" => true,
					"value" => array("30px","0","5px","10px","15px","20px","25px","35px","40px","45px","50px","55px","60px","65px","70px","75px","80px","85px","90px","95px","100px","105px","110px","115px","120px","125px","130px","135px","140px","145px","150px","155px","160px","165px","170px","175px","180px","185px","190px","195px","200px")
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Padding Right", "logistic-textdomain"),
					"param_name" => "padding_right",
					"admin_label" => true,
					"value" => array("30px","0","5px","10px","15px","20px","25px","35px","40px","45px","50px","55px","60px","65px","70px","75px","80px","85px","90px","95px","100px","105px","110px","115px","120px","125px","130px","135px","140px","145px","150px","155px","160px","165px","170px","175px","180px","185px","190px","195px","200px")
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Padding Bottom", "logistic-textdomain"),
					"param_name" => "padding_bottom",
					"admin_label" => true,
					"value" => array("30px","0","5px","10px","15px","20px","25px","35px","40px","45px","50px","55px","60px","65px","70px","75px","80px","85px","90px","95px","100px","105px","110px","115px","120px","125px","130px","135px","140px","145px","150px","155px","160px","165px","170px","175px","180px","185px","190px","195px","200px")
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Padding Left", "logistic-textdomain"),
					"param_name" => "padding_left",
					"admin_label" => true,
					"value" => array("30px","0","5px","10px","15px","20px","25px","35px","40px","45px","50px","55px","60px","65px","70px","75px","80px","85px","90px","95px","100px","105px","110px","115px","120px","125px","130px","135px","140px","145px","150px","155px","160px","165px","170px","175px","180px","185px","190px","195px","200px")
				)									
		   ),
		   "js_view" => 'VcColumnView'
		) );
	}

	class WPBakeryShortCode_Ozy_Vc_Anywrapper2 extends WPBakeryShortCodesContainer{}

	/**
	* Anything Wrapper
	*/
	if (!function_exists('ozy_vc_anywrapper')) {
		function ozy_vc_anywrapper( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_anywrapper', $atts);
			extract(shortcode_atts(array(
				'padding_top' => '30px',
				'padding_right' => '30px',
				'padding_bottom' => '30px',
				'padding_left' => '30px'
			), $atts));
			
			return '<div class="ozy-anything-wrapper" style="display:inline-block;width:100%;padding:'. esc_attr($padding_top) .' '. esc_attr($padding_right) .' '. esc_attr($padding_bottom) .' '. esc_attr($padding_left) .'">'. do_shortcode($content) .'</div>';
		}
		
		add_shortcode('ozy_vc_anywrapper', 'ozy_vc_anywrapper');
		
		vc_map( array(
			"name" => __("Anything Wrapper", "logistic-textdomain"),
			"base" => "ozy_vc_anywrapper",
			"as_parent" => array('except' => 'ozy_vc_iabox,ozy_vc_flipbox'),
			"content_element" => true,
			"show_settings_on_create" => false,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Padding Top", "logistic-textdomain"),
					"param_name" => "padding_top",
					"admin_label" => true,
					"value" => array("30px","0","5px","10px","15px","20px","25px","35px","40px","45px","50px","55px","60px","65px","70px","75px","80px","85px","90px","95px","100px","105px","110px","115px","120px","125px","130px","135px","140px","145px","150px","155px","160px","165px","170px","175px","180px","185px","190px","195px","200px")
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Padding Right", "logistic-textdomain"),
					"param_name" => "padding_right",
					"admin_label" => true,
					"value" => array("30px","0","5px","10px","15px","20px","25px","35px","40px","45px","50px","55px","60px","65px","70px","75px","80px","85px","90px","95px","100px","105px","110px","115px","120px","125px","130px","135px","140px","145px","150px","155px","160px","165px","170px","175px","180px","185px","190px","195px","200px")
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Padding Bottom", "logistic-textdomain"),
					"param_name" => "padding_bottom",
					"admin_label" => true,
					"value" => array("30px","0","5px","10px","15px","20px","25px","35px","40px","45px","50px","55px","60px","65px","70px","75px","80px","85px","90px","95px","100px","105px","110px","115px","120px","125px","130px","135px","140px","145px","150px","155px","160px","165px","170px","175px","180px","185px","190px","195px","200px")
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Padding Left", "logistic-textdomain"),
					"param_name" => "padding_left",
					"admin_label" => true,
					"value" => array("30px","0","5px","10px","15px","20px","25px","35px","40px","45px","50px","55px","60px","65px","70px","75px","80px","85px","90px","95px","100px","105px","110px","115px","120px","125px","130px","135px","140px","145px","150px","155px","160px","165px","170px","175px","180px","185px","190px","195px","200px")
				)									
		   ),
		   "js_view" => 'VcColumnView'
		) );
	}

	class WPBakeryShortCode_Ozy_Vc_Anywrapper extends WPBakeryShortCodesContainer{}

	/**
	* Styled Heading
	*/
	if (!function_exists('ozy_vc_sheading')) {
		function ozy_vc_sheading( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_sheading', $atts);
			extract(shortcode_atts(array(
				'caption' 		=> '',
				'caption_size'	=> 'h1',
				'caption_position'=> 'center',
				'border_style'	=> 'solid',
				'border_size'	=> '1px',
				'accent_color' 	=> '#000',
				'bg_color' 		=> '',
				'padding' 		=> '5px',
				'css_animation' => ''
				), $atts ) 
			);
			
			if($css_animation) {
				wp_enqueue_script('waypoints');
				$css_animation = " wpb_animate_when_almost_visible wpb_" . esc_attr($css_animation) . " ";
			}

			$padding = esc_attr($padding) . ' ' . ((int)esc_attr($padding)+5) . 'px';

			return '<div class="wpb_content_element" style="text-align:'. esc_attr($caption_position) .';"><'. esc_attr($caption_size) .' style="border:'. esc_attr($border_size) .' '. esc_attr($border_style) .' '. esc_attr($accent_color) . (esc_attr($bg_color) ? ';background-color:' . esc_attr($bg_color) : '') .';color:'. esc_attr($accent_color) .';padding:'. $padding .';display:inline-block;">'. esc_attr($caption) .'</'. esc_attr($caption_size) .'></div>';
		}

		add_shortcode('ozy_vc_sheading', 'ozy_vc_sheading');

		vc_map( array(
		   "name" => __("Styled Heading", "logistic-textdomain"),
		   "base" => "ozy_vc_sheading",
		   "class" => "",
		   "controls" => "full",
		   'category' => 'by OZY',
		   "icon" => "icon-wpb-ozy-el",
		   "params" => array(
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Caption", "logistic-textdomain"),
					"param_name" => "caption",
					"admin_label" => true,
					"value" => __("Enter your caption here", "logistic-textdomain"),
					"description" => __("Caption of the component.", "logistic-textdomain")
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Caption Size", "logistic-textdomain"),
					"param_name" => "caption_size",
					"admin_label" => true,
					"value" => array("h1", "h2", "h3", "h4", "h5", "h6")
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Caption Position", "logistic-textdomain"),
					"param_name" => "caption_position",
					"admin_label" => true,
					"value" => array("center", "left", "right")
				),						
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Border Style", "logistic-textdomain"),
					"param_name" => "border_style",
					"admin_label" => true,
					"value" => array("solid","dotted","dashed","double")
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Border Size", "logistic-textdomain"),
					"param_name" => "border_size",
					"admin_label" => true,
					"value" => array("0","1px","2px","3px","4px","5px","6px","7px","8px","9px","10px")
				),			
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Accent Color", "logistic-textdomain"),
					"param_name" => "accent_color",
					"admin_label" => false,
					"value" => "#000"
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Background Color", "logistic-textdomain"),
					"param_name" => "bg_color",
					"admin_label" => false,
					"value" => "#fff"
				),			
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Padding", "logistic-textdomain"),
					"param_name" => "padding",
					"admin_label" => true,
					"value" => array("5px","10px","15px","20px","25px","30px","35px","40px","45px","50px")
				),$add_css_animation
			)
		) );
	}

	/**
	* Flip Box
	*/
	if (!function_exists('ozy_vc_flipbox')) {
		function ozy_vc_flipbox( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_flipbox', $atts);
			extract(shortcode_atts(array(
				'front_icon' => '',
				'front_title' => '',
				'front_excerpt' => '',
				'front_bg_color' => '',
				'front_fg_color' => '#222222',
				'front_bg_image' => '',
				'back_icon' => '',
				'back_title' => '',
				'back_excerpt' => '',
				'back_bg_color' => '#222222',
				'back_fg_color' => '#ffffff',
				'back_bg_image' => '',
				'direction' => 'horizontal',
				'height' => '427',
				'link' => '',
				'link_target' => '_self'
			), $atts));

			global $ozyHelper;

			$front_bg_image = wp_get_attachment_image_src(esc_attr($front_bg_image), 'full');
			$back_bg_image 	= wp_get_attachment_image_src(esc_attr($back_bg_image), 'full');
			
			$front_bg 	= $ozyHelper->background_style_render(esc_attr($front_bg_color), (isset($front_bg_image[0]) ? esc_attr($front_bg_image[0]):''), '', '', '', '', '', '');
			$back_bg 	= $ozyHelper->background_style_render(esc_attr($back_bg_color), (isset($back_bg_image[0]) ? esc_attr($back_bg_image[0]):''), '', '', '', '', '', '');
			
			return '<div class="flip-container '. esc_attr($direction) .' wpb_content_element" ontouchstart="this.classList.toggle(\'hover\');" style="height:'. esc_attr($height).'px;">
					<a class="flipper" '. (esc_attr($link) ? 'href="'. esc_attr($link) .'" target="'. esc_attr($link_target) .'"' : '') .'>
						<span class="front" style="'. $front_bg .'">
							'. (esc_attr($front_icon) ? '<i style="color:'. esc_attr($front_fg_color) .'" class="'. esc_attr($front_icon) .'"></i>' : '') .'
							'. (esc_attr($front_title) ? '<h3 style="color:'. esc_attr($front_fg_color) .'">'. esc_attr($front_title) .'</h3>' : '') .'
							'. (esc_attr($front_excerpt) ? '<p style="color:'. esc_attr($front_fg_color) .'">'. nl2br(strip_tags($front_excerpt)) .'</p>' : '') .'
						</span>
						<span class="back" style="'. $back_bg .'">
							'. (esc_attr($back_icon) ? '<i style="color:'. esc_attr($back_fg_color) .'" class="'. esc_attr($back_icon) .'"></i>' : '') .'
							'. (esc_attr($back_title) ? '<h3 style="color:'. esc_attr($back_fg_color) .'">'. esc_attr($back_title) .'</h3>' : '') .'
							'. (esc_attr($back_excerpt) ? '<p style="color:'. esc_attr($back_fg_color) .'">'. nl2br(strip_tags($back_excerpt)) .'</p>' : '') .'
						</span>
					</a>
				</div>';		
		}
		
		add_shortcode( 'ozy_vc_flipbox', 'ozy_vc_flipbox' );
		
		vc_map( array(
			"name" => __("Flip Box", "logistic-textdomain"),
			"base" => "ozy_vc_flipbox",
			"content_element" => true,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "select_an_icon",
					"heading" => __("Front Icon", "logistic-textdomain"),
					"param_name" => "front_icon",
					"value" => '',
					"admin_label" => false
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Front Title", "logistic-textdomain"),
					"param_name" => "front_title",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "textarea",
					"class" => "",
					"heading" => __("Front Excerpt", "logistic-textdomain"),
					"param_name" => "front_excerpt",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __("Front Background Image", "logistic-textdomain"),
					"param_name" => "front_bg_image",
					"admin_label" => false,
					"value" => ""
				),array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Front Background Color", "logistic-textdomain"),
					"param_name" => "front_bg_color",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Front Foreground Color", "logistic-textdomain"),
					"param_name" => "front_fg_color",
					"admin_label" => true,
					"value" => "#222222"
				),array(
					"type" => "select_an_icon",
					"heading" => __("Back Icon", "logistic-textdomain"),
					"param_name" => "back_icon",
					"value" => '',
					"admin_label" => false
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Back Title", "logistic-textdomain"),
					"param_name" => "back_title",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "textarea",
					"class" => "",
					"heading" => __("Back Excerpt", "logistic-textdomain"),
					"param_name" => "back_excerpt",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __("Back Background Image", "logistic-textdomain"),
					"param_name" => "back_bg_image",
					"admin_label" => false,
					"value" => ""
				),array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Back Background Color", "logistic-textdomain"),
					"param_name" => "back_bg_color",
					"admin_label" => true,
					"value" => "#222222"
				),array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Back Foreground Color", "logistic-textdomain"),
					"param_name" => "back_fg_color",
					"admin_label" => true,
					"value" => "#ffffff"
				),array(
					"type" => "dropdown",
					"heading" => __("Direction", "logistic-textdomain"),
					"param_name" => "direction",
					"value" => array("horizontal", "vertical"),
					"admin_label" => true
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Height", "logistic-textdomain"),
					"param_name" => "height",
					"admin_label" => true,
					"value" => "427",
					"description" => __("Please enter only integer values. Will be processed in pixels.", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Link", "logistic-textdomain"),
					"param_name" => "link",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "dropdown",
					"heading" => __("Link Target", "logistic-textdomain"),
					"param_name" => "link_target",
					"value" => array("_self", "_blank", "_parent"),
					"admin_label" => false,
					"description" => __("Select link target window.", "logistic-textdomain")
				)
		   )
		) );	
	}

	/**
	* Textillate
	*/
	if (!function_exists('ozy_vc_textillate')) {
		function ozy_vc_textillate( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_textillate', $atts);
			extract(shortcode_atts(array(
				'size' 				=> '22px',
				'display_time' 		=> '2000',
				'font_color' 		=> '#000000',
				'font_weight' 		=> '300',
				'in_effect' 		=> 'fadeInLeftBig',
				'in_effect_type'	=> 'sequence',
				'out_effect' 		=> 'hinge',
				'out_effect_type'	=> 'shuffle',
				'loop'				=> 'true',
				'align'				=> ''
			), $atts));
			
			switch ($align) {
				case 'right':
					$align = 'width:100%;display:inline-block;text-align:right;';
					break;
				case 'center':
					$align = 'width:100%;display:inline-block;text-align:center;';
					break;
				default:
					$align = '';
			}
			
			$output = '<div class="ozy-tlt" style="'. esc_attr($align) .'color:'. esc_attr($font_color) .';font-weight:'. esc_attr($font_weight) .';font-size:'. esc_attr($size) .'px;line-height:'. ((int)esc_attr($size)+10) .'px" data-display_time="'. esc_attr($display_time) .'" data-in_effect="'. esc_attr($in_effect) .'" data-in_effect_type="'. esc_attr($in_effect_type) .'" data-out_effect="'. esc_attr($out_effect) .'" data-out_effect_type="'. esc_attr($out_effect_type) .'" data-loop="'. esc_attr($loop) .'">';
			$content = explode("<br />", $content);
			if(is_array($content)) {
				$output.= '<ul class="ozy-tlt-texts" style="display: none">';
				foreach($content as $line) {
					$output.= '<li>'. trim($line) .'</li>';
				}
				$output.= '</ul>';
			}
			$output.= '</div>';
			
			return $output;
		}
		
		add_shortcode('ozy_vc_textillate', 'ozy_vc_textillate');

		vc_map( array(
			"name" => __("Textillate", "logistic-textdomain"),
			"base" => "ozy_vc_textillate",
			"content_element" => true,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "textarea",
					"class" => "",
					"heading" => __("Content", "logistic-textdomain"),
					"param_name" => "content",
					"admin_label" => true,
					"description" => __('Each line will be processed as a slide.', 'logistic-textdomain'),
					"value" => ""
				),array(
					"type" => "dropdown",
					"heading" => __("Size", "logistic-textdomain"),
					"param_name" => "size",
					"value" => array("12", "14", "16", "18", "20", "22", "24", "26", "28", "30", "32", "34", "36", "38", "40", "42", "44", "46", "48", "50", "52", "54", "56", "58", "60", "62", "64", "66", "68", "70", "72", "74", "76", "78", "80", "90", "92", "94", "96", "98", "100"),
					"admin_label" => true
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Display Time", "logistic-textdomain"),
					"param_name" => "display_time",
					"admin_label" => true,
					"description" => __('Sets the minimum display time for each text before it is replaced.', 'logistic-textdomain'),
					"value" => "2000"
				),array(
					"type" => "dropdown",
					"heading" => __("Loop", "logistic-textdomain"),
					"param_name" => "loop",
					"value" => array("true", "false"),
					"admin_label" => true
				),array(
					"type" => "colorpicker",
					"heading" => __("Color", "logistic-textdomain"),
					"param_name" => "font_color",
					"admin_label" => false,
					"value" => "",
					"description" => __("Color of your text.", "logistic-textdomain")
				),array(
					"type" => "dropdown",
					"heading" => __("Font Weight", "logistic-textdomain"),
					"param_name" => "font_weight",
					"value" => array("300", "100", "200", "400", "500" , "600", "700", "800", "900"),
					"admin_label" => true
				),array(
					"type" => "dropdown",
					"heading" => __("In Animation Effect", "logistic-textdomain"),
					"param_name" => "in_effect",
					"value" => $animate_css_effects,
					"admin_label" => true
				),array(
					"type" => "dropdown",
					"heading" => __("In Animation Type", "logistic-textdomain"),
					"param_name" => "in_effect_type",
					"value" => array("sequence", "reverse", "sync", "shuffle"),
					"admin_label" => true
				),array(
					"type" => "dropdown",
					"heading" => __("Out Animation Effect", "logistic-textdomain"),
					"param_name" => "out_effect",
					"value" => $animate_css_effects,
					"admin_label" => true
				),array(
					"type" => "dropdown",
					"heading" => __("Out Animation Type", "logistic-textdomain"),
					"param_name" => "out_effect_type",
					"value" => array("sequence", "reverse", "sync", "shuffle"),
					"admin_label" => true
				),array(
					"type" => "dropdown",
					"heading" => __("Text Align", "logistic-textdomain"),
					"param_name" => "align",
					"value" => array("left", "center", "right"),
					"admin_label" => true
				)								
		   )
		) );
	}


	/**
	* Floating Box
	*/
	if (!function_exists('ozy_vc_floatingbox')) {
		function ozy_vc_floatingbox( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_floatingbox', $atts);
			extract(shortcode_atts(array(
				'height' => '300px',
				'align' => 'left'
			), $atts));
			
			return '<div class="ozy-floating-box" style="height:'. esc_attr($height) .';text-align:'. esc_attr($align) .'"><div>'. do_shortcode($content) .'</div></div>';
		}
		
		add_shortcode('ozy_vc_floatingbox', 'ozy_vc_floatingbox');
		
		vc_map( array(
			"name" => __("Floating Box", "logistic-textdomain"),
			"base" => "ozy_vc_floatingbox",
			"as_parent" => array('except ' => ''),
			"content_element" => true,
			"show_settings_on_create" => true,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "textfield",
					"heading" => __("Height", "logistic-textdomain"),
					"param_name" => "height",
					"value" => "300px",
					"description" => __("Please set same height as your row height as initial value, in order to make it work as expected", "logistic-textdomain"),
					"admin_label" => true
				),array(
					"type" => "dropdown",
					"heading" => __("Content Align", "logistic-textdomain"),
					"param_name" => "align",
					"value" => array("left", "center", "right"),
					"admin_label" => true
				)			
		   ),
		   "js_view" => 'VcColumnView'
		) );
	}

	class WPBakeryShortCode_Ozy_Vc_Floatingbox extends WPBakeryShortCodesContainer{}

	/**
	* Morph Text
	*/
	if (!function_exists('ozy_vc_morphtext')) {
		function ozy_vc_morphtext( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_morphtext', $atts);
			extract(shortcode_atts(array(
				'text_before'	=> '',
				'text_after' 	=> '',
				'text_rotate'	=> '',
				'size' 			=> 'h1',
				'font_color' 	=> '#000000',
				'rotating_color'=> '#000000',
				'separator' 	=> ',',
				'effect' 		=> 'bounceIn',
				'speed' 		=> '2000'
			), $atts));
			
			return '<div><'. esc_attr($size) .' class="ozy-morph-text" style="color:'. esc_attr($font_color) .'" data-separator="'. esc_attr($separator) .'" data-effect="'. esc_attr($effect) .'" data-speed="'. esc_attr($speed) .'"><span class="bt">'. esc_attr($text_before) .'</span> <span class="text-rotate" style="color:'. esc_attr($rotating_color) .'">'. esc_attr($text_rotate) .'</span> '. esc_attr($text_after) .'</'. esc_attr($size) .'"></div>';
		}
		
		add_shortcode('ozy_vc_morphtext', 'ozy_vc_morphtext');

		vc_map( array(
			"name" => __("Morph Text", "logistic-textdomain"),
			"base" => "ozy_vc_morphtext",
			"content_element" => true,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Before Rotating Text", "logistic-textdomain"),
					"param_name" => "text_before",
					"admin_label" => true,
					"description" => __('This text will be shown before rotating text.', 'logistic-textdomain'),
					"value" => ""
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("After Rotating Text", "logistic-textdomain"),
					"param_name" => "text_after",
					"admin_label" => true,
					"description" => __('This text will be shown after rotating text.', 'logistic-textdomain'),
					"value" => ""
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Rotating Text", "logistic-textdomain"),
					"param_name" => "text_rotate",
					"admin_label" => true,
					"description" => __('Use separator between words, default ",". Seperator could be managed by the box below.', 'logistic-textdomain'),
					"value" => ""
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Separator", "logistic-textdomain"),
					"param_name" => "separator",
					"admin_label" => true,
					"description" => __('If you don\'t want commas to be the separator, you can define a new separator (|, &, * etc.) by yourself using this field.', 'logistic-textdomain'),
					"value" => ","
				),array(
					"type" => "dropdown",
					"heading" => __("Size", "logistic-textdomain"),
					"param_name" => "size",
					"value" => array("h1", "h2", "h3", "h4", "h5", "h6"),
					"admin_label" => true
				),array(
					"type" => "colorpicker",
					"heading" => __("Color", "logistic-textdomain"),
					"param_name" => "font_color",
					"admin_label" => false,
					"value" => "",
					"description" => __("Color of your text.", "logistic-textdomain")
				),array(
					"type" => "colorpicker",
					"heading" => __("Rotating Text Color", "logistic-textdomain"),
					"param_name" => "rotating_color",
					"admin_label" => false,
					"value" => "",
					"description" => __("Color of your rotating text.", "logistic-textdomain")
				),array(
					"type" => "dropdown",
					"heading" => __("Effect", "logistic-textdomain"),
					"param_name" => "effect",
					"value" => $animate_css_effects,
					"admin_label" => true
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Speed", "logistic-textdomain"),
					"param_name" => "speed",
					"admin_label" => true,
					"description" => __('How many milliseconds until the next word show.', 'logistic-textdomain'),
					"value" => "2000"
				)						
		   )
		) );
	}

	/**
	* Spacer
	*/
	if (!function_exists('ozy_vc_spacer')) {
		function ozy_vc_spacer( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_spacer', $atts);
			extract(shortcode_atts(array(
				'size' 			=> '30px'
			), $atts));
			
			return '<div style="height:'. esc_attr($size) .'" class="ozy-spacer"></div>';
		}
		
		add_shortcode('ozy_vc_spacer', 'ozy_vc_spacer');

		vc_map( array(
			"name" => __("Spacer", "logistic-textdomain"),
			"base" => "ozy_vc_spacer",
			"content_element" => true,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Size", "logistic-textdomain"),
					"param_name" => "size",
					"admin_label" => true,
					"description" => __('Enter size like 10px, 3em. Please don\'t use percentage values.', 'logistic-textdomain'),
					"value" => "30px"
				)
		   )
		) );
	}
	
	/**
	* Instagram Carousel Feeder
	*/
	if (!function_exists('ozy_vc_instagramcarousel')) {
		function ozy_vc_instagramcarousel( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_instagramcarousel', $atts);
			extract(shortcode_atts(array(
				'user'			=> 'self',
				'autoplay'		=> '',
				'items'			=> '',
				'singleitem'	=> '',
				'slidespeed'	=> '',
				'autoheight'	=> '',
				'extra_css'		=> '',
				'css_animation' => ''
			), $atts));
			
			$essentials_options = get_option('ozy_logistic_essentials');
			if( is_array($essentials_options) && isset($essentials_options['instagram_access_token_key'])) {		
				if($css_animation) {
					wp_enqueue_script('waypoints');
					$css_animation = " wpb_animate_when_almost_visible wpb_" . esc_attr($css_animation) . " ";
				}
		
				$output = '<div class="ozy-owlcarousel with-feed '. $css_animation .'" data-autoplay="'. esc_attr($autoplay) .'" data-items="'. esc_attr($items) .'" data-singleitem="'. esc_attr($singleitem) .'" data-slidespeed="'. esc_attr($slidespeed) .'" data-paginationSpeed="800" data-autoheight="'. esc_attr($autoheight) .'">' . PHP_EOL;
		
				$result = ozy_fetchCurlData("https://api.instagram.com/v1/users/". esc_attr($user) ."/media/recent/?access_token=". esc_attr($essentials_options['instagram_access_token_key']));
				
				if($result === '-10') {
					$output .= '<div class="item item-extended">Curl is not active on your server</div>';
				}else{
					$result = json_decode($result);
					if(isset($result->data) && is_array($result->data)) {
						foreach ($result->data as $post) {
							$output .= '<div class="item item-extended">';
							$output .= '<img class="lazyOwl" src="'. get_template_directory_uri() .'/images/blank-large.gif" alt="" data-src="'. esc_url($post->images->standard_resolution->url) .'">';
							$output .= '<a href="'. esc_url($post->link) .'" target="_blank">';
							$output .= '</a>';
							$output .= '</div>';
						}
					}else{
						$output .= '<div class="item item-extended">An error occuired. Pleaese check your access token and try again.</div>';
					}
				}
				$output.= do_shortcode( $content );
				$output.= PHP_EOL .'</div>';
			
				return $output;
			}else{
				return 'ozy Essentials Plugin has to be installed and necessary Instagram Parameters has to be set on it';
			}
		}
		
		add_shortcode('ozy_vc_instagramcarousel', 'ozy_vc_instagramcarousel');
		
		vc_map( array(
			"name" => __("Instagram Carousel", "logistic-textdomain"),
			"base" => "ozy_vc_instagramcarousel",
			"content_element" => true,
			"show_settings_on_create" => true,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("User ID", "logistic-textdomain"),
					"param_name" => "user",
					"admin_label" => true,
					"value" => "self",
					"description" => __('Use your own images or get User ID <a href="http://jelled.com/instagram/lookup-user-id" target="_blank">here</a>.', "logistic-textdomain")
				),
				array(
					"type" => "dropdown",
					"heading" => __("Auto Play", "logistic-textdomain"),
					"param_name" => "autoplay",
					"value" => array("true", "false", "1000", "2000", "3000", "4000", "5000", "6000", "7000", "8000", "9000", "10000"),
					"admin_label" => true,
					"description" => __("Change to any available integrer for example 3000 to play every 3 seconds. If you set it true default speed will be 5 seconds.", "logistic-textdomain")
				),		
				array(
					"type" => "dropdown",
					"heading" => __("Item Count", "logistic-textdomain"),
					"param_name" => "items",
					"value" => array("3", "1", "2", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16"),
					"admin_label" => true,
					"description" => __("This variable allows you to set the maximum amount of items displayed at a time with the widest browser width.", "logistic-textdomain")
				),
				array(
					"type" => "dropdown",
					"heading" => __("Show Single Item?", "logistic-textdomain"),
					"param_name" => "singleitem",
					"value" => array("false", "true"),
					"admin_label" => true,
					"description" => __("Display only one item. Set Item Count to 1 to make it work as expected.", "logistic-textdomain")
				),
				array(
					"type" => "dropdown",
					"heading" => __("Slide Speed", "logistic-textdomain"),
					"param_name" => "slidespeed",
					"value" => array("200", "100", "300", "400", "500", "600", "700", "800", "900", "1000", "1100", "1200", "1300", "1400", "1500", "1600", "1700", "1800", "1900", "2000"),
					"admin_label" => true,
					"description" => __("Slide speed in milliseconds.", "logistic-textdomain")
				),
				array(
					"type" => "dropdown",
					"heading" => __("Auto Height", "logistic-textdomain"),
					"param_name" => "autoheight",
					"value" => array("false", "true"),
					"admin_label" => true,
					"description" => __("Add height to owl-wrapper-outer so you can use diffrent heights on slides. Use it only for one item per page setting.", "logistic-textdomain")
				),		
				$add_css_animation,
				array(
					"type" => "textfield",
					"heading" => __("Extra class name", "logistic-textdomain"),
					"param_name" => "el_class",
					"description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "logistic-textdomain")
				)			
		   )
		) );
	}

	/**
	* WooCommerce Carousel
	*/
	if (!function_exists('ozy_vc_woocarousel')) {
		function ozy_vc_woocarousel( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_woocarousel', $atts);
			extract(shortcode_atts(array(
				'autoplay'		=> '',
				'items'			=> '',
				'singleitem'	=> '',
				'slidespeed'	=> '',
				'autoheight'	=> '',
				'extra_css'		=> '',
				'css_animation' => ''
			), $atts));
			
			if(!class_exists('woocommerce')) {
				return '<div>'. __('WooCommerce Plugin is not installed or activated', 'logistic-textdomain') .'</div>';
			}
			
			if($css_animation) {
				wp_enqueue_script('waypoints');
				$css_animation = " wpb_animate_when_almost_visible wpb_" . esc_attr($css_animation) . " ";
			}		

			$output = '<div class="ozy-owlcarousel woocommerce-carousel '. $css_animation .'" data-autoplay="'. esc_attr($autoplay) .'" data-items="'. esc_attr($items) .'" data-singleitem="'. esc_attr($singleitem) .'" data-slidespeed="'. esc_attr($slidespeed) .'" data-paginationSpeed="800" data-autoheight="'. esc_attr($autoheight) .'">' . PHP_EOL;

			$args = array(
				'post_type' 			=> 'product',
				'posts_per_page'		=> '8',
				'orderby' 				=> 'date',
				'order' 				=> 'DESC',
				'ignore_sticky_posts' 	=> 1,							
				'meta_key' 				=> '_thumbnail_id'
			);

			$the_query = new WP_Query( $args );
				
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				global $product;
				
				$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'showbiz');

				$output .= '<div class="item item-extended">';
				$output .= '<figure>';
				if(isset($thumb[0])) {$output .= '<img class="lazyOwl" src="'. get_template_directory_uri() .'/images/blank-large.gif" data-src="'. esc_url($thumb[0]) .'" alt="'. get_the_title() .'"/>';}else{$output .= '<img class="lazyOwl" src="'. get_template_directory_uri() .'/images/blank-large.gif" data-src="'. get_template_directory_uri() .'/images/blank-large.gif" alt=""/>';}
				$output .= '<div class="overlay">';
				$output .= '<div>';

				if ($product->is_in_stock()) {
					$link = array(
						'url' => '',
						'label' => '',
						'class' => ''
					);
					
					$handler = apply_filters( 'woocommerce_add_to_cart_handler', $product->product_type, $product );
					
					switch ( $handler ) {
						case "variable" :
							$link['url'] = apply_filters( 'variable_add_to_cart_url', get_permalink( $product->id ) );
							$link['label'] = apply_filters( 'variable_add_to_cart_text', __( 'SELECT OPTIONS', 'logistic-textdomain' ) );
							break;
						case "grouped" :
							$link['url'] = apply_filters( 'grouped_add_to_cart_url', get_permalink( $product->id ) );
							$link['label'] = apply_filters( 'grouped_add_to_cart_text', __( 'VIEW OPTIONS', 'logistic-textdomain' ) );
							break;
						case "external" :
							$link['url'] = apply_filters( 'external_add_to_cart_url', get_permalink( $product->id ) );
							$link['label'] = apply_filters( 'external_add_to_cart_text', __( 'READ MORE', 'logistic-textdomain' ) );
							break;
						default :
							if ( $product->is_purchasable() ) {
								$link['url'] = apply_filters( 'add_to_cart_url', esc_url( $product->add_to_cart_url() ) );
								$link['label'] = apply_filters( 'add_to_cart_text', __( 'ADD TO CART', 'logistic-textdomain' ) );
								$link['class'] = apply_filters( 'add_to_cart_class', 'add_to_cart_button' );
							} else {
								$link['url'] = apply_filters( 'not_purchasable_url', get_permalink( $product->id ) );
								$link['label'] = apply_filters( 'not_purchasable_text', __( 'READ MORE', 'logistic-textdomain' ) );
							}
						break;
					}
					$output .= apply_filters( 'woocommerce_loop_add_to_cart_link', 
						sprintf('<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="%s button product_type_%s"><div class="btn-basket">%s</div></a>', 
						esc_url( $link['url'] ), 
						esc_attr( $product->id ), 
						esc_attr( $product->get_sku() ), 
						esc_attr( $link['class'] ), 
						esc_attr( $product->product_type ), 
						esc_html( $link['label'] ) ), $product, $link );
					$output .= '<a href="'. get_permalink() .'" class="product-details">'. __('SEE PRODUCT DETAILS', 'logistic-textdomain') .'</a>';
				}else{
					$output .= '<a href="' . apply_filters( 'out_of_stock_add_to_cart_url', get_permalink( $product->id ) ) .'" class="button">'. apply_filters( 'out_of_stock_add_to_cart_text', __( 'READ MORE', 'logistic-textdomain' ) ) .'</a>';
				}
							
				$output .= '</div>';
				$output .= '</div>';
				$output .= '</figure>';
				$output .= '<div class="info">';
				$output .= '<h3 class="content-color">'. get_the_title() .'</h3>';
				$output .= '<h5 class="content-color-alternate">'. $product->get_price_html().'</h5>';
				$output .= '</div>';

				$output .= '</div>';			
			}
			wp_reset_postdata();
			
			$output.= do_shortcode( $content );
			$output.= PHP_EOL .'</div>';
			
			return $output;
		}
		
		add_shortcode('ozy_vc_woocarousel', 'ozy_vc_woocarousel');
		
		vc_map( array(
			"name" => __("WooCommerce Product Carousel", "logistic-textdomain"),
			"base" => "ozy_vc_woocarousel",
			"content_element" => true,
			"show_settings_on_create" => true,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(			
				array(
					"type" => "dropdown",
					"heading" => __("Auto Play", "logistic-textdomain"),
					"param_name" => "autoplay",
					"value" => array("true", "false", "1000", "2000", "3000", "4000", "5000", "6000", "7000", "8000", "9000", "10000"),
					"admin_label" => true,
					"description" => __("Change to any available integrer for example 3000 to play every 3 seconds. If you set it true default speed will be 5 seconds.", "logistic-textdomain")
				),		
				array(
					"type" => "dropdown",
					"heading" => __("Item Count", "logistic-textdomain"),
					"param_name" => "items",
					"value" => array("3", "1", "2", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16"),
					"admin_label" => true,
					"description" => __("This variable allows you to set the maximum amount of items displayed at a time with the widest browser width.", "logistic-textdomain")
				),
				array(
					"type" => "dropdown",
					"heading" => __("Show Single Item?", "logistic-textdomain"),
					"param_name" => "singleitem",
					"value" => array("false", "true"),
					"admin_label" => true,
					"description" => __("Display only one item. Set Item Count to 1 to make it work as expected.", "logistic-textdomain")
				),
				array(
					"type" => "dropdown",
					"heading" => __("Slide Speed", "logistic-textdomain"),
					"param_name" => "slidespeed",
					"value" => array("200", "100", "300", "400", "500", "600", "700", "800", "900", "1000", "1100", "1200", "1300", "1400", "1500", "1600", "1700", "1800", "1900", "2000"),
					"admin_label" => true,
					"description" => __("Slide speed in milliseconds.", "logistic-textdomain")
				),
				array(
					"type" => "dropdown",
					"heading" => __("Auto Height", "logistic-textdomain"),
					"param_name" => "autoheight",
					"value" => array("false", "true"),
					"admin_label" => true,
					"description" => __("Add height to owl-wrapper-outer so you can use diffrent heights on slides. Use it only for one item per page setting.", "logistic-textdomain")
				),		
				$add_css_animation,
				array(
					"type" => "textfield",
					"heading" => __("Extra class name", "logistic-textdomain"),
					"param_name" => "el_class",
					"description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "logistic-textdomain")
				)			
		   )
		) );
	}


	/**
	* Owl Carousel Feeder
	*/
	if (!function_exists('ozy_vc_owlcarousel_feed')) {
		function ozy_vc_owlcarousel_feed( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_owlcarousel_feed', $atts);
			extract(shortcode_atts(array(
				'bg_color'		=> '',
				'link_caption' 	=> 'Find out more →',
				'link_target'	=> '',
				'default_overlay' => 'off',
				'autoplay'		=> '',
				'items'			=> '',
				'singleitem'	=> '',
				'slidespeed'	=> '',
				'autoheight'	=> '',
				'extra_css'		=> '',
				'css_animation' => ''
			), $atts));
			
			if($css_animation) {
				wp_enqueue_script('waypoints');
				$css_animation = " wpb_animate_when_almost_visible wpb_" . esc_attr($css_animation) . " ";
			}		

			$output = '<div class="ozy-owlcarousel with-feed '. $css_animation .'" data-autoplay="'. esc_attr($autoplay) .'" data-items="'. esc_attr($items) .'" data-singleitem="'. esc_attr($singleitem) .'" data-slidespeed="'. esc_attr($slidespeed) .'" data-paginationSpeed="800" data-autoheight="'. esc_attr($autoheight) .'">' . PHP_EOL;

			$args = array(
				'post_type' 			=> 'post',
				'posts_per_page'		=> '8',
				'orderby' 				=> 'date',
				'order' 				=> 'DESC',
				'ignore_sticky_posts' 	=> 1,							
				'meta_key' 				=> '_thumbnail_id'
			);

			$the_query = new WP_Query( $args );
				
			while ( $the_query->have_posts() ) {
				$the_query->the_post();

				$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'showbiz');

				$style = esc_attr($bg_color) ? ' style="background-color:'. esc_attr($bg_color) .';" ' : ''; //height:278px;
				$output .= '<div class="item item-extended" '. $style .'>';
				if(isset($thumb[0])) {
					$output .= '<img class="lazyOwl" src="'. get_template_directory_uri() .'/images/blank-large.gif" data-src="'. esc_url($thumb[0]) .'" alt="'. get_the_title() .'"/>';
				}else{
					$output .= '<img class="lazyOwl" src="'. get_template_directory_uri() .'/images/blank-large.gif" data-src="'. get_template_directory_uri() .'/images/blank-large.gif" alt=""/>';
				}
				$output .= '<a href="'. get_permalink() .'" target="'. esc_attr($link_target) .'">';
				if(esc_attr($default_overlay) === 'on') {
					$output .= '<div class="overlay-two">';
					$output .= '<div>';
					$output .= '<h2>'. get_the_title() .'</h2>';
					$output .= '<h5>'. get_the_date() .'</h5>';
					$output .= '</div>';			
					$output .= '</div>';
				}
				$output .= '</a>';
				$output .= '</div>';			
			}
			wp_reset_postdata();
			
			$output.= do_shortcode( $content );
			$output.= PHP_EOL .'</div>';
			
			return $output;
		}
		
		add_shortcode('ozy_vc_owlcarousel_feed', 'ozy_vc_owlcarousel_feed');
		
		vc_map( array(
			"name" => __("Owl Carousel Feed", "logistic-textdomain"),
			"base" => "ozy_vc_owlcarousel_feed",
			"content_element" => true,
			"show_settings_on_create" => true,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "colorpicker",
					"heading" => __("Carousel Background", "logistic-textdomain"),
					"param_name" => "bg_color",
					"admin_label" => false,
					"value" => "",
					"description" => __("Not requrired. Select a background color for your item.", "logistic-textdomain")
				),
				array(
					"type" => "dropdown",
					"heading" => __("Hover Overlay?", "logistic-textdomain"),
					"param_name" => "default_overlay",
					"value" => array("off", "on"),
					"admin_label" => false,
					"description" => __("ON/OFF default overlay on your items.", "logistic-textdomain")
				),		
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Link Caption", "logistic-textdomain"),
					"param_name" => "link_caption",
					"admin_label" => true,
					"value" => __("Find out more →", "logistic-textdomain")
				),array(
					"type" => "dropdown",
					"heading" => __("Link Target", "logistic-textdomain"),
					"param_name" => "link_target",
					"value" => array("_self", "_blank", "_parent"),
					"admin_label" => false,
					"description" => __("Select link target window.", "logistic-textdomain")
				),					
				array(
					"type" => "dropdown",
					"heading" => __("Auto Play", "logistic-textdomain"),
					"param_name" => "autoplay",
					"value" => array("true", "false", "1000", "2000", "3000", "4000", "5000", "6000", "7000", "8000", "9000", "10000"),
					"admin_label" => true,
					"description" => __("Change to any available integrer for example 3000 to play every 3 seconds. If you set it true default speed will be 5 seconds.", "logistic-textdomain")
				),		
				array(
					"type" => "dropdown",
					"heading" => __("Item Count", "logistic-textdomain"),
					"param_name" => "items",
					"value" => array("3", "1", "2", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16"),
					"admin_label" => true,
					"description" => __("This variable allows you to set the maximum amount of items displayed at a time with the widest browser width.", "logistic-textdomain")
				),
				array(
					"type" => "dropdown",
					"heading" => __("Show Single Item?", "logistic-textdomain"),
					"param_name" => "singleitem",
					"value" => array("false", "true"),
					"admin_label" => true,
					"description" => __("Display only one item. Set Item Count to 1 to make it work as expected.", "logistic-textdomain")
				),
				array(
					"type" => "dropdown",
					"heading" => __("Slide Speed", "logistic-textdomain"),
					"param_name" => "slidespeed",
					"value" => array("200", "100", "300", "400", "500", "600", "700", "800", "900", "1000", "1100", "1200", "1300", "1400", "1500", "1600", "1700", "1800", "1900", "2000"),
					"admin_label" => true,
					"description" => __("Slide speed in milliseconds.", "logistic-textdomain")
				),
				array(
					"type" => "dropdown",
					"heading" => __("Auto Height", "logistic-textdomain"),
					"param_name" => "autoheight",
					"value" => array("false", "true"),
					"admin_label" => true,
					"description" => __("Add height to owl-wrapper-outer so you can use diffrent heights on slides. Use it only for one item per page setting.", "logistic-textdomain")
				),		
				$add_css_animation,
				array(
					"type" => "textfield",
					"heading" => __("Extra class name", "logistic-textdomain"),
					"param_name" => "el_class",
					"description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "logistic-textdomain")
				)			
		   )
		) );
	}

	/**
	* Owl Carousel
	*/
	if (!function_exists('ozy_vc_owlcarousel_wrapper')) {
		function ozy_vc_owlcarousel_wrapper( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_owlcarousel_wrapper', $atts);
			extract(shortcode_atts(array(
				'autoplay'		=> 'true',
				'items'			=> '4',
				'singleitem'	=> 'false',
				'slidespeed'	=> '200',
				'autoheight'	=> 'false',
				'extra_css'		=> '',
				'css_animation' => '',
				'bullet_nav'	=> 'on'
			), $atts));
			
			if($css_animation) {
				wp_enqueue_script('waypoints');
				$css_animation = " wpb_animate_when_almost_visible wpb_" . esc_attr($css_animation) . " ";
			}		

			return '<div class="ozy-owlcarousel '. (esc_attr($bullet_nav) != 'on' ? 'navigation-off' : '') .' '. $css_animation .'" data-autoplay="'. esc_attr($autoplay) .'" data-items="'. esc_attr($items) .'" data-singleitem="'. esc_attr($singleitem) .'" data-slidespeed="'. esc_attr($slidespeed) .'" data-paginationSpeed="800" data-autoheight="'. esc_attr($autoheight) .'">' . PHP_EOL . do_shortcode( $content ) . PHP_EOL .'</div>';
		}
		
		add_shortcode('ozy_vc_owlcarousel_wrapper', 'ozy_vc_owlcarousel_wrapper');
		
		vc_map( array(
			"name" => __("Owl Carousel", "logistic-textdomain"),
			"base" => "ozy_vc_owlcarousel_wrapper",
			"as_parent" => array('only' => 'ozy_vc_owlcarousel,ozy_vc_owlcarousel2,ozy_vc_owlcarousel_testimonial'),
			"content_element" => true,
			"show_settings_on_create" => false,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "dropdown",
					"heading" => __("Auto Play", "logistic-textdomain"),
					"param_name" => "autoplay",
					"value" => array("true", "false", "1000", "2000", "3000", "4000", "5000", "6000", "7000", "8000", "9000", "10000"),
					"admin_label" => true,
					"description" => __("Change to any available integrer for example 3000 to play every 3 seconds. If you set it true default speed will be 5 seconds.", "logistic-textdomain")
				),		
				array(
					"type" => "dropdown",
					"heading" => __("Item Count", "logistic-textdomain"),
					"param_name" => "items",
					"value" => array("4", "1", "2", "3", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16"),
					"admin_label" => true,
					"description" => __("This variable allows you to set the maximum amount of items displayed at a time with the widest browser width.", "logistic-textdomain")
				),
				array(
					"type" => "dropdown",
					"heading" => __("Show Single Item?", "logistic-textdomain"),
					"param_name" => "singleitem",
					"value" => array("false", "true"),
					"admin_label" => true,
					"description" => __("Display only one item. Set Item Count to 1 to make it work as expected.", "logistic-textdomain")
				),
				array(
					"type" => "dropdown",
					"heading" => __("Slide Speed", "logistic-textdomain"),
					"param_name" => "slidespeed",
					"value" => array("200", "100", "300", "400", "500", "600", "700", "800", "900", "1000", "1100", "1200", "1300", "1400", "1500", "1600", "1700", "1800", "1900", "2000"),
					"admin_label" => true,
					"description" => __("Slide speed in milliseconds.", "logistic-textdomain")
				),
				array(
					"type" => "dropdown",
					"heading" => __("Auto Height", "logistic-textdomain"),
					"param_name" => "autoheight",
					"value" => array("false", "true"),
					"admin_label" => true,
					"description" => __("Add height to owl-wrapper-outer so you can use diffrent heights on slides. Use it only for one item per page setting.", "logistic-textdomain")
				),
				array(
					"type" => "dropdown",
					"heading" => __("Bullet Navigation", "logistic-textdomain"),
					"param_name" => "bullet_nav",
					"value" => array("on", "off"),
					"admin_label" => true
				),		
				$add_css_animation,
				array(
					"type" => "textfield",
					"heading" => __("Extra class name", "logistic-textdomain"),
					"param_name" => "el_class",
					"description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "logistic-textdomain")
				)			
		   ),
		   "js_view" => 'VcColumnView'
		) );
	}

	if (!function_exists('ozy_vc_owlcarousel')) {
		function ozy_vc_owlcarousel( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_owlcarousel', $atts);
			extract(shortcode_atts(array(
				'src' 			=> '',
				'img_size'		=> 'full',
				'link'			=> '',
				'link_target'	=> ''
			), $atts));

			$output = '';

			$img_size = strpos(strtolower(esc_attr($img_size)), "x") > -1 ? explode('x', esc_attr($img_size)) : $img_size;
			$thumb = wp_get_attachment_image_src($src, $img_size);

			if(isset($thumb[0])) {
				if(esc_attr($link)) $output = '<a href="'. esc_attr($link) .'" target="'. esc_attr($link_target) .'">';
				$output .= '<div class="item"><img class="lazyOwl" data-src="'. esc_url($thumb[0]) .'" src="'. get_template_directory_uri() .'/images/blank-large.gif" alt="'. esc_attr($img_size) .'"/></div>';
				if(esc_attr($link)) $output .= '</a>';
			}
			
			return $output;
		}
		
		add_shortcode('ozy_vc_owlcarousel', 'ozy_vc_owlcarousel');

		vc_map( array(
			"name" => __("Owl Carousel Item", "logistic-textdomain"),
			"base" => "ozy_vc_owlcarousel",
			"content_element" => true,
			"as_child" => array('only' => 'ozy_vc_owlcarousel_wrapper'),
			"icon" => "icon-wpb-ozy-el",
			"params" => array(
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __("Carousel Image", "logistic-textdomain"),
					"param_name" => "src",
					"admin_label" => false,
					"value" => "",
					"description" => __("Select images for your slider.", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"heading" => __("Image size", "logistic-textdomain"),
					"param_name" => "img_size",
					"value" => "full",
					"description" => __("Enter image size. Example: 'thumbnail', 'medium', 'large', 'full' or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use 'thumbnail' size.", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Link", "logistic-textdomain"),
					"param_name" => "link",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "dropdown",
					"heading" => __("Link Target", "logistic-textdomain"),
					"param_name" => "link_target",
					"value" => array("_self", "_blank", "_parent"),
					"admin_label" => false,
					"description" => __("Select link target window.", "logistic-textdomain")
				),$add_css_animation
		   )
		) );
	}

	if (!function_exists('ozy_vc_owlcarousel2')) {
		function ozy_vc_owlcarousel2( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_owlcarousel2', $atts);
			extract(shortcode_atts(array(
				'src' 			=> '',
				'bg_color'		=> '',
				'icon' 			=> '',
				'icon_src'		=> '',
				'title' 		=> '',
				'excerpt' 		=> '',
				'link_caption' 	=> 'Find out more →',
				'link' 			=> '',
				'link_target'	=> '_self',
				'img_size'		=> 'full',
				'default_overlay' => 'off',
				'overlay_bg' 	=> '',
				'title_size'	=> 'h2'
			), $atts));

			$output = '';

			$img_size = strpos(strtolower(esc_attr($img_size)), "x") > -1 ? explode('x', esc_attr($img_size)) : $img_size;
			$thumb = wp_get_attachment_image_src($src, $img_size);

			$style = esc_attr($bg_color) ? ' style="background-color:'. esc_attr($bg_color) .';" ' : ''; //height:278px;
			$output = '<div class="item item-extended" '. $style .'>';
			if(isset($thumb[0])) {
				$output .= '<img src="'. esc_url($thumb[0]) .'" alt=""/>';
			}else{
				$output .= '<img src="'. get_template_directory_uri() .'/images/blank-large.gif" alt=""/>';
			}
			$output .= '<a'. ($link ? ' href="'. esc_attr($link) .'" target="'. esc_attr($link_target) .'"':'') .'>';
			$output .= '<div class="overlay-one '.(esc_attr($default_overlay) === 'on' ? 'overlay-one-bg' : '').'">';
			if(esc_attr($icon_src)) {// && isset($icon_thumb[0])
				$icon_thumb = wp_get_attachment_image_src($icon_src, 'full');
				$output .= '<span><img src="'. esc_url($icon_thumb[0]) .'" alt="'. esc_attr($title) .'"/></span>';
			}else{
				if(esc_attr($icon)){
					$output .= '<span class="'. esc_attr($icon) .'"></span>';
				}else{
					$output .= '<'. $title_size .'>'. esc_attr($title) .'</'. $title_size .'>';
				}
			}
			$output .= '</div>';
			$output .= '<div class="overlay-two"'. ($overlay_bg ? ' style="background-color:'. esc_attr($overlay_bg) .'"':'') .'>';
			$output .= '<p>'. esc_attr($excerpt);
			if($link) {
				$output .= '<span>'. esc_attr($link_caption) .'</span>';
			}
			$output .= '</p>';
			$output .= '</div>';
			$output .= '</a>';
			$output .= '</div>';

			
			return $output;
		}
		
		add_shortcode('ozy_vc_owlcarousel2', 'ozy_vc_owlcarousel2');

		vc_map( array(
			"name" => __("Owl Carousel Extended Item", "logistic-textdomain"),
			"base" => "ozy_vc_owlcarousel2",
			"content_element" => true,
			"as_child" => array('only' => 'ozy_vc_owlcarousel_wrapper'),
			"icon" => "icon-wpb-ozy-el",
			"params" => array(
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __("Carousel Image", "logistic-textdomain"),
					"param_name" => "src",
					"admin_label" => false,
					"value" => "",
					"description" => __("Select images for your slider.", "logistic-textdomain")
				),array(
					"type" => "colorpicker",
					"heading" => __("Carousel Background", "logistic-textdomain"),
					"param_name" => "bg_color",
					"admin_label" => false,
					"value" => "",
					"description" => __("Not requrired. Select a background color for your item.", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Title", "logistic-textdomain"),
					"param_name" => "title",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "select_an_icon",
					"heading" => __("or Icon", "logistic-textdomain"),
					"param_name" => "icon",
					"value" => '',
					"admin_label" => false,
					"description" => __("Once you select an Icon, title will not be shown on overlay.", "logistic-textdomain")
				),array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __("or Image Icon", "logistic-textdomain"),
					"param_name" => "icon_src",
					"admin_label" => false,
					"value" => "",
					"description" => __("Once you select an Image Icon, title or Icon will not be shown on overlay.", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"heading" => __("Image size", "logistic-textdomain"),
					"param_name" => "img_size",
					"value" => "full",
					"description" => __("Enter image size. Example: 'thumbnail', 'medium', 'large', 'full' or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use 'thumbnail' size.", "logistic-textdomain")
				),array(
					"type" => "dropdown",
					"heading" => __("Default Overlay?", "logistic-textdomain"),
					"param_name" => "default_overlay",
					"value" => array("off", "on", "_parent"),
					"admin_label" => false,
					"description" => __("ON/OFF default overlay on your items.", "logistic-textdomain")
				),array(
					"type" => "colorpicker",
					"heading" => __("Overlay Background Color", "logistic-textdomain"),
					"param_name" => "overlay_bg",
					"value" => "",
					"admin_label" => false
				),array(
					"type" => "textarea",
					"class" => "",
					"heading" => __("Excerpt", "logistic-textdomain"),
					"param_name" => "excerpt",
					"admin_label" => false,
					"value" => ""
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Link Caption", "logistic-textdomain"),
					"param_name" => "link_caption",
					"admin_label" => true,
					"value" => __("Find out more →", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Link", "logistic-textdomain"),
					"param_name" => "link",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "dropdown",
					"heading" => __("Link Target", "logistic-textdomain"),
					"param_name" => "link_target",
					"value" => array("_self", "_blank", "_parent"),
					"admin_label" => false,
					"description" => __("Select link target window.", "logistic-textdomain")
				)
		   )
		) );
	}
	
	/*if (!function_exists('ozy_vc_owlcarousel_testimonial')) {
		function ozy_vc_owlcarousel_testimonial( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_owlcarousel_testimonial', $atts);
			extract(shortcode_atts(array(
				'src' 			=> '',
				'img_size'		=> 'full',
				'bg_color'		=> '',
				'title' 		=> '',
				'sub_title'		=> '',
				'excerpt' 		=> '',
				'profile_src'	=> '',
				'link' 			=> '',
				'link_target'	=> '_self'
			), $atts));

			$output = '';

			$img_size = strpos(strtolower(esc_attr($img_size)), "x") > -1 ? explode('x', esc_attr($img_size)) : $img_size;
			$thumb = wp_get_attachment_image_src($src, $img_size);

			$style = esc_attr($bg_color) ? ' style="background-color:'. esc_attr($bg_color) .';" ' : '';
			$output = '<div class="item item-extended testimonial" '. $style .'><div class="overlay-one">';
			$output .= '<a'. ($link ? ' href="'. esc_attr($link) .'" target="'. esc_attr($link_target) .'"':'') .'>';
			if(isset($thumb[0])) {
				$output .= '<img src="'. esc_url($thumb[0]) .'" alt=""/>';
			}else{
				$output .= '<img src="'. get_template_directory_uri() .'/images/blank-large.gif" alt=""/>';
			}

			$output .= '<div class="one">';
			$output .= esc_attr($excerpt);
			$output .= '<i class="oic-quote-1 icon-1"></i><i class="oic-quote-1 icon-2"></i>';
			$output .= '</div>';
			$output .= '<div class="two">';
			if($profile_src) {
				$profile_thumb = wp_get_attachment_image_src($profile_src, 'thumbnail');
				if(isset($profile_thumb[0])) {
					$output .= '<img src="'. esc_url($profile_thumb[0]) .'" class="profile-pic" alt=""/>';
				}
			}
			$output .= '<span><strong>'. esc_attr($title) . '</strong>';
			$output .= '<br/>'. esc_attr($sub_title) .'</span>';
			$output .= '</div>';

			$output .= '</a>';

			$output .= '</div></div>';

			
			return $output;
		}
		
		add_shortcode('ozy_vc_owlcarousel_testimonial', 'ozy_vc_owlcarousel_testimonial');

		vc_map( array(
			"name" => __("Owl Carousel Testimonial Item", "logistic-textdomain"),
			"base" => "ozy_vc_owlcarousel_testimonial",
			"content_element" => true,
			"as_child" => array('only' => 'ozy_vc_owlcarousel_wrapper'),
			"icon" => "icon-wpb-ozy-el",
			"params" => array(
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __("Carousel Background Image", "logistic-textdomain"),
					"param_name" => "src",
					"admin_label" => false,
					"value" => "",
					"description" => __("Select images for your slider.", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"heading" => __("Image size", "logistic-textdomain"),
					"param_name" => "img_size",
					"value" => "full",
					"description" => __("Enter image size. Example: 'thumbnail', 'medium', 'large', 'full' or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use 'thumbnail' size.", "logistic-textdomain")
				),array(
					"type" => "colorpicker",
					"heading" => __("Carousel Background", "logistic-textdomain"),
					"param_name" => "bg_color",
					"admin_label" => false,
					"value" => "",
					"description" => __("Not requrired. Select a background color for your item.", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Title", "logistic-textdomain"),
					"param_name" => "title",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Sub Title", "logistic-textdomain"),
					"param_name" => "sub_title",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "textarea",
					"class" => "",
					"heading" => __("Excerpt", "logistic-textdomain"),
					"param_name" => "excerpt",
					"admin_label" => false,
					"value" => ""
				),array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __("Profile Picture", "logistic-textdomain"),
					"param_name" => "profile_src",
					"admin_label" => false,
					"value" => "",
					"description" => __("Select a picture to use as profile picture of testimonial's writer.", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Link", "logistic-textdomain"),
					"param_name" => "link",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "dropdown",
					"heading" => __("Link Target", "logistic-textdomain"),
					"param_name" => "link_target",
					"value" => array("_self", "_blank", "_parent"),
					"admin_label" => false,
					"description" => __("Select link target window.", "logistic-textdomain")
				)
		   )
		) );
	}*/
	
if (!function_exists('ozy_vc_owlcarousel_testimonial')) {
		function ozy_vc_owlcarousel_testimonial( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_owlcarousel_testimonial', $atts);
			extract(shortcode_atts(array(
				'src' 			=> '',
				'img_size'		=> 'full',
				'bg_color'		=> '',
				'title' 		=> '',
				'sub_title'		=> '',
				'excerpt' 		=> '',
				'profile_src'	=> '',
				'link' 			=> '',
				'link_target'	=> '_self'
			), $atts));

			$output = ''; $bg_style = '';

			$img_size = strpos(strtolower(esc_attr($img_size)), "x") > -1 ? explode('x', esc_attr($img_size)) : $img_size;
			$thumb = wp_get_attachment_image_src($src, $img_size);

			$style = esc_attr($bg_color) ? ' style="background-color:'. esc_attr($bg_color) .';" ' : '';
			$output = '<div class="item item-extended testimonial" '. $style .'><div class="overlay-one">';
			//$output .= '<a'. ($link ? ' href="'. esc_attr($link) .'" target="'. esc_attr($link_target) .'"':'') .'>';
			
			if(isset($thumb[0])) {
				//$output .= '<img src="'. esc_url($thumb[0]) .'" alt=""/>';
				$bg_style = ' style="background:url('. esc_url($thumb[0]) .');" ';
			}else{
				//$output .= '<img src="'. get_template_directory_uri() .'/images/blank-large.gif" alt=""/>';
				$bg_style = ' style="background:url('. get_template_directory_uri() .'/images/blank-large.gif);" ';
			}
			$output .= '<a'. ($link ? ' href="'. esc_attr($link) .'" target="'. esc_attr($link_target) .'"':'') .' '. $bg_style .'>';

			$output .= '<div class="one"><i class="oic-quote-1 icon-1"></i>';
			$output .= esc_attr($excerpt);
			$output .= '<i class="oic-quote-1 icon-2"></i>';
			$output .= '</div>';
			$output .= '<div class="two">';
			if($profile_src) {
				$profile_thumb = wp_get_attachment_image_src($profile_src, 'thumbnail');
				if(isset($profile_thumb[0])) {
					$output .= '<img src="'. esc_url($profile_thumb[0]) .'" class="profile-pic" alt=""/>';
				}
			}
			$output .= '<span><strong>'. esc_attr($title) . '</strong>';
			$output .= '<br/>'. esc_attr($sub_title) .'</span>';
			$output .= '</div>';

			$output .= '</a>';

			$output .= '</div></div>';

			
			return $output;
		}
		
		add_shortcode('ozy_vc_owlcarousel_testimonial', 'ozy_vc_owlcarousel_testimonial');

		vc_map( array(
			"name" => esc_attr__("Owl Carousel Testimonial Item", "logistic-textdomain"),
			"base" => "ozy_vc_owlcarousel_testimonial",
			"content_element" => true,
			"as_child" => array('only' => 'ozy_vc_owlcarousel_wrapper'),
			"icon" => "icon-wpb-ozy-el",
			"params" => array(
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => esc_attr__("Carousel Background Image", "logistic-textdomain"),
					"param_name" => "src",
					"admin_label" => false,
					"value" => "",
					"description" => esc_attr__("Select images for your slider.", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"heading" => esc_attr__("Image size", "logistic-textdomain"),
					"param_name" => "img_size",
					"value" => "full",
					"description" => esc_attr__("Enter image size. Example: 'thumbnail', 'medium', 'large', 'full' or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use 'thumbnail' size.", "logistic-textdomain")
				),array(
					"type" => "colorpicker",
					"heading" => esc_attr__("Carousel Background", "logistic-textdomain"),
					"param_name" => "bg_color",
					"admin_label" => false,
					"value" => "",
					"description" => esc_attr__("Not requrired. Select a background color for your item.", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => esc_attr__("Title", "logistic-textdomain"),
					"param_name" => "title",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => esc_attr__("Sub Title", "logistic-textdomain"),
					"param_name" => "sub_title",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "textarea",
					"class" => "",
					"heading" => esc_attr__("Excerpt", "logistic-textdomain"),
					"param_name" => "excerpt",
					"admin_label" => false,
					"value" => ""
				),array(
					"type" => "attach_image",
					"class" => "",
					"heading" => esc_attr__("Profile Picture", "logistic-textdomain"),
					"param_name" => "profile_src",
					"admin_label" => false,
					"value" => "",
					"description" => esc_attr__("Select a picture to use as profile picture of testimonial's writer.", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => esc_attr__("Link", "logistic-textdomain"),
					"param_name" => "link",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "dropdown",
					"heading" => esc_attr__("Link Target", "logistic-textdomain"),
					"param_name" => "link_target",
					"value" => array("_self", "_blank", "_parent"),
					"admin_label" => false,
					"description" => esc_attr__("Select link target window.", "logistic-textdomain")
				)
		   )
		) );
	}		

	class WPBakeryShortCode_Ozy_Vc_Owlcarousel_Wrapper extends WPBakeryShortCodesContainer{}
	class WPBakeryShortCode_Ozy_Vc_Owlcarousel extends WPBakeryShortCode{}
	class WPBakeryShortCode_Ozy_Vc_Owlcarousel2 extends WPBakeryShortCode{}
	class WPBakeryShortCode_Ozy_Vc_Owlcarousel_testimonial extends WPBakeryShortCode{}

	/**
	* Owl Carousel Single
	*/
	if (!function_exists('ozy_vc_owlcarousel_single_wrapper')) {
		function ozy_vc_owlcarousel_single_wrapper( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_owlcarousel_single_wrapper', $atts);
			extract(shortcode_atts(array(
				'autoplay'		=> 'true',
				'items'			=> '1',
				'singleitem'	=> 'true',
				'slidespeed'	=> '200',
				'autoheight'	=> 'false',
				'extra_css'		=> '',
				'css_animation' => '',
				'bullet_nav'	=> 'on'
			), $atts));
			
			if($css_animation) {
				wp_enqueue_script('waypoints');
				$css_animation = " wpb_animate_when_almost_visible wpb_" . esc_attr($css_animation) . " ";
			}		

			return '<div class="ozy-owlcarousel single '. (esc_attr($bullet_nav) != 'on' ? 'navigation-off' : '') .' '. $css_animation .'" data-autoplay="'. esc_attr($autoplay) .'" data-items="'. esc_attr($items) .'" data-singleitem="'. esc_attr($singleitem) .'" data-slidespeed="'. esc_attr($slidespeed) .'" data-paginationSpeed="800" data-autoheight="'. esc_attr($autoheight) .'">' . PHP_EOL . do_shortcode( $content ) . PHP_EOL .'</div>';
		}
		
		add_shortcode('ozy_vc_owlcarousel_single_wrapper', 'ozy_vc_owlcarousel_single_wrapper');
		
		vc_map( array(
			"name" => __("Simple Slider", "logistic-textdomain"),
			"base" => "ozy_vc_owlcarousel_single_wrapper",
			"as_parent" => array('only' => 'ozy_vc_owlcarousel_single'),
			"content_element" => true,
			"show_settings_on_create" => false,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "dropdown",
					"heading" => __("Auto Play", "logistic-textdomain"),
					"param_name" => "autoplay",
					"value" => array("true", "false", "1000", "2000", "3000", "4000", "5000", "6000", "7000", "8000", "9000", "10000"),
					"admin_label" => true,
					"description" => __("Change to any available integrer for example 3000 to play every 3 seconds. If you set it true default speed will be 5 seconds.", "logistic-textdomain")
				),		
				array(
					"type" => "dropdown",
					"heading" => __("Slide Speed", "logistic-textdomain"),
					"param_name" => "slidespeed",
					"value" => array("200", "100", "300", "400", "500", "600", "700", "800", "900", "1000", "1100", "1200", "1300", "1400", "1500", "1600", "1700", "1800", "1900", "2000"),
					"admin_label" => true,
					"description" => __("Slide speed in milliseconds.", "logistic-textdomain")
				),
				array(
					"type" => "dropdown",
					"heading" => __("Bullet Navigation", "logistic-textdomain"),
					"param_name" => "bullet_nav",
					"value" => array("on", "off"),
					"admin_label" => true
				),		
				$add_css_animation,
				array(
					"type" => "textfield",
					"heading" => __("Extra class name", "logistic-textdomain"),
					"param_name" => "el_class",
					"description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "logistic-textdomain")
				)			
		   ),
		   "js_view" => 'VcColumnView'
		) );
	}

	if (!function_exists('ozy_vc_owlcarousel_single')) {
		function ozy_vc_owlcarousel_single( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_owlcarousel_single', $atts);
			extract(shortcode_atts(array(
				'src' 				=> '',
				'img_size'			=> 'full',
				'link'				=> '',
				'link_caption'		=> 'Find Out More',
				'link_target'		=> '',
				'caption'			=> '',
				'excerpt'			=> '',
				'caption_position'	=> '',
				'fn_color'			=> '#ffffff',
				'link_style'		=> 'frame'
			), $atts));

			$output = '';

			$img_size = strpos(strtolower(esc_attr($img_size)), "x") > -1 ? explode('x', esc_attr($img_size)) : $img_size;
			$thumb = wp_get_attachment_image_src($src, $img_size);
			$rand_id = 'owl-item-' . rand(1, 10000);
			
			if(isset($thumb[0])) {
				$output .= '<div class="item '. $rand_id .'" style="width:'. esc_attr($thumb[1]) .'px;height:'. esc_attr($thumb[2]) .'px">';
				$output .= '<img src="'. esc_url($thumb[0]) .'" alt="'. esc_attr($img_size) .'"/>';
				$output .= '<div class="caption">';
				if($caption || $excerpt) {
					$output .= '<h1>'. esc_html($caption) .'</h1>'. ($excerpt ? '<p>'. $excerpt .'</p>' : '');
				}
				if(esc_attr($link)) {
					$output .= '<a href="'. esc_attr($link) .'" target="'. esc_attr($link_target) .'" class="'. esc_attr($link_style) .'">'. $link_caption .'</a>';
				}
				$output .= '</div>';
				$output .= '</div>';
				
				global $ozyHelper;
				$ozyHelper->set_footer_style('.' . $rand_id . ' .caption{text-align:'. esc_attr($caption_position) .'}');
				$ozyHelper->set_footer_style('.' . $rand_id . ' .caption a{border-color:'. esc_attr($fn_color) .';}');
				$ozyHelper->set_footer_style('.' . $rand_id . ' .caption h1,.' . $rand_id . ' .caption p,.' . $rand_id . ' .caption a{color:'. esc_attr($fn_color) .' !important;}');
				
			}
			
			return $output;
		}
		
		add_shortcode('ozy_vc_owlcarousel_single', 'ozy_vc_owlcarousel_single');

		vc_map( array(
			"name" => __("Simple Slider - Slide", "logistic-textdomain"),
			"base" => "ozy_vc_owlcarousel_single",
			"content_element" => true,
			"as_child" => array('only' => 'ozy_vc_owlcarousel_single_wrapper'),
			"icon" => "icon-wpb-ozy-el",
			"params" => array(
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __("Carousel Image", "logistic-textdomain"),
					"param_name" => "src",
					"admin_label" => false,
					"value" => "",
					"description" => __("Select images for your slider.", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"heading" => __("Image size", "logistic-textdomain"),
					"param_name" => "img_size",
					"value" => "full",
					"description" => __("Enter image size. Example: 'thumbnail', 'medium', 'large', 'full' or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use 'thumbnail' size.", "logistic-textdomain")
				),array(
					"type" => "colorpicker",
					"heading" => __("Foreground Color", "logistic-textdomain"),
					"param_name" => "fn_color",
					"admin_label" => false,
					"value" => "#ffffff"
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Caption", "logistic-textdomain"),
					"param_name" => "caption",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Excerpt", "logistic-textdomain"),
					"param_name" => "excerpt",
					"admin_label" => false,
					"value" => ""
				),array(
					"type" => "dropdown",
					"heading" => __("Caption Position", "logistic-textdomain"),
					"param_name" => "caption_position",
					"value" => array("left", "right", "center"),
					"admin_label" => false
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Link Caption", "logistic-textdomain"),
					"param_name" => "link_caption",
					"admin_label" => true,
					"value" => __("Find Out More", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Link", "logistic-textdomain"),
					"param_name" => "link",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "dropdown",
					"heading" => __("Link Target", "logistic-textdomain"),
					"param_name" => "link_target",
					"value" => array("_self", "_blank", "_parent"),
					"admin_label" => false,
					"description" => __("Select link target window.", "logistic-textdomain")
				),array(
					"type" => "dropdown",
					"heading" => __("Link Style", "logistic-textdomain"),
					"param_name" => "link_style",
					"value" => array("frame", "generic-button"),
					"admin_label" => false
				)
		   )
		) );
	}

	class WPBakeryShortCode_Ozy_Vc_Owlcarousel_single_Wrapper extends WPBakeryShortCodesContainer{}
	class WPBakeryShortCode_Ozy_Vc_Owlcarousel_single extends WPBakeryShortCode{}

	/**
	* Counter
	*/
	if (!function_exists('ozy_vc_count_to')) {
		function ozy_vc_count_to( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_count_to', $atts);
			extract(shortcode_atts(array(
				'color' 		=> '#000000',
				'from'			=> 0,
				'to'			=> 100,
				'subtitle' 		=> '',
				'sign'			=> '',
				'signpos'		=> 'right',
				'css_animation' => ''
			), $atts));
			
			if($css_animation) {
				wp_enqueue_script('waypoints');
				$css_animation = " wpb_animate_when_almost_visible wpb_" . esc_attr($css_animation) . " ";
			}		
			
			return '<div class="ozy-counter wpb_content_element '. $css_animation .'" style="color:'. esc_attr($color) .'"><div class="timer" data-sign="'. esc_attr($sign) .'" data-signpos="'. esc_attr($signpos) .'" data-from="'. esc_attr($from) .'" data-to="'. esc_attr($to) .'">'. esc_attr($from) .'</div><div class="hr" style="background-color:'. esc_attr($color) .'"></div>'. (esc_attr($subtitle) ? '<span>'. esc_attr($subtitle) .'</span>' : '') .'</div>';
		}
		
		add_shortcode('ozy_vc_count_to', 'ozy_vc_count_to');
		
		vc_map( array(
			"name" => __("Count To", "logistic-textdomain"),
			"base" => "ozy_vc_count_to",
			"icon" => "",
			"class" => '',
			"controls" => "full",
			'category' => 'by OZY',
			"icon" => "icon-wpb-ozy-el",
			"params" => array(
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Sub Title", "logistic-textdomain"),
					"param_name" => "subtitle",
					"admin_label" => true,
					"value" => "",
					"description" => __("Counter title.", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("From", "logistic-textdomain"),
					"param_name" => "from",
					"admin_label" => true,
					"value" => "0",
					"description" => __("Counter start from", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("To", "logistic-textdomain"),
					"param_name" => "to",
					"admin_label" => true,
					"value" => "100",
					"description" => __("Counter count to", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Sign", "logistic-textdomain"),
					"param_name" => "sign",
					"admin_label" => true,
					"value" => "",
					"description" => __("Enter a sign like % or whatever you like", "logistic-textdomain")
				),array(
					"type" => "dropdown",
					"heading" => __("Sign Position", "logistic-textdomain"),
					"param_name" => "signpos",
					"value" => array('right', 'left'),
					"admin_label" => false,
					"description" => __("Select position of your sign.", "logistic-textdomain")
				),array(
					"type" => "colorpicker",
					"heading" => __("Forecolor", "logistic-textdomain"),
					"param_name" => "color",
					"value" => "#000000",
					"admin_label" => false
				),$add_css_animation
		   )
		) );	
	}

	/**
	* Twitter Slider
	*/
	if (!function_exists('ozy_vc_twitter_ticker')) {
		function ozy_vc_twitter_ticker( $atts, $content = null ) {
		
			$essentials_options = get_option('ozy_logistic_essentials');
			if( is_array($essentials_options) && 
				isset($essentials_options['twitter_consumer_key']) && 
				isset($essentials_options['twitter_secret_key']) &&
				isset($essentials_options['twitter_token_key']) &&
				isset($essentials_options['twitter_token_secret_key']) ) 
			{
			     $atts = vc_map_get_attributes('ozy_vc_twitter_ticker', $atts);
				extract(shortcode_atts(array(
					'count' => '10',
					'screenname' => 'ozythemes',
					'forecolor' => ''
				), $atts));		
				
				require_once("classes/ozy_twitteroauth.php"); //Path to twitteroauth library
				
				if(!function_exists('getConnectionWithAccessToken')) {
					function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
						$connection = new Ozy_TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
						return $connection;
					}
				}

				$connection = getConnectionWithAccessToken(
					$essentials_options['twitter_consumer_key'],
					$essentials_options['twitter_secret_key'],
					$essentials_options['twitter_token_key'],
					$essentials_options['twitter_token_secret_key']
				);
				 
				$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=". esc_attr($screenname) ."&count=". esc_attr($count));

				if(!function_exists('makeLinks')) {
					function makeLinks($str) {    
						return preg_replace('/(https?):\/\/([A-Za-z0-9\._\-\/\?=&;%,]+)/i', '<a href="$1://$2" target="_blank">$1://$2</a>', $str);
					}
				}

				$output = '';
				if(is_array($tweets)) {
					foreach($tweets as $tweet) {
						$h_time = sprintf( __('%s ago', 'logistic-textdomain'), human_time_diff( date( 'U', strtotime( $tweet->created_at ) )));

						$output .= '<div>';
						$output .= '<div class="testimonial" style="color:'. esc_attr($forecolor) .'">'. makeLinks($tweet->text) .'<br>'. $h_time  .'</div>';
						$output .= '<div class="info">';
						$output .= '<span class="thumb"><span><img src="'. esc_url($tweet->user->profile_image_url) .'" alt="'. esc_attr($tweet->user->screen_name) .'"/></span></span>';
						$output .= '<span class="username"><a href="'. esc_url('http://twitter.com/' . $tweet->user->screen_name) .'" style="color:'. esc_attr($forecolor) .'" target="_blank">'. $tweet->user->screen_name .'</a></span>';
						$output .= '</div>';
						$output .= '</div>';
					}
				
					//return '<div class="royalSlider contentSlider ozy-testimonials">' . PHP_EOL . $output . PHP_EOL .'</div>';
					return '<div class="ozy-owlcarousel ozy-testimonials wpb_content_element" data-autoplay="true" data-items="1" data-singleitem="true" data-slidespeed="400" data-paginationSpeed="800" data-autoheight="false">' . PHP_EOL . $output . PHP_EOL .'</div>';
				}else{
					return 'Possible Twitter data error.';
				}
			}
			
			return '<p>**Required Twitter parameters are not supplied. Please go to your admin panel, Settings > ozy Essentials.**</p>';
		}

		add_shortcode('ozy_vc_twitter_ticker', 'ozy_vc_twitter_ticker');
		
		vc_map( array(
			"name" => __("Twitter Slider", "logistic-textdomain"),
			"base" => "ozy_vc_twitter_ticker",
			"content_element" => true,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "colorpicker",
					"heading" => __("ForeColor", "logistic-textdomain"),
					"param_name" => "forecolor",
					"value" => "",
					"admin_label" => false,
					"description" => __("Font color for rest of the slider", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Count", "logistic-textdomain"),
					"param_name" => "count",
					"admin_label" => true,
					"value" => "10"
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Screenname", "logistic-textdomain"),
					"param_name" => "screenname",
					"admin_label" => true,
					"value" => "ozythemes"
				),array(
					"type" => "textfield",
					"heading" => __("Extra class name", "logistic-textdomain"),
					"param_name" => "el_class",
					"description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "logistic-textdomain")
				)
			)
		) );	
	}

	/**
	* Testimonials
	*/
	if (!function_exists('ozy_vc_testimonials')) {
		function ozy_vc_testimonials( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_testimonials', $atts);
			extract(shortcode_atts(array(
				'forecolor' => ''
			), $atts));		
			
			$GLOBALS['OZY_TESTIMONIAL_SLIDER_FORECOLOR'] = esc_attr($forecolor);
			
			return '<div class="ozy-owlcarousel ozy-testimonials wpb_content_element" data-autoplay="true" data-items="1" data-singleitem="true" data-slidespeed="400" data-paginationSpeed="800" data-autoheight="false">' . PHP_EOL . do_shortcode( $content ) . PHP_EOL .'</div>';
		}

		add_shortcode('ozy_vc_testimonials', 'ozy_vc_testimonials');

		vc_map( array(
			"name" => __("Testimonials Slider", "logistic-textdomain"),
			"base" => "ozy_vc_testimonials",
			"as_parent" => array('only' => 'ozy_vc_testimonial'), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
			"content_element" => true,
			"show_settings_on_create" => false,
			"icon" => "icon-wpb-ozy-el",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "colorpicker",
					"heading" => __("ForeColor", "logistic-textdomain"),
					"param_name" => "forecolor",
					"value" => "",
					"admin_label" => false,
					"description" => __("Font color for rest of the slider", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"heading" => __("Extra class name", "logistic-textdomain"),
					"param_name" => "el_class",
					"description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "logistic-textdomain")
				)
			),
			"js_view" => 'VcColumnView'
		) );
	}

	if (!function_exists('ozy_vc_testimonial')) {
		function ozy_vc_testimonial( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_testimonial', $atts);
			extract(shortcode_atts(array(
				'title' => '',
				'subtitle' => '',
				'testimonial_content' => '',
				'image' => ''
			), $atts));
			
			$output  = '<div style="'. ($GLOBALS['OZY_TESTIMONIAL_SLIDER_FORECOLOR'] ? 'color:' . $GLOBALS['OZY_TESTIMONIAL_SLIDER_FORECOLOR'] : '') .'">';
			$output .= '<div class="testimonial">'. do_shortcode( $testimonial_content ) .'</div>';
			$output .= '<div class="info">';		
			$member_image = wp_get_attachment_image_src($image, 'medium');
			if(isset($member_image[0])) {
				$output .= '<div class="thumb"><span><img src="'. esc_url($member_image[0]) .'" alt="' . esc_attr($title) . '"/></span></div>';
			}
			$output .= '<div class="itext">';
			if(!empty($title)) $output .= '<strong>' . esc_attr($title) . '</strong>';
			if(!empty($subtitle)) $output .= '<br/><small>' . esc_attr($subtitle) . '</small>';
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';
			
			return $output;
		}

		add_shortcode( 'ozy_vc_testimonial', 'ozy_vc_testimonial' );
		
		vc_map( array(
			"name" => __("Testimonial", "logistic-textdomain"),
			"base" => "ozy_vc_testimonial",
			"content_element" => true,
			"as_child" => array('only' => 'ozy_vc_testimonials'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"icon" => "icon-wpb-ozy-el",
			"params" => array(
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Title", "logistic-textdomain"),
					"param_name" => "title",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Sub Title", "logistic-textdomain"),
					"param_name" => "subtitle",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "textarea",
					"holder" => "div",
					"class" => "",
					"heading" => __("Content", "logistic-textdomain"),
					"param_name" => "testimonial_content",
					"description" => __("Testimonial content.", "logistic-textdomain")
				),array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __("Image", "logistic-textdomain"),
					"param_name" => "image",
					"admin_label" => false,
					"value" => "",
					"description" => __("Select an image to show as testimonial visual.", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"heading" => __("Extra class name", "logistic-textdomain"),
					"param_name" => "el_class",
					"description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "logistic-textdomain")
				)
			)
		) );	
	}

	//Your "container" content element should extend WPBakeryShortCodesContainer class to inherit all required functionality
	class WPBakeryShortCode_Ozy_Vc_Testimonials extends WPBakeryShortCodesContainer{}
	class WPBakeryShortCode_Ozy_Vc_Testimonial extends WPBakeryShortCode{}

	/**
	* Icon Title with Content
	*/
	if(!function_exists('ozy_title_with_icon_func')) {
		function ozy_title_with_icon_func( $atts, $content=null ) {
            $atts = vc_map_get_attributes('title_with_icon', $atts);
			extract( shortcode_atts( array(
				  'icon' => '',
				  'icon_size' => 'medium',
				  'icon_position' => 'left',
				  'size' => 'h1',
				  'title' => '',
				  'icon_type' => '',
				  'icon_color' => '',
				  'text_color' => '',
				  'icon_bg_color' => 'transparent',
				  'icon_shadow_color' => '',
				  'go_link' => '',
				  'go_target' => '_self',
				  'connected' => 'no',
				  'dot_bg_color' => 'transparent'
				), $atts ) 
			);

			global $ozyHelper;
			$element_id = 'tile-with-icon_icon' . rand(100,10000);
			$a_begin = $a_end = $add_style = "";
			if(trim($go_link) !== '') {
				$a_begin = '<a href="' . esc_attr($go_link) . '" '. ($go_target=='fancybox' || $go_target=='fancybox-media' ? 'class':'target') .'="' . esc_attr($go_target) . '">';
				$a_end   = '</a>';
			}

			if($icon_type === 'circle') {
				$icon_bg_color = 'transparent';
				$add_style = 'border-color:'. esc_attr($icon_color) .';';
			}
			
			$o_icon = ($icon ? $a_begin . '<span ' . ($icon_color ? ' style="'. $add_style .'color:'. esc_attr($icon_color) .' !important;background-color:'. esc_attr($icon_bg_color) .' !important;"' : '') . ' class="' . esc_attr($icon) . ' ' . esc_attr($icon_type) . ' ' . esc_attr($icon_size) . ' ' . '" '. (esc_attr($icon_shadow_color) ? 'data-color="'. esc_attr($icon_shadow_color) .'"':'') .'></span>' . $a_end : '');
			
			return '<div id="'. $element_id .'" class="title-with-icon-wrapper '. esc_attr($icon_type) . ' ' . esc_attr($icon_size) .' '. (esc_attr($connected) === 'yes' ? 'connected' : '') .'" data-color="'. esc_attr($dot_bg_color) .'"><div class="wpb_content_element title-with-icon clearfix ' . (trim($content) !== '' ? 'margin-bottom-0 ' : '') . ($icon_position === 'top' ? 'top-style' : '') . '">' . $o_icon . '<' . $size . (!$text_color ? (!$icon ? ' class="no-icon content-color"' : ' class="content-color"'):'') . ' style="'. ($text_color ? 'color:' . esc_attr($text_color) : '') .'">' . $a_begin . $title . $a_end . '</' . $size . '></div>' . (trim($content) !== '' ? '<div class="wpb_content_element '. esc_attr($icon_position) .'-cs title-with-icon-content '. esc_attr($icon_size) .' clearfix" style="'. (esc_attr($text_color) ? 'color:' . esc_attr($text_color) : '') .'">' . wpb_js_remove_wpautop(do_shortcode($content)) . '</div>' : '') . '</div>';
		}
		
		add_shortcode( 'title_with_icon', 'ozy_title_with_icon_func' );
		
		vc_map( array(
			"name" => __("Title With Icon", "logistic-textdomain"),
			"base" => "title_with_icon",
			"class" => "",
			"controls" => "full",
			'category' => 'by OZY',
			"icon" => "icon-wpb-ozy-el",
			"params" => array(
			  array(
				"type" => "select_an_icon",
				"heading" => __("Icon", "logistic-textdomain"),
				"param_name" => "icon",
				"value" => '',
				"admin_label" => false,
				"description" => __("Title heading style.", "logistic-textdomain")
			  ),array(
				"type" => "dropdown",
				"heading" => __("Icon Size", "logistic-textdomain"),
				"param_name" => "icon_size",
				"value" => array(__("medium", "logistic-textdomain") => "medium", __("large", "logistic-textdomain") => "large", __("xlarge", "logistic-textdomain") => "xlarge", __("xxlarge", "logistic-textdomain") => "xxlarge", __("xxxlarge", "logistic-textdomain") => "xxxlarge"),
				"admin_label" => false,
				"description" => __("Size of the Icon.", "logistic-textdomain")
			  ),array(
				"type" => "dropdown",
				"heading" => __("Icon Position", "logistic-textdomain"),
				"param_name" => "icon_position",
				"value" => array(__("left", "logistic-textdomain") => "left", __("top", "logistic-textdomain") => "top"),
				"admin_label" => false,
				"description" => __("Position of the Icon.", "logistic-textdomain")
			  ),array(
				"type" => "colorpicker",
				"heading" => __("Icon Alternative Color", "logistic-textdomain"),
				"param_name" => "icon_color",
				"value" => "",
				"admin_label" => false,
				"description" => __("This field is not required.", "logistic-textdomain")
			  ),array(
				"type" => "dropdown",
				"heading" => __("Icon Background Type", "logistic-textdomain"),
				"param_name" => "icon_type",
				"value" => array(__("rectangle", "logistic-textdomain") => "rectangle", __("rounded", "logistic-textdomain") => "rounded", __("circle", "logistic-textdomain") => "circle", __("clear", "logistic-textdomain") => "clear"),
				"admin_label" => false,
				"description" => __("Position of the Icon.", "logistic-textdomain")
			  ),array(
				"type" => "colorpicker",
				"heading" => __("Icon Background Color", "logistic-textdomain"),
				"param_name" => "icon_bg_color",
				"value" => "",
				"admin_label" => false,
				"description" => __("Background color of Icon.", "logistic-textdomain")
			  ),array(
				"type" => "colorpicker",
				"heading" => __("Icon Shadow Color", "logistic-textdomain"),
				"param_name" => "icon_shadow_color",
				"value" => "",
				"admin_label" => false,
				"description" => __("Shadow color of Icon.", "logistic-textdomain")
			  ),array(
				"type" => "dropdown",
				"heading" => __("Heading Style", "logistic-textdomain"),
				"param_name" => "size",
				"value" => array("h1", "h2", "h3", "h4", "h5", "h6"),
				"admin_label" => false,
				"description" => __("Title heading style.", "logistic-textdomain")
			  ),array(
				 "type" => "textfield",
				 "class" => "",
				 "heading" => __("Link", "logistic-textdomain"),
				 "param_name" => "go_link",
				 "admin_label" => true,
				 "value" => "",
				 "description" => __("Enter full path.", "logistic-textdomain")
			  ),array(
				"type" => "dropdown",
				"heading" => __("Link Target", "logistic-textdomain"),
				"param_name" => "go_target",
				"value" => array("_self", "_blank", "_parent", "fancybox", "fancybox-media"),
				"admin_label" => false,
				"description" => __("Select link target window. fancybox will launch an lightbox window for images, and fancybox-media will launch a lightbox window for frames/video.", "logistic-textdomain")
			  ),array(
				 "type" => "textfield",
				 "class" => "",
				 "heading" => __("Title", "logistic-textdomain"),
				 "param_name" => "title",
				 "admin_label" => true,
				 "value" => __("Enter your title here", "logistic-textdomain"),
				 "description" => __("Content of the title.", "logistic-textdomain")
			  ),array(
				"type" => "colorpicker",
				"heading" => __("Font Color", "logistic-textdomain"),
				"param_name" => "text_color",
				"value" => "",
				"admin_label" => false,
				"description" => __("This option will affect Title and Content color.", "logistic-textdomain")
			  ),array(
				"type" => "dropdown",
				"heading" => __("Connected", "logistic-textdomain"),
				"param_name" => "connected",
				"value" => array("no", "yes"),
				"admin_label" => false,
				"description" => __("Select yes to connect elements to next one with a dashed border.", "logistic-textdomain")
			  ),array(
				"type" => "colorpicker",
				"heading" => __("Border Color", "logistic-textdomain"),
				"param_name" => "dot_bg_color",
				"value" => "",
				"admin_label" => false,
				"dependency" => Array('element' => "connected", 'value' => 'yes')
			  ),array(
				"type" => "textarea_html",
				"holder" => "div",
				"class" => "",
				"heading" => __("Content", "logistic-textdomain"),
				"param_name" => "content",
				"value" => "",
				"description" => __("Type your content here.", "logistic-textdomain")
			  )
		   )
		) );
	}
	
	/**
	* Icon
	*/
	if (!function_exists('ozy_vc_icon')) {
		function ozy_vc_icon( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_icon', $atts);
			extract(shortcode_atts(array(
				'icon' => '',
				'size' => 'regular',
				'style' => '',
				'link' => '',
				'target' => '',
				'color' => '',
				'icon_shadow_color' => '',
				'textcolor' => '',
				'align' => 'left',
				'css_animation' => '',
				'el_class' => ''
			), $atts));
			
			if($css_animation) {
				wp_enqueue_script('waypoints');
				$css_animation = " wpb_animate_when_almost_visible wpb_" . esc_attr($css_animation) . " ";
			}
			
			$element_id = 'ozy_icon_'. rand(100,10000);
					
			$inline_style = 'style="';
			if(esc_attr($color)) {
				global $ozyHelper;
				switch(esc_attr($style)) {
					case 'square':
						$inline_style .= 'background-color:'. esc_attr($color).'!important';
						break;
					case 'circle':
						$inline_style .= 'background-color:'. esc_attr($color);
						break;
					case 'circle2':
						$inline_style .= 'border-color:'. esc_attr($color) .';color:'. esc_attr($color);
						break;
				}
			}
			
			if(esc_attr($textcolor)) {
				$inline_style .= ';color:'. esc_attr($textcolor);
			}
			
			if(esc_attr($align) === 'left' || esc_attr($align) === 'right') {
				$inline_style .= ';float:'. esc_attr($align);
			}
			
			$inline_style .= '"';
			
			$output = '';		
			if(esc_attr($link)) $output .= '<a href="'. esc_attr($link) .'" target="'. esc_attr($target) .'" class="'. esc_attr($el_class) .' ozy-icon-a">';
			$output .= '<span id="'. $element_id .'" class="ozy-icon '. (!esc_attr($link)? esc_attr($el_class) : '') .' wpb_content_element align-'. esc_attr($align) .' ' . esc_attr($size) .' ' . esc_attr($style) . ' icon '. esc_attr($icon) .' '. $css_animation .'" '. $inline_style .' '. (esc_attr($icon_shadow_color) ? 'data-color="'. esc_attr($icon_shadow_color) .'"':'') .'></span>';
			if(esc_attr($link)) $output .= '</a>';
			
			return $output;
		}

		add_shortcode('ozy_vc_icon', 'ozy_vc_icon');
		
		vc_map( array(
		   "name" => __("Icon", "logistic-textdomain"),
		   "base" => "ozy_vc_icon",
		   "icon" => "icon-wpb-ozy-el",
		   'category' => 'by OZY',
		   "params" => array(
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Title", "logistic-textdomain"),
					"param_name" => "title",
					"admin_label" => true,
					"value" => "",
					"description" => __("Only place holder.", "logistic-textdomain")
				),array(
					"type" => "select_an_icon",
					"class" => "",
					"heading" => __("Icon", "logistic-textdomain"),
					"param_name" => "icon",
					"admin_label" => true,
					"value" => "",
					"description" => __("Select a type icon from the opened window.", "logistic-textdomain")
				),array(
					"type" => "dropdown",
					"heading" => __("Size", "logistic-textdomain"),
					"param_name" => "size",
					"value" => array("regular", "large", "xlarge", "xxlarge", "xxxlarge"),
					"admin_label" => false
				),array(
					"type" => "dropdown",
					"heading" => __("Style", "logistic-textdomain"),
					"param_name" => "style",
					"value" => array("clean", "square", "circle", "circle2"),
					"admin_label" => false
				),array(
					"type" => "dropdown",
					"heading" => __("Align", "logistic-textdomain"),
					"param_name" => "align",
					"value" => array("left", "center", "right"),
					"admin_label" => false
				),array(
					"type" => "textfield",
					"heading" => __("Link", "logistic-textdomain"),
					"param_name" => "link",
					"admin_label" => false
				),array(
					"type" => "dropdown",
					"heading" => __("Target Window", "logistic-textdomain"),
					"param_name" => "target",
					"value" => array("_self", "_blank"),
					"admin_label" => false
				),array(
					"type" => "colorpicker",
					"heading" => __("Background Color", "logistic-textdomain"),
					"param_name" => "color",
					"admin_label" => false
				),array(
					"type" => "colorpicker",
					"heading" => __("Foreground Color", "logistic-textdomain"),
					"param_name" => "textcolor",
					"admin_label" => false
				),array(
					"type" => "colorpicker",
					"heading" => __("Icon Shadow Color", "logistic-textdomain"),
					"param_name" => "icon_shadow_color",
					"value" => "",
					"admin_label" => false,
					"description" => __("Shadow color of Icon.", "logistic-textdomain")
				),$add_css_animation,
				array(
					"type" => "textfield",
					"heading" => __("Extra class name", "logistic-textdomain"),
					"param_name" => "el_class",
					"description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "logistic-textdomain")
				)			
		   )
		) );
	}

	/**
	* Custom List
	*/
	if (!function_exists('ozy_vc_ul')) {
		function ozy_vc_ul( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_ul', $atts);
			extract(shortcode_atts(array(
				'icon' => '',
				'icon_color' => '',
				'ul_content' => '',
				'css_animation' => ''
			), $atts));

			if($content) $ul_content = $content; //this line covers new Content field after 2.0 version			
			
			$content = wpb_js_remove_wpautop($ul_content);
			
			if($css_animation) {
				wp_enqueue_script('waypoints');
				$css_animation = " wpb_animate_when_almost_visible wpb_" . esc_attr($css_animation) . " ";
			}
						
			$tag = $style = '';		
			if(strtolower(strpos($content, '<ul'))) {
				$tag = 'ul';
			}else if(strtolower(strpos($content, '<ol'))) {
				$tag = 'ol';
			}
			if($icon_color) {
				$style = ' style="color:'. esc_attr($icon_color) .'"';
			}
			$content = ozy_strip_single($tag, $content);
			$content = str_replace('<li>' , '<li><span class="oic '. esc_attr($icon) .'"'. $style .'></span><span>', $content);
			$content = str_replace('</li>' , '</span></li>', $content);
			$content = ozy_strip_single('p', $content);
			if(!$tag) { 
				$tag = 'ul';
			}
			return '<'. $tag .' class="ozy-custom-list wpb_content_element '. $css_animation .'">'. $content . '</'. $tag .'>';
		}

		add_shortcode('ozy_vc_ul', 'ozy_vc_ul');
		
		vc_map( array(
			"name" => __("Custom List", "logistic-textdomain"),
			"base" => "ozy_vc_ul",
			"icon" => "icon-wpb-ozy-el",
			"class" => '',
			"controls" => "full",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Title", "logistic-textdomain"),
					"param_name" => "title",
					"admin_label" => true,
					"value" => "",
					"description" => __("Only place holder.", "logistic-textdomain")
				),array(
					"type" => "select_an_icon",
					"class" => "",
					"heading" => __("Icon", "logistic-textdomain"),
					"param_name" => "icon",
					"admin_label" => true,
					"value" => "",
					"description" => __("Select a type icon from the opened window.", "logistic-textdomain")
				),array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Icon Color", "logistic-textdomain"),
					"param_name" => "icon_color"
				),array(
					"type" => "textarea",
					"class" => "",
					"heading" => __("Content (OLD)", "logistic-textdomain"),
					"param_name" => "ul_content",
					"admin_label" => false,
					"description" => __("Please do not use this field, only to cover old versions. Copy your content into next Content field.", "logistic-textdomain"),
					"value" => ""
				),array(
					"type" => "textarea_html",
					"class" => "",
					"heading" => __("Content", "logistic-textdomain"),
					"param_name" => "content",
					"admin_label" => false,
					"value" => ""
				),$add_css_animation
		   )
		) );	
	}

	function ozy_strip_single($tag,$string){
		$string=preg_replace('/<'.$tag.'[^>]*>/i', '', $string);
		$string=preg_replace('/<\/'.$tag.'>/i', '', $string);
		return $string;
	}

	/**
	* Team Member
	*/
	if (!function_exists('ozy_vc_team_member')) {
		function ozy_vc_team_member( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_team_member', $atts);
			extract(shortcode_atts(array(
				'image' => '',
				'title' => '',
				'sub_title' => '',
				'excerpt' => '',			
				'twitter' => '',
				'facebook' => '',
				'linkedin' => '',
				'pinterest' => '',
				'link' => '',
				'css_animation' => ''
			), $atts));
			
			if($css_animation) {
				wp_enqueue_script('waypoints');
				$css_animation = " wpb_animate_when_almost_visible wpb_" . esc_attr($css_animation) . " ";
			}	
			
			$output = PHP_EOL . '<div class="ozy-team_member '. $css_animation .'">' . PHP_EOL;
			$output.= '<figure>';
			$member_image = wp_get_attachment_image_src($image, 'full');
			if(isset($member_image[0])) {
				$output.= $link? '<a href="'. esc_attr($link) .'">' : '';
				$output.= '<img src="'. $member_image[0] .'" alt="'. esc_attr($title) .'">';
				$output.= $link? '</a>' : '';
			}
			$output.= '<figcaption>';
			$output.= esc_attr($title) ? '<h3>'. esc_attr($title) .'</h3>' : '';
			$output.= esc_attr($sub_title) ? '<h5 class="content-color-alternate">'. esc_attr($sub_title) .'</h5>' : '';
			$output.= '<p>'. esc_attr($excerpt) .'</p>';

			$output.= '<div>';
			$output.= esc_attr($twitter) ? '<a href="http://www.twitter.com/'. esc_attr($twitter) .'" target="_blank" class="symbol-twitter tooltip" title="twitter"><span class="symbol">twitter'.'</span></a>' : '';
			$output.= esc_attr($facebook) ? '<a href="http://www.facebook.com/'. esc_attr($facebook) .'" target="_blank" class="symbol-facebook tooltip" title="facebook"><span class="symbol">facebook'.'</span></a>' : '';
			$output.= esc_attr($linkedin) ? '<a href="http://www.linkedin.com/'. esc_attr($linkedin) .'" target="_blank" class="symbol-linkedin tooltip" title="linkedin"><span class="symbol">linkedin'.'</span></a>' : '';
			$output.= esc_attr($pinterest) ? '<a href="http://pinterest.com/'. esc_attr($pinterest) .'" target="_blank" class="symbol-pinterest tooltip" title="pinterest"><span class="symbol">pinterest'.'</span></a>' : '';
			$output.= '</div>';
			
			$output.= '</figcaption>';
			$output.= '</figure>';
			$output.= PHP_EOL . '</div>' . PHP_EOL;		
			
			return $output;
		}

		add_shortcode( 'ozy_vc_team_member', 'ozy_vc_team_member' );
		
		vc_map( array(
			"name" => __("Team Member", "logistic-textdomain"),
			"base" => "ozy_vc_team_member",
			"icon" => "icon-wpb-ozy-el",
			"class" => '',
			"controls" => "full",
			'category' => 'by OZY',
			"params" => array(
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __("Member Image", "logistic-textdomain"),
					"param_name" => "image",
					"admin_label" => false,
					"value" => "",
					"description" => __("Select image for your team member.", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Title", "logistic-textdomain"),
					"param_name" => "title",
					"admin_label" => true,
					"value" => "",
					"description" => __("Title for your Team Member, like a name.", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Sub Title", "logistic-textdomain"),
					"param_name" => "sub_title",
					"admin_label" => true,
					"value" => "",
					"description" => __("Sub Title for your Team Member, like work title.", "logistic-textdomain")
				),array(
					"type" => "textarea",
					"class" => "",
					"heading" => __("Excerpt", "logistic-textdomain"),
					"param_name" => "excerpt",
					"admin_label" => true,
					"value" => ""
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Twitter", "logistic-textdomain"),
					"param_name" => "twitter",
					"admin_label" => true,
					"value" => "",
					"description" => __("Enter your Twitter account name", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Facebook", "logistic-textdomain"),
					"param_name" => "facebook",
					"admin_label" => true,
					"value" => "",
					"description" => __("Enter your Facebook account name", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("LinkedIn", "logistic-textdomain"),
					"param_name" => "linkedin",
					"admin_label" => true,
					"value" => "",
					"description" => __("Enter your LinkedIn account name", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Pinterest", "logistic-textdomain"),
					"param_name" => "pinterest",
					"admin_label" => true,
					"value" => "",
					"description" => __("Enter your Pinterest account name", "logistic-textdomain")
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Link", "logistic-textdomain"),
					"param_name" => "link",
					"admin_label" => false,
					"value" => "",
					"description" => __("Define a path to details page", "logistic-textdomain")
				),$add_css_animation
		   )
		) );	
	}

	/**
	* Divider
	*/
	if (!function_exists('ozy_vc_divider')) {
		function ozy_vc_divider( $atts, $content = null ) {
            $atts = vc_map_get_attributes('ozy_vc_divider', $atts);
			extract(shortcode_atts(array(
				'caption_size' => 'h3',
				'caption' 		=> '',
				'caption_align'	=> 'center',
				'caption_position' => '',
				'border_style'	=> 'solid',
				'border_size' => '1',
				'border_color' => '',
				'css_animation' => '',
				'more_custom' => 'off',
				'width' => '',
				'align' => 'center'
				), $atts ) 
			);
			
			if($css_animation) {
				wp_enqueue_script('waypoints');
				$css_animation = " wpb_animate_when_almost_visible wpb_" . esc_attr($css_animation) . " ";
			}
			
			$output = $more_custom_html = '';
			if(esc_attr($more_custom) == 'on' && esc_attr($width) && esc_attr($align)) {
				$more_custom_html = ';width:'. esc_attr($width) .';max-width:'. esc_attr($width) .';';
				switch(esc_attr($align)) {
					case 'center':
						$more_custom_html .= 'margin:20px auto;';
						break;
					case 'left':
						$more_custom_html .= 'float:left;';
						break;
					case 'right':
						$more_custom_html .= 'float:right;';
						break;						
					default:
						$more_custom_html .= 'margin:0 auto;';
				}
			}
			if('top' === esc_attr($caption_position)){
				$output = ( trim( esc_attr( $caption ) ) ? '<'. esc_attr($caption_size) .' class="ozy-divider-cap-' . esc_attr($caption_align) . ' wpb_content_element">' . esc_attr( $caption ) . '</'. esc_attr($caption_size) .'>' : '' );
				$output.= '<div class="ozy-content-divider '. $css_animation .'" style="border-top-style:'. esc_attr($border_style) . ';border-top-width:' . ('double' == esc_attr($border_style)?'3':esc_attr($border_size)) .'px' . ('' != esc_attr($border_color)?';border-top-color:'. esc_attr($border_color) .'':'') . $more_custom_html .'"></div>';
			}else{
				$output = '<fieldset class="ozy-content-divider '. $css_animation .' wpb_content_element" style="border-top-style:'. esc_attr($border_style) . ';border-top-width:' . ('double' == esc_attr($border_style)?'3':esc_attr($border_size)) .'px' . ('' != esc_attr($border_color)?';border-top-color:'. esc_attr($border_color) .'':'') . $more_custom_html .'">' . ( trim( esc_attr( $caption ) ) ? '<legend class="d' . esc_attr($caption_align) . '" align="' . esc_attr($caption_align) . '">' . esc_attr( $caption ) . '</legend>' : '' ) . '</fieldset>';
			}
			return $output;
		}

		add_shortcode('ozy_vc_divider', 'ozy_vc_divider');

		vc_map( array(
		   "name" => __("Separator (Divider) With Caption", "logistic-textdomain"),
		   "base" => "ozy_vc_divider",
		   "class" => "",
		   "controls" => "full",
		   'category' => 'by OZY',
		   "icon" => "icon-wpb-ozy-el",
		   "params" => array(
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Caption Size", "logistic-textdomain"),
					"param_name" => "caption_size",
					"admin_label" => true,
					"value" => array("h3","h1","h2","h4","h5","h6")
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Caption", "logistic-textdomain"),
					"param_name" => "caption",
					"admin_label" => true,
					"value" => __("Enter your caption here", "logistic-textdomain"),
					"description" => __("Caption of the divider.", "logistic-textdomain")
				),array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Border Style", "logistic-textdomain"),
					"param_name" => "border_style",
					"admin_label" => true,
					"value" => array("solid","dotted","dashed","double")
				),array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Border Size", "logistic-textdomain"),
					"param_name" => "border_size",
					"admin_label" => true,
					"value" => array("1","2","3","4","5","6","7","8","9","10")
				),array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Border Color", "logistic-textdomain"),
					"param_name" => "border_color",
					"admin_label" => false,
					"value" => ""
				),array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Caption Align", "logistic-textdomain"),
					"param_name" => "caption_align",
					"admin_label" => true,
					"value" => array("center", "left", "right"),
					"description" => __("Caption align.", "logistic-textdomain")
				),array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Caption Position", "logistic-textdomain"),
					"param_name" => "caption_position",
					"admin_label" => true,
					"value" => array("overlay", "top"),
					"description" => __("Caption position.", "logistic-textdomain")
				),array(
					"type" => 'dropdown',
					"heading" => __("More Customization", "logistic-textdomain"),
					"param_name" => "more_custom",
					"value" => array("off", "on"),
				),array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Width", "logistic-textdomain"),
					"param_name" => "width",
					"admin_label" => true,
					"value" => "400px",
					"dependency" => Array('element' => "more_custom", 'value' => 'on')
				),array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Align", "logistic-textdomain"),
					"param_name" => "align",
					"admin_label" => true,
					"value" => array("center", "left", "right"),
					"dependency" => Array('element' => "more_custom", 'value' => 'on')
				),$add_css_animation	
			)
		) );
	}
}