<?php
// Output option-based style
if( !function_exists( 'ozy_logistic_style') ) :
	function ozy_logistic_style() {
		global $ozyHelper, $ozy_data;

		// is page based styling enabled?
		$body_style = $content_background_color = $transparent_content_background = '';
		$page_id = get_the_ID();
		
		$shop_page_id = ozy_get_woocommerce_page_id();
		if ($shop_page_id > 0) { $page_id = $shop_page_id; }
		
		if(vp_metabox('ozy_logistic_meta_page.ozy_logistic_meta_page_use_custom_style', null, $page_id) == '1') {
			$_var = 'ozy_logistic_meta_page.ozy_logistic_meta_page_layout_group.0.ozy_logistic_meta_page_layout_';
			$content_background_color 		= vp_metabox($_var . 'ascend_background', null, $page_id);
			$transparent_content_background = vp_metabox($_var . 'transparent_background', null, $page_id);
		}else{
			$content_background_color 		= ozy_get_option('content_background_color', null, $page_id);
		}
		
		if(vp_metabox('ozy_logistic_meta_page.ozy_logistic_meta_page_use_custom_background', null, $page_id) == '1' && !is_search()) {
			$_var = 'background_group.0.ozy_logistic_meta_page_background_';
			$body_style = $ozyHelper->background_style_render(
				ozy_get_metabox($_var . 'color', null, $page_id),
				ozy_get_metabox($_var . 'image', null, $page_id),
				ozy_get_metabox($_var . 'image_size', null, $page_id),
				ozy_get_metabox($_var . 'image_repeat', null, $page_id),
				ozy_get_metabox($_var . 'image_attachment', null, $page_id),
				false,
				ozy_get_metabox($_var . 'image_pos_x', null, $page_id),
				ozy_get_metabox($_var . 'image_pos_y', null, $page_id)				
			);
		}else{
			$_var = 'body_background_';
			$body_style = $ozyHelper->background_style_render(
				ozy_get_option($_var . 'color', null, $page_id), 
				ozy_get_option($_var . 'image', null, $page_id), 
				ozy_get_option($_var . 'image_size', null, $page_id), 
				ozy_get_option($_var . 'image_repeat', null, $page_id), 
				ozy_get_option($_var . 'image_attachment', null, $page_id)
			);
		}
	
	?>
		<style type="text/css">
			@media only screen and (min-width: 1212px) {
				.container{padding:0;width:<?php echo $ozy_data->container_width; ?>px;}
				#content{width:<?php echo $ozy_data->content_width; ?>px;}
				#sidebar{width:<?php echo $ozy_data->sidebar_width; ?>px;}
			}
	
			<?php
				if(ozy_get_option('primary_menu_side_menu') === '-1') {
			?>
				@media only screen and (min-width: 960px) {
					#nav-primary>nav>div>ul>li.menu-item-side-menu{display:none !important;}
				}			
			<?php					
				}				
				$primary_menu_height = ozy_get_option('primary_menu_height');
				if(ozy_get_option('top_info_bar_is_active')) {$primary_menu_height = $primary_menu_height + 40;}
			?>	
	
			/* Body Background Styling
			/*-----------------------------------------------------------------------------------*/
			body{<?php echo $body_style; ?>}
		
			/* Layout and Layout Styling
			/*-----------------------------------------------------------------------------------*/
			#main,
			.main-bg-color{
				background-color:<?php echo $content_background_color ?>;
			}
			#main.header-slider-active>.container,
			#main.footer-slider-active>.container{
				margin-top:0px;
			}
			/*.ozy-header-slider{
				margin-top:<?php echo $primary_menu_height?>px;
			}*/

			#footer .container>div,
			#footer .container,
			#footer{
				height:<?php echo ozy_get_option('footer_height')?>px;min-height:<?php echo ozy_get_option('footer_height')?>px;
			}
			#footer,#footer>footer .container{
				line-height:<?php echo ozy_get_option('footer_height')?>px;
			}
			#footer .top-social-icons>a>span {
				line-height:<?php echo (int)ozy_get_option('footer_height')?>px;
			}
			@-moz-document url-prefix() { 
				#footer .top-social-icons>a>span{line-height:<?php echo (int)ozy_get_option('footer_height')?>px;}
			}

			#footer-wrapper {
				<?php
					$footer_background_image = ozy_get_option('footer_background_image');
					$footer_background_color = ozy_get_option('footer_color_1', 'rgba(0,0,0,1)');
					if($footer_background_image) {
						echo 'background:'. $footer_background_color .' url('. $footer_background_image .') repeat center center;';
					}else{
						echo 'background-color:'. $footer_background_color .';';
					}
				?>
			}			
			#footer *,
			#footer-widget-bar * {
				color:<?php echo ozy_get_option('footer_color_2', '#ffffff');?> !important;
			}
			#footer a:hover,
			#footer-widget-bar a:hover {
				color:<?php echo ozy_get_option('footer_color_3', '#ff0000');?> !important;
			}				
			#footer,
			#footer-widget-bar,
			#footer .top-social-icons>a {
				border-color:<?php echo ozy_get_option('footer_color_4', '#383838');?>
			}
			#footer-widget-bar>.container>section>div.widget>span.line {
				border-color:<?php echo ozy_get_option('footer_color_3', '#383838');?>
			}			
			#footer a,
			#footer-widget-bar a {
				color:<?php echo ozy_get_option('footer_color_3', '#f33337');?>
			}
			#footer-widget-bar input {
				background-color:<?php echo ozy_get_option('footer_color_1', 'rgba(0,0,0,1)');?> !important;
			}
			#footer-widget-bar input,
			#footer-widget-bar .opening-time {
				border-color:<?php echo ozy_get_option('footer_color_2', '#ffffff');?> !important;				
			}
		<?php echo $transparent_content_background == '1' ? '	#main>.container{background-color:transparent !important;-webkit-box-shadow:none !important;-moz-box-shadow:none !important;box-shadow:none !important;}' . PHP_EOL : '' ?>
			@media only screen and (max-width: 479px) {
				#footer{height:<?php echo (int)ozy_get_option('footer_height')*2;?>px;}			
				/*#main>.container{margin-top:<?php echo ozy_get_option('primary_menu_height');?>px;}*/
			}
			@media only screen and (max-width: 1024px) and (min-width: 668px) {
				#header #title{padding-right:<?php echo ((int)ozy_get_option('primary_menu_height') + 20);?>px;}
				#header #title>a{line-height:<?php echo ozy_get_option('primary_menu_height');?>px;}
				#main>.container{margin-top:<?php echo ozy_get_option('primary_menu_height');?>px;}
				#footer{height:<?php echo ozy_get_option('footer_height');?>px;}
			}	
			
		<?php 
		//if(ozy_check_is_woocommerce_page()) { 
		if(is_woocommerce_activated()) {
		?>
			/* WooCommerce
			/*-----------------------------------------------------------------------------------*/
			.ozy-product-overlay .button:hover{
				background-color:<?php echo $ozyHelper->rgba2rgb(ozy_get_option('primary_menu_background_color'))?> !important;
				color:<?php echo $ozyHelper->rgba2rgb(ozy_get_option('primary_menu_font_color_hover'))?> !important;
				border:1px solid <?php echo ozy_get_option('primary_menu_background_color')?> !important;
			}
			.woocommerce div.product .woocommerce-tabs ul.tabs li.active,
			.woocommerce-page div.product .woocommerce-tabs ul.tabs li.active,
			.woocommerce #content div.product .woocommerce-tabs ul.tabs li.active,
			.woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active{
				background-color:<?php echo ozy_get_option('content_color_alternate') ?> !important;
				border-color:<?php echo $ozyHelper->rgba2rgb(ozy_get_option('content_color_alternate')) ?> !important;
				border-color:<?php echo ozy_get_option('content_color_alternate') ?> !important;
			}
			.woocommerce div.product .woocommerce-tabs ul.tabs li,
			.woocommerce-page div.product .woocommerce-tabs ul.tabs li,
			.woocommerce #content div.product .woocommerce-tabs ul.tabs li,
			.woocommerce-page #content div.product .woocommerce-tabs ul.tabs li{
				border-color:<?php echo ozy_get_option('primary_menu_separator_color') ?>;
			}
			.woocommerce div.product span.price, 
			.woocommerce-page div.product span.price, 
			.woocommerce #content div.product span.price, 
			.woocommerce-page #content div.product span.price, 
			.woocommerce div.product p.price, 
			.woocommerce-page div.product p.price, 
			.woocommerce #content div.product p.price,
			.woocommerce-page #content div.product p.price,
			.woocommerce div.product .woocommerce-tabs ul.tabs li a,
			.woocommerce-page div.product .woocommerce-tabs ul.tabs li a,
			.woocommerce #content div.product .woocommerce-tabs ul.tabs li a,
			.woocommerce-page #content div.product .woocommerce-tabs ul.tabs li a{
				color:<?php echo ozy_get_option('content_color');?> !important;
			}
			.woocommerce-pagination>ul>li>a,
			.woocommerce-pagination>ul>li>span{
				color:<?php echo ozy_get_option('content_color');?> !important;
			}
			#woocommerce-lightbox-cart h3:first-letter,
			#woocommerce-lightbox-cart ul.cart_list.product_list_widget>li{			
				/*border-color:<?php echo  $ozyHelper->change_opacity(ozy_get_option('content_color'),'.2') ?>;*/
				border-color:<?php echo  $ozyHelper->hex2rgba(ozy_get_option('content_color'),'.2') ?>;
			}
			
			.woocommerce-page .button,
			body.woocommerce-page input[type=button],
			body.woocommerce-page input[type=submit],
			body.woocommerce-page button[type=submit]{
				background:<?php echo ozy_get_option('form_button_background_color')?> !important;
				color:<?php echo $ozyHelper->rgba2rgb(ozy_get_option('form_button_font_color'))?> !important;
				border:1px solid <?php echo ozy_get_option('form_button_background_color')?> !important;
			}
			.woocommerce-page .button:hover,
			body.woocommerce-page input[type=button]:hover,
			body.woocommerce-page input[type=submit]:hover,
			body.woocommerce-page button[type=submit]:hover{
				background:<?php echo $ozyHelper->rgba2rgb(ozy_get_option('form_button_background_color_hover'))?> !important;
				color:<?php echo $ozyHelper->rgba2rgb(ozy_get_option('form_button_font_color_hover'))?> !important;
				border:1px solid <?php echo ozy_get_option('form_button_background_color_hover')?> !important;
			}
			
		<?php } ?>
		
			/* Primary Menu Styling
			/*-----------------------------------------------------------------------------------*/
		<?php
			$menu_logo_height = ozy_get_option('primary_menu_height', '100') . 'px';
		?>
		
			#top-menu .logo>h1>a,
			#top-menu .logo2>h1>a{
				color:<?php echo ozy_get_option('primary_menu_logo_color')?> !important;
			}
		
			#top-menu,
			#top-menu .logo,
			#top-menu>ul,
			#top-menu>ul>li,
			#top-menu>ul>li>a,
			#top-menu>ul>li>a:before,
			#top-menu>ul>li>a:after,
			#top-menu>ul>li>.submenu-button {
				height:44px;
				line-height:44px;
				<?php 
				echo $ozyHelper->font_style_render(
					ozy_get_option('primary_menu_typography_font_face'), 
					ozy_get_option('primary_menu_typography_font_weight'), 
					ozy_get_option('primary_menu_typography_font_style'), 
					ozy_get_option('primary_menu_typography_font_size') . 'px', 
					'', 
					ozy_get_option('primary_menu_font_color')
				);
				?>
			}
			#top-menu,
			#top-menu .logo {
				line-height:<?php echo $menu_logo_height ?>;
				height:<?php echo $menu_logo_height ?>;				
				<?php 
				echo $ozyHelper->font_style_render(
					ozy_get_option('primary_menu_typography_font_face'), 
					ozy_get_option('primary_menu_typography_font_weight'), 
					ozy_get_option('primary_menu_typography_font_style'), 
					ozy_get_option('primary_menu_typography_font_size') . 'px', 
					'', 
					ozy_get_option('primary_menu_font_color')
				);
				?>
			}			
			#top-menu ul ul li a{
				color:<?php echo ozy_get_option('primary_menu_font_color') ?>;
			}
			#top-menu ul li>a:before,
			#top-menu ul li>a:after,
			#top-menu span.submenu-button:before,
			#top-menu span.submenu-button:after,
			#top-menu .menu-button:before,
			#top-menu .menu-button.menu-opened:after {
				background-color:<?php echo ozy_get_option('primary_menu_font_color') ?> !important;
			}
			#top-menu .menu-button:after,
			#top-menu .menu-item-search>a>span,
			#top-menu .menu-item-wpml>a>span {
				border-color:<?php echo ozy_get_option('primary_menu_font_color') ?> !important;
			}
			@media screen and (max-width:1280px){
				#top-menu #head-mobile {
					line-height:72px;
					min-height:72px;
				}
				#top-menu>ul>li {
					height:auto !important;
				}
				#top-menu,
				#top-menu ul li{
					background-color:<?php echo ozy_get_option('primary_menu_background_color') ?>;
				}
				#header {
					position:relative !important;
				}
				#top-menu>ul>li.sub-active {
					background-color:<?php echo ozy_get_option('primary_menu_background_color_hover') ?>;
				}
			}
			#header{position:<?php echo ozy_get_option('primary_sticky_menu') == '' ? 'absolute' : 'fixed'; ?>}			
			#header,
			#top-menu ul ul li{
				background-color:<?php echo ozy_get_option('primary_menu_background_color') ?>;
			}
			#top-menu ul ul li:hover{
				background-color:<?php echo ozy_get_option('primary_menu_background_color_hover') ?>;
				color:<?php echo ozy_get_option('primary_menu_font_color_hover') ?>;
			}
			#top-menu>ul>li:hover>a,
			#top-menu ul ul li:hover>a,
			#top-menu>ul>li.active>a,
			#top-menu ul ul li.current-menu-parent>a,
			#top-menu ul ul li.current-menu-item>a,
			#top-menu ul li.sub-active>a,
			#top-menu ul>li.current-page-ancestor>a{
				color:<?php echo ozy_get_option('primary_menu_font_color_hover') ?>;
			}
			#top-menu ul>li.current-menu-parent>a:before,
			#top-menu ul>li.current-menu-parent>a:after,
			#top-menu ul>li.current-menu-item>a:before,
			#top-menu ul>li.current-menu-item>a:after,
			#top-menu ul>li.current-page-ancestor>a:before,
			#top-menu ul>li.current-page-ancestor>a:after,
			#top-menu li:hover>span.submenu-button:before,
			#top-menu li:hover>span.submenu-button:after,
			#top-menu li.sub-active>span.submenu-button:before,
			#top-menu li.sub-active>span.submenu-button:after,
			#top-menu>ul>li:hover>a:before,
			#top-menu>ul>li:hover>a:after,
			#top-menu>ul ul>li:hover>a:before,
			#top-menu>ul ul>li:hover>a:after{
				background-color:<?php echo ozy_get_option('primary_menu_font_color_hover') ?> !important;
			}
			#header,
			#top-menu ul ul li a{
				border-color:<?php echo $ozyHelper->change_opacity(ozy_get_option('primary_menu_separator_color_2'), '0.3')?>;		
			}
			/* Top Info Bar
			/*-----------------------------------------------------------------------------------*/			
			/*#info-bar .ozy-selectOptions{background-color:<?php echo ozy_get_option('top_info_bar_background_color3', '#fff'); ?>;}*/
			#info-bar .ozy-selectBox{border-color:<?php echo $ozyHelper->hex2rgba(ozy_get_option('top_info_bar_font_color', '#fff'), '.5'); ?>;}
			#info-bar *{color:<?php echo ozy_get_option('top_info_bar_font_color', '#fff'); ?>;}
			/*#info-bar .ozy-selectOptions{background-color:<?php echo ozy_get_option('top_info_bar_background_color4', '#000'); ?>;}*/

			#info-bar div.ozy-selectOptions, #info-bar .ozy-selectOption{background-color:<?php echo ozy_get_option('top_info_bar_background_color3', '#fff'); ?>;}
			#info-bar .ozy-selectOption>a{color:<?php echo ozy_get_option('top_info_bar_background_color4', '#000'); ?>;}			
			#info-bar .ozy-selectOption:hover{background-color:<?php echo ozy_get_option('top_info_bar_background_color4', '#000'); ?>;}
			#info-bar .ozy-selectOption:hover>a{color:<?php echo ozy_get_option('top_info_bar_background_color3', '#fff'); ?>;}			
			div.ozy-selectOptions:before{border-bottom-color:<?php echo ozy_get_option('top_info_bar_background_color3', '#fff'); ?>;}
			<?php 
				$bg_color_start = ozy_get_option('top_info_bar_background_color1', '#0076ff');
				$bg_color_end = ozy_get_option('top_info_bar_background_color2', '#1fd87c');
			?>
			#info-bar {
				background: <?php echo $bg_color_start ?>;
				background: -moz-linear-gradient(left,  <?php echo $bg_color_start ?> 0%, <?php echo $bg_color_end ?> 100%);
				background: -webkit-linear-gradient(left,  <?php echo $bg_color_start ?> 0%,<?php echo $bg_color_end ?> 100%);
				background: linear-gradient(to right,  <?php echo $bg_color_start ?> 0%,<?php echo $bg_color_end ?> 100%);
				filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $bg_color_start ?>', endColorstr='<?php echo $bg_color_end ?>',GradientType=1 );
			}
			
			/* Widgets
			/*-----------------------------------------------------------------------------------*/
			.widget li>a{
				color:<?php echo ozy_get_option('content_color'); ?> !important;
			}
			.widget li>a:hover{
				color:<?php echo ozy_get_option('content_color_alternate'); ?> !important;
			}
			.ozy-latest-posts>a>span{
				background-color:<?php echo $ozyHelper->hex2rgba(ozy_get_option('content_color_alternate'),'.8') ?>;color:<?php echo ozy_get_option('content_background_color') ?>;
			}
			
			/* Page Styling and Typography
			/*-----------------------------------------------------------------------------------*/
			ul.menu li.current_page_item>a,
			.content-color-alternate{
				color:<?php echo ozy_get_option('content_color_alternate'); ?> !important;
			}
			.content-color,
			h1.content-color>a,h2.content-color>a,h3.content-color>a,h4.content-color>a,h5.content-color>a,h6.content-color>a {
				color:<?php echo ozy_get_option('content_color'); ?> !important;
			}
			.ozy-footer-slider,
			.content-font,
			.ozy-header-slider,
			#content,
			#footer-widget-bar,
			#sidebar,
			#footer,
			input,
			select,
			textarea,
			.tooltipsy,
			.fancybox-inner,
			#woocommerce-lightbox-cart {
				<?php echo $ozyHelper->font_style_render(ozy_get_option('typography_font_face'), 
				ozy_get_option('typography_font_weight'), 
				ozy_get_option('typography_font_style'), 
				ozy_get_option('typography_font_size') . 'px', 
				ozy_get_option('typography_font_line_height') . 'em', 
				ozy_get_option('content_color'));?>
			}
			#content a:not(.ms-btn):not(.vc_btn3),
			#sidebar a,#footer a,
			.alternate-text-color,
			#footer-widget-bar>.container>.widget-area a:hover,
			.fancybox-inner a,
			#woocommerce-lightbox-cart a {
				color:<?php echo ozy_get_option('content_color_alternate');?>;
			}
			#footer #social-icons a,
			#ozy-share-div>a>span,
			.a-page-title,
			.page-pagination>a,
			.fancybox-inner,
			#woocommerce-lightbox-cart{
				color:<?php echo ozy_get_option('content_color');?> !important;
			}
			.page-pagination>.current{
				background-color:<?php echo ozy_get_option('primary_menu_separator_color') ?>;
			}
			.a-page-title:hover{
				border-color:<?php echo ozy_get_option('content_color');?> !important;
			}
			.nav-box a,
			#page-title-wrapper h1,
			#page-title-wrapper h3,
			#side-nav-bar a,
			#side-nav-bar h3,
			#content h1,
			#footer-widget-bar h1,
			#footer-widget-bar h2,
			#footer-widget-bar h3,
			#footer-widget-bar h4,
			#footer-widget-bar h5,
			#footer-widget-bar h6,
			#sidr h1,
			#sidr h2,
			#sidr h3,
			#sidr h4,
			#sidr h5,
			#sidr h6,
			#sidebar .widget h1,
			#footer h1,
			#content h2,
			#sidebar .widget h2,
			#footer h2,
			#content h3,
			#sidebar .widget h3,
			#footer h3,
			#content h4,
			#sidebar .widget h4,
			#footer h4,
			#content h5,
			#sidebar .widget h5,
			#footer h5,
			#content h6,
			#sidebar .widget h6,
			#footer h6,
			.heading-font,
			#logo,
			#tagline,
			.ozy-ajax-shoping-cart{
				<?php echo $ozyHelper->font_style_render(ozy_get_option('typography_heading_font_face'), '', '', '', '', ozy_get_option('content_color'));?>
			}
			#page-title-wrapper h1,
			#content h1,
			#footer-widget-bar h1,
			#sidebar h1,
			#footer h1,
			#sidr h1{
					<?php echo $ozyHelper->font_style_render('', 
				ozy_get_option('typography_heading_font_weight_h1'), 
				ozy_get_option('typography_heading_h1_font_style'), 
				ozy_get_option('typography_heading_h1_font_size') . 'px', 
				ozy_get_option('typography_heading_line_height_h1', '1.5') . 'em', '', '', 
				ozy_get_option('typography_heading_font_ls_h1'));?>
			}
			#footer-widget-bar .widget-area h4,
			#sidebar .widget>h4 {
				<?php echo $ozyHelper->font_style_render('', 
				ozy_get_option('typography_heading_font_weight_h4'), 
				ozy_get_option('typography_heading_h4_font_style'), 
				ozy_get_option('typography_heading_h4_font_size') . 'px', 
				ozy_get_option('typography_heading_line_height_h4', '1.5') . 'em', '', '',
				ozy_get_option('typography_heading_font_ls_h4'));?>
			}
			#content h2,
			#footer-widget-bar h2,
			#sidebar h2,
			#footer h2,
			#sidr h2{
				<?php echo $ozyHelper->font_style_render('', 
				ozy_get_option('typography_heading_font_weight_h2'), 
				ozy_get_option('typography_heading_h2_font_style'), 
				ozy_get_option('typography_heading_h2_font_size') . 'px', 
				ozy_get_option('typography_heading_line_height_h2', '1.5') . 'em', '', '',
				ozy_get_option('typography_heading_font_ls_h2'));?>;
			}
			#page-title-wrapper h3,
			#content h3,
			#footer-widget-bar h3,
			#sidebar h3,
			#footer h3,
			#sidr h3{
				<?php echo $ozyHelper->font_style_render('', 
				ozy_get_option('typography_heading_font_weight_h3'), 
				ozy_get_option('typography_heading_h3_font_style'), 
				ozy_get_option('typography_heading_h3_font_size') . 'px', 
				ozy_get_option('typography_heading_line_height_h3', '1.5') . 'em', '', '',
				ozy_get_option('typography_heading_font_ls_h3'));?>;
			}
			#content h4,
			#footer-widget-bar h4,
			#sidebar h4,
			#footer h4,
			#sidr h4{
				<?php echo $ozyHelper->font_style_render('', 
				ozy_get_option('typography_heading_font_weight_h4'), 
				ozy_get_option('typography_heading_h4_font_style'), 
				ozy_get_option('typography_heading_h4_font_size') . 'px', 
				ozy_get_option('typography_heading_line_height_h4', '1.5') . 'em', '', '',
				ozy_get_option('typography_heading_font_ls_h4'));?>;
			}
			#content h5,
			#footer-widget-bar h5,
			#sidebar h5,
			#footer h5,
			#sidr h5{
				<?php echo $ozyHelper->font_style_render('', 
				ozy_get_option('typography_heading_font_weight_h5'), 
				ozy_get_option('typography_heading_h5_font_style'), 
				ozy_get_option('typography_heading_h5_font_size') . 'px', 
				ozy_get_option('typography_heading_line_height_h5', '1.5') . 'em', '', '',
				ozy_get_option('typography_heading_font_ls_h5'));?>;
			}
			#content h6,
			#footer-widget-bar h6,
			#sidebar h6,
			#footer h6,
			#sidr h6{
				<?php echo $ozyHelper->font_style_render('', 
				ozy_get_option('typography_heading_font_weight_h6'), 
				ozy_get_option('typography_heading_h6_font_style'), 
				ozy_get_option('typography_heading_h6_font_size') . 'px', 
				ozy_get_option('typography_heading_line_height_h6', '1.5') . 'em', '', '',
				ozy_get_option('typography_heading_font_ls_h6'));?>;
			}
			#footer-widget-bar .widget a:hover,
			#sidebar .widget a:hover{
				color:<?php echo ozy_get_option('content_color')?>;
			}
			span.plus-icon>span{
				background-color:<?php echo ozy_get_option('content_color')?>;
			}
			<?php
			if(ozy_get_metabox('show_loader') == '1' && $ozy_data->device_type === 'computer') {
			?>
			/* Loader
			/*-----------------------------------------------------------------------------------*/
			.deviceis_phone #loaderMask,.deviceis_tablet #loaderMask{display:none!important}#loaderMask{position:fixed;top:0;bottom:0;left:0;right:0;width:100%;height:100%;z-index:1000}#loaderMask>span{position:absolute;display:block;z-index:1001;top:50%;left:50%;margin-top:-48px;margin-left:-50px;width:100px;height:100px;line-height:100px;text-align:center;color:#fff;}.no-js #loaderMask{display:none}#loader{display:block;position:relative;left:50%;top:50%;width:150px;height:150px;margin:-75px 0 0 -75px;border-radius:50%;border:3px solid transparent;border-top-color:#16a085;-webkit-animation:spin 1.7s linear infinite;animation:spin 1.7s linear infinite;z-index:11}#loader:before{content:"";position:absolute;top:5px;left:5px;right:5px;bottom:5px;border-radius:50%;border:3px solid transparent;border-top-color:#e74c3c;-webkit-animation:spin-reverse .6s linear infinite;animation:spin-reverse .6s linear infinite}#loader:after{content:"";position:absolute;top:15px;left:15px;right:15px;bottom:15px;border-radius:50%;border:3px solid transparent;border-top-color:#f9c922;-webkit-animation:spin 1s linear infinite;animation:spin 1s linear infinite}@-webkit-keyframes spin{0%{-webkit-transform:rotate(0deg)}100%{-webkit-transform:rotate(360deg)}}@keyframes spin{0%{-webkit-transform:rotate(0deg);-ms-transform:rotate(0deg);transform:rotate(0deg)}100%{-webkit-transform:rotate(360deg);-ms-transform:rotate(360deg);transform:rotate(360deg)}}@-webkit-keyframes spin-reverse{0%{-webkit-transform:rotate(0deg)}100%{-webkit-transform:rotate(-360deg)}}@keyframes spin-reverse{0%{-webkit-transform:rotate(0deg);-ms-transform:rotate(0deg);transform:rotate(0deg)}100%{-webkit-transform:rotate(-360deg);-ms-transform:rotate(-360deg);transform:rotate(-360deg)}}#loaderMask .loader-section{position:fixed;top:0;width:51%;height:100%;background:#000;z-index:10}#loaderMask .loader-section.section-left{left:0}#loaderMask .loader-section.section-right{right:0}.loaded #loaderMask .loader-section.section-left{-webkit-transform:translateX(-100%);-ms-transform:translateX(-100%);transform:translateX(-100%);-webkit-transition:all .7s .3s cubic-bezier(0.645,0.045,0.355,1);transition:all .7s .3s cubic-bezier(0.645,0.045,0.355,1)}.loaded #loaderMask .loader-section.section-right{-webkit-transform:translateX(100%);-ms-transform:translateX(100%);transform:translateX(100%);-webkit-transition:all .7s .3s cubic-bezier(0.645,0.045,0.355,1);transition:all .7s .3s cubic-bezier(0.645,0.045,0.355,1)}.loaded #loader{opacity:0;-webkit-transition:all .3s ease-out;transition:all .3s ease-out}.loaded #loaderMask{visibility:hidden;-webkit-transform:translateY(-100%);-ms-transform:translateY(-100%);transform:translateY(-100%);-webkit-transition:all .3s 1s ease-out;transition:all .3s 1s ease-out}
			<?php
			}
			?>			
			
			/* Forms
			/*-----------------------------------------------------------------------------------*/
			input:not([type=submit]):not([type=file]),
			select,
			textarea{
				background-color:<?php echo ozy_get_option('form_background_color')?>;
				border-color:<?php echo ozy_get_option('form_font_color')?> !important;
			}
			#request-a-rate input:not([type=submit]):not([type=file]):hover,
			#request-a-rate textarea:hover,
			#request-a-rate select:hover,
			#request-a-rate input:not([type=submit]):not([type=file]):focus,
			#request-a-rate textarea:focus,
			#request-a-rate select:focus,
			#content input:not([type=submit]):not([type=file]):hover,
			#content textarea:hover,
			#content input:not([type=submit]):not([type=file]):focus,
			#content textarea:focus{
				border-color:<?php echo ozy_get_option('content_color_alternate')?> !important;
			}
			.rsMinW .rsBullet span{
				background-color:<?php echo $ozyHelper->rgba2rgb(ozy_get_option('form_font_color'))?>;
			}
			.generic-button,
			.woocommerce-page .button,
			input[type=button],
			input[type=submit],
			button[type=submit],
			.comment-body .reply>a,
			#to-top-button,			
			.tagcloud>a{
				color:<?php echo $ozyHelper->rgba2rgb(ozy_get_option('form_button_font_color'))?> !important;
				background-color:<?php echo ozy_get_option('form_button_background_color')?>;
				border:1px solid <?php echo ozy_get_option('form_button_background_color')?>;
			}
			.post-submeta>a.button:hover,
			.woocommerce-page .button:hover,
			input[type=button]:hover,
			input[type=submit]:hover,
			button[type=submit]:hover,
			.comment-body .reply>a:hover,
			.tagcloud>a:hover{
				background-color:<?php echo $ozyHelper->rgba2rgb(ozy_get_option('form_button_background_color_hover'))?>;
				color:<?php echo $ozyHelper->rgba2rgb(ozy_get_option('form_button_font_color_hover'))?> !important;
				border:1px solid <?php echo ozy_get_option('form_button_background_color_hover')?>;
			}			
			
			/* Blog Comments & Blog Stuff
			/*-----------------------------------------------------------------------------------*/
			.comment-body,
			#ozy-share-div>a{
				background-color:<?php echo ozy_get_option('content_background_color_alternate') ?>;
			}
			.post-submeta>div>div.button{
				background-color:<?php echo ozy_get_option('content_color') ?>;
			}
			.post-submeta>div>div.arrow{
				border-color: transparent <?php echo ozy_get_option('content_color') ?> transparent transparent;
			}
			.post-title>span,
			.post-submeta>a>span,
			.simple-post-format>div>span{
				background-color:<?php echo ozy_get_option('content_color_alternate') ?> !important;
			}
			.featured-thumbnail-header p,
			.featured-thumbnail-header a,
			.featured-thumbnail-header h1{
				color:<?php echo ozy_get_option('content_color_alternate3') ?> !important;
			}
			.featured-thumbnail-header>div{
				background-color:<?php echo $ozyHelper->hex2rgba(ozy_get_option('content_color_alternate'),'.4') ?>;
			}
			.featured-thumbnail>a,
			.ozy-related-posts .related-post-item>a{
				background-color:<?php echo $ozyHelper->hex2rgba(ozy_get_option('content_color_alternate'),'.8') ?>;
			}
			.post-submeta>div>div.button>a>span{
				color:<?php echo ozy_get_option('content_background_color_alternate') ?>;
			}
			.post-meta p.g{
				color:<?php echo ozy_get_option('content_color_alternate2')?>;
			}	
			
			#single-blog-tags>a,
			.ozy-related-posts .caption,
			.ozy-related-posts .caption>h4>a{
				color:<?php echo ozy_get_option('content_background_color') ?> !important;
				background-color:<?php echo ozy_get_option('content_color') ?>;
			}
			#single-blog-tags>a:hover{
				color:<?php echo ozy_get_option('content_background_color') ?>;
				background-color:<?php echo ozy_get_option('content_color_alternate') ?>;
			}
	
			/*post formats*/
			.simple-post-format.post-excerpt-aside>div{
				background-color:<?php echo $ozyHelper->hex2rgba(ozy_get_option('content_color'),'.8')?>;
			}
			.simple-post-format>div{
				background-color:<?php echo ozy_get_option('content_color')?>;
			}
			.simple-post-format>div>span,
			.simple-post-format>div>h2,
			.simple-post-format>div>p,
			.simple-post-format>div>p>a,
			.simple-post-format>div>blockquote,
			.post-excerpt-audio>div>div{
				color:<?php echo $ozyHelper->rgba2rgb(ozy_get_option('content_background_color'))?> !important;
			}
			div.sticky.post-single {
				background-color:<?php echo ozy_get_option('primary_menu_separator_color') ?>;
				border-color:<?php echo ozy_get_option('primary_menu_separator_color') ?>;
			}			
			/* Shortcodes
			/*-----------------------------------------------------------------------------------*/
			.ozy-postlistwithtitle-feed>a:hover{
				background-color:<?php echo $ozyHelper->rgba2rgb(ozy_get_option('form_button_background_color_hover'))?>;
			}
			.ozy-postlistwithtitle-feed>a:hover *{
				color:<?php echo $ozyHelper->rgba2rgb(ozy_get_option('form_button_font_color_hover'))?> !important;
			}
			
			.ozy-accordion>h6.ui-accordion-header>span,
			.ozy-tabs .ozy-nav .ui-tabs-selected a,
			.ozy-tabs .ozy-nav .ui-tabs-active a,
			.ozy-toggle span.ui-icon{
				background-color:<?php echo ozy_get_option('content_color_alternate') ?>;
			}
			.ozy-tabs .ozy-nav .ui-tabs-selected a,
			.ozy-tabs .ozy-nav .ui-tabs-active a{
				border-color:<?php echo ozy_get_option('content_color_alternate') ?> !important;
			}
			.ozy-tabs .ozy-nav li a{
				color:<?php echo ozy_get_option('content_color');?> !important;
			}
			
			/*owl carousel*/
			.ozy-owlcarousel .item.item-extended>a .overlay-one *,
			.ozy-owlcarousel .item.item-extended>a .overlay-two *{
				color:<?php echo ozy_get_option('content_color_alternate3') ?> !important;
			}
			.ozy-owlcarousel .item.item-extended>a .overlay-one-bg{
				background-color:<?php echo ozy_get_option('content_color_alternate') ?>;
				background-color:<?php echo $ozyHelper->hex2rgba(ozy_get_option('content_color_alternate'),.50) ?>;
			}
			.ozy-owlcarousel .item.item-extended>a .overlay-two{
				background-color:<?php echo ozy_get_option('content_color_alternate') ?>;
				background-color:<?php echo $ozyHelper->hex2rgba(ozy_get_option('content_color_alternate'),.85) ?>;
			}
			.owl-theme .owl-controls .owl-page.active span{
				background-color:<?php echo ozy_get_option('content_color_alternate') ?>;
			}
			
			.ozy-button.auto,.wpb_button.wpb_ozy_auto{
				background-color:<?php echo ozy_get_option('form_button_background_color') ?>;
				color:<?php echo ozy_get_option('form_button_font_color')?>;
			}
			.ozy-button.auto:hover,
			.wpb_button.wpb_ozy_auto:hover{
				border-color:<?php echo ozy_get_option('form_button_background_color_hover') ?>;
				color:<?php echo ozy_get_option('form_button_font_color_hover') ?> !important;
				background-color:<?php echo ozy_get_option('form_button_background_color_hover')?>;
			}
			
			.ozy-icon.circle{
				background-color:<?php echo ozy_get_option('content_color') ?>;
			}
			.ozy-icon.circle2{
				color:<?php echo ozy_get_option('content_color') ?>;
				border-color:<?php echo ozy_get_option('content_color') ?>;
			}
			a:hover>.ozy-icon.square,
			a:hover>.ozy-icon.circle{
				background-color:transparent !important;color:<?php echo ozy_get_option('content_color') ?>;
			}
			a:hover>.ozy-icon.circle2{
				color:<?php echo ozy_get_option('content_color') ?>;
				border-color:transparent !important;
			}
	
			.wpb_content_element .wpb_tabs_nav li.ui-tabs-active{
				background-color:<?php echo ozy_get_option('content_color_alternate') ?> !important;
				border-color:<?php echo ozy_get_option('content_color_alternate') ?> !important;
			}
			.wpb_content_element .wpb_tabs_nav li,
			.wpb_accordion .wpb_accordion_wrapper .wpb_accordion_header{
				border-color:<?php echo ozy_get_option('primary_menu_separator_color') ?> !important;
			}
			.wpb_content_element .wpb_tabs_nav li.ui-tabs-active>a{
				color:<?php echo ozy_get_option('content_background_color');?> !important;
			}
			.wpb_content_element .wpb_tour_tabs_wrapper .wpb_tabs_nav a,
			.wpb_content_element .wpb_accordion_header a{
				color:<?php echo ozy_get_option('content_color');?> !important;
			}
			.wpb_content_element .wpb_accordion_wrapper .wpb_accordion_header{
				font-size:<?php echo ozy_get_option('typography_font_size') ?>px !important;
				line-height:<?php echo ozy_get_option('typography_font_line_height') ?>em !important
			}
			.pricing-table .pricing-table-column+.pricetable-featured .pricing-price{
				color:<?php echo ozy_get_option('content_color_alternate')?> !important;
			}
			.pricing-table li,
			.pricing-table .pricing-table-column:first-child,
			.pricing-table .pricing-table-column{
				border-color:<?php echo ozy_get_option('primary_menu_separator_color') ?> !important;
			}
			.pricing-table .pricing-table-column+.pricetable-featured,
			.pricing-table .pricing-table-column.pricetable-featured:first-child{
				border:4px solid <?php echo ozy_get_option('content_color_alternate') ?> !important;
			}
			.ozy-service-box:hover {
				background-color:<?php echo ozy_get_option('content_color_alternate2') ?> !important;
			}
			.ozy-service-box:hover>* {
				color:<?php echo ozy_get_option('content_color_alternate3') ?> !important;
			}
			
			/* Shared Border Color
			/*-----------------------------------------------------------------------------------*/			
			.post .pagination>a,
			.ozy-border-color,
			#ozy-share-div.ozy-share-div-blog,
			.page-content table td,
			#content table tr,
			.post-content table td,
			.ozy-toggle .ozy-toggle-title,
			.ozy-toggle-inner,
			.ozy-tabs .ozy-nav li a,
			.ozy-accordion>h6.ui-accordion-header,
			.ozy-accordion>div.ui-accordion-content,
			.chat-row .chat-text,
			#sidebar .widget>h4,
			#sidebar .widget li,
			.ozy-content-divider,
			#post-author,
			.single-post .post-submeta,
			.widget ul ul,
			blockquote,
			.page-pagination>a,
			.page-pagination>span,
			.woocommerce-pagination>ul>li>*,
			#content select,
			body.search article.result,
			div.rssSummary,
			#sidr input,
			#content table tr td,
			#content table tr th,
			.widget .testimonial-box,
			.shared-border-color {
				border-color:<?php echo ozy_get_option('primary_menu_separator_color') ?>;
			}
			#content table tr.featured {
				border:2px solid <?php echo ozy_get_option('content_color_alternate') ?> !important;
			}		
			/* Specific heading styling
			/*-----------------------------------------------------------------------------------*/	
		<?php
			$use_no_page_title_margin = $custom_header = false;
			if(!is_search()) {
				$post_id = ozy_get_woocommerce_page_id();		
				if($post_id > 0) {
					echo '.woocommerce-page article .page-title{
						display:none !important
					}';
				}			
				if(ozy_get_metabox('use_custom_title', 0, $post_id) == '1') {
					$_var = 'use_custom_title_group.0.ozy_logistic_meta_page_custom_title_';
					$h_height 	= ozy_get_metabox($_var . 'height', '240', $post_id);
					$h_bgcolor 	= ozy_get_metabox($_var . 'bgcolor', '', $post_id);
					$h_bgimage 	= ozy_get_metabox($_var . 'bg', '', $post_id);
					$h_bg_xpos	= ozy_get_metabox($_var . 'bg_x_position', '', $post_id);
					$h_bg_ypos	= ozy_get_metabox($_var . 'bg_y_position', '', $post_id);
					
					$h_css = (int)$h_height > 0 ? 'height:'. $h_height .'px;' : '';
					$h_css.= (int)$h_height > 0 ? $ozyHelper->background_style_render($h_bgcolor, $h_bgimage, 'cover', 'repeat', 'inherit', true, $h_bg_xpos, $h_bg_ypos) : '';
					echo '#page-title-wrapper{'. $h_css .'}';					
					$h_title_color = ozy_get_metabox($_var . 'color', 0, $post_id);
					if($h_title_color) {
						echo '#page-title-wrapper>div>h1{
							color:'. $h_title_color .';
						}';
					}
					$h_sub_title_color = ozy_get_metabox('use_custom_title_group.0.ozy_logistic_meta_page_custom_sub_title_color', 0, $post_id);
					if($h_sub_title_color) {
						echo '#page-title-wrapper>div>h3{
							color:'. $h_sub_title_color .';
						}';
					}
					
					$h_title_position = ozy_get_metabox($_var . 'position', 0, $post_id);
					if($h_title_position) {
						echo '#page-title-wrapper>div>h1,
						#page-title-wrapper>div>h3{
							text-align:'. $h_title_position .';
						}';
					}
					$custom_header = true;
				}else{
					echo '#page-title-wrapper{
						height:100px
					}';
				}
			}else{
				echo '#page-title-wrapper{
					height:100px
				}';
			}
			
			if(is_page_template('page-countdown.php') || is_page_template('404.php') || is_404() || is_page_template('page-revo-full.php') || is_page_template('page-masterslider-full.php')) {
				echo '#main{margin-top:0!important}';
			}else{
				echo '@media only screen and (min-width: 1025px) {#main{padding-top:'. $primary_menu_height .'px}}';
			}
			
			if(ozy_get_metabox('use_no_content_padding') === '1') {
				echo '#main>.container{
					padding-top:0!important;
				}';
			}
		?>		
			
			/* Conditional Page Template Styles
			/*-----------------------------------------------------------------------------------*/
			<?php
			if(is_page_template('page-grid-gallery.php')) {
			?>
			.ozy-grid-gallery .info {
				background-color:<?php echo ozy_get_option('content_color_alternate') ?>;
			}
			<?php
			}
			if(is_page_template('page-isotope-blog.php')) {
			?>
			#main>.container{
				padding-left:20px !important;
				padding-right:0 !important;
			}
			#content .wpb_row.vc_row-fluid>div.parallax-wrapper,
			#main>.container>#content{								
				padding-bottom:0 !important;
			}
			#content .wpb_wrapper>.post{
				margin-bottom:20px !important;
				margin-top:0 !important;
				padding:0 20px 0 0 !important;
				width:33.333%;
				float:left;
				clear:none !important;
			}
			#content .wpb_wrapper>.post>.featured-thumbnail {
				border:0px solid #000;
				-webkit-transition: all .5s;
				transition: all .5s;				
			}			
			#content .wpb_wrapper>.post:hover>.featured-thumbnail {
				border:10px solid #000;
			}
			.featured-thumbnail>a{background-color:transparent;}
			#content .wpb_wrapper>.post .caption {
				position:absolute;
				left: 20px;
				right: 40px;
				width: auto;
				bottom: 20px;
				padding:15px 20px 15px 20px;
				background-color:#fff;
				-webkit-transition: all .5s;
				transition: all .5s;
				cursor:default;
			}
			#content .wpb_wrapper>.post:hover .caption {background-color:rgba(255,255,255,.8);}
			#content .wpb_wrapper>.post .caption p {
				padding:0;
				font-size:12px;
			}
			.post.has_thumb .post-title,
			.featured-thumbnail{margin:0 !important;}
			.post-single{padding:0 !important;}
			@media only screen and (max-width: 1280px) {#content .wpb_wrapper>.post{width:33.333%;}}
			@media only screen and (max-width: 800px) {#content .wpb_wrapper>.post{width:50%;}}
			@media only screen and (max-width: 479px) {#content .wpb_wrapper>.post{width:100%;}}
			.post-content img{height:auto;}
			#filters{padding:20px 20px 10px 0;}
			#filters a.button{
				color: <?php echo ozy_get_option('content_color_alternate3')?>;
				background-color: <?php echo ozy_get_option('content_color_alternate2')?>;
				padding:7px 15px 5px 15px; 
				display:inline-block;
				margin:0 0 8px 0;
				font-weight:700;
				text-transform:uppercase;
				font-size:12px;
			}
			#filters a.button.is-checked{background-color: <?php echo ozy_get_option('content_color_alternate')?>;}
			.ozy-owlcarousel.single .owl-pagination{display:none;}
			div.sticky.post-single{border-color:transparent !important;background-color:transparent !important;}
			<?php
			}
			if(is_page_template('page-full-blog.php')) {
			?>
			#main>.container{
				padding-left:0 !important;
				padding-right:0 !important;
			}
			#content .wpb_row.vc_row-fluid>div.parallax-wrapper,
			#main>.container>#content{								
				max-width:100% !important;
				width:100% !important;
				padding-bottom:0 !important;
			}
			#content .wpb_wrapper>.post{
				margin-bottom:0 !important;
				margin-top:0 !important;
				padding-bottom:0 !important;
			}
			#content .wpb_wrapper>.post>div {
				width:50%;
				position:relative;
			}
			#content .wpb_wrapper>.post>div.t {padding:71px 80px;}
			#content .wpb_wrapper>.post>div.p .owl-post-slider,
			#content .wpb_wrapper>.post>div.p .post-audio,
			#content .wpb_wrapper>.post>div.p .post-video,			
			#content .wpb_wrapper>.post>div.p .featured-thumbnail {margin:0 !important;}
			#content .wpb_wrapper>.post>div.p .ozy-owlcarousel.single .owl-controls {margin-top:-32px !important;}
			#content .wpb_wrapper>.post>div.p div.arrow {
				position:absolute;
				width: 0;
				height: 0;
				top:50%;
				margin-top:-12px;
				border-style: solid;
				border-width: 24px 0 24px 24px;
				border-color: transparent transparent transparent #ffffff;
				z-index:1;
				opacity:0;
				-webkit-transition: all .2s;
				transition: all .2s;				
			}
			#content .wpb_wrapper>.post>div.p.l>div.arrow {
				right:0;
				border-width: 24px 24px 24px 0;
				border-color: transparent #ffffff transparent transparent;
			}
			#content .wpb_wrapper>.post:hover>div.p div.arrow {opacity:1;}
			#content .wpb_wrapper>.post>div.p.r>div.arrow {left:0;}
			#content .wpb_wrapper>.post>div.l {float:left;}
			#content .wpb_wrapper>.post>div.r {float:right;}
			.post-submeta{line-height:26px!important;}
			.post-meta>p{padding:0 !important;}
			.post-meta>p:first-child{text-transform:uppercase;}

			.featured-thumbnail>a{color:#fff !important;}
			.featured-thumbnail>a>div {
				display:inline-block;
				padding:6px 18px !important;
				border-radius:22px;
				position:absolute;
				left:50%;
				top:50%;
				transform:translate(-50%, -50%);
				-webkit-transform:translate(-50%, -50%);
				-moz-transform:translate(-50%, -50%);
				border:1px solid #fff;				
				font-weight:500 !important;
				text-decoration:none !important;
			}
			.featured-thumbnail>a>div>i {
				padding-left:20px !important;
				font-size:24px !important;
				vertical-align: text-top;
			}
			.post-submeta{margin-top:15px !important;}
			.post-submeta>.share-buttons{margin-left:-10px !important;}
			.page-pagination{
				display:inline-block !important;
				text-align:center;
				width:100% !important;
				padding:36px 0 !important;
			}
			.page-pagination>a{
				display:inline-block !important;
				float:none !important;
			}
			@media screen and (max-width:1024px){#content .wpb_wrapper>.post>div{width:100%}}
			@media screen and (max-width:479px){#content .wpb_wrapper>.post>div.t {padding:31px 40px;}}
			<?php
			}
			
			if(is_page_template('page-classic-gallery.php') || 
			is_page_template('page-thumbnail-gallery.php') || 
			is_page_template('page-nearby-gallery.php') || 
			is_page_template('page-revo-full.php')) {
			?>
			#main>.container.no-vc,
			#content,
			#content.no-vc{
				max-width:100% !important;
				width:100% !important;
				padding-left:0 !important;
				padding-right:0 !important;
				padding-top:0 !important;
				padding-bottom:0 !important;
			}				
			<?php
			}
			$ozyHelper->render_custom_fonts();
			?>		
		</style>
		<?php
		$ozyHelper->render_google_fonts();
	}
	
	add_action( 'wp_head', 'ozy_logistic_style', 99 );
endif;
?>