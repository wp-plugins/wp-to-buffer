<?php
/**
* Plugin Name: WP to Buffer
* Plugin URI: http://www.n7studios.co.uk/2012/04/29/wordpress-to-buffer-plugin/
* Version: 1.03
* Author: <a href="http://www.n7studios.co.uk/">n7 Studios</a>
* Description: Unofficial Plugin to send WordPress Pages, Posts or Custom Post Types to your bufferapp.com account for scheduled publishing to social networks.
*/

/**
* WP to Buffer Class
* 
* @package WordPress
* @subpackage WP to Buffer
* @author Tim Carr
* @version 1.03
* @copyright n7 Studios
*/
class WPToBuffer {
    /**
    * Constructor.
    */
    function WPToBuffer() {
        // Plugin Details
        $this->plugin->name = 'wp-to-buffer';
        $this->plugin->displayName = 'WP to Buffer';
        $this->plugin->url = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
        $this->plugin->settingsUrl = get_bloginfo('url').'/wp-admin/admin.php?page='.$this->plugin->name; 
        $this->plugin->ignorePostTypes = array('attachment','revision','nav_menu_item');      
		$this->plugin->publishDefaultString = 'New Post: {title} {url}';
		$this->plugin->updateDefaultString = 'Updated Post: {title} {url}';

		// Publish Actions for chosen post types
		$settings = get_option($this->plugin->name);
		if (is_array($settings['enabled'])) {
			foreach ($settings['enabled'] as $type=>$opts) {
				add_action('publish_'.$type, array(&$this, 'PublishToBufferNow'));
				add_action('publish_future_'.$type, array(&$this, 'PublishToBufferFuture'));
				add_action('xmlrpc_publish_'.$type, array(&$this, 'PublishToBufferXMLRPC'));	
			}
		}
	
        if (is_admin()) {
            add_action('init', array(&$this, 'AdminScriptsAndCSS'));
            add_action('admin_menu', array(&$this, 'AddAdminPanelsAndMetaBoxes'));
            add_action('admin_notices', array(&$this, 'AdminNotices'));
            add_action('save_post', array(&$this, 'Save'));  
        }
    }
    
    /**
    * Register and enqueue any JS and CSS for the WordPress Administration
    */
    function AdminScriptsAndCSS() {
    	// JS
    	wp_register_script($this->plugin->name.'-admin-js', $this->plugin->url.'js/admin.js');
       	wp_enqueue_script($this->plugin->name.'-admin-js'); 
                
    	// CSS
        wp_register_style($this->plugin->name.'-admin-css', $this->plugin->url.'css/admin.css'); 
        wp_enqueue_style($this->plugin->name.'-admin-css');   
    }
    
    /**
    * Adds a single option panel to the WordPress Administration, as well as meta
    * box for every Post Types, apart from the ignored ones specified.
    */
    function AddAdminPanelsAndMetaBoxes() {
        add_menu_page($this->plugin->displayName, $this->plugin->displayName, 9, $this->plugin->name, array(&$this, 'AdminPanel'), $this->plugin->url.'images/icons/small.png');
        
        // Go through all Post Types, adding a meta box for each
    	$types = get_post_types('', 'names');
    	foreach ($types as $key=>$type) {
    		if (in_array($type, $this->plugin->ignorePostTypes)) continue; // Skip ignored Post Types
    		add_meta_box($this->plugin->name.'-fields', 'Post to Buffer', array(&$this, 'DisplayBufferFields'), $type, 'side', 'low');
    	}
    }
    
    /**
    * Deferred init for adding publish actions after all Custom Post Types are registered
    */
    function AddPublishActions() {
		// Publishing Hooks for each Post Type
        $types = get_post_types('', 'names');
    	foreach ($types as $key=>$type) {
    		if (in_array($type, $this->plugin->ignorePostTypes)) continue; // Skip ignored Post Types
    		
    		add_action('publish_'.$type, array(&$this, 'PublishToBufferNow'));
			add_action('publish_future_'.$type, array(&$this, 'PublishToBufferFuture'));
			add_action('xmlrpc_publish_'.$type, array(&$this, 'PublishToBufferXMLRPC'));
		}
    }
    
