<?php namespace s2clean;

if(!defined('WPINC')) // MUST have WordPress.
	exit('Do NOT access this file directly: '.basename(__FILE__));
$theme = theme(); // Theme instance.
?>
<head>
<?php wp_head(); // Via hooks.
do_action(__NAMESPACE__.'__head'); ?>
</head>