=== WP to Buffer ===
Contributors: n7studios,wpcube
Donate link: http://www.wpcube.co.uk/plugins/wp-to-buffer/
Tags: buffer,bufferapp,schedule,twitter,facebook,linkedin,google,social,media,sharing,post
Requires at least: 3.0
Tested up to: 3.6
Stable tag: trunk

Send WordPress Pages, Posts or Custom Post Types to your bufferapp.com account for scheduled publishing to social networks.

== Description ==

WP to Buffer is a plugin for WordPress that sends updates to your Buffer (bufferapp.com) account  for scheduled publishing to social networks when you publish and/or update WordPress Pages, Posts and/or Custom Post Types.

Plugin settings allow granular control over choosing:
- Sending updates to Buffer for Posts, Pages and/or any Custom Post Types
- Sending updates when any of the above are published, updated or both or neither
- Text format to use when sending an update on publish or update events, with support for tags including site name, Post title, excerpt, categories, date, URL and author
- Which social media accounts connected to your Buffer account to publish updates to (Facebook, Twitter or LinkedIn)

When creating or editing a Page, Post or Custom Post Type, sending the update to Buffer can be overridden for that specific content item.

== Installation ==

1. Download the WP to Buffer Plugin.
2. In your WordPress Administration, go to Plugins > Add New > Upload, and select the plugin ZIP file.
3. Activate the plugin
4. Click on the Plugin name in the Administration menu, and follow the steps to authenticate with Buffer.

== Frequently Asked Questions ==


== Screenshots ==

1. Settings Panel when plugin is first installed.
2. Settings Panel when Buffer Access Token is entered.
3. Settings Panel showing available options for Posts, Pages and any Custom Post Types when the plugin is authenticated with Buffer.
4. Post level settings meta box.

== Changelog ==

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
