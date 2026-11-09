<?php
// ----------------------------------------------------------------------
// Story End module: shared helpers for storyend.php and admin.php.
// ----------------------------------------------------------------------

if(!defined("_CHARSET")) exit( );

if(!function_exists("storyend_options")) {
	// The three display options, each 0 or 1. A missing option is on.
	function storyend_option_keys( ) {
		return array("display_authorinfo", "display_favouritesinfo", "display_seriesinfo");
	}

	// Keeps only the three known keys and casts each value to 0 or 1; missing keys are on (1).
	function storyend_clean_options($values) {
		$options = array( );
		foreach(storyend_option_keys( ) as $key) {
			$options[$key] = (is_array($values) && array_key_exists($key, $values)) ? (empty($values[$key]) ? 0 : 1) : 1;
		}
		return $options;
	}

	// Reads the value of the fanfiction_settings.storyend column.
	// JSON (written since 1.8) or legacy serialize() data (1.4 - 1.7, starts with "a:").
	// An empty, missing or unreadable value means "all options on".
	function storyend_options($raw) {
		$values = null;
		if(is_string($raw) && $raw !== "") {
			if(substr($raw, 0, 2) == "a:") $values = @unserialize($raw, array("allowed_classes" => false));
			else $values = json_decode($raw, true);
		}
		if(!is_array($values)) $values = array( );
		return storyend_clean_options($values);
	}

	// Escapes a value for HTML text or attributes.
	function storyend_html($value) {
		return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
	}
}
