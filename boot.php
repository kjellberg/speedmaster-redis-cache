<?php
// Prepend a base path if Predis is not available in your "include_path".
require_once 'predis/autoload.php';
Predis\Autoloader::register();

require 'functions.php';

// Check if requeste is cachable and has cache.
if ( true == speedmaster_is_cachable() ) {

	try {
		$redis = speedmaster_redis_object();
		$key = speedmaster_page_redis_key();
		$response = $redis->get($key);

		if ( !empty( $response ) ) {
			echo $response;
			die();
		} else {
			// Start buffering output.
			ob_start('speedmaster_save_cache');
		}

	} catch (Exception $e) { }
}