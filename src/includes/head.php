<?php namespace s2clean;

if(!defined('WPINC'))
	exit('Do NOT access this file directly.');
$theme = theme(); // Theme instance.
?>
<head>
<?php wp_head(); // Via hooks.
do_action(__NAMESPACE__.'__head'); ?>
</head>
