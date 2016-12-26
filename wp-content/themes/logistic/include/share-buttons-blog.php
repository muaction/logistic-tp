                            <div class="post-submeta">
                               	<?php if(!$hide_title) {
	                                echo '<a href="'. get_permalink() .'" class="read-more content-color" title="'. __('Read More', 'vp_textdomain') .'">→</a>';
								?>
                                    <span class="share-buttons">
                                    	<a href="http://www.facebook.com/share.php?u=<?php the_permalink() ?>"><span class="tooltip-top symbol content-color" title="Facebook">&#xe027;</span></a>
                                        <a href="https://twitter.com/share?url=<?php the_permalink() ?>"><span class="tooltip-top symbol content-color" title="Twitter">&#xe086;</span></a>
                                    </span>
                                <?php
								}
								?>
                            </div>