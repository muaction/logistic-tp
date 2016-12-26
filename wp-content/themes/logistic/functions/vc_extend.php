<?php
$animate_css_effects = array("flash","bounce","shake","tada","swing","wobble","pulse","flip","flipInX","flipOutX","flipInY","flipOutY","fadeIn","fadeInUp","fadeInDown","fadeInLeft","fadeInRight","fadeInUpBig","fadeInDownBig","fadeInLeftBig","fadeInRightBig","fadeOut","fadeOutUp","fadeOutDown","fadeOutLeft","fadeOutRight","fadeOutUpBig","fadeOutDownBig","fadeOutLeftBig","fadeOutRightBig","bounceIn","bounceInDown","bounceInUp","bounceInLeft","bounceInRight","bounceOut","bounceOutDown","bounceOutUp","bounceOutLeft","bounceOutRight","rotateIn","rotateInDownLeft","rotateInDownRight","rotateInUpLeft","rotateInUpRight","rotateOut","rotateOutDownLeft","rotateOutDownRight","rotateOutUpLeft","rotateOutUpRight","hinge","rollIn","rollOut");
/**
* VC ROW
*/
vc_add_param("vc_row", array(
	"type" => 'checkbox',
	"heading" => __("Full Width?", "vp_textdomain"),
	"param_name" => "row_fullwidth",
	"description" => __("If selected, your row will be stretched to limits of the parent container.", "vp_textdomain"),
	"value" => Array(__("Yes, please", "vp_textdomain") => '1')
));

vc_add_param("vc_row", array(
	"type" => 'checkbox',
	"heading" => __("Vertical Centered Content?", "vp_textdomain"),
	"param_name" => "row_vertical_center",
	"description" => __("If selected, elements in the columns will be tried to aligned as verticaly centered.", "vp_textdomain"),
	"value" => Array(__("Yes, please", "vp_textdomain") => '1')
));

vc_add_param("vc_row", array(
	"type" => 'checkbox',
	"heading" => __("Full Height?", "vp_textdomain"),
	"param_name" => "row_fullheight",
	"description" => __("If selected, your row will be stretched to limits of the document. Useful to build single page sites.", "vp_textdomain"),
	"value" => Array(__("Yes, please", "vp_textdomain") => '1')
));

vc_add_param("vc_row", array(
	"type" => 'checkbox',
	"heading" => __("Zero Column Space?", "vp_textdomain"),
	"param_name" => "row_zero_column_space",
	"description" => __("If selected, your columns inside this row will have no horizontal space between themselves.", "vp_textdomain"),
	"value" => Array(__("Yes, please", "vp_textdomain") => '1')
));

if (defined('WPB_VC_VERSION')) { /*font color option removed after 4.4.0, so, add it back*/
	if(version_compare(WPB_VC_VERSION, '4.4.0','>')){
		vc_add_param("vc_row", array(
			"type" => "colorpicker",
			"heading" => __('Font Color', 'vp_textdomain'),
			"param_name" => "font_color",
			"description" => __("Select font face color", "vp_textdomain"),
			"group" => __("Custom Design Options", "vp_textdomain")	
		));		
	}
}

vc_add_param("vc_row", array(
	"type" => 'dropdown',
	"heading" => __("Parallax?", "vp_textdomain"),
	"param_name" => "bg_parallax",
	"description" => __("If selected, parallax effect will be applied on background image.", "js_composer"),
	"value" => array("", "off", "on"),
	"group" => __("Custom Design Options", "vp_textdomain")	
));

vc_add_param("vc_row", array(
	"type" => "dropdown",
	"heading" => __('Background Scroll', 'vp_textdomain'),
	"param_name" => "bg_scroll",
	"value" => array(
			  __(" ", 'wpb') => '',
			  __("Left", 'wpb') => 'h,-1',
			  __("Right", 'wpb') => 'h,1',
			  __('Top', 'wpb') => 'y,-1',
			  __('Bottom', 'wpb') => 'y,1'
			),
	"description" => __("Please do not use with other Background Options", "vp_textdomain"),
	"group" => __("Custom Design Options", "vp_textdomain")	
));

vc_add_param("vc_row", array(
	"type" => "textfield",
	"heading" => __("Minimum Height", "vp_textdomain"),
	"param_name" => "row_min_height",
	"description" => __("Set minimum height of your row in pixels. Not required", "vp_textdomain")
));

vc_add_param("vc_row", array(
	"type" => 'dropdown',
	"heading" => __("Background Slider", "vp_textdomain"),
	"param_name" => "bg_slider",
	"description" => __("If selected, you can select background images for your row.", "vp_textdomain"),
	"value" => array("off", "on"),
	"group" => __("Custom Design Options", "vp_textdomain")	
));

vc_add_param("vc_row", array(
	"type" => 'dropdown',
	"heading" => __("Size", "vp_textdomain"),
	"param_name" => "bg_slider_size",
	"dependency" => Array('element' => "bg_slider", 'value' => 'on'),
	"value" => array("full", "centered"),
	"group" => __("Custom Design Options", "vp_textdomain")	
));

vc_add_param("vc_row", array(
	"type" => "attach_images",
	"heading" => __("Images", "vp_textdomain"),
	"param_name" => "bg_slider_images",
	"description" => __("Select images for your slider", "vp_textdomain"),
	"dependency" => Array('element' => "bg_slider", 'value' => 'on'),
	"group" => __("Custom Design Options", "vp_textdomain")	
));

vc_add_param("vc_row", array(
	"type" => 'dropdown',
	"heading" => __("Video Background", "vp_textdomain"),
	"param_name" => "bg_video",
	"description" => __("If selected, you can set background of your row as video.", "js_composer"),
	"value" => array("off", "on"),
	"group" => __("Custom Design Options", "vp_textdomain")	
));

