<?php 
/*
Template Name: Blog : Full Page
*/
get_header(); 
?>
	<div id="content" class="<?php echo esc_attr($ozy_data->_page_content_css_name); ?>">
        
        <div class="wpb_row vc_row-fluid">
			<div class="parallax-wrapper">
            	<div class="vc_col-sm-12 wpb_column vc_column_container">
                	<div class="wpb_wrapper">
						<?php
                            query_posts( array(
                                'cat'					=> $ozy_data->_blog_include_categories,
                                'post_type' 			=> 'post',
                                'post_status'			=> 'publish',
                                'orderby' 				=> $ozy_data->_blog_orderby,
                                'order' 				=> $ozy_data->_blog_order,
								'meta_key' 				=> '_thumbnail_id',
								'ignore_sticky_posts' 	=> 1,
                                'paged'					=> get_query_var('paged'),
								'tax_query' 			=> 
									array(
										array(
											'taxonomy' => 'post_format',
											'field' => 'slug',
											'terms' => array( 'post-format-quote', 'post-format-status', 'post-format-link', 'post-format-audio' ),
											'operator' => 'NOT IN'
										)
									)									
								)
							);
                            $counter = 0;
                            if (have_posts()) : while (have_posts()) : the_post(); 
                                global $more, $ozy_global_params; $more = 0; $ozy_global_params['media_object'] = '';
                                
                                /*get post format*/
                                $ozy_temporary_post_format = $ozy_current_post_format = get_post_format();
                                if ( false === $ozy_current_post_format ) {
                                    $ozy_current_post_format = 'standard';
                                }
                                $hide_title = false;
                                
                                /*here i am handling content to extract media objects*/
                                ob_start();
                                if($post->post_excerpt) {
                                    the_excerpt();
                                }else{
                                    //if this is a gallery post, please remove gallery shortcode to render it as expected
                                    if('gallery' === $ozy_current_post_format) {
                                        ozy_convert_classic_gallery();
                                    } else {
										the_content('');										
                                    }
                                }
                                $ozy_content_output = ob_get_clean();				
                
                        ?>
						<div <?php post_class('post-single post-format-'. esc_attr($ozy_current_post_format) . ' ozy-waypoint-animate ozy-appear'); ?>>
                        	<div class="p <?php echo $counter%2===0 ? 'l' : 'r' ?>">
                                <div class="arrow"></div>
							<?php
                                $thumbnail_image_src = $post_image_src = array();
                                if ( has_post_thumbnail() ) { 
                                    $thumbnail_image_src 	= wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' , false );
                                    $post_image_src 		= wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'blog' , false );						
                                }
                                
                                /*post format processing*/
                                if( 'gallery' === $ozy_current_post_format ) {
                                    echo $ozyHelper->post_owl_slider();
                                } 
                                else if( 'video' !== $ozy_current_post_format && 'audio' !== $ozy_current_post_format )
                                {
                                    if ( isset($thumbnail_image_src[0]) && isset($post_image_src[0])) { 
                                        echo '<div class="featured-thumbnail" style="background-image:url('. esc_url($post_image_src[0]) .');"><a href="'. get_permalink() .'"><div>'. __('READ MORE', 'vp_textdomain') .'</div></a>'; the_post_thumbnail('blog'); echo '</div>'; 
                                    }									
                                }
                
                                /*and here i am printing media object which handled in functions.php ozy_add_video_embed_title()*/
                                if(isset($ozy_global_params['media_object'])) echo $ozy_global_params['media_object'];
                            ?>
                            </div>
                            <div class="t <?php echo $counter%2===0 ? 'r' : 'l' ?>">
								<?php
                                if($hide_title) {
    
                                        echo '<h2 class="post-title">';
                                        the_title();
                                        echo '</h2>';
                                        echo the_excerpt();
    
                                }
                                if('audio' == $ozy_current_post_format) {
                                    $thumbnail_image_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'showbiz' , false );
                                    echo '<div class="post-excerpt-'. esc_attr($ozy_current_post_format) .' simple-post-format">
                                            <div>
                                                <span class="icon"></span>';
                                        if(isset($thumbnail_image_src[0])) {
                                            echo '<img src="'. esc_url($thumbnail_image_src[0]) .'" class="audio-thumb" alt=""/>';
                                        }
                                        echo '<div>';							
                                        echo the_excerpt();
                                        echo '</div>';
                                    echo '	</div>
                                        </div>';								
                                }

                                if(!$hide_title && 'audio' !== $ozy_current_post_format) {
                                    echo '<h2 class="post-title">';
                                        echo '<a href="'. get_permalink() .'" title="'. get_the_title() .'" class="a-page-title" rel="bookmark">'. ( get_the_title() ? get_the_title() : get_the_time('F j, Y') ) .'</a>';
                                    echo '</h2>';
                                 ?>
                                <div class="post-meta content-color-alternate">
                                    <p><?php the_category(', '); ?></p>
                                    <p> / </p>
                                    <p><?php the_time('F j, Y'); ?></p>
                                </div><!--#post-meta-->                                 
                                 <?php   
                                    echo '<div class="post-content">';
                                        echo the_excerpt();
                                    echo '</div>';
                                }
                                
                                include('include/share-buttons-blog.php');
                                ?>
                			</div>
                        </div><!--.post-single-->        
                        
                        <?php $counter++; endwhile; else: ?>
                        <div class="no-results">
                            <p><strong><?php _e('There has been an error.', 'vp_textdomain'); ?></strong></p>
                            <p><?php _e('We apologize for any inconvenience, please hit back on your browser or use the search form below.', 'vp_textdomain'); ?></p>
                            <?php get_search_form(); /* outputs the default Wordpress search form */ ?>
                        </div><!--noResults-->
                        <?php endif; ?>
                        
                        <?php echo get_pagination('<div class="page-pagination">', '</div>'); ?>

					</div>
				</div>
             
        	</div>
        </div>
        
	</div><!--#content-->
    
<?php                 
get_footer();
?>