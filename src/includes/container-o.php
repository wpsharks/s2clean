<?php namespace s2clean;

if(!defined('WPINC')) // MUST have WordPress.
	exit('Do NOT access this file directly: '.basename(__FILE__));
$theme = theme(); // Theme instance.
?>
<?php do_action(__NAMESPACE__.'__before_container'); ?>

<div id="container" class="container<?php echo ($theme->is_fluid()) ? '-fluid' : ''; ?>" role="main">
