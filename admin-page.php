<?php
// create custom plugin settings menu
add_action('admin_menu', 'my_cool_plugin_create_menu');

function my_cool_plugin_create_menu() {
	add_submenu_page(
        'options-general.php',
        'Speedmaster Redis Cache', //page title
        'Speedmaster', //menu title
        'administrator', //capability,
        'speedmaster-redis-cache',//menu slug
        'speedmaster_admin_page' //callback function
    );
}

function speedmaster_icon_success() {
	echo '<span class="success">✓</span>';
}

function speedmaster_icon_failed() {
	echo '<span class="failed">×</span>';
}

function speedmaster_admin_test_advanced_cache() {
	$tpl_file = plugin_dir_path(__FILE__) . 'advanced-cache.php-tpl';
	$original_file = trailingslashit(WP_CONTENT_DIR) . 'advanced-cache.php';

	if ( !file_exists($original_file) ) {
		return speedmaster_icon_failed();
	}

	if ( file_get_contents( $tpl_file ) != file_get_contents( $original_file) ) {
		return speedmaster_icon_failed();
	}

	speedmaster_icon_success();
}

function speedmaster_test_redis_connection() {
	if ( speedmaster_redis_connected() ) {
		return speedmaster_icon_success();
	} else {
		return speedmaster_icon_failed();
	}
}

function speedmaster_test_wp_config_cache() {
	if ( !defined('WP_CACHE') ) {
		return speedmaster_icon_failed();
	}

	if ( true !== WP_CACHE ) {
		return speedmaster_icon_failed();
	}

	speedmaster_icon_success();
}

function speedmaster_admin_page() {
	$plugin_data = get_plugin_data( plugin_dir_path(__FILE__) )	;
	$plugin_version = $plugin_data['Version'];
?>
<div class="wrap">
	<h1>Speedmaster Redis Cache</h1>
	<div class="box">
		<a name="configuration"></a>
		<h2>Configuration guide</h2>
		<div class="table">
			<table>
				<tr>
					<td>1.</td>
					<td><h4>Download, install and activate this plugin</h4></td>
					<td><?php speedmaster_icon_success(); ?></td>
				</tr>
				<tr>
					<td>2.</td>
					<td>
						<h4>Place 'advanced-cache.php'-file in your wp-content/-directory</h4>
						This file should be automatically created and placed in the correct folder when you activate this plugin.<br><br>
						If WordPress don't have write access to your wp-content folder, you'll have to manually create the file <code><?php echo WP_CONTENT_DIR; ?>/<strong>advanced-cache.php</strong></code><br> with <strong><u>all</u></strong> of the following content:<br>
						<textarea readonly="readonly" rows="6"><?php echo file_get_contents( plugin_dir_path(__FILE__) . '/advanced-cache.php-tpl'); ?></textarea>
					</td>
					<td><?php speedmaster_admin_test_advanced_cache(); ?></td>
				</tr>
				<tr>
					<td>3.</td>
					<td>
						<h4>Add <code>REDIS_URL</code> to your wp-config.php or as an environment variable.</h4>
						Specify your Redis servers ip and port by adding REDIS_URL to wp-config.php or as an environment variable. Environment variable with override wp-config.php.<br><br>
						<strong>wp-config.php example:</strong> <code>define('REDIS_URL', 'tcp://127.0.0.1:6379');</code><br><br>
						Current REDIS_URL: <br>
						<input type="text" readonly="readonly" value="<?php echo speedmaster_redis_url(); ?>">
					</td>
					<td><?php speedmaster_test_redis_connection(); ?></td>
				</tr>
				<tr>
					<td>4.</td>
					<td>
						<h4>Add <code>WP_CACHE</code> to wp-config.php</h4>
						Activate advanced cache by adding <code>define('WP_CACHE', true);</code> to your wp-config.php file.
					</td>
					<td><?php speedmaster_test_wp_config_cache(); ?></td>
				</tr>
			</table>
		</div>
	</div>
</div>
<style type="text/css">

	.box {
		margin-top: 15px;
		background: #fff;
		padding: 30px;
		border: 1px #aeaeae solid;
		width: 85%;
		max-width:  800px;
	}

	.box table {
		width: 100%;
		margin-top: -30px;
		margin-bottom: -30px;
		line-height: 24px;
	}

	.box table td {
		font-size: 14px;
		padding: 30px 0px;
		border-bottom: 1px #cdcdcd solid;
	}

	.box table td:nth-child(1) {
		width: 30px;
		font-size: 16px;
		vertical-align: top;
	}

	.box table td:nth-child(3) {
		width: 60px;
		font-size: 30px;
		vertical-align: top;
		text-align: center;
	}

	.box table tr:last-child td {
		border-bottom: 0;
	}

	.box table h4 {
		margin: 0;
	}

	.box table input,
	.box table textarea {
		margin-top: 15px;
		width: 100%;
		display: block;
	}

	.status-field {
		width: 30px;
	}

	.success { color: green; }
	.failed { color: red; }

	.box h2 {
		margin: 0;
		margin-bottom: 50px;
	}
</style>
<?php } ?>