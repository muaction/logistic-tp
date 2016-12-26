<?php
echo '<div id="ozyMegaMenuStyleWindow"><div>
			<p>
				<label for="custom-menu-request-a-rate">'. __('Run Request a Rate form?', 'vp_textdomain') .'<br />
					<span>
						<input id="custom-menu-request-a-rate" type="checkbox" value="1"/>
						<br/>
						<small>'. __('Please add your HTML content and shortcodes to following box', 'vp_textdomain') .'</small>
					</span>
				</label>
			</p>			
			<p>
				<label for="custom-menu-html-content">'. __('Request a Rate Form Content', 'vp_textdomain') .'<br />
					<span>
						<textarea id="custom-menu-html-content" rows="7" type="text" class="widefat"/></textarea>
						<br/>
						<small>'. __('"Run Request a Rate form" option has to be checked in order this content appear as Request a Rate form', 'vp_textdomain') .'</small>						
					</span>
				</label>
			</p>
			<p>
				<label for="custom-menu-request-a-rate">'. __('I am having custom character issues', 'vp_textdomain') .'<br />
					<span>
						<input id="custom-menu-character-issues" type="checkbox" value="1"/>
						<br/>
						<small>'. __('Please check this field if you are having custom character issues for Request a Rate window at frontend', 'vp_textdomain') .'</small>
					</span>
				</label>
			</p>			
			<p>
				<label for="custom-menu-bg-color">'. __('Background Color', 'vp_textdomain') .'<br />
					<span>
						<input id="custom-menu-bg-color" type="text" class="widefat ozy-simple-color-picker"/>
					</span>
				</label>
			</p>
			<p>
				<label for="custom-menu-fn-color">'. __('Foreground Color', 'vp_textdomain') .'<br />
					<span>
						<input id="custom-menu-fn-color" type="text" class="widefat ozy-simple-color-picker"/>
					</span>
				</label>
			</p>
			<p>
				<label for="custom-menu-border-color">'. __('Border Color', 'vp_textdomain') .'<br />
					<span>
						<input id="custom-menu-border-color" type="text" class="widefat ozy-simple-color-picker"/>
					</span>
				</label>
			</p>
			<p>
				<label for="custom-menu-border-width">'. __('Border Width', 'vp_textdomain') .'<br />
					<span>
						<select id="custom-menu-border-width" class="widefat">
							<option value="0">0</option>
							<option value="1px">1px</option>
							<option value="2px">2px</option>
							<option value="3px">3px</option>
							<option value="4px">4px</option>
							<option value="5px">5px</option>
							<option value="6px">6px</option>
							<option value="7px">7px</option>
							<option value="8px">8px</option>
							<option value="9px">9px</option>
							<option value="10px">10px</option>
						</select>
					</span>
				</label>
			</p>

			<p>
				<a href="javascript:void(0);" class="button-primary" id="custom-menu-bg-apply">'. __('Apply Changes', 'vp_textdomain') .'</a>
				<br/>
				<i>'. __('Please note, you have to use "Save Menu" in order to get this changes applied', 'vp_textdomain') .'</i>
			</p>
			
								
</div></div>';
?>