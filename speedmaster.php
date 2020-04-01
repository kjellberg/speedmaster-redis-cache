<?php
/**
 * Plugin Name:       Speedmaster Redis Cache
 * Plugin URI:        https://github.com/aurovrata/ReOrder-posts-within-categories
 * Description:       Cache static HTML and store them in Redis instead of the file system on stateless servers.
 * Version:           1.0.0
 * Author:            Rasmus Kjellberg
 * Author URI:        https://www.rasmuskjellberg.se
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

// Prepend a base path if Predis is not available in your "include_path".
require_once 'predis/autoload.php';
Predis\Autoloader::register();

require_once( 'functions.php' );
require_once( '3dparty-integrations.php' );
require_once( 'admin-page.php' );

register_activation_hook( __FILE__, 'speedmaster_install' );
register_deactivation_hook( __FILE__, 'speedmaster_uninstall' );

if ( ! defined('SPEEDMASTER_ADVANCED_CACHE') ) {
	add_action( 'admin_notices', 'speedmaster_admin_notice_not_configured');
} 

add_action('admin_init', function() {
	if ( ! speedmaster_redis_connected() ) {
		add_action( 'admin_notices', 'speedmaster_admin_notice_no_redis_connection');
	} 
});

add_action('speedmaster_purge_cache', function() {
	speedmaster_purge_all_cache();
});