vc_add_param("vc_row", array(
	"type" => "textfield",
	"heading" => __("MP4 File", "vp_textdomain"),
	"param_name" => "bg_video_mp4",
	"description" => __("MP4 Video file path", "vp_textdomain"),
	"dependency" => Array('element' => "bg_video", 'value' => 'on'),
	"group" => __("Custom Design Options", "vp_textdomain")	
));

vc_add_param("vc_row", array(
	"type" => "textfield",
	"heading" => __("WEBM File", "vp_textdomain"),
	"param_name" => "bg_video_webm",
	"description" => __("WEBM Video file path", "vp_textdomain"),
	"dependency" => Array('element' => "bg_video", 'value' => 'on'),
	"group" => __("Custom Design Options", "vp_textdomain")	
));

vc_add_param("vc_row", array(
	"type" => "textfield",
	"heading" => __("OGV File", "vp_textdomain"),
	"param_name" => "bg_video_ogv",
	"description" => __("OGV Video file path", "vp_textdomain"),
	"dependency" => Array('element' => "bg_video", 'value' => 'on'),
	"group" => __("Custom Design Options", "vp_textdomain")	
));

vc_add_param("vc_row", array(
	"type" => "attach_image",
	"heading" => __("Poster Image", "vp_textdomain"),
	"param_name" => "bg_poster_image",
	"description" => __("Poster Image, that will be used as a place holder on mobile devices.", "vp_textdomain"),
	"dependency" => Array('element' => "bg_video", 'value' => 'on'),
	"group" => __("Custom Design Options", "vp_textdomain")	
));

vc_add_param("vc_row", array(
	"type" => "colorpicker",
	"heading" => __('Overlay Background', 'vp_textdomain'),
	"param_name" => "video_overlay_color",
	"description" => __("Select background color", "vp_textdomain"),
	//"edit_field_class" => 'col-md-6',
	"group" => __("Custom Design Options", "vp_textdomain")	
));

if(version_compare(WPB_VC_VERSION, '4.5','<')){
	vc_add_param("vc_row", array(
		"type" => "textfield",
		"heading" => __("Row ID", "vp_textdomain"),
		"param_name" => "row_id",
		"description" => __("Set a unique ID for your row. Please do not use spaces and custom characters. Use like; 'about_us' or 'aboutus'. With this option, you can build a single page site.", "js_composer")
	));
}

vc_add_param("vc_row", array(
	"type" => "dropdown",
	"heading" => __("Bottom Button", "vp_textdomain"),
	"param_name" => "bottom_button",
	"value" => array("off", "on"),
	"admin_label" => false,
	"description" => __("If selected, you can put a button bottom of your row, useful to jump in page.", "vp_textdomain")
));

vc_add_param("vc_row", array(
	"type" => "select_an_icon",
	"heading" => __("Icon", "js_composer"),
	"param_name" => "bottom_button_icon",
	"description" => __("Select an icon from the list of available icon set.", "vp_textdomain"),
	"dependency" => Array('element' => "bottom_button", 'value' => 'on')
));

vc_add_param("vc_row", array(
	"type" => "textfield",
	"heading" => __("Link", "js_composer"),
	"param_name" => "bottom_button_link",
	"dependency" => Array('element' => "bottom_button", 'value' => 'on')
));

vc_add_param("vc_row", array(
	"type" => "colorpicker",
	"heading" => __("Color", "js_composer"),
	"param_name" => "bottom_button_color",
	"dependency" => Array('element' => "bottom_button", 'value' => 'on'),
	"value" => "#222222"
));
/**
* VC TOGGLE
*/
vc_add_param("vc_toggle", array(
	"type" => 'dropdown',
	"heading" => __("Size", "js_composer"),
	"param_name" => "heading_size",
	"description" => __("Select size of your heading", "vp_textdomain"),
	"value" => array("h4", "h1", "h2", "h3", "h5", "h6")
));

/**
* VC SINGLE IMAGE
*/
vc_add_param("vc_single_image", array(
	"type" => "dropdown",
	"heading" => __("Open Link in Lightbox?", "vp_textdomain"),
	"param_name" => "lightbox",
	"value" => array("no", "yes"),
	"admin_label" => false
));
vc_add_param("vc_single_image", array(
	"type" => "dropdown",
	"heading" => __("Zoom on Hover?", "vp_textdomain"),
	"param_name" => "zoom_on_hover",
	"value" => array("no", "yes"),
	"admin_label" => false
));
vc_add_param("vc_single_image", array(
	"type" => "dropdown",
	"heading" => __("Animated?", "vp_textdomain"),
	"param_name" => "img_animated",
	"value" => array("no", "yes"),
	"admin_label" => false
));
vc_add_param("vc_single_image", array(
	"type" => "dropdown",
	"heading" => __("Infinite Animation?", "vp_textdomain"),
	"param_name" => "img_infinite",
	"value" => array("no", "yes"),
	"dependency" => Array('element' => "img_animated", 'value' => 'yes'),	
	"admin_label" => false
));
vc_add_param("vc_single_image", array(
	"type" => "dropdown",
	"heading" => __("Animation", "vp_textdomain"),
	"param_name" => "img_animation",
	"value" => $animate_css_effects,
	"dependency" => Array('element' => "img_animated", 'value' => 'yes'),	
	"admin_label" => false
));

/**
* VC BUTTON 2
*/
vc_add_param("vc_button2", array(
	"type" => "dropdown",
	"heading" => __("Full Width?", "vp_textdomain"),
	"param_name" => "full_width",
	"value" => array("no", "yes"),
	"admin_label" => false
));
?>