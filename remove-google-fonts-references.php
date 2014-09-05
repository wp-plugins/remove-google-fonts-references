<?php
/*
Plugin Name: Remove Google Fonts References
Plugin URI: http://www.brunoxu.com/remove-google-fonts-references.html
Description: Remove Open Sans and other google fonts references from all pages.
Author: Bruno Xu
Author URI: http://www.brunoxu.com/
Version: 1.1
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

function gf_is_login_page() {
	return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
}

if (is_admin()) {
	$action = 'admin_init';
} elseif (gf_is_login_page()) {
	$action = 'init'; // OK
	//$action = 'wp'; // NG
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
	$regexp = "/<link([^<>]*)>/i";

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
