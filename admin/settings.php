<div class="wrap">
    <div id="<?php echo $this->plugin->name; ?>-title" class="icon32"></div> 
    <h2><?php echo $this->plugin->displayName; ?> &raquo; <?php _e('Settings'); ?></h2>
           
    <?php    
    if ($this->message != '') {
        ?>
        <div class="updated"><p><?php echo $this->message; ?></p></div>  
        <?php
    }
    if ($this->errorMessage != '') {
        ?>
        <div class="error"><p><?php echo $this->errorMessage; ?></p></div>  
        <?php
    }
    ?> 
    
    <div id="poststuff" class="metabox-holder has-right-sidebar"> 
        <!-- Sidebar -->
        <div id="side-info-column" class="inner-sidebar">
            <div id="side-sortables" class="meta-box-sortables">                    
                <!-- Donate -->
                <div class="postbox">
                    <div class="handlediv" title="Click to toggle"><br /></div>
                    <h3 class="hndle"><span>About</span></h3>
                    <div class="inside">
                    	<p>This is an unofficial plugin allowing you to send updates to Buffer when you publish or update any Page, Post or Custom Post Type on your WordPress web site.</p>
                    	<p>I try my best to maintain this plugin outside of my full time job, therefore
                    	any donations for ongoing development and support are greatly appreciated.</p>
						
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
							<p>
								<input type="hidden" name="cmd" value="_s-xclick">
								<input type="hidden" name="hosted_button_id" value="RBAHE4QWKJX3N">
								<input type="hidden" name="on0" value="Donation">
								<select name="os0">
									<option value="2">&pound;2 GBP (~ $3 USD)</option>
									<option value="5">&pound;5 GBP (~ $8 USD)</option>
									<option value="7">&pound;7.50 GBP (~ $12 USD)</option>
									<option value="10">&pound;10 GBP (~ $15 USD)</option>
								</select> 
								<input type="hidden" name="currency_code" value="GBP">
							</p>
							<p>
								<input type="submit" name="donate" value="Donate Now" class="button button-primary" />
								<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1" />
							</p>
						</form>
                    </div>  
                </div>
                
                <!-- Keep Updated -->
                <div class="postbox">
                    <div class="handlediv" title="Click to toggle"><br /></div>
                    <h3 class="hndle"><span>Keep Updated</span></h3>
                    <div class="inside">
                    	<p><?php _e('Subscribe to the newsletter and receive updates on latest projects and offers'); ?>.</p>
                    	<form action="http://n7studios.createsend.com/t/r/s/jdkdjui/" method="post" id="subForm" target="_blank">
		                    <p>
		                        <label for="jdkdjui-jdkdjui"><?php _e('Email Address'); ?></label>
		                        <input type="text" name="cm-jdkdjui-jdkdjui" id="jdkdjui-jdkdjui" placeholder="<?php _e('Enter your email address'); ?>" />
		                        <input type="submit" name="submit" value="<?php _e('Subscribe'); ?>" class="button" />
		                    </p>
		                </form>
                    </div>  
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <form id="post" name="post" method="post" action="admin.php?page=<?php echo $this->plugin->name; ?>">
	        <div id="post-body" class="has-sidebar">
	            <div id="post-body-content" class="has-sidebar-content">
	                <div id="normal-sortables" class="meta-box-sortables ui-sortable" style="position: relative;">                        
						<!-- Authentication -->
	                    <div class="postbox">
	                        <h3 class="hndle"><?php _e('Buffer Authentication'); ?></h3>
	                        <div class="inside">
	                        	<?php
	                        	if (isset($this->settings['accessToken']) AND $this->settings['accessToken'] != '') {
	                        		// Already authenticated
	                        		?>
	                        		<p>
	                        			<?php _e('Thanks - you\'ve authenticated the plugin with your Buffer account.'); ?>
	                        		    <input type="hidden" name="<?php echo $this->plugin->name; ?>[accessToken]" value="<?php echo $this->settings['accessToken']; ?>" class="widefat" />  
	                                </p>
	                        		<p>
	                        			<a href="admin.php?page=<?php echo $this->plugin->name; ?>&disconnect=1" class="button button-primary">
	                        				<?php _e('Disconnect Buffer'); ?>
	                        			</a>
	                        		</p>
	                        		<?php
	                        	} else {
	                            	?>
	                            	<p><strong><?php _e('Access Token'); ?></strong></p>
	                                <p>
	                                    <label class="screen-reader-text" for="label"><?php _e('Access Token'); ?></label>
	                                    <input type="text" name="<?php echo $this->plugin->name; ?>[accessToken]" value="<?php echo $this->settings['accessToken']; ?>" class="widefat" />  
	                                </p>
	                                <p>
	                                	You can obtain an access token to allow this Plugin to post updates to your Buffer account by
	                                	<a href="http://bufferapp.com/developers/apps/create" target="_blank">Registering an Application</a>
	                                </p>
	                                <p>
	                                	Set the Callback URL to <strong><?php bloginfo('url'); ?>/wp-admin/admin.php?page=<?php echo $this->plugin->name; ?></strong>. You can set the other settings to anything.
	                                </p>
	                            	<?php
	                        	}
	                        	?>
							</div>
	                    </div>
	                    
	                    <?php
	                    // Buffer Settings, only displayed if we have an access token
	                    if (isset($this->settings['accessToken']) AND $this->settings['accessToken'] != '') {
	                    	?>
	                    	<!-- Publishing -->
	                        <div class="postbox publishing-defaults">
	                            <h3 class="hndle"><?php _e('Publishing Defaults'); ?></h3>
	                            <div class="inside">
	                            	<p><strong><?php _e('Publish the following Post Types'); ?></strong></p>
	                            	<p>For each Post Type, tick whether to send an update to Buffer when Publishing and/or Updating a Post.</p>
	                            	<p>
	                            		Define the update's text using the text boxes below each option.  Valid tags are:<br />
		                            	{sitename}: the title of your blog<br />
		                            	{title}: the title of your blog post<br />
										{excerpt}: a short excerpt of the post content<br />
										{category}: the first selected category for the post<br />
										{date}: the post date<br />
										{url}: the post URL<br />
										{author}: the post author
	                                </p>
	                                
	                                <p>
	                                	Publishing can be disabled on a post by post basis.
	                                </p>

	                                <?php                            	
	                            	// Go through all Post Types
	                            	$types = get_post_types('', 'names');
	                            	foreach ($types as $key=>$type) {
	                            		if (in_array($type, $this->plugin->ignorePostTypes)) continue; // Skip ignored Post Types
	                            		$postType = get_post_type_object($type);
	                            		?>
	                            		<hr />
	                            		
	                            		<p><strong><?php _e($postType->label); ?></strong></p>
	                            		
	                            		<div>
		                            		<p><?php _e('On Publish'); ?> <input type="checkbox" name="<?php echo $this->plugin->name; ?>[enabled][<?php echo $type; ?>][publish]" value="1"<?php echo ($this->settings['enabled'][$type]['publish'] == 1 ? ' checked' : ''); ?> /></p>
		                            		<p class="update">
			                                    <input type="text" name="<?php echo $this->plugin->name; ?>[message][<?php echo $type; ?>][publish]" value="<?php echo (($this->settings['message'][$type]['publish'] != '') ? $this->settings['message'][$type]['publish'] : $this->plugin->publishDefaultString); ?>" class="widefat" />  
			                                </p>
		                                </div>
		                                
		                                <div>
			                                <p><?php _e('On Update'); ?> <input type="checkbox" name="<?php echo $this->plugin->name; ?>[enabled][<?php echo $type; ?>][update]" value="1"<?php echo ($this->settings['enabled'][$type]['update'] == 1 ? ' checked' : ''); ?> /></p>
		                            		<p class="update">
			                                    <input type="text" name="<?php echo $this->plugin->name; ?>[message][<?php echo $type; ?>][update]" value="<?php echo (($this->settings['message'][$type]['update'] != '') ? $this->settings['message'][$type]['update'] : $this->plugin->updateDefaultString); ?>" class="widefat" />  
			                                </p> 
		                                </div>
		                                
		                                <p><?php _e('Accounts'); ?></p>		                            	
		                            	<?php
		                            	if ($this->buffer->accounts AND count($this->buffer->accounts) > 0) {
		                            		foreach ($this->buffer->accounts as $key=>$account) {
		                            			?>
		                            			<div class="buffer-account">
		                            				<img src="<?php echo $account->avatar; ?>" width="48" height="48" alt="<?php echo $account->formatted_name; ?>" />
		                            				<input type="checkbox" name="<?php echo $this->plugin->name; ?>[ids][<?php echo $type; ?>][<?php echo $account->id; ?>]" value="1"<?php echo ($this->settings['ids'][$type][$account->id] == 1 ? ' checked' : ''); ?> />
		                            				<span class="<?php echo $account->service; ?>"></span>
		                            			</div>
		                            			<?php
		                            		}
		                            	}	
	                            	}
	                            	?> 
								</div>
	                        </div>	
	                    	<?php
	                    }
	                    ?>
	                	
	                    <!-- Save -->
	                    <div class="submit">
	                        <input type="submit" name="submit" value="<?php _e('Save'); ?>" class="button button-primary" /> 
	                    </div>
	                </div>
	            </div>
	        </div>
	    </form>
	</div>       
</div>