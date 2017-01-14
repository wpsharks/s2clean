<?php
namespace s2clean // Root namespace.
{
	if(!defined('WPINC'))
		exit('Do NOT access this file directly.');

	class navwalker extends \Walker_Nav_Menu // Bootstrap compatibility.
	{
		public function start_lvl(&$output, $depth = 0, $args = array())
		{
			$indent = ($depth) ? str_repeat("\t", $depth) : '';
			$output .= "\n".$indent.'<ul class="dropdown-menu" role="menu">'."\n";
		}

		public function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output)
		{
			if(!$element) return; // Nothing to do in this case.

			if(isset($args[0]) && is_object($args[0])) // Handle objects too :-)
				$args[0]->has_children = !empty($children_elements[$element->{$this->db_fields['id']}]);

			parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
		}

		public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
		{
			$theme = theme(); // Theme instance.

			$indent = ($depth) ? str_repeat("\t", $depth) : '';
			$args   = (object)$args; // Force object type.

			if($depth > 0 && $item->{__NAMESPACE__.'_stype'} === 'divider')
				$output .= $indent.'<li role="presentation" class="divider">';

			else if($depth > 0 && $item->{__NAMESPACE__.'_stype'} === 'dropdown-header')
				$output .= $indent.'<li role="presentation" class="dropdown-header">'.
				           apply_filters('the_title', $item->title, $item->ID);

			else if($item->{__NAMESPACE__.'_stype'} === 'disabled')
				$output .= $indent.'<li role="presentation" class="disabled">'.
				           '<a href="#">'.apply_filters('the_title', $item->title, $item->ID).'</a>';

			else // Handle it like any other menu item; e.g. NOT a special sub-type.
			{
				$_li['id'] = apply_filters( // Support native WP filters.
					'nav_menu_item_id', 'menu-item-'.$item->ID, $item, $args);

				$_li['classes'] = array($_li['id']); // Initialize classes.
				if(!empty($item->classes)) // Include item classes; if available.
					$_li['classes'] = array_merge($_li['classes'], (array)$item->classes);

				if($args->has_children) $_li['classes'][] = 'dropdown';
				if($item->{__NAMESPACE__.'_icon_only'}) $_li['classes'][] = 'only-icon';
				if(in_array('current-menu-item', $_li['classes'])) $_li['classes'][] = 'active';

				$_li['classes'] = apply_filters('nav_menu_css_class', $_li['classes'], $item, $args);
				$_li['classes'] = array_filter($_li['classes']); // Remove empty values.

				$_li['id']      = ($_li['id']) ? ' id="'.esc_attr($_li['id']).'"' : ''; // Convert these.
				$_li['classes'] = ($_li['classes']) ? ' class="'.esc_attr(implode(' ', $_li['classes'])).'"' : '';

				$output .= $indent.'<li'.$_li['id'].$_li['classes'].'>'; // Construct <li>.
				unset($_li); // Just a little housekeeping; we're done building the <li> tag now.

				$do_url_replacements  = function ($url) use ($theme)
				{
					$url = str_ireplace('%%seo_title%%', urlencode($theme->seo_title()), $url);
					$url = str_ireplace('%%current_url%%', urlencode($theme->current_url()), $url);
					return $url; // Return URL. Yep, always a good idea. haha
				};
				$_a['attr']['href']   = $do_url_replacements($item->url);
				$_a['attr']['title']  = $item->attr_title;
				$_a['attr']['target'] = $item->target;
				$_a['attr']['rel']    = $item->xfn;

				if($args->has_children) // Bootstrap (parent).
				{
					$_a['attr']['href']        = '#';
					$_a['attr']['data-toggle'] = 'dropdown';
					$_a['attr']['class']       = 'dropdown-toggle';
				}
				$_a['attr'] = apply_filters('nav_menu_link_attributes', $_a['attr'], $item, $args);

				$_a['attributes'] = ''; // Initialize attributes.

				foreach($_a['attr'] as $_attr => $_value)
					if($_value) $_a['attributes'] .= ' '.$_attr.'="'.esc_attr($_value).'"';
				unset($_a['attr'], $_attr, $_value); // Housekeeping.

				$_a['tag'] = $args->before; // Custom HTML before.

				if($item->{__NAMESPACE__.'_icon'} && (strpos($item->{__NAMESPACE__.'_icon'}, 'glyphicon-') === 0 || strpos($item->{__NAMESPACE__.'_icon'}, 'fa-') === 0))

					$_a['tag'] .= '<a'.$_a['attributes']. // There are color options for both the label and the icon.
					              ($item->{__NAMESPACE__.'_label_color'} ? ' style="color:'.esc_attr($item->{__NAMESPACE__.'_label_color'}).';"' : '').

					              ($item->{__NAMESPACE__.'_label_hover_color'} || $item->{__NAMESPACE__.'_icon_hover_color'}
						              ? ' onmouseout="'.
						                ($item->{__NAMESPACE__.'_label_hover_color'} ? ' '.esc_attr('this.style.color = \''.$item->{__NAMESPACE__.'_label_color'}.'\';') : '').
						                ($item->{__NAMESPACE__.'_icon_hover_color'} ? ' '.esc_attr('this.childNodes[0].style.color = \''.$item->{__NAMESPACE__.'_icon_color'}.'\';') : '').
						                '"'.

						                ' onmouseover="'.
						                ($item->{__NAMESPACE__.'_label_hover_color'} ? ' '.esc_attr('this.style.color = \''.$item->{__NAMESPACE__.'_label_hover_color'}.'\';') : '').
						                ($item->{__NAMESPACE__.'_icon_hover_color'} ? ' '.esc_attr('this.childNodes[0].style.color = \''.$item->{__NAMESPACE__.'_icon_hover_color'}.'\';') : '').
						                '"'
						              : '').
					              '>'.
					              '<i class="'.(strpos($item->{__NAMESPACE__.'_icon'}, 'glyphicon-') === 0 ? 'glyphicon' : 'fa').' '.esc_attr($item->{__NAMESPACE__.'_icon'}).'"'.
					              ($item->{__NAMESPACE__.'_icon_color'} ? ' style="color:'.esc_attr($item->{__NAMESPACE__.'_icon_color'}).';"' : '').'></i>'.
					              ($item->{__NAMESPACE__.'_icon_only'} ? '' : '&nbsp;');

				else $_a['tag'] .= '<a'.$_a['attributes']. // There are color options for both the label and the icon.
				                   ($item->{__NAMESPACE__.'_label_color'} ? ' style="color:'.esc_attr($item->{__NAMESPACE__.'_label_color'}).';"' : '').

				                   ($item->{__NAMESPACE__.'_label_hover_color'}
					                   ? ' onmouseout="'.($item->{__NAMESPACE__.'_label_hover_color'} ? ' '.esc_attr('this.style.color = \''.$item->{__NAMESPACE__.'_label_color'}.'\';') : '').'"'.
					                     ' onmouseover="'.($item->{__NAMESPACE__.'_label_hover_color'} ? ' '.esc_attr('this.style.color = \''.$item->{__NAMESPACE__.'_label_hover_color'}.'\';') : '').'"'
					                   : '').
				                   '>';

				if(!$item->{__NAMESPACE__.'_icon_only'})
					$_a['tag'] .= $args->link_before. // Custom HTML before.
					              apply_filters('the_title', $item->title, $item->ID).
					              $args->link_after; // And, custom HTML after.

				if($args->has_children) // Bootstrap.
					$_a['tag'] .= ' <span class="caret"></span>';

				$_a['tag'] .= '</a>'; // Close anchor tag (always).
				$_a['tag'] .= $args->after; // Custom HTML after.

				$output .= apply_filters('walker_nav_menu_start_el', $_a['tag'], $item, $depth, $args);
				unset($_a); // Just a little housekeeping; we're done building the <a> tag now.
			}
		}
	}

	add_filter('wp_get_nav_menu_items', function ($menu_items) // Conditionals.
	{
		$excluded_menu_items = array(); // Initialize; we'll build this array below.

		if(!is_admin()) foreach($menu_items as $_key => $_menu_item) // Iterate all menu items.
		{
			if($_menu_item->menu_item_parent && isset($excluded_menu_items[$_menu_item->menu_item_parent]))
			{
				$excluded_menu_items[$_menu_item->ID] = $_menu_item->ID;
				unset($menu_items[$_key]); // Exclude children of excluded parents.
			}
			else if($_menu_item->{__NAMESPACE__.'_logic'} && !eval('return ('.$_menu_item->{__NAMESPACE__.'_logic'}.');'))
			{
				$excluded_menu_items[$_menu_item->ID] = $_menu_item->ID;
				unset($menu_items[$_key]); // Exclude.
			}
		}
		return $menu_items; // After exclusions.

	}, 10, 1); // End conditional logic w/ 1 argument.

	add_filter('wp_setup_nav_menu_item', function ($menu_item) // Include these additional properties.
	{
		$menu_item->{__NAMESPACE__.'_stype'}             = get_post_meta($menu_item->ID, '_menu_item_'.__NAMESPACE__.'_stype', TRUE);
		$menu_item->{__NAMESPACE__.'_icon'}              = get_post_meta($menu_item->ID, '_menu_item_'.__NAMESPACE__.'_icon', TRUE);
		$menu_item->{__NAMESPACE__.'_icon_color'}        = get_post_meta($menu_item->ID, '_menu_item_'.__NAMESPACE__.'_icon_color', TRUE);
		$menu_item->{__NAMESPACE__.'_icon_hover_color'}  = get_post_meta($menu_item->ID, '_menu_item_'.__NAMESPACE__.'_icon_hover_color', TRUE);
		$menu_item->{__NAMESPACE__.'_icon_only'}         = get_post_meta($menu_item->ID, '_menu_item_'.__NAMESPACE__.'_icon_only', TRUE);
		$menu_item->{__NAMESPACE__.'_label_color'}       = get_post_meta($menu_item->ID, '_menu_item_'.__NAMESPACE__.'_label_color', TRUE);
		$menu_item->{__NAMESPACE__.'_label_hover_color'} = get_post_meta($menu_item->ID, '_menu_item_'.__NAMESPACE__.'_label_hover_color', TRUE);
		$menu_item->{__NAMESPACE__.'_logic'}             = get_post_meta($menu_item->ID, '_menu_item_'.__NAMESPACE__.'_logic', TRUE);

		return $menu_item; // With properties.

	}, 10, 1); // End properties setup w/ 1 argument.

	/***********************************************************************************************************************************/

	if(!is_admin()) return; // The routines below apply only to administrative areas.

	/***********************************************************************************************************************************/

	require_once ABSPATH.'wp-admin/includes/nav-menu.php'; // Need this base class now.

	class navwalker_editor extends \Walker_Nav_Menu_Edit // Supports additional configurations.
	{
		function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
		{
			$theme = theme(); // Theme instance.

			parent::start_el($output, $item, $depth, $args, $id);

			$regex_before              = '/\<p\s+class\="field\-link\-target\s+description"\>'.
			                             '\s*\<label\s+for\="edit\-menu\-item\-target\-'.preg_quote($item->ID).'"/i';
			$additional_config_options = // Additional configuration options.

				'<p class="description description-thin">'.
				'  <label for="edit-menu-item-'.esc_attr(__NAMESPACE__).'-stype-'.esc_attr($item->ID).'">'.
				'     '.__('Special Sub-Type', $theme->text_domain).'<br />'.
				'     <select id="edit-menu-item-'.esc_attr(__NAMESPACE__).'-stype-'.esc_attr($item->ID).'" name="menu-item-'.esc_attr(__NAMESPACE__).'-stype['.esc_attr($item->ID).']" class="widefat" />'.
				'        <option value=""'.selected('', $item->{__NAMESPACE__.'_stype'}, FALSE).'>'.__('N/A', $theme->text_domain).'</option>'.
				'        <option value="divider"'.selected('divider', $item->{__NAMESPACE__.'_stype'}, FALSE).'>'.__('Divider', $theme->text_domain).'</option>'.
				'        <option value="dropdown-header"'.selected('dropdown-header', $item->{__NAMESPACE__.'_stype'}, FALSE).'>'.__('Dropdown Header', $theme->text_domain).'</option>'.
				'        <option value="disabled"'.selected('disabled', $item->{__NAMESPACE__.'_stype'}, FALSE).'>'.__('Disabled Item', $theme->text_domain).'</option>'.
				'     </select>'.
				'  </label>'.
				'</p>'.
				'<p class="description description-thin">'.
				'  <label for="edit-menu-item-'.esc_attr(__NAMESPACE__).'-icon-only-'.esc_attr($item->ID).'" style="display:inline-block; float:right; text-align:right; font-size:80%;">'.
				'     <span title="'.__('Icon Only e.g. Hide Label?', $theme->text_domain).'">'.__('Icon Only', $theme->text_domain).'</span> <input type="checkbox" id="edit-menu-item-'.esc_attr(__NAMESPACE__).'-icon-only-'.esc_attr($item->ID).'" name="menu-item-'.esc_attr(__NAMESPACE__).'-icon-only['.esc_attr($item->ID).']" value="1"'.checked('1', $item->{__NAMESPACE__.'_icon_only'}, FALSE).' />'.
				'  </label>'.

				'  <label for="edit-menu-item-'.esc_attr(__NAMESPACE__).'-icon-'.esc_attr($item->ID).'">'.
				'     '.__('Icon Class [<a href="http://fontawesome.io/icons/" target="_blank">list</a>]', $theme->text_domain).'<br />'.
				'     <input type="text" id="edit-menu-item-'.esc_attr(__NAMESPACE__).'-icon-'.esc_attr($item->ID).'" name="menu-item-'.esc_attr(__NAMESPACE__).'-icon['.esc_attr($item->ID).']" value="'.esc_attr($item->{__NAMESPACE__.'_icon'}).'" class="widefat code" />'.
				'  </label>'.
				'</p>'.

				'<p class="description description-thin">'.
				'  <label for="edit-menu-item-'.esc_attr(__NAMESPACE__).'-icon-color-'.esc_attr($item->ID).'">'.
				'     '.__('Icon Color', $theme->text_domain).'<br />'.
				'     <input type="text" id="edit-menu-item-'.esc_attr(__NAMESPACE__).'-icon-color-'.esc_attr($item->ID).'" name="menu-item-'.esc_attr(__NAMESPACE__).'-icon-color['.esc_attr($item->ID).']" value="'.esc_attr($item->{__NAMESPACE__.'_icon_color'}).'" class="widefat code" />'.
				'  </label>'.
				'</p>'.
				'<p class="description description-thin">'.
				'  <label for="edit-menu-item-'.esc_attr(__NAMESPACE__).'-icon-hover-color-'.esc_attr($item->ID).'">'.
				'     '.__('Icon Hover Color', $theme->text_domain).'<br />'.
				'     <input type="text" id="edit-menu-item-'.esc_attr(__NAMESPACE__).'-icon-hover-color-'.esc_attr($item->ID).'" name="menu-item-'.esc_attr(__NAMESPACE__).'-icon-hover-color['.esc_attr($item->ID).']" value="'.esc_attr($item->{__NAMESPACE__.'_icon_hover_color'}).'" class="widefat code" />'.
				'  </label>'.
				'</p>'.

				'<p class="description description-thin">'.
				'  <label for="edit-menu-item-'.esc_attr(__NAMESPACE__).'-label-color-'.esc_attr($item->ID).'">'.
				'     '.__('Label Color', $theme->text_domain).'<br />'.
				'     <input type="text" id="edit-menu-item-'.esc_attr(__NAMESPACE__).'-label-color-'.esc_attr($item->ID).'" name="menu-item-'.esc_attr(__NAMESPACE__).'-label-color['.esc_attr($item->ID).']" value="'.esc_attr($item->{__NAMESPACE__.'_label_color'}).'" class="widefat code" />'.
				'  </label>'.
				'</p>'.
				'<p class="description description-thin">'.
				'  <label for="edit-menu-item-'.esc_attr(__NAMESPACE__).'-label-hover-color-'.esc_attr($item->ID).'">'.
				'     '.__('Label Hover Color', $theme->text_domain).'<br />'.
				'     <input type="text" id="edit-menu-item-'.esc_attr(__NAMESPACE__).'-label-hover-color-'.esc_attr($item->ID).'" name="menu-item-'.esc_attr(__NAMESPACE__).'-label-hover-color['.esc_attr($item->ID).']" value="'.esc_attr($item->{__NAMESPACE__.'_label_hover_color'}).'" class="widefat code" />'.
				'  </label>'.
				'</p>'.

				'<div class="description description-wide" style="margin-top:1em; margin-bottom:1em;">'.
				'  <label for="edit-menu-item-'.esc_attr(__NAMESPACE__).'-logic-'.esc_attr($item->ID).'">'.
				'     '.__('Conditional Logic [see: <a href="http://codex.wordpress.org/Conditional_Tags" target="_blank">Conditionals</a>]', $theme->text_domain).'</label><br />'.
				'  <table style="width:100%;"><tr><td style="width:1px; font-weight:bold; white-space:nowrap;">if(</td><td><input type="text" id="edit-menu-item-'.esc_attr(__NAMESPACE__).'-logic-'.esc_attr($item->ID).'" name="menu-item-'.esc_attr(__NAMESPACE__).'-logic['.esc_attr($item->ID).']" value="'.esc_attr($item->{__NAMESPACE__.'_logic'}).'" class="widefat code" /></td><td style="width:1px; font-weight:bold; white-space:nowrap;">)</td></tr></table>'.
				'</div>';

			$output = preg_replace($regex_before, $additional_config_options.'${0}', $output); // By reference.
		}
	}

	add_filter('wp_edit_nav_menu_walker', function ($walker, $menu_id)
	{
		return '\\'.__NAMESPACE__.'\\navwalker_editor'; // Use this walker.

	}, 10, 2); // End filter handler w/ 2 arguments.

	add_action('wp_update_nav_menu_item', function ($menu_id, $menu_item_db_id, $args) // Updates.
	{
		if(isset($_REQUEST['menu-item-'.__NAMESPACE__.'-stype'][$menu_item_db_id]))
			if(is_string($stype = $_REQUEST['menu-item-'.__NAMESPACE__.'-stype'][$menu_item_db_id]))
				update_post_meta($menu_item_db_id, '_menu_item_'.__NAMESPACE__.'_stype', stripslashes($stype));

		if(isset($_REQUEST['menu-item-'.__NAMESPACE__.'-icon'][$menu_item_db_id]))
			if(is_string($icon = $_REQUEST['menu-item-'.__NAMESPACE__.'-icon'][$menu_item_db_id]))
				update_post_meta($menu_item_db_id, '_menu_item_'.__NAMESPACE__.'_icon', stripslashes($icon));

		if(isset($_REQUEST['menu-item-'.__NAMESPACE__.'-icon-color'][$menu_item_db_id]))
			if(is_string($icon_color = $_REQUEST['menu-item-'.__NAMESPACE__.'-icon-color'][$menu_item_db_id]))
				update_post_meta($menu_item_db_id, '_menu_item_'.__NAMESPACE__.'_icon_color', stripslashes($icon_color));

		if(isset($_REQUEST['menu-item-'.__NAMESPACE__.'-icon-hover-color'][$menu_item_db_id]))
			if(is_string($icon_hover_color = $_REQUEST['menu-item-'.__NAMESPACE__.'-icon-hover-color'][$menu_item_db_id]))
				update_post_meta($menu_item_db_id, '_menu_item_'.__NAMESPACE__.'_icon_hover_color', stripslashes($icon_hover_color));

		if(isset($_REQUEST['menu-item-'.__NAMESPACE__.'-icon-only'][$menu_item_db_id])
		   && is_string($icon_only = $_REQUEST['menu-item-'.__NAMESPACE__.'-icon-only'][$menu_item_db_id])
		) update_post_meta($menu_item_db_id, '_menu_item_'.__NAMESPACE__.'_icon_only', stripslashes($icon_only));
		else update_post_meta($menu_item_db_id, '_menu_item_'.__NAMESPACE__.'_icon_only', ''); // Assume NO in this case.

		if(isset($_REQUEST['menu-item-'.__NAMESPACE__.'-label-color'][$menu_item_db_id]))
			if(is_string($label_color = $_REQUEST['menu-item-'.__NAMESPACE__.'-label-color'][$menu_item_db_id]))
				update_post_meta($menu_item_db_id, '_menu_item_'.__NAMESPACE__.'_label_color', stripslashes($label_color));

		if(isset($_REQUEST['menu-item-'.__NAMESPACE__.'-label-hover-color'][$menu_item_db_id]))
			if(is_string($label_hover_color = $_REQUEST['menu-item-'.__NAMESPACE__.'-label-hover-color'][$menu_item_db_id]))
				update_post_meta($menu_item_db_id, '_menu_item_'.__NAMESPACE__.'_label_hover_color', stripslashes($label_hover_color));

		if(isset($_REQUEST['menu-item-'.__NAMESPACE__.'-logic'][$menu_item_db_id]))
			if(is_string($logic = $_REQUEST['menu-item-'.__NAMESPACE__.'-logic'][$menu_item_db_id]))
				update_post_meta($menu_item_db_id, '_menu_item_'.__NAMESPACE__.'_logic', stripslashes($logic));
	}, 10, 3); // End update handler w/ 3 arguments.

	add_action('delete_post', function ($post_id) // Cleanup.
	{
		if(!is_nav_menu_item($post_id)) return; // Nothing to do.

		delete_post_meta($post_id, '_menu_item_'.__NAMESPACE__.'_stype');
		delete_post_meta($post_id, '_menu_item_'.__NAMESPACE__.'_icon');
		delete_post_meta($post_id, '_menu_item_'.__NAMESPACE__.'_icon_color');
		delete_post_meta($post_id, '_menu_item_'.__NAMESPACE__.'_icon_hover_color');
		delete_post_meta($post_id, '_menu_item_'.__NAMESPACE__.'_icon_only');
		delete_post_meta($post_id, '_menu_item_'.__NAMESPACE__.'_label_color');
		delete_post_meta($post_id, '_menu_item_'.__NAMESPACE__.'_label_hover_color');
		delete_post_meta($post_id, '_menu_item_'.__NAMESPACE__.'_logic'); #

	}, 10, 1); // End deletion handler w/ 1 argument.
}
