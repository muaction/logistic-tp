            <?php if(!$ozy_data->hide_everything_but_content) { ?>
			
            <div id="footer-wrapper">
	            
				<?php if(ozy_get_metabox('hide_footer_widget_bar') !== '1' && ozy_get_metabox('hide_footer_widget_bar') !== '2') { ?>
                <div id="footer-widget-bar" class="widget">
                    <div class="container">
                        <section class="widget-area">
                            <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("ozy-footer-widget-bar-one" . $ozy_data->wpml_current_language_) ) : ?><?php endif; ?>
                        </section>
                        <section class="widget-area">
                            <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("ozy-footer-widget-bar-two" . $ozy_data->wpml_current_language_) ) : ?><?php endif; ?>
                        </section>
                        <section class="widget-area">
                            <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("ozy-footer-widget-bar-three" . $ozy_data->wpml_current_language_) ) : ?><?php endif; ?>
                        </section>
                        <section class="widget-area">
                            <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("ozy-footer-widget-bar-four" . $ozy_data->wpml_current_language_) ) : ?><?php endif; ?>
                        </section>
                    </div><!--.container-->
                </div><!--#footer-widget-bar-->
                <?php } ?>
                <?php if(ozy_get_metabox('hide_footer_widget_bar') !== '2') { ?>			
                <div id="footer" class="widget"><footer>
                    <div class="container">
                        <?php
                            global $ozyHelper;
                            echo '<div>' . ozy_get_option('section_footer_copyright_text' . str_replace('_', '', $ozy_data->wpml_current_language_)) . '</div>';
                            echo '<div class="top-social-icons">'; $ozyHelper->social_icons(); echo '</div>';
                        ?>
                    </div><!--.container-->
                </footer></div><!--#footer-->
                <?php } ?>
            </div>
            <?php } ?>

			<?php
            if(is_woocommerce_activated()) {
                echo '<div id="woocommerce-lightbox-cart-wrapper" class="woocommerce woocommerce-page">
						<div id="woocommerce-lightbox-cart">
							<a href="javascript:void(0)" id="woocommerce-cart-close"><i class="oic-x"></i></a>
							<div>
								<h3>'. __('YOUR SHOPPING BAG', 'vp_textdomain') .'</h3>
								<div>
									<div class="widget_shopping_cart_content"></div>
								</div>
							</div>
                    	</div>
					</div>';
            }								
            ?> 