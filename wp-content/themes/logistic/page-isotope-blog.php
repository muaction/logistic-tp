<?php 
/*
Template Name: Blog : Masonry
*/
get_header(); 
?>
	<div id="content" class="<?php echo esc_attr($ozy_data->_page_content_css_name); ?>">
        
        <div class="wpb_row vc_row-fluid">
			<div class="parallax-wrapper">
            	<div class="vc_col-sm-12 wpb_column vc_column_container">
                	<div id="filters" class="button-group">
                    	<a href="#all" class="button is-checked" data-filter="*"><?php _e('SHOW ALL', 'vp_textdomain') ?></a>
                        <?php
							$args = array(
								'type'              => 'post',
								'child_of'          => 0,
								'parent'            => 0,
                                'orderby' 			=> $ozy_data->_blog_orderby,
                                'order' 			=> $ozy_data->_blog_order,							
								'hide_empty'        => 1,
								'hierarchical'      => 1,
								'exclude'           => '',
								'include'           => $ozy_data->_blog_include_categories,
								'number'            => '0',
								'taxonomy'          => 'category',
								'pad_counts'		=> false 
							);
							
							$categories = get_categories($args);
							foreach ($categories as $category) {
								echo '<a href="#'. $category->category_nicename .'" class="button" data-filter=".category-'. $category->category_nicename .'">' . $category->cat_name . '</a>' . PHP_EOL;
							}						
						?>
                    </div>
                	<div class="wpb_wrapper isotope">
						<?php
                            query_posts( array(
                                'cat'				=> $ozy_data->_blog_include_categories,
                                'ignore_sticky_posts' => '1',
								'post_type' 		=> 'post',
                                'post_status'		=> 'publish',
								'posts_per_page'	=> 200,
                                'orderby' 			=> $ozy_data->_blog_orderby,
                                'order' 			=> $ozy_data->_blog_order,
								'meta_key' 			=> '_thumbnail_id',
								'tax_query' 			=> array(
									array(
										'taxonomy' => 'post_format',
										'field' => 'slug',
										'terms' => array( 'post-format-quote', 'post-format-status', 'post-format-link', 'post-format-aside' ),
										'operator' => 'NOT IN'
									)
								),
                                'paged'				=> get_query_var('paged') ));
                            
                            if (have_posts()) : while (have_posts()) : the_post(); 
                                global $more, $ozy_global_params; $more = 0;
                                
                                /*get post format*/
                                $ozy_temporary_post_format = $ozy_current_post_format = get_post_format();
                                if ( false === $ozy_current_post_format ) {
                                    $ozy_current_post_format = 'standard';
                                }

                        ?>
						<div <?php post_class('post-single post-format-'. esc_attr($ozy_current_post_format) . ' ozy-waypoint-animate ozy-appear'); ?>>                        
						<?php
							$thumbnail_image_src = $post_image_src = array();
							if ( has_post_thumbnail() ) { 
								$thumbnail_image_src 	= wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'blog' , false );
								$post_image_src 		= wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' , false );						
	
	
								if ( isset($thumbnail_image_src[0]) && isset($post_image_src[0])) { 
									echo '<div class="featured-thumbnail" style="background-image:url('. esc_url($post_image_src[0]) .');"><a href="'. esc_url($thumbnail_image_src[0]) .'" class="fancybox"></a>'; the_post_thumbnail('blog'); echo '</div>'; 
								}
							}

							?>
                            <div class="caption">
                                <div class="post-meta">
                                    <p><?php the_category(', '); ?></p>
                                    <p><?php the_time('F j, Y'); _e(' at ', 'vp_textdomain'); the_time(); ?></p>
                                </div><!--#post-meta-->                  
                                <?php
								echo '<h3 class="post-title">';
									echo '<a href="'. get_permalink() .'" title="'. get_the_title() .'" class="a-page-title" rel="bookmark">'. ( get_the_title() ? get_the_title() : get_the_time('F j, Y') ) .'</a>';
								echo '</h3>';
                                ?>
                			</div>
                        </div><!--.post-single-->        
                        
                        <?php endwhile; else: ?>
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