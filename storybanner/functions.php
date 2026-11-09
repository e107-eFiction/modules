<?php
// ----------------------------------------------------------------------
// Banner image module: shared helpers for the hook files.
// ----------------------------------------------------------------------

if(!defined("_CHARSET")) exit( );

if(!function_exists("storybanner_clean")) {
	// Returns the banner value that may be stored, or "" for anything else.
	// Accepted: an http:// or https:// URL, or a site-relative path that starts with a
	// single "/" (not "//"). At most 250 characters (the size of the banner columns).
	// No whitespace, quotes, angle brackets, backslashes or control characters.
	function storybanner_clean($value) {
		if(!is_string($value)) return "";
		$value = trim($value);
		if($value === "" || strlen($value) > 250) return "";
		if(preg_match('/[\s"\'<>`\\\\\x00-\x1F\x7F]/', $value)) return "";
		if(preg_match('!^https?://[^/?#]+!i', $value)) return $value;
		if(preg_match('#^/(?!/)#', $value)) return $value;
		return "";
	}

	// Escapes a value for an HTML attribute or text.
	function storybanner_html($value) {
		return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
	}

	// A story or series title as plain text for alt/title attributes.
	function storybanner_title($title) {
		return storybanner_html(strip_tags(stripslashes((string)$title)));
	}
}
