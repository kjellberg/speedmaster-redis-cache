<?php
/*
 * Is cachable request?
 * Check if response is cachable
 *
 */
function speedmaster_is_cachable() {
	if ('GET' !== $_SERVER['REQUEST_METHOD']) return false;
	if (function_exists('is_user_logged_in') and is_user_logged_in()) return false;
	if (preg_match("/wp-admin/i", $_SERVER['REQUEST_URI'])) return false;
    if (preg_match("/\.txt/i", $_SERVER['REQUEST_URI'])) return false;
    if (preg_match("/\.xml/i", $_SERVER['REQUEST_URI'])) return false;

    if (count($_COOKIE)) {
      foreach ($_COOKIE as $key => $val) {
        if (preg_match("/wordpress_logged_in/i", $key)) return false;
      }
    }

	return true;
}

/*
 * Speedmaster Redis Object
 * Returns a Redis connection
 *
 */
function speedmaster_redis_object() {
	$redis_url = speedmaster_redis_url();
	return new Credis_Client($redis_url);
}

/**
 * Redis connected
 * Check if Redis is available and connectable
 */
function speedmaster_redis_connected()
{ 
	try {
		@$redis = speedmaster_redis_object();
		@$redis->ping();
		return true;
	} catch (Exception $e) {
		return false;
	}
}

/*
 * Redis URL
 * Get Redis URL from environment variable or defined constant.
 *
 */
function speedmaster_redis_url() {
	if (false !== getenv('REDIS_URL') ) {
		return getenv('REDIS_URL');
	} else if ( defined('REDIS_URL') ) {
		return REDIS_URL;
	}

	return null;
}

/*
 * Page redis key
 * Generate a redis key from URL
 *
 */
function speedmaster_page_redis_key() {
	return "sm-cache_" . $_SERVER['REQUEST_URI'];
}

/**
 * Save cache
 * Save buffered HTML to redis cache storage;
 *
 */
function speedmaster_save_cache( $buffer ) {
	$redis = speedmaster_redis_object();
	$timestamp = date("Y-m-d H:i:s");

	$html = $buffer;

	$cached_html = str_replace('<html', "<!-- Cached by Speedmaster at $timestamp--><html", $buffer);
	@$redis->set( speedmaster_page_redis_key(), $cached_html );

	return $html;	
}

/**
 * Purge all cache
 * Purge all cache by deleting Redis data
 *
 */
function speedmaster_purge_all_cache() {

	$redis = speedmaster_redis_object();

	// Find all Speedmaster keys..
	$keys = $redis->keys('sm-cache_*');

	// .. and delete all of them.
	foreach ($keys as $key) {
		$redis->del($key);
	}
}

/**
 * Speedmaster install
 * Try to create advanced-cache.php file. This script will run on plugin activation.
 *
 */
function speedmaster_install() {
	$advanced_cache_tpl_file_path = plugin_dir_path(__FILE__) . 'advanced-cache.php-tpl';
	$advanced_cache_path = trailingslashit(WP_CONTENT_DIR) . 'advanced-cache.php';

	// Create file if it does not exist.
	@touch($file_path, 0655, true);

	// Copy data from template file.
	@file_put_contents($advanced_cache_path, file_get_contents($advanced_cache_tpl_file_path) );
}

/**
 * Speedmaster uninstall
 * Delete advanced-cache.php file. This script will run on plugin deactivation.
 */
function speedmaster_uninstall() {
	$advanced_cache_path = trailingslashit(WP_CONTENT_DIR) . 'advanced-cache.php';

	if (defined('SPEEDMASTER_ADVANCED_CACHE')) {
		@unlink($advanced_cache_path);
	}
}

/*
 * Not configured alert
 * Show an error message in dashboard if plugin is not configured correctly.
 *
 */
function speedmaster_admin_notice_not_configured() {
    $class = 'notice notice-error';
    $message = __( 'Not configured correctly. Please check the <a href="options-general.php?page=speedmaster-redis-cache#configuration">configration guide</a>', 'speedmaster' );

    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), "<strong>Speedmaster:</strong> " . $message ); 
}

/*
 * No Redis connection alert
 * Show an error message in dashboard if Redis isn't connected.
 *
 */
function speedmaster_admin_notice_no_redis_connection() {
    $class = 'notice notice-error';
    $message = __( 'Could not connect to Redis server. Please check your configuration.', 'speedmaster' );

    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), "<strong>Speedmaster:</strong> " . $message ); 
}