=== Plugin Name ===
Contributors: OneScreen Inc.
Plugin Name: OneScreen Toolkit for WordPress
Plugin URI: http://wordpress.org/plugins/onescreen-video
Author: OneScreen Inc.
Author URI: http://www.onescreen.com
Donate link: http://www.onescreen.com
Tags: onescreen, one, screen, video, media, social, mediagraph, graph, one-screen, os video, onescreen video, one screen video, toolkit
Requires at least: 3.3 (lowest version plugin will work on)
Tested up to: 3.7.1
Stable tag: 1.4
Version: 1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easy-to-use plugin to manage your OneScreen account within Wordpress and to publish video applications on your WordPress Site

== Description ==

OneScreen (http://www.onescreen.com) provides a network for video stakeholders to connect with each other, a platform to manage video initiatives, and a marketplace to transact.

Our OneScreen Toolkit gives publishers a simple way to embed video players from their OneScreen account into WordPress. Publishers can quickly embed video players using simple shortcodes and deliver interactive video experiences across every screen to engage audiences.  OneScreen video players are fully customizable, compatible across devices and environments, and come with complete monetization support. 

= OneScreen Video Player Features =

* Customizable design to match the look and feel of your brand (size, color, controls, and other configurations)
* Support for responsive web pages
* Cross-device and cross-environment compatibility 
* Social integrations for Facebook and Twitter
* Ad integrations to manage and deliver ads using VAST standards

= Plugin Requirements =

* A OneScreen account.  If you do not have an account, sign up today at http://www.onescreen.com/?network=wp-toolkit
* OneScreen account token

= Coming Soon =

* OneScreen user interface inside the WordPress text editor to easily configure and publish video players in a few simple clicks
* Auto-posting of videos

= More Resources =

To learn more about OneScreen's platform: 

* [OneScreen Toolkit Documentation](http://share.onescreen.co/resources/HelpDocuments/wordpressdoc/onescreentoolkit.html)
* [OneScreen](http://www.onescreen.com)
* [OneScreen Features](http://company.onescreen.com/features/)
* Follow [@OneScreen](http://www.twitter.com/onescreen) for the latest news and updates.

== Installation ==

Installing and Activating the Video Plugin

= To Install =
* Download the plugin and move it to the '/wp-content/plugins' directory OR
* Use WordPress's built-in plugin installer to search for and install the OneScreen Toolkit.  Search for "OneScreen Video" to find the plugin and click "Install Now".

= To Activate = 

Go into the Plugin section and go to "OneScreen Toolkit for WordPress" and click Activate. (refer to Screenshots section)

= To Configure =

Once the plugin is activated, you will need to generate an account token and obtain a default widget(app) ID to finish configuring the plugin. 

To generate an account token follow these steps:

1. Log into your OneScreen account
1. Click "Settings"
1. Click "User Tokens" located in the left navigation bar
1. Generate a token with read access

To find the widget/application ID follow these steps:

1. Log into your OneScreen Account
1. Click the "Publishing Tab"
1. Click "Applications"
1. Choose the application that you want to set as your default player (See the FAQ section to learn more about the use of the default player)
1. Click the "Get Code" button to view the embed code for the player
1. Copy widget ID (See the highlighted portion in the "How to find widget_id" screenshot in the Screenshots section) 

Once you have your OneScreen account token and widget/app ID, to finish configuring the plugin:

1. Click "Settings"
1. Click "OneScreen Account"
1. Enter your OneScreen account token and default widget ID (refer to Screenshots section)

== Frequently Asked Questions ==

= What is OneScreen? =

OneScreen provides a network for video stakeholders to connect with each other, a platform to manage video initiatives, and a marketplace to transact.

Users can log in to the [OneScreen](http://www.onescreen.com) to access a full suite of technologies to manage video.
 
= Where Do I Sign up for a OneScreen Account? =

http://www.onescreen.com/?network=wp-toolkit

= How do I post my Video Player/Application that I created in the OneScreen? = 

Posting a video player is simply done through shortcodes.  

Shortcode to post/embed a specific player application:

`[onescreen widget_id="9438-6b15da674037f0822f2fe6e630ef56e7"]`

Shortcode to post/embed your default player application:  

`[onescreen]`

= Is it possible to override properties in the player application? =

Yes, it is possible.  The following are additional shortcodes to override set properties of the player application:

Override the playlist that is set and play only a single video

`[onescreen item="5075194"]`

Override the width or height of the player:

`[onescreen width="300"]`

`[onescreen height="250"]`

Override the autoPlay setting:

`[onescreen autoPlay="false"]`

Override the default onescreen script

`[onescreen apps_js="2.0"]`

List of currently available properties that can be overridden include: 

* item
* widget_id
* target_div
* custom_render_css
* custom_render_script
* force_html5
* playlist_id
* app_id (replaces widget_id, but still backwards compatible with widget_id)
* playback_priority
* apps_js (either 1.9 or 2.0)

For the full detailed list of integrated player properties, please visit:
[http://share.onescreen.co/resources/HelpDocuments/playerdoc/propertiesandevents.html](http://share.onescreen.co/resources/HelpDocuments/playerdoc/propertiesandevents.html)

For additional shortcode examples, please visit: http://share.onescreen.co/resources/HelpDocuments/wordpressdoc/wordpressplugin.html#embedding

== Screenshots ==

1. Activate plugin
2. Settings page to enter account token and default widget id
3. How to find widget_id

== Changelog ==

= 1.4 =
* No longer a pre-defined list of attributes, shortcode can handle all player properties now including custom ones
* Added a new option for choosing default onescreen script
* Added attribute 'apps_js' (either 1.9 or 2.0, can be used to override default onescreen script)

= 1.3.8 =
* Added 'show_thumbs' attribute
* Updated error handling for onescreen api downtime

= 1.3.7 =
* Fixed playlist id attribute for shared-content accounts

= 1.3.6 =
* Added attribute 'playback_priority' with possible values 'html5,flash' or 'flash,html5', which checks if browser is compatible and uses the appropriate player
* Added versioning in target div

= 1.3.5 =
* Tested with latest version of wordpress (3.5.2)
* Fixed a token issue

= 1.3.4 =
* Plugin URI change
* Readme Updates

= 1.3.3 =
* Added authentication for accounts that have synced playlists but do not have any owned/shared content
* Force enqueue script in footer for WordPress Versions 3.3 and newer
* Token is now defined within shortcode class
* Removed global variable usage
* Fixed companion banner attribute
* Fixed other bugs

= 1.3.2 =
* Added 'app_id' attribute
* Removed load script (load is now called in onescreen.js)

= 1.3.1 =
* Added attribute playlist_id
* WordPress plugin should be submitted to WordPress Plugin Directory SVN for auto updating
* Feature: 'Allows playlist_id' override in the shorcode ( ie. [onescreen widget_id="1232-2..." playlist_id="342309"] )
* Fix:  JavaScript -  No longer exposes com.onescreen.jq  

= 0.5 =
* 1.3.1

== Upgrade Notice ==

= 1.3.3 =
* Please Update

= 1.3.2 =
* Please Update to avoid conflicting JavaScript calls

= 1.3.1 =
* First Official WordPress Upload

