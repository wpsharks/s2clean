<?php namespace s2clean;

if(!defined('WPINC')) // MUST have WordPress.
	exit('Do NOT access this file directly: '.basename(__FILE__));
$theme = theme(); // Theme instance.
?>
<?php do_action(__NAMESPACE__.'__before_header'); ?>

	<header id="header" role="banner">
		<?php echo $theme->header(); ?>
	</header>

<?php do_action(__NAMESPACE__.'__after_header'); ?>