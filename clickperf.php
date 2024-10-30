<?php
/*
Plugin Name: Clickperf
Plugin URI: https://www.click-perf.com
Description: Clickperf conversion postback url
Version: 0.1
Author: Clickperf
License: GPL2
*/

function clickperf_cookie($content){
	$key = sanitize_key($_GET['clickid']);
	
	if(!empty($key) && strlen($key) < 30) {
		setcookie('clickperf', $key, time()+31556926, "/");
	}
}

function clickperf_call($content){
	$cookie = sanitize_key($_COOKIE['clickperf']);
	
	if(!empty($cookie)) {
		if (strpos($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],esc_attr(get_option('conv_page_url'))) !== false) {
			$response = wp_remote_get(esc_url_raw("https://www.click-perf.com/postback?clickid=".$cookie));
		}
	}
}

function clickperf_menu() {
	add_menu_page('Clickperf plugin', 'Clickperf', 'administrator', __FILE__, 'clickperf_settings' , 'data:image/svg+xml;base64,' . base64_encode('<svg version="1.1" id="Calque_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="99.21px" height="99.21px" viewBox="0 0 99.21 99.21" enable-background="new 0 0 99.21 99.21" xml:space="preserve">
<path fill="#FFFFFF" d="M90.26,56.48L22.298,0.875c-1.322-1.078-3.268-0.88-4.347,0.442c-0.449,0.55-0.694,1.239-0.695,1.949v92.676
	c-0.001,1.361,0.89,2.564,2.193,2.959c0.291,0.088,0.593,0.131,0.896,0.131c1.033-0.002,1.998-0.56146516,2.57-1.379l23.8-35.691h41.592
	c1.707-0.002,3.088-1.387,3.086-3.094C91.391,57.941,90.977,57.066,90.26,56.48z"/>
</svg>') );

}

function clickperf_plugin_settings() {
	register_setting('clickperf-group', 'conv_page_url');
}

add_action('init', 'clickperf_cookie');
add_action('init', 'clickperf_call');
add_action('admin_menu', 'clickperf_menu');
add_action('admin_init', 'clickperf_plugin_settings');

function clickperf_settings() {
?>
<div class="wrap">
<h1>Clickperf postback url</h1>

<form method="post" action="options.php">
    <?php settings_fields( 'clickperf-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        	<th scope="row">Url de la page de confirmation</th>
        	<td><input type="text" name="conv_page_url" value="<?php echo esc_attr( get_option('conv_page_url') ); ?>" style="width:100%" /></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>