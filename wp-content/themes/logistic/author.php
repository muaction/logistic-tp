<?php 
get_header(); 

if(isset($_GET['author_name'])){$curauth = get_userdatabylogin($author_name);}else{$curauth = get_userdata(intval($author));}

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
	<div id="content" class="<?php echo esc_attr($ozy_data->_page_content_css_name); ?> author-page">
        
        <div class="wpb_row vc_row-fluid">
			<div class="parallax-wrapper">
            	<div class="vc_col-sm-12 wpb_column vc_column_container">
                	<div class="wpb_wrapper">

                        <div class="author-bio">
                            <p class="avatar"><?php if(function_exists('get_avatar')) { echo get_avatar( $curauth->user_email, $size = '80' ); } /* Displays the Gravatar based on the author's email address. Visit Gravatar.com for info on Gravatars */ ?></p>
                            
                            <?php if($curauth->description !="") { /* Displays the author's description from their Wordpress profile */ ?>
                                <p><?php echo $curauth->description; ?></a></p>
                            <?php } ?>
                        </div>
                        <h3 class="page-title"><?php _e('Recent Posts by ', 'vp_textdomain'); echo $curauth->display_name; ?></h3>
                        
                        <?php							
							
                            if (have_posts()) : while (have_posts()) : the_post(); 
							
								// Number of posts to display
								static $count = 0;
								if ($count == "5") { 
									break;
								}
							
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
								
								wp_link_pages();
								
                                $ozy_content_output = ob_get_clean();				
                        ?>
                        <div class="post-single post-format-<?php echo esc_attr($ozy_current_post_format) ?> ozy-waypoint-animate ozy-appear">
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
                            else if( 'aside' === $ozy_current_post_format || 
							'link' === $ozy_current_post_format || 
							'status' === $ozy_current_post_format || 
							'quote' === $ozy_current_post_format   ) {
                                $hide_title = true;
                            } 
                            else if( 'video' !== $ozy_current_post_format && 'audio' !== $ozy_current_post_format )
                            {
                                if ( isset($thumbnail_image_src[0]) ) { 
									echo '<div class="featured-thumbnail" style="background-image:url('. esc_attr($post_image_src[0]) .');"><a href="'. esc_attr($thumbnail_image_src[0]) .'" class="fancybox"><span class="oic-simple-line-icons-49"></span></a>'; the_post_thumbnail('blog'); echo '</div>'; 
                                }
                            }
            
                            /*and here i am printing media object which handled in functions.php ozy_add_video_embed_title()*/
                            if(isset($ozy_global_params['media_object'])) echo $ozy_global_params['media_object'];
                            
                            if($hide_title) {
								echo '<div class="post-excerpt-'. $ozy_current_post_format .' simple-post-format" style="'. (isset($thumbnail_image_src[0]) ? 'background-image:url('. esc_attr($thumbnail_image_src[0]) .');':'' ) .'">
										<div>
											<span class="icon"></span>';
                                	echo '<h2 class="post-title">';
                               		the_title();
	                                echo '</h2>';
									echo $ozy_content_output;
								echo '	</div>
									</div>';
                            }
							if('audio' == $ozy_current_post_format) {
								$thumbnail_image_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'showbiz' , false );
								echo '<div class="post-excerpt-'. $ozy_current_post_format .' simple-post-format">
										<div>
											<span class="icon"></span>';
									if(isset($thumbnail_image_src[0])) {
										echo '<img src="'. esc_attr($thumbnail_image_src[0]) .'" class="audio-thumb" alt=""/>';
									}
									echo '<div>';							
									echo $ozy_content_output;
									echo '</div>';
								echo '	</div>
									</div>';								
							}
							?>
                            <div class="post-meta">
                                <p class="g"><?php _e('By ', 'vp_textdomain'); ?></p>
                                <p><?php the_author_posts_link(); ?></p>
                                <p class="g"><?php _e(' in ', 'vp_textdomain');?></p>
                                <p><?php the_category(', '); ?></p>
                                <p class="g"><?php _e('Posted ', 'vp_textdomain'); ?></p>
                                <p><?php the_time('F j, Y'); _e(' at ', 'vp_textdomain'); the_time(); ?></p>
                            </div><!--#post-meta-->                  
                            <?php
							if(!$hide_title && 'audio' !== $ozy_current_post_format) {
                                echo '<h2 class="post-title">';
                               		echo '<a href="'. get_permalink() .'" title="'. get_the_title() .'" class="a-page-title" rel="bookmark">'. ( get_the_title() ? get_the_title() : get_the_time('F j, Y') ) .'</a>';
                                echo '</h2>';
                                
								echo '<div class="post-content">';
									echo $ozy_content_output;
								echo '</div>';
                            }
							
							include('include/share-buttons-blog-regular.php');
                            ?>
                
                        </div><!--.post-single-->        
                        
                        <?php endwhile; else: ?>
                        <div class="no-results">
                            <p><strong><?php _e('There has been an error.', 'vp_textdomain'); ?></strong></p>
                            <p><?php _e('We apologize for any inconvenience, please hit back on your browser or use the search form below.', 'vp_textdomain'); ?></p>
                            <?php get_search_form(); /* outputs the default Wordpress search form */ ?>
                        </div><!--noResults-->
                        <?php endif; ?>
                        
                        <p>&nbsp;</p>
                        
                        <div id="recent-author-comments">
                            <h3 class="page-title"><?php _e('Recent Comments by ', 'vp_textdomain'); echo $curauth->display_name; ?></h3>
                            <?php
                                $number=5; // number of recent comments to display
                                $comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_approved = '1' and comment_author_email='%s' ORDER BY comment_date_gmt DESC LIMIT %d", $curauth->user_email, $number));
                            ?>
                            <ul>
                                <?php
                                    if ( $comments ) : foreach ( (array) $comments as $comment) :
                                    echo  '<li class="recentcomments"><i class="oic-bubble"></i>&nbsp;' . sprintf(__('%1$s on %2$s', 'vp_textdomain'), get_comment_date(), '<a href="'. get_comment_link($comment->comment_ID) . '">' . get_the_title($comment->comment_post_ID) . '</a>') . '</li>';
                                endforeach; else: ?>
                                    <p><?php _e('No comments by ', 'vp_textdomain'); echo $curauth->display_name; ?></p>
                                <?php endif; ?>
                            </ul>
                        </div><!--#recentAuthorComments-->                        

					</div>
				</div>
             
        	</div>
        </div>
        
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