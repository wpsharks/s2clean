<?php namespace s2clean;

if(!defined('WPINC')) // MUST have WordPress.
	exit('Do NOT access this file directly: '.basename(__FILE__));
$theme = theme(); // Theme instance.
?>
<?php comments_template('/src/includes/comments-tpl.php'); ?>
