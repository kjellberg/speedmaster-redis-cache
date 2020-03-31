=== Speedmaster Redis Cache ===
Contributors: rkjellberg
Tags: speedmaster, redis cache, stateless cache
Requires at least: 4.0
Tested up to: 5.3.2
Stable tag: 1.0.0
Requires PHP: 5.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Cache static HTML and store them in Redis instead of the file system on stateless servers.

=== Description ===

Speedmaster Redis Cache is a cache plugin for WordPress that works without using the file system. The plugin buffers all HTML output and stores it in a Redis database. A cached version will load directly from the Redis cache when a visitor visits your page, all without booting up WordPress and connecting to your mySQL-database.

Some use cases are: 
* Docker instances without file storage
* Docku
* Kubernetes

== Frequently Asked Questions ==

= What do I need to use this plugin? =
This plugin requires that Redis and the php-redis module is installed and running on your server to work. It also requires that you know how to update your wp-config.php manually.

= How do I configure this plugin? = 
You'll find a configuration guide in your WordPress Dashboard under "Settings > Speedmaster" after activating this plugin.

= Do I need write access on my server? =
No, you don't need write access for this plugin to work. However, if WordPress can't write to wp-content/ - you'll have to create the advanced-cache.php file manually.

= What content gets cached? =
All HTML-content that is generated from a GET-request will get cached. It's not possible to use dynamic content such as visitor counters, user comments or dynamic widgets when this plugin is activated, unless you call the 'speedmaster_purge_cache'-hook from your plugin/theme to clear cache when data is updated. 

= Will this plugin cache POST-requests? =
Only GET-requests will get cached. POST-requests are ignored and will load the live version of your website.

= Does this plugin work with other optimization plugins? =
Yes, you can use other plugins for speed optimizations like minifying or combining asset files.

= Does it work with any webserver? =
Yes, it works with Apache, Nginx or any other web server running PHP with the redis module version 5.2 or higher.

= How do I install Redis and the php-redis module on my VPS? = 
Run the following commands as root:

$ sudo apt-get install redis-server php-redis
$ sudo service redis start

= How do I find my REDIS_URL? =
By default, your Redis url is: tcp://127.0.0.1:6379, but it may differ depending on your server configuration. Please contact your server administrator if you're unsure.

=== Changelog ===

= 1.0.0 =
Initial release.