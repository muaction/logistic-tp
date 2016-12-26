<?php

require_once('classes/tgm-plugin.php');
require_once('classes/tgm-plugin-activation.php');

/**
* TGM Plugin activator
*/
function logistic_register_required_plugins() {

	$plugins = array(
		array(
			'name'     	=> 'Visual Content Composer',
			'slug'     	=> 'js_composer',
			'source'   	=> get_template_directory() . '/plugins/js_composer.zip',
			'required' 	=> true,
			'version'	=> '4.12.1'
		),array(
			'name'     	=> 'Logistic Essentials',
			'slug'     	=> 'ozy-logistic-essentials',
			'source'   	=> get_template_directory() . '/plugins/ozy-logistic-essentials.zip',
			'required' 	=> true,
			'force_deactivation' => true,
			'version'	=> '2.5'
		),array(
			'name'     	=> 'Master Slider',
			'slug'     	=> 'masterslider',
			'source'   	=> get_template_directory() . '/plugins/masterslider.zip',
			'required' 	=> false,
			'version'	=> '3.0.4'
		),array(
			'name'     	=> 'Revolution Slider',
			'slug'     	=> 'revslider',
			'source'   	=> get_template_directory() . '/plugins/revslider.zip',
			'required' 	=> false,
			'version'	=> '5.2.6'
		),array(
			'name'     	=> 'Theme Updater (by Envato)',
			'slug'     	=> 'envato-wordpress-toolkit-master',
			'source'   	=> get_template_directory() . '/plugins/envato-wordpress-toolkit-master.zip',
			'required' 	=> false,
			'version'	=> '1.7.3'
		),array(
			'name'     	=> 'Contact Form 7',
			'slug'     	=> 'contact-form-7',
			'required' 	=> false,
			'version'	=> '4.5'
		),array(
			'name'     	=> 'Mailchimp Widget',
			'slug'     	=> 'mailchimp-widget',
			'source'   	=> get_template_directory() . '/plugins/mailchimp-widget.zip',
			'required' 	=> false,
			'version'	=> '0.8.12'
		),array(
			'name'     	=> 'WordPress Importer',
			'slug'     	=> 'wordpress-importer',			
			'required' 	=> false,
			'version'	=> '0.6.3'			
		),array(
			'name'     	=> 'Widget Data - Setting Import/Export Plugin',
			'slug'     	=> 'widget-settings-importexport',
			'required' 	=> false,
			'version'	=> '1.4.1'
		),array(
			'name'     	=> 'Sidekick',
			'slug'     	=> 'sidekick',
			'required' 	=> false,
			'version'	=> '2.6.8'
		)
	);
	
	$config = array(
		'id'           => 'vp-textdomain',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'install-required-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.				
	);

	tgmpa( $plugins, $config );	
}

add_action('tgmpa_register', 'logistic_register_required_plugins');

?>