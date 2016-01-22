<?php namespace s2clean;

if(!defined('WPINC')) // MUST have WordPress.
	exit('Do NOT access this file directly: '.basename(__FILE__));
$theme = theme(); // Theme instance.
?>
<?php
if(is_singular() && post_count_gt0())
	the_post(); // Setup post data.

$part = $theme->part(); // Cache.

get_header($part); // Header template.

get_template_part('src/includes/navbar', $part);
get_template_part('src/includes/container-o', $part);
get_template_part('src/includes/header', $part);

if(is_singular() || is_404() || !post_count_gt0())
	get_template_part('src/includes/content', $part);
else get_template_part('src/includes/excerpts', $part);

get_template_part('src/includes/alert', $part);
get_template_part('src/includes/confirm', $part);

get_template_part('src/includes/footer', $part);
get_template_part('src/includes/comments', $part);
get_template_part('src/includes/container-c', $part);
get_template_part('src/includes/sidebar', $part);
get_template_part('src/includes/sharebar', $part);
get_template_part('src/includes/footbar', $part);

get_footer($part); // Footer template.
