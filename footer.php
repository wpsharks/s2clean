<?php namespace s2clean;

if(!defined('WPINC')) // MUST have WordPress.
	exit('Do NOT access this file directly: '.basename(__FILE__));
$theme = theme(); // Theme instance.
?>
<div id="wp-footer" role="presentation">
<?php wp_footer(); ?>
</div>

</body>
</html>