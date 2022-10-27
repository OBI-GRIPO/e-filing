=== gsislogin ===
Contributors: sl45sms
Stable tag: trunk
Tags: Oauth 2.0, gsis, Authorization, Greek Goverment
Requires at least: 4.9.5
Tested up to: 4.9.5
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Use oauth 2.0 from the GSIS site.

== Description ==
With this plugin you can use oauth 2.0 from the Greek Goverment GSIS site to authenticate and register users


== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/gsislogin` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Settings->gsislogin settings screen to set the provided usename and password from gsis
1. Create a login link on your page that pointing to http(s)://YOURSITENAME/gsis
1. You can catch return errors from your theme by check if the url query field 'login' have the value 'failed', and then display the value of field 'reason'
   e.q., http(s)://YOURSITENAME/gsis?login=failed&reason=You+chose+not+to+proceed+to+login+from+the+systems+of+the+Greek+General+Secretariat+for+Information+Systems.


