=== [CR]HelloTXT Extended ===
Contributors: silentwind
Donate link: http://bayu.freelancer.web.id/about/
Tags: publish, hellotxt
Requires at least: 3.0
Tested up to: 3.0.5
Stable tag: 1.0.1

IMPORTANT: YOU CAN USE HELLOTXT INSTEAD OF PING.FM. BECAUSE THEY HAVE NO REAL PROGRESS ON IT.
DOWNLOAD IT HERE: http://bayu.freelancer.web.id/2011/02/16/crpost2pingfm-plugin-ported-to-crhellotxtextended/

== Description ==

**NEW FEATURE IN THIS RELEASE**

IMPORTANT: YOU CAN USE HELLOTXT INSTEAD OF PING.FM. BECAUSE THEY HAVE NO REAL PROGRESS ON IT.
DOWNLOAD IT HERE: http://bayu.freelancer.web.id/2011/02/16/crpost2pingfm-plugin-ported-to-crhellotxtextended/


This is a wordpress plugin that will automatically post an update to [hellotxt](http://www.hellotxt.com/ "hellotxt") for every publish.
Features:

1. Customizable ping message. Right now, there are 10 messages you can set. The plugin will choose randomly.
1. Ping on all categories
1. Ping only on selected categories
1. Disable ping on selected categories
1. Option to allow ping for everytime you hit "publish" button, or just the first publish of that particular post
1. Option to differentiate wording on second publish (based on above option)
1. Ability to set custom ping message for each post. Just look at "HelloTXT Message" panel on "Add New" post page.
1. Ability to set custom ping status for each category.
1. Ability to choose between CURL or FSockOpen connection method.
1. Ability to test which connection method is supported on each server.
1. You can now set custom ping status update for each category
1. You will have the ability to set what connection method you want to use.
1. There is tool to check what connection method is supported on your server.
1. My plugin will retry 10 times in case there's no response from server.


== Installation ==

1. Upload `cr-hellotxt-extended` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to Settings » [CR]HelloTXT.Ext and fill in your USER KEY [User Key](http://hellotxt.com/dashboard/applications/wordpress-plug-in/ "HelloTXT User Key")
1. Don't forget to configure the other settings to ensure the plugin to working properly

== Frequently Asked Questions ==

= What is the minimum PHP requirement? =
Untested on PHP4. I develop it using PHP 5.3

= I can post to WP without problems but I am not seeing the update come into HelloTXT? =
First, make sure you fill in the correct [User Key](http://hellotxt.com/ "HelloTXT User Key"). Then, set the mode to: For all categories (*all*)

= I still don't see any update come to Pingfm  =
Maybe there's something wrong with your server configurations. Note: You need PHP5 and CURL library to make it working properly.

== Screenshots ==

1. This is where you fill in the Application Key. Image courtesy of http://www.dragonblogger.com/
2. This is where you set custom ping message. It's on post creation page.