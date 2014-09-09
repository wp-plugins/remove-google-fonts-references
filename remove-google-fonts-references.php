<?php
/*
Plugin Name: Remove Google Fonts References
Plugin URI: http://www.brunoxu.com/remove-google-fonts-references.html
Description: Remove Open Sans and other google fonts references from all pages.
Author: Bruno Xu
Author URI: http://www.brunoxu.com/
Version: 1.2
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

function gf_is_login_page() {
	return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
}

if (is_admin()) {
	//$action = 'admin_head'; // NG
	$action = 'admin_init'; // OK
	//$action = 'wp'; // NG
} elseif (gf_is_login_page()) {
	//$action = 'wp'; // NG
	//$action = 'init'; // OK
	$action = 'wp_loaded'; // OK
} else {
	$action = 'get_header';
}

add_action($action, 'remove_google_fonts_obstart');
function remove_google_fonts_obstart() {
	ob_start('remove_google_fonts_obend');
}

function remove_google_fonts_obend($content) {
	return remove_google_fonts_filter($content);
}

function remove_google_fonts_filter($content)
{
	/*
	<link rel="stylesheet" id="open-sans-css" href="//fonts.googleapis.com/css?family=Open+Sans%3A300italic%2C400italic%2C600italic%2C300%2C400%2C600&amp;subset=latin%2Clatin-ext&amp;ver=3.9.2" type="text/css" media="all">
	*/
	$regexp = "/<link([^<>]+)>/i";
	$content = preg_replace_callback(
		$regexp,
		"remove_google_fonts_str_handler",
		$content
	);

	/*
	@import url(http://fonts.googleapis.com/css?family=Roboto+Condensed:regular);
	@import url(http://fonts.googleapis.com/css?family=Merriweather:300,300italic,700,700italic);
	*/
	$regexp = "/@import\s+url\([^\(\)]+\);?/i";
	$content = preg_replace_callback(
		$regexp,
		"remove_google_fonts_str_handler",
		$content
	);

	return $content;
}

function remove_google_fonts_str_handler($matches)
{
	$str = $matches[0];

	if (! preg_match("/\/\/fonts.googleapis.com\//i", $str)) {
		return $str;
	} else {
		return '';
	}
}
