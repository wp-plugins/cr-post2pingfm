=== Plugin Name ===
Contributors: silentwind
Donate link: http://bayu.freelancer.web.id/about/
Tags: publish, ping.fm
Requires at least: 2.7
Tested up to: 2.9.1
Stable tag: 0.8

a wordpress plugin that will automatically post an update to ping.fm for every publish.

== Description ==

a wordpress plugin that will automatically post an update to [ping.fm](http://www.ping.fm/ "ping.fm") for every publish.
Features:

1. Customizable ping message. Right now, there are 10 messages you can set. The plugin will choose randomly.
1. Ping on all categories
1. Ping only on selected categories
1. Disable ping on selected categories
1. Option to allow ping for everytime you hit "publish" button, or just the first publish of that particular post
1. Option to differentiate wording on second publish (based on above option)

== Installation ==

1. Upload `cr-post2pingfm` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to Settings » CR Post2Pingfm and fill in your Ping.fm [Application Key](http://ping.fm/key/ "Ping.fm Application Key")
1. Don't forget to configure the other settings to ensure the plugin to working properly

== Frequently Asked Questions ==

= What is the minimum PHP requirement? =
For the plugin itself, PHP4 is enough. But, PHPingFm class needed PHP5 to run properly. Conclusion? PHP5!

= Why don't you use / create a library that support PHP4  =
Why should I reinvent the whell? It's you or your hosting server that needed an upgrade. PHP4 is dead, move along.

= I can post to WP without problems but I am not seeing the update come into Pingfm? =
First, make sure you fill in the correct [Application Key](http://ping.fm/key/ "Ping.fm Application Key"). Then, set the mode to: For all categories (*all*)

= I still don't see any update come to Pingfm  =
Maybe there's something wrong with your server configurations. Note: You need PHP5 and CURL library to make it working properly.

== Screenshots ==

1. This is where you fill in the Application Key
