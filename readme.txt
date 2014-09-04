=== WP to Buffer ===
Contributors: n7studios,wpcube
Donate link: http://www.wpcube.co.uk/plugins/wp-to-buffer-pro
Tags: buffer,bufferapp,schedule,twitter,facebook,linkedin,google,social,media,sharing,post
Requires at least: 3.6
Tested up to: 4.0
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Send WordPress Pages, Posts or Custom Post Types to your Buffer (bufferapp.com) account for scheduled publishing to social networks.

== Description ==

WP to Buffer is a plugin for WordPress that sends updates to your Buffer (bufferapp.com) account  for scheduled publishing to social networks when you publish and/or update WordPress Pages, Posts and/or Custom Post Types.

Plugin settings allow granular control over choosing:
- Sending updates to Buffer for Posts, Pages and/or any Custom Post Types
- Sending updates when any of the above are published, updated or both or neither
- Text format to use when sending an update on publish or update events, with support for tags including site name, Post title, excerpt, categories, date, URL and author
- Which social media accounts connected to your Buffer account to publish updates to (Facebook, Twitter or LinkedIn)

When creating or editing a Page, Post or Custom Post Type, sending the update to Buffer can be overridden for that specific content item.

= Support =

*Premium Plugins*

For many of our plugins on wordpress.org, Premium versions are available. These typically provide additional functionality,
and come with one to one email support.

*Free Plugins*

We will do our best to provide support through the WordPress forums. However, please understand that this is a free plugin, 
so support will be limited. Please read this article on <a href="http://www.wpbeginner.com/beginners-guide/how-to-properly-ask-for-wordpress-support-and-get-it/">how to properly ask for WordPress support and get it</a>.

= WP Cube =
We produce free and premium WordPress Plugins that supercharge your site, by increasing user engagement, boost site visitor numbers
and keep your WordPress web sites secure.

Find out more about us:

* <a href="http://www.wpcube.co.uk">Our Plugins</a>
* <a href="http://www.facebook.com/wpcube">Facebook</a>
* <a href="http://twitter.com/wp_cube">Twitter</a>
* <a href="https://plus.google.com/b/110192203343779769233/110192203343779769233/posts?rel=author">Google+</a>

== Installation ==

1. Upload the `wp-to-buffer` folder to the `/wp-content/plugins/` directory
2. Active the WP to Buffer plugin through the 'Plugins' menu in WordPress
3. Configure the plugin by going to the `WP to Buffer` menu that appears in your admin menu

== Frequently Asked Questions ==



== Screenshots ==

1. Settings Panel when plugin is first installed.
2. Settings Panel when Buffer Access Token is entered.
3. Settings Panel showing available options for Posts, Pages and any Custom Post Types when the plugin is authenticated with Buffer.
4. Post level settings meta box.

== Changelog ==

= 2.3.6 =
* Fix: &hellip; HTML character code appearing on Facebook + Google+ status updates when no excerpt defined on a Post

= 2.3.5 =
* Fix: Removed logging

= 2.3.4 =
* Fix: Double posts in Buffer when a scheduled Post goes live.

= 2.3.3 =
* Dropped html_entity_decode and apply_filters on Post Title - causing too many issues.

= 2.3.2 =
* Fix: Settings tabs not working / all settings panels displaying at once
* Added translation support and .pot file 

= 2.3.1 =
* Fix: Issue with characters in the title being HTML encoded

= 2.3 =
* Fix: Uses get_the_title() when generating status updates for social networks
* Fix: Check that at least one social media profile has been chosen before trying to update via the API

= 2.2.1 =
* Fix: Prevent double posting when Posts with category filtering are enabled, and a Post is added via third party apps using the XML RPC API
* Fix: Pages can be posted to Buffer via XML RPC API

= 2.2 =
* Fix: Twitter Images attached to tweets
* Fix: Featured Images on Facebook

= 2.1.8 =
* Fix: Stops URLs and images being stripped from some updates to LinkedIn

= 2.1.7 =
* Fix: Removed unused addPublishActions function

= 2.1.6 =
* Fix: Dashboard widget
* Fix: Some Posts not adding to Buffer due to meta key check

= 2.1.5 =
* Fix: Don't show success message when Post/Page not posted to Buffer
* Fix: Removed Post to Buffer meta box, which wasn't honouring settings / causing double postings
* Settings: changed to tabbed interface

= 2.1.4 =
* Fix: Dashboard: PHP fatal error

= 2.1.3 =
* Fix: Posts with an image no longer show the image link, but instead show the Page / Post URL

= 2.1.2 =
* Fix: Donation Form

= 2.1.1 =
* Fix: Some assets missing from SVN checkin on 2.1

= 2.1 =
* Fix: 'Creating default object from empty value' warning
* Fix: {excerpt} tag working on Pages and Custom Post Types that do not have an Excerpt field
* Fix: Capabilities for add_menu_page
* Fix: Check for page $_GET variable

= 2.0.1 =
* Fix: Removed console.log messages
* Fix: Added Google+ icon for Buffer accounts linked to Google+ Pages

= 2.0 =
* Fix: admin_enqueue_scripts used to prevent 3.6+ JS errors
* Fix: Force older versions of WP to Buffer to upgrade to 2.x branch.
* Fix: Check for Buffer accounts before outputting settings (avoids invalid argument errors).
* Enhancement: Validation of access token to prevent several errors.
* Enhancement: Add callback URL value (not required, but avoids user confusion).
* Enhancement: Check the access token pasted into the settings field is potentially valid (avoids questions asking why the plugin doesn't work,
because the user hasn't carefully checked the access token).

= 1.1 =
* Enhancement: Removed spaces from categories in hashtags (thanks, Douglas!)
* Fix: "Error creating default object from empty value" message.
* Enhancement: Added Featured Image when posting to Buffer, if available.
* Fix: Simplified authentication process using Access Token. Fixes many common oAuth issues.

= 1.03 =
* Fix: Publish hooks now based on settings instead of registered post types, to ensure they hook early enough to work on custom post types.

= 1.02 =
* Fix: Scheduled Posts now post to Buffer on scheduled publication.

= 1.01 =
* SSL verification fix for Buffer API authentication.

= 1.0 =
* First release.

== Upgrade Notice ==

