<?php
/**
 * Autoptimze
 * Purge speedmaster cache when Autoptimize cache is deleted
 *
 * Plugin URL: https://wordpress.org/plugins/autoptimize/
 */
add_action( 'autoptimize_action_cachepurged', 'speedmaster_purge_all_cache' );