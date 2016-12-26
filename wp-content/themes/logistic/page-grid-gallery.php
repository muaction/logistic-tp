<?php
/*
Template Name: Gallery : Grid
*/
get_header();

global $ozyHelper, $ozy_data;

// meta params & bg slider for page
ozy_page_meta_params();

/* Widgetized LEFT sidebar */
if(function_exists( 'dynamic_sidebar' ) && $ozyHelper->hasIt($ozy_data->_page_content_css_name,'left-sidebar') && $ozy_data->_page_sidebar_name) {
?>
	<div id="sidebar" class="<?php echo esc_attr($ozy_data->_page_content_css_name); ?>">
		<ul>
        	<?php dynamic_sidebar( $ozy_data->_page_sidebar_name ); ?>
		</ul>
	</div>
	<!--sidebar-->
<?php
}
?>
<div id="content" class="<?php echo esc_attr($ozy_data->_page_content_css_name); ?> template-clean-page">
    <?php if ( have_posts() && $ozy_data->_page_hide_page_content != '1') while ( have_posts() ) : the_post(); ?>
        <div id="post-<?php the_ID(); ?>" <?php post_class('page'); ?>>
            <article>
                
                <div class="post-content page-content">
                    <!--ozy-grid-gallery-->
                    <div class="ozy-grid-gallery">
                    	<ul>
						<?php
                            foreach(ozy_grab_ids_from_gallery() as $attachment_id) {
                                $attachment  = get_post($attachment_id);
								$thumb_image = wp_get_attachment_image_src( $attachment_id, 'boxyboxy' );
								echo '<li>';
								echo '<a href="'. $attachment->guid .'" class="fancybox" title="'. esc_attr($attachment->post_title) .'"><img src="'. $thumb_image[0] .'" alt=""/><div class="info"><h3 class="title">'. $attachment->post_title .'</h3></div></a>';
								echo '</li>';
                            }
                        ?>
						</ul>
                    </div>
                    <!--.ozy-grid-gallery-->
                    					
                </div><!--.post-content .page-content -->
            </article>
			
        </div><!--#post-# .post-->

    <?php endwhile; ?>
</div><!--#content-->
<?php
/* Widgetized RIGHT sidebar */
if(function_exists( 'dynamic_sidebar' ) && $ozyHelper->hasIt($ozy_data->_page_content_css_name,'right-sidebar') && $ozy_data->_page_sidebar_name) {
?>
	<div id="sidebar" class="<?php echo esc_attr($ozy_data->_page_content_css_name); ?>">
		<ul>
        	<?php dynamic_sidebar( $ozy_data->_page_sidebar_name ); ?>
		</ul>
	</div>
	<!--sidebar-->
<?php
}
get_footer();
?>