    /**
    * Outputs a notice if:
    * - Buffer hasn't authenticated i.e. we do not have an access token
    * - A Post has been sent to Buffer and we have a valid message response
    */
    function AdminNotices() {
        if ($_GET['page'] == $this->plugin->name) return false; // Don't check on plugin main page
        $this->settings = get_option($this->plugin->name); // Get settings
        
        // Check if no access token
        if (!isset($this->settings['accessToken']) OR $this->settings['accessToken'] == '') {
        	echo (' <div class="error"><p>'.$this->plugin->displayName.' requires authorisation with Buffer in order to post updates to your account.
        			Please visit the <a href="admin.php?page='.$this->plugin->name.'" title="Settings">Settings Page</a> to grant access.</p></div>');
            return false;	
        }
        
        // Check if post published or updated and we have a response from Buffer
        if (isset($_GET['message']) AND isset($_GET['post'])) {
        	$log = get_post_meta($_GET['post'], $this->plugin->name.'-log', true);
        	if (is_object($log) AND $log->message != '') {
        		echo (' <div class="updated"><p>'.$log->message.'</p></div>');
            	return false;	
        	}
        }
    } 
    
    /**
    * Displays Buffer Fields
    */
    function DisplayBufferFields() {
		global $post;
        
        $meta = get_post_meta($post->ID, $this->plugin->name, true); // Get post meta
        $log = get_post_meta($post->ID, $this->plugin->name.'-log', true); // Get post meta log
        $defaults = get_option($this->plugin->name); // Get settings
        if ($defaults['accessToken'] != '') $accounts = $this->Request($defaults['accessToken'], 'profiles.json'); // Get buffer profiles
        
        // Output fields
        echo (' <div class="'.$this->plugin->name.'-meta-box">
                    <input type="hidden" name="'.$this->plugin->name.'_wpnonce" id="theme_wpnonce" value="'.wp_create_nonce(plugin_basename(__FILE__)).'" /> 
                    
                    <p>
                    	Yes <input type="radio" name="'.$this->plugin->name.'[publish]" value="1"'.(!is_array($meta) ? ($defaults['enabled'][$post->post_type]['publish'] == '1' ? ' checked' : '') : ($meta['publish'] == '1' ? ' checked' : '')).' />
                    	No <input type="radio" name="'.$this->plugin->name.'[publish]" value="0"'.(!is_array($meta) ? ($defaults['enabled'][$post->post_type]['publish'] != '1' ? ' checked' : '') : ($meta['publish'] != '1' ? ' checked' : '')).' />
                	</p>
                	<p class="notes">
                		'.$this->plugin->displayName.' will update Buffer<br />
                		- on Publish: '.($defaults['enabled'][$post->post_type]['publish'] == '1' ? __('Yes') : __('No')).'<br />
                		- on Update: '.($defaults['enabled'][$post->post_type]['update'] == '1' ? __('Yes') : __('No')).'<br />
                		To change these options, edit your defaults.<br /><br />
                		<a href="admin.php?page='.$this->plugin->name.'" target="_blank" class="button">Edit Defaults</a>
                	</p>
                </div>');	
    }
    
    /**
    * Saves meta fields for Pages and Posts
    *
    * @param int $post_id Post ID
    */
    function Save($post_id) {
        if (!wp_verify_nonce($_POST[$this->plugin->name.'_wpnonce'], plugin_basename(__FILE__))) return $post_id;
        if (!current_user_can('edit_post', $post_id)) return $post_id;
        if (count($_POST[$this->plugin->name]) > 0) {
        	update_post_meta($post_id, $this->plugin->name, $_POST[$this->plugin->name]);    
        } else {
        	update_post_meta($post_id, $this->plugin->name, array('saved' => '1')); // Checkboxes don't get sent, so store a random key/value pair so isset() checks in DisplayBufferFields dont tick fields from defaults
        }  
    }
    
    /**
    * Alias function called when a post is published or updated
    *
    * Passes on the request to the main Publish function
    *
    * @param int $postID Post ID
    */
    function PublishToBufferNow($postID) {
    	$this->Publish($postID);
    }
    
    /**
    * Alias function called when a post, set to be published in the future, reaches the time
    * when it is being published
    *
    * Passes on the request to the main Publish function
    *
    * @param int $postID Post ID
    */
    function PublishToBufferFuture($postID) {
    	$this->Publish($postID, true);
    }
    
    /**
    * Alias function called when a post is published or updated via XMLRPC
    *
    * Passes on the request to the main Publish function
    *
    * @param int $postID Post ID
    */
    function PublishToBufferXMLRPC($postID) {
    	$this->Publish($postID, true);	
    }
    
    /**
    * Called when any Page, Post or Custom Post Type is published or updated, live or for a scheduled post
    *
    * @param int $postID Post ID
    */
    function Publish($postID, $isPublishAction = false) {
    	$meta = get_post_meta($postID, $this->plugin->name, true); // Get post meta
        $defaults = get_option($this->plugin->name); // Get settings
        
        if (!is_array($meta) OR count($meta) == 0) $meta['publish'] = $_POST[$this->plugin->name]['publish']; // If no meta defined, this is a brand new post - read from post data
        if ($defaults['accessToken'] == '') return false; // No access token so cannot publish to Buffer
        if ($meta['publish'] != '1') return false; // Do not need to publish or update
        
        // Get post
        $post = get_post($postID);

        if ($_POST['original_post_status'] == 'draft' OR 
        	$_POST['original_post_status'] == 'auto-draft' OR 
        	$_POST['original_post_status'] == 'pending' OR
        	$_POST['original_post_status'] == 'future' OR
        	$isPublishAction) {
        	
        	// Publish?
        	if ($defaults['enabled'][$post->post_type]['publish'] != '1') return false; // No Buffer needed for publish
        	$updateType = 'publish';
        }
        
		if ($_POST['original_post_status'] == 'publish') {
        	// Update?
        	if ($defaults['enabled'][$post->post_type]['update'] != '1') return false; // No Buffer needed for update
        	$updateType = 'update';
        }
        
        // If here, build and send data to Buffer

		// 1. Get post categories if any exist
		$catNames = '';
		$cats = wp_get_post_categories($postID, array('fields' => 'ids'));
		if (is_array($cats) AND count($cats) > 0) {
			foreach ($cats as $key=>$catID) {
				$cat = get_category($catID);
				$catNames .= '#'.$cat->name.' ';
			}
		}
		
		// 2. Get author
		$author = get_user_by('id', $post->post_author);
		
		// 4. Parse text and description
		$params['text'] = $defaults['message'][$post->post_type][$updateType];
		$params['text'] = str_replace('{sitename}', get_bloginfo('name'), $params['text']);
		$params['text'] = str_replace('{title}', $post->post_title, $params['text']);
		$params['text'] = str_replace('{excerpt}', $post->post_excerpt, $params['text']);
		$params['text'] = str_replace('{category}', trim($catNames), $params['text']);
		$params['text'] = str_replace('{date}', date('dS F Y', strtotime($post->post_date)), $params['text']);
		$params['text'] = str_replace('{url}', get_permalink($postID), $params['text']);
		$params['text'] = str_replace('{author}', $author->display_name, $params['text']);

		// 5. Add profile IDs
		foreach ($defaults['ids'][$post->post_type] as $profileID=>$enabled) {
			if ($enabled) $params['profile_ids'][] = $profileID; 
		}
	
		// 6. Send to Buffer and store response
		$result = $this->Request($defaults['accessToken'], 'updates/create.json', 'post', $params);
		update_post_meta($postID, $this->plugin->name.'-log', $result);
    }
	
	/**
    * Outputs the plugin Admin Panel in Wordpress Admin
    */
    function AdminPanel() {
        // Save Settings
        if (isset($_POST['submit'])) {
            update_option($this->plugin->name, $_POST[$this->plugin->name]);
            $this->message = __('Settings Updated.'); 
        }
        
        // Get latest settings
        $this->settings = get_option($this->plugin->name);

        // Check if we have an error message from Buffer
        if (isset($_GET['error'])) {
        	switch ($_GET['error']) {
        		case 'invalid_client':
        			$this->errorMessage = __('The Client ID specified is invalid.  Please change this, Save settings and click the Connect to Buffer API button.');
        			break;
        		case 'redirect_uri_mismatch':
        			$this->errorMessage = __('The redirect URI specified by this plugin does not match the redirect URI specified for the application.');
        			$this->errorMessage .= '<br />';
        			$this->errorMessage = __('Check that the Callback URL for application at <a href="http://bufferapp.com/developers/apps/" target="_blank">http://bufferapp.com/developers/apps/</a> is set to '.$this->plugin->settingsUrl);
        			break;
        	}
        }
        
        // If we have a client ID and client secret, attempt to authenticate with the Buffer API
        if ($this->settings['clientID'] != '' AND $this->settings['clientSecret'] != '') $this->BufferAPIAuth();
        
        // If we have an access token, get the user's profile listing their accounts
        if ($this->settings['accessToken'] != '') {
        	$this->buffer->accounts = $this->Request($this->settings['accessToken'], 'profiles.json');
        }
        
        // Load form
        include_once(WP_PLUGIN_DIR.'/'.$this->plugin->name.'/admin/settings.php');  
    }
    
    /**
    * Goes through the oAuth 2 authentication process for the Buffer API
    *
    * Only called if a client ID and client secret have been specified in the Settings screen
    */
    function BufferAPIAuth() {
    	// If a request to revoke access has been sent, delete the access token
    	if (isset($_GET['revoke']) AND $_GET['revoke'] == 1) {
    		$this->settings['accessToken'] = '';
    		update_option($this->plugin->name, $this->settings['accessToken']);
    		$this->authUrl = $this->BufferAPIAuthURL();
    	}
    
    	// If back from Buffer and have a code, convert it to an access token
		if (isset($_GET['code']) AND isset($_GET['state'])) {
			// Swap our Authorisation Code / Token for an Access Token and Refresh Token
			$result = wp_remote_post('https://api.bufferapp.com/1/oauth2/token.json', array(
				'body' => array(
					'client_id' => $this->settings['clientID'],
					'client_secret' => $this->settings['clientSecret'],
					'redirect_uri' => $this->plugin->settingsUrl,
					'code' => $_GET['code'],
					'grant_type' => 'authorization_code'
				),
				'sslverify' => false
			));
			
			// Check the request is valid
			if (is_wp_error($result)) {
				$this->errorMessage = $result->get_error_message();
				return false;	
			}
			
			if ($result['response']['code'] != 200) {
				$this->errorMessage = 'Error '.$result['response']['code'].' whilst trying to authenticate: '.$result['response']['message'].'. Please try again.';
				$this->authUrl = $this->BufferAPIAuthURL();
				return false;
			}
			
			// Check the body contains an access token
			$body = json_decode($result['body']);
			
			if (!is_object($body) OR $body->access_token == '') {
				$this->errorMessage = __('An error occurred whilst trying to authenticate. Please try again.');
				$this->authUrl = $this->BufferAPIAuthURL();
				return false;	
			}
			
			// If here, we have an access token
			// Store it and check it works
			$this->settings['accessToken'] = $body->access_token;			
			update_option($this->plugin->name, $this->settings);
		} else {
			// Create auth URL for button in Settings screen
    		$this->authUrl = $this->BufferAPIAuthURL();
    	}
    }
    
    /**
    * Returns the URL for the Connect to Buffer API button, to begin the authorization process
    *
    * @return string Buffer Authorization URL
    */
    function BufferAPIAuthURL() {
    	return 'https://bufferapp.com/oauth2/authorize?client_id='.$this->settings['clientID'].'&response_type=code&redirect_uri='.urlencode($this->plugin->settingsUrl);
    }
    
    /**
    * Sends a GET request to the Buffer API
    *
    * @param string $accessToken Access Token
    * @param string $cmd Command
    * @param string $method Method (get|post)
    * @param array $params Parameters (optional)
    * @return mixed JSON decoded object or error string
    */
    function Request($accessToken, $cmd, $method = 'get', $params = array()) {
    	// Check for access token
    	if ($accessToken == '') return 'Invalid access token';
		
		// Send request
		switch ($method) {
			case 'get':
				$result = wp_remote_get('https://api.bufferapp.com/1/'.$cmd.'?access_token='.$accessToken, array(
		    		'body' => $params,
		    		'sslverify' => false
		    	));
				break;
			case 'post':
				$result = wp_remote_post('https://api.bufferapp.com/1/'.$cmd.'?access_token='.$accessToken, array(
		    		'body' => $params,
		    		'sslverify' => false
		    	));
				break;
		}
    	
    	// Check the request is valid
    	if (is_wp_error($result)) return $result->get_error_message();
		if ($result['response']['code'] != 200) return 'Error '.$result['response']['code'].' whilst trying to authenticate: '.$result['response']['message'].'. Please try again.';

		return json_decode($result['body']);		
    }
}
$WPToBuffer = new WPToBuffer(); // Invoke class
?>
