<?php namespace s2clean;

if(!defined('WPINC')) // MUST have WordPress.
	exit('Do NOT access this file directly: '.basename(__FILE__));
$theme = theme(); // Theme instance.
?>
</div>

<?php do_action(__NAMESPACE__.'__after_container'); ?>