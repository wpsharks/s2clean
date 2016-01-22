<?php
namespace s2clean // Root namespace.
{
	if(!defined('WPINC')) // MUST have WordPress.
		exit('Do NOT access this file directly: '.basename(__FILE__));

	class comwalker extends \Walker_Comment // Bootstrap compatibility.
	{
		protected function ping($comment, $depth, $args)
		{
			return $this->comment($comment, $depth, $args);
		}

		protected function html5_comment($comment, $depth, $args)
		{
			return $this->comment($comment, $depth, $args);
		}

		protected function comment($comment, $depth, $args)
		{
			$theme = theme(); // Reference to theme instance.

			if(!empty($args['style']) && $args['style'] === 'div') // List items w/ Bootstrap.
				throw new \exception(__('Div-style comments are currently unsupported by your theme.', $theme->text_domain));

			$ID         = get_comment_ID();
			$link       = get_comment_link($ID);
			$author_url = get_comment_author_url();
			$type       = get_comment_type();

			$is_comment             = ($type === 'comment');
			$is_reply               = ($is_comment && $comment->comment_parent);
			$is_ping                = ($type === 'pingback' || $type === 'trackback');
			$is_awaiting_moderation = (wp_get_comment_status($ID) !== 'approved');
			$is_by_post_author      = $comment->user_id && (integer)$comment->user_id === (integer)get_the_author_meta('ID');

			$classes     = implode(' ', get_comment_class((!empty($args['has_children'])) ? 'parent' : ''));
			$date_time   = sprintf(__('%1$s @ %2$s', $theme->text_domain), get_comment_date('M jS, Y'), get_comment_time('g:i a'));
			$share_title = sprintf(__('%1$s by: %2$s — %3$s', $theme->text_domain), ucfirst($type), get_comment_author(), $date_time);

			echo '<li id="comment-'.esc_attr($ID).'" class="'.esc_attr($classes).' media">'."\n";

			if($is_comment) // Comments only; NOT for pings.
			{
				if(get_option('show_avatars') && $args['avatar_size'] !== 0)
					echo '<div class="avatar media-object pull-left hidden-sm hidden-xs">'.
					     (($author_url) ? '<a href="'.esc_attr($author_url).'" rel="external nofollow">' : '').
					     str_replace(array('class="avatar', 'class=\'avatar'), // Both quote variations.
					                 array('class="img-thumbnail avatar', 'class=\'img-thumbnail avatar'),
					                 get_avatar($comment, $args['avatar_size'])).
					     (($author_url) ? '</a>' : '').
					     '</div>'."\n";
			}
			echo '<div class="entry media-body no-x-overflow">'."\n";

			if($is_comment || $is_ping) // This section for both types; e.g. comments and pings.
			{
				echo '<div class="meta btn-group btn-group-xs pull-right l-margin font-70 hidden-print">'."\n";
				echo '<a href="'.esc_attr($link).'" title="'.esc_attr('#comment-'.$ID.' ('.$date_time.')').'" class="btn btn-default hidden-sm hidden-xs"><i class="fa fa-calendar"></i> '.get_comment_date('M jS, Y').' <i class="fa fa-anchor"></i></a>'."\n";
				echo '</div>'."\n";
			}
			if(($is_comment || $is_ping) && current_user_can('edit_comment', $ID)) // This section for both types; e.g. comments and pings.
			{
				echo '<div class="edit btn-group btn-group-xs pull-right l-margin font-70 hidden-print">'."\n";
				echo '<a href="'.esc_attr(get_edit_comment_link($ID)).'" title="'.esc_attr(__('Edit', $theme->text_domain)).'" class="btn btn-default"><i class="fa fa-edit"></i></a>'."\n";
				echo '</div>'."\n";
			}
			if($is_comment) // This section for comments only; NOT for pings.
			{
				echo '<div class="share btn-group btn-group-xs pull-right l-margin font-70 hidden-xs hidden-print" title="'.esc_attr(sprintf(__('Share #comment-%1$s', $theme->text_domain), $ID)).'">'."\n";
				echo '<a href="#" data-toggle="share" data-title="'.esc_attr($share_title).'" data-url="'.esc_attr($link).'" data-service="facebook" class="btn btn-default"><i class="fa fa-facebook-square"></i></a>'."\n";
				echo '<a href="#" data-toggle="share" data-title="'.esc_attr($share_title).'" data-url="'.esc_attr($link).'" data-service="twitter" class="btn btn-default"><i class="fa fa-twitter"></i></a>'."\n";
				echo '<a href="#" data-toggle="share" data-title="'.esc_attr($share_title).'" data-url="'.esc_attr($link).'" data-service="google_plus" class="btn btn-default"><i class="fa fa-google-plus"></i></a>'."\n";
				echo '</div>'."\n";
			}
			if($is_comment) // This section for comments only; NOT for pings.
			{
				echo '<div class="reply-tools btn-group btn-group-xs pull-right l-margin font-70 hidden-print">'."\n";

				if(!is_user_logged_in() && theme()->options['navbar_login_box_enable'])
					echo '<a href="#login-box" data-toggle="modal" class="btn btn-default btn-xs">'.
					     '<i class="fa fa-plus-square"></i> '.__('Reply', $theme->text_domain).'</a>'."\n";

				else echo str_replace( // Replace both quote variations.
					          array('class="comment-reply', 'class=\'comment-reply', 'class=\'comment-reply'),
					          array('class="btn btn-default btn-xs comment-reply', 'class=\'btn btn-default btn-xs comment-reply'),

					          get_comment_reply_link(array_merge($args, array('depth'      => $depth, 'max_depth' => $args['max_depth'],
					                                                          'reply_text' => '<i class="fa fa-plus-square"></i> '.__('Reply', $theme->text_domain),
					                                                          'login_text' => '<i class="fa fa-plus-square"></i> '.__('Reply', $theme->text_domain))))
				          )."\n";
				echo '</div>'."\n";
			}
			if($is_comment) // This section for comments only; NOT for pings.
			{
				echo '<h4 title="'.esc_attr(get_comment_author().': '.$date_time).'" class="author media-heading b-margin-sm text-ellipsis">'."\n";
				echo '<cite>'.($is_by_post_author ? '<small>⧼</small> <em class="font-90">'.__('Post Author', $theme->text_domain).'</em> <small>⧽</small> ' : '').
				     get_comment_author_link().'</cite> <em class="says translucent font-80">'.__('writes:', $theme->text_domain).'</em>'."\n";
				echo '</h4>'."\n";
			}
			if($is_comment || $is_ping) // This section for both types; e.g. comments and pings.
			{
				echo '<div class="data clearfix">'."\n";
				if($is_ping) // Pingbacks and trackbacks are abbreviated considerably.
					echo '<p><i class="fa fa-bullhorn"></i> <strong>'.ucwords($type).':</strong> '.get_comment_author_link().'</p>';
				else if($is_comment && $is_awaiting_moderation) // Awaiting moderation by site owner.
					echo '<p><em>'.__('Your comment is awaiting moderation.', $theme->text_domain).'</em></p>'."\n";
				else comment_text(); // In support of native WordPress filters.
				echo '</div>'."\n";
			}
			echo '</div>'."\n"; // Closing `<div class="media-body">` tag.

			echo '<div class="clear"></div>'."\n"; // Clear before possible `<ul class="children">`.

			// Closing `</li>` tag is automatically added by WordPress core methods.
		}

		public function start_lvl(&$output, $depth = 0, $args = array())
		{
			$GLOBALS['comment_depth'] = $depth + 1;
			$l_margin                 = $l_padding = round($args['avatar_size'] / 2);
			$style                    = 'style="margin-left:'.$l_margin.'px; padding-left:'.$l_padding.'px;"';

			switch($args['style'])
			{
				case 'ol':
					$output .= '<ol class="children" '.$style.'>'."\n";
					break;

				case 'ul':
				default: // Default handler.
					$output .= '<ul class="children" '.$style.'>'."\n";
					break;
			}
		}
	}
}