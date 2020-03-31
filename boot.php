<?php

require 'functions.php';
require 'credis/Client.php';
	
// Check if requeste is cachable and has cache.
if ( true == speedmaster_is_cachable() ) {

	$redis = speedmaster_redis_object();
	$key = speedmaster_page_redis_key();

	try {
		@$response = $redis->get($key);

		if ( !empty( $response ) ) {
			echo $response;
			die();
		} else {
			// Start buffering output.
			ob_start('speedmaster_save_cache');
		}

	} catch (Exception $e) { }
}