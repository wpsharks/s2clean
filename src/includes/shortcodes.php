<?php
namespace s2clean // Root namespace.
{
	if(!defined('WPINC'))
		exit('Do NOT access this file directly.');

	class shortcodes // Shortcode handler.
	{
		/**
		 * @var theme Theme instance.
		 */
		protected $theme; // Set by constructor.

		public function __construct()
		{
			$this->theme = theme();
		}

		public function raw(array $attr, $content = '', $tag = '', $return_attr = FALSE)
		{
			if($return_attr) // Documentation for shortcode attributes.
				return array(); // Not shortcode attributes.

			$html = ($content) ? $this->theme->unesc_raw($content) : $content;

			return apply_filters(__METHOD__, $html, get_defined_vars());
		}

		public function tag_cloud(array $attr, $content = '', $tag = '', $return_attr = FALSE)
		{
			if($return_attr) // Documentation for shortcode attributes.
				return array('*' => __('Accepts any <a href="http://codex.wordpress.org/Function_Reference/wp_tag_cloud" target="_blank">wp_tag_cloud()</a> argument as a shortcode attribute.', $this->theme->text_domain).
				                    ' '.__('Example:', $this->theme->text_domain).' `[tag_cloud format="flat" smallest="8" largest="22" taxonomy="post_tag" /]`.');

			$attr['echo']   = FALSE; // Force this to FALSE at all times.
			$attr['format'] = !empty($attr['format']) && in_array($attr['format'], array('flat', 'list'), TRUE)
				? $attr['format'] : 'flat'; // Must have a valid format.

			$html = '<div class="sc-tag-cloud sc-tag-cloud-'.esc_attr($attr['format']).' tagcloud">'."\n".
			        wp_tag_cloud($attr)."\n". // WordPress tag cloud; template tag.
			        '</div>'."\n"; // Closing div.
			return apply_filters(__METHOD__, $html, get_defined_vars());
		}

		public function contact_form(array $attr, $content = '', $tag = '', $return_attr = FALSE)
		{
			if($return_attr) // Documentation for shortcode attributes.
				return array(
					'from'              => __('Email address that contact form submissions will come from. Defaults to global theme option for this setting.', $this->theme->text_domain),
					'to'                => __('Email address where contact form submissions will be sent to. Defaults to global theme option for this setting.', $this->theme->text_domain),
					'subject'           => __('Subject line used in contact form submissions. Defaults to global theme option for this setting.', $this->theme->text_domain),
					'recaptcha_theme'   => __('reCAPTCHA theme; e.g. `red`, `white`, `blackglass` or `clean`. Defaults to `white`.', $this->theme->text_domain),
					'recaptcha_lang'    => __('reCAPTCHA language code. Defaults to `en`. See: <https://developers.google.com/recaptcha/docs/customization#i18n>.', $this->theme->text_domain),
					'thank_you_message' => __('Thank you message. Defaults to `Thank you! Message sent successfully.`.', $this->theme->text_domain)
				);
			$defaults = array('from'              => $this->theme->options['contact_form_from'],
			                  'to'                => $this->theme->options['contact_form_to'],
			                  'subject'           => $this->theme->options['contact_form_subject'],
			                  'recaptcha_theme'   => 'white', 'recaptcha_lang' => 'en',
			                  'thank_you_message' => __('Thank you! Message sent successfully.', $this->theme->text_domain));
			$attr     = shortcode_atts($defaults, $attr);

			ob_start(); // Output buffer.
			require dirname(__FILE__).'/contact-form.php';
			return apply_filters(__METHOD__, ob_get_clean(), get_defined_vars());
		}

		public function wp_readme_tabs(array $attr, $content = '', $tag = '', $return_attr = FALSE)
		{
			if($return_attr) // Documentation for shortcode attributes.
				return array(
					'file'                 => __('The local and/or remote location of a WordPress `readme.txt` file. Default value is an empty string.', $this->theme->text_domain),
					'slug'                 => __('A WordPress theme or plugin slug; which might be associated with screenshots in the `readme.txt` file; e.g. `my-plugin`. Default value is an empty string.', $this->theme->text_domain),
					'max_age'              => __('The max age of the cache file created by this shortcode (in seconds). The cache file contains the rendered version of the `readme.txt` tabs. Default value is `3600`.', $this->theme->text_domain),
					'default_active_tab'   => __('The default active `readme.txt` tab (by its section name). Defaults value is `Description`.', $this->theme->text_domain),
					'include_all_sections' => __('Include all sections? Any true value to enable; e.g. `1|on|yes|true`.', $this->theme->text_domain),
				);
			$defaults = array('file' => '', 'slug' => '', 'max_age' => '3600', 'default_active_tab' => 'Description', 'include_all_sections' => 'no');
			$attr     = shortcode_atts($defaults, $attr);

			$attr['max_age']              = (integer)$attr['max_age'];
			$attr['include_all_sections'] = filter_var($attr['include_all_sections'], FILTER_VALIDATE_BOOLEAN);

			$tabs = $this->theme->md_parse_cache_wp_readme_tabs($attr['file'], $attr['slug'], $attr['max_age'], $attr['default_active_tab'], $attr['include_all_sections']);

			return apply_filters(__METHOD__, $tabs, get_defined_vars());
		}

		public function shortlink_copier(array $attr, $content = '', $tag = '', $return_attr = FALSE)
		{
			if($return_attr) // Documentation for shortcode attributes.
				return array(
					'post_id' => __('A specific Post/Page ID. Defaults to the current Post/Page.', $this->theme->text_domain),
					'label'   => __('A label to appear above the copier. Default value is "Shortlink (Share)".', $this->theme->text_domain),
					'class'   => __('One or more CSS classes to wrap the copier with. Default value is an empty string.', $this->theme->text_domain),
					'style'   => __('Any additional CSS styles you\'d like to apply. Default value is an empty string.', $this->theme->text_domain),
				);
			$defaults = array('post_id' => 0, 'label' => '', 'class' => '', 'style' => '');
			$attr     = shortcode_atts($defaults, $attr);

			$attr['post_id'] = (integer)$attr['post_id'];
			$copier          = $this->theme->shortlink_copier($attr);

			return apply_filters(__METHOD__, $copier, get_defined_vars());
		}

		public function share_btn_icons(array $attr, $content = '', $tag = '', $return_attr = FALSE)
		{
			if($return_attr) // Documentation for shortcode attributes.
				return array(
					'heading'   => __('Optional heading. Defaults to: `Share this on...`.', $this->theme->text_domain),
					'size'      => __('Button icon size. One of: `lg` (default), `2x`, `3x`, `4x`, `5x`.', $this->theme->text_domain),
					'vertical'  => __('Enable vertical layout? Any true value to enable; e.g. `1|on|yes|true`. Any false value to disable; e.g. `0|no|off|false` (default).', $this->theme->text_domain),
					'labels'    => __('Enable button/icon labels? Any true value to enable; e.g. `1|on|yes|true` (default). Any false value to disable; e.g. `0|no|off|false`.', $this->theme->text_domain),
					'btn_class' => __('Any Bootstrap-compatible btn class. One of: `btn-default` (default), `btn-primary`, `btn-success`, `btn-info`, `btn-warning`, `btn-danger`.', $this->theme->text_domain),
					'tooltips'  => __('Hover tooltip placement. One of: `auto` (default), `auto top`, `auto right`, `auto bottom`, `auto left`; or `none` to disable tooltips.', $this->theme->text_domain),
					'colorize'  => __('Colorize icons? Any true value to enable; e.g. `1|on|yes|true` (default). Any false value to disable; e.g. `0|no|off|false`.', $this->theme->text_domain),
					'services'  => __('Comma-delimited list of services to include; in the order you desire.', $this->theme->text_domain).
					               ' '.__('Any combination of:', $this->theme->text_domain). // See: <http://www.addthis.com/services/list>.
					               ' `facebook`, `twitter`, `google_plus`, `linkedin`, `pinterest`, `amazon`, `wordpress`, `tumblr`, `email`, `more`.'.
					               ' '.__('Default value:', $this->theme->text_domain).' `facebook,twitter,google_plus,linkedin,pinterest,wordpress,tumblr,email,more`.',
					'title'     => __('Title of the page to share; else leave blank for current page.', $this->theme->text_domain),
					'url'       => __('URL of the page to share; else leave blank for the current page.', $this->theme->text_domain)
				);
			$defaults = array( // Defaults.
			                   'heading'   => __('Share this on...', $this->theme->text_domain),
			                   'size'      => 'lg', 'vertical' => FALSE, 'labels' => TRUE,
			                   'btn_class' => 'btn-default', // Bootstrap compatible.
			                   'tooltips'  => 'auto', // Based on `vertical` option.
			                   'colorize'  => NULL, // Only w/ `btn-default` styles.
			                   'services'  => 'facebook,twitter,google_plus,linkedin,pinterest,wordpress,tumblr,email,more',
			                   'title'     => '', 'url' => '');
			$attr     = shortcode_atts($defaults, $attr);

			$attr['vertical'] = $this->theme->is_true($attr['vertical']);
			$attr['labels']   = $this->theme->is_true($attr['labels']);

			if($attr['tooltips'] === 'auto') // Determine automatically?
				$attr['tooltips'] = ($attr['vertical']) ? 'auto left' : 'auto top';
			else if($attr['tooltips'] === 'none') $attr['tooltips'] = '';

			if(!isset($attr['colorize']) && strpos($attr['btn_class'], 'default') !== FALSE)
				$attr['colorize'] = TRUE; // Colorize icons w/ `btn-default`.
			else $attr['colorize'] = $this->theme->is_true($attr['colorize']);

			$esc_attr['size']      = esc_attr($attr['size']);
			$esc_attr['btn_class'] = esc_attr($attr['btn_class']);
			$esc_attr['tooltips']  = esc_attr($attr['tooltips']);
			$esc_attr['services']  = esc_attr($attr['services']);
			$esc_attr['title']     = esc_attr($attr['title']);
			$esc_attr['url']       = esc_attr($attr['url']);

			$esc_attr['__(Share on)'] = esc_attr(sprintf(__('Share%1$s on', $this->theme->text_domain),
				((!$attr['url']) ? ' '.__('this', $this->theme->text_domain) : '')));

			$esc_attr['__(Post on your)'] = esc_attr(sprintf(__('Post%1$s on your', $this->theme->text_domain),
				((!$attr['url']) ? ' '.__('this', $this->theme->text_domain) : '')));

			$esc_attr['__(Add to your)'] = esc_attr(sprintf(__('Add%1$s to your', $this->theme->text_domain),
				((!$attr['url']) ? ' '.__('this', $this->theme->text_domain) : '')));

			$esc_attr['__(Share via Email)'] = esc_attr(sprintf(__('Share%1$s via Email', $this->theme->text_domain),
				((!$attr['url']) ? ' '.__('this', $this->theme->text_domain) : '')));

			$esc_attr['__(via Email)']             = esc_attr(__('via Email', $this->theme->text_domain));
			$esc_attr['__(More...)']               = esc_attr(__('More...', $this->theme->text_domain));
			$esc_attr['__(More ways to share...)'] = esc_attr(__('More ways to share...', $this->theme->text_domain));

			$service_specs = array(
				// Tooltip, label, FA icon; and a HEX color code.
				'facebook'    => array('tooltip' => $esc_attr['__(Share on)'].' Facebook',
				                       'label'   => 'Facebook', 'icon' => 'facebook-square', 'color' => '305891'),

				'twitter'     => array('tooltip' => $esc_attr['__(Share on)'].' Twitter',
				                       'label'   => 'Twitter', 'icon' => 'twitter', 'color' => '2CA8D2'),

				'google_plus' => array('tooltip' => $esc_attr['__(Share on)'].' Google+',
				                       'label'   => 'Google+', 'icon' => 'google-plus-square', 'color' => 'CE4D39'),

				'linkedin'    => array('tooltip' => $esc_attr['__(Share on)'].' LinkedIn',
				                       'label'   => 'LinkedIn', 'icon' => 'linkedin-square', 'color' => '4498C8'),

				'pinterest'   => array('tooltip' => $esc_attr['__(Share on)'].' Pinterest',
				                       'label'   => 'Pinterest', 'icon' => 'pinterest', 'color' => 'C82828'),

				'amazon'      => array('tooltip' => $esc_attr['__(Add to your)'].' Amazon Wish List — @Amazon.com',
				                       'label'   => 'Amazon', 'icon' => 'gift', 'color' => 'F88818'),

				'tumblr'      => array('tooltip' => $esc_attr['__(Post on your)'].' Tumblr Blog',
				                       'label'   => 'Tumblr', 'icon' => 'tumblr', 'color' => '2F5070'),

				'wordpress'   => array('tooltip' => $esc_attr['__(Post on your)'].' WP Blog',
				                       'label'   => 'WordPress', 'icon' => 'wordpress', 'color' => '585858'),

				'email'       => array('tooltip' => $esc_attr['__(Share via Email)'],
				                       'label'   => $esc_attr['__(via Email)'], 'icon' => 'envelope', 'color' => '738A8D'),

				'more'        => array('tooltip' => $esc_attr['__(More ways to share...)'],
				                       'label'   => $esc_attr['__(More...)'], 'icon' => 'plus-square', 'color' => 'F8694D'));
			$service_specs = apply_filters(__METHOD__.'__service_specs', $service_specs, get_defined_vars());

			$anchor = function ($service) use ($attr, $esc_attr, $service_specs)
			{
				$specs = (!empty($service_specs[$service])) ? $service_specs[$service]
					: array('tooltip' => $esc_attr['__(Share on)'].' '.ucwords($service),
					        'label'   => ucwords($service), 'icon' => 'external-link', 'color' => '000000');

				return '<a href="#" data-toggle="'.(($attr['tooltips']) ? 'tooltip share' : 'share').'"'.
				       ' title="'.$specs['tooltip'].'"'.(($attr['tooltips']) ? ' data-placement="'.$esc_attr['tooltips'].'"' : '').
				       ' data-title="'.$esc_attr['title'].'" data-url="'.$esc_attr['url'].'" data-service="'.$service.'"'.
				       ' class="btn '.$esc_attr['btn_class'].'">'.

				       ($attr['labels'] ? $specs['label'].' ' : '').
				       '<i class="fa fa-'.$specs['icon'].' fa-'.$esc_attr['size'].' fa-fw"'.
				       ($attr['colorize'] ? ' style="color:#'.$specs['color'].';"' : '').'></i></a>';
			};
			$html   = // Construct HTML for each of the button icons.
				'<div class="sc-share-btn-icons text-center hidden-xs hidden-print">'."\n".
				'<div class="btn-group'.($attr['vertical'] ? '-vertical' : '').'">'."\n";

			if($attr['heading']) // Using a button heading; i.e. a call-to-action?
				$html .= '<a href="#" class="btn '.$esc_attr['btn_class'].'" disabled="disabled">'.$attr['heading'].'</a>'."\n";

			foreach(preg_split('/[\s;,]+/', $esc_attr['services'], NULL, PREG_SPLIT_NO_EMPTY) as $_service)
				$html .= $anchor($_service)."\n"; // Constructs each service anchor; in preferential order.
			unset($_service); // Just a little housekeeping.

			$html .= '</div></div>'."\n"; // Closing divs.

			return apply_filters(__METHOD__, $html, get_defined_vars());
		}

		public function trending_posts(array $attr, $content = '', $tag = '', $return_attr = FALSE)
		{
			if($return_attr) // Documentation for shortcode attributes.
				return array(
					'heading'            => __('Defaults to `You might also like...`.', $this->theme->text_domain),
					'type'               => __('The type of posts to consider. Default is `post`.', $this->theme->text_domain),
					'formats'            => __('Comma-delimited post formats to consider. Default is `standard`.', $this->theme->text_domain),
					'consider'           => __('Maximum number of posts to consider. Default is `25`.', $this->theme->text_domain),
					'max'                => __('Maximum number of posts to display. Default is `4`.', $this->theme->text_domain),
					'rows'               => __('Total number of rows to break the posts down into. Default is `1`.', $this->theme->text_domain),
					'thumbs'             => __('Display thumbnails? e.g. `1|on|yes|true|0|off|no|false`. Default is `yes`.', $this->theme->text_domain),
					'thumb_width'        => __('Thumbnail width. The default width is `300`.".', $this->theme->text_domain),
					'thumb_height'       => __('Thumbnail height. The default height is `169`.".', $this->theme->text_domain),
					'titles'             => __('Display titles? e.g. `1|on|yes|true|0|off|no|false`. Default is `yes`.', $this->theme->text_domain),
					'title_clip'         => __('Maximum number of chars allowed in a post title. Default is `75`.', $this->theme->text_domain),
					'title_line_clamp'   => __('Maximum number of lines [1-5] allowed in a post title. Default is `1`.', $this->theme->text_domain),
					'tooltips'           => __('Display tooltips? e.g. `1|on|yes|true|0|off|no|false`. Default is `yes`.', $this->theme->text_domain),
					'summaries'          => __('Display summaries? e.g. `1|on|yes|true|0|off|no|false`. Default is `yes`.', $this->theme->text_domain),
					'summary_clip'       => __('Maximum number of chars allowed in a post summary. Default is `150`.', $this->theme->text_domain),
					'summary_line_clamp' => __('Maximum number of lines [1-5] allowed in a post summary. Default is `3`.', $this->theme->text_domain),
					'summary_more'       => __('Link text after summary. Default is `[read more]`. To disable, use an empty string.', $this->theme->text_domain),
					'class'              => __('One or more CSS classes to wrap the posts with. Default value is an empty string.', $this->theme->text_domain),
					'style'              => __('Any additional CSS styles you\'d like to apply. Defaults to: `margin-bottom:-20px !important;`.', $this->theme->text_domain),
				);
			$defaults = array('heading'   => __('You might also like...', $this->theme->text_domain),
			                  'type'      => 'post', 'formats' => 'standard', 'consider' => '25', 'max' => '4', 'rows' => '1',
			                  'thumbs'    => 'yes', 'thumb_width' => '300', 'thumb_height' => '169',
			                  'titles'    => 'yes', 'title_clip' => '75', 'title_line_clamp' => '1', 'tooltips' => 'yes',
			                  'summaries' => 'yes', 'summary_clip' => '150', 'summary_line_clamp' => '3', 'summary_more' => __('[read more]', $this->theme->text_domain),
			                  'class'     => '', 'style' => 'margin-bottom:-20px !important;');
			$attr     = shortcode_atts($defaults, $attr);

			$attr['thumbs']    = $this->theme->is_true($attr['thumbs']);
			$attr['titles']    = $this->theme->is_true($attr['titles']);
			$attr['summaries'] = $this->theme->is_true($attr['summaries']);
			$attr['formats']   = preg_split('/[;,\s]+/', strtolower($attr['formats']), PREG_SPLIT_NO_EMPTY);
			foreach(array('consider', 'max', 'rows', 'thumb_width', 'thumb_height', 'title_clip', 'title_line_clamp', 'summary_clip', 'summary_line_clamp') as $_attr_key)
				$attr[$_attr_key] = (integer)($attr[$_attr_key] > 0 ? $attr[$_attr_key] : $defaults[$_attr_key]);
			unset($_attr_key); // Just a little houskeeping.

			$trending_posts = $this->theme->trending_posts($attr['type'], $attr['formats'], $attr['consider'], $attr['thumb_width'], $attr['thumb_height']);

			if(!empty($GLOBALS['snippet_post']->ID)) // Exclude parent post ID.
				unset($trending_posts[$GLOBALS['snippet_post']->ID]);

			if(!empty($GLOBALS['post']->ID)) // Exclude current post ID.
				unset($trending_posts[$GLOBALS['post']->ID]);

			$_current_path      = (string)parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
			$_regex_current_uri = '/\/\/'.preg_quote($_SERVER['HTTP_HOST'], '/'). // `//[host name]`.
			                      ($_current_path === '/' ? '' : '\/'.preg_quote(trim($_current_path, '/'), '/')).
			                      '(?:[\/?&#]|$)/i'; // Followed by any of these tokens; or nothing.

			foreach($trending_posts as $_key => $_post)  // Also exclude the current URI.
				if(preg_match($_regex_current_uri, $_post->url)) unset($trending_posts[$_key]);
			unset($_current_path, $_regex_current_uri, $_key, $_post); // Housekeeping.

			if(!$trending_posts) return ''; // Not possible; there are no trending posts.

			shuffle($trending_posts); // Keys are reset by this routine.

			$posts        = ''; // Initialize output.
			$col_counter  = 0; // Initialize column counter.
			$counter      = 0; // Initialize overall counter.
			$number       = min($attr['max'], count($trending_posts));
			$cols_per_row = floor($number / $attr['rows']);
			$col_size     = floor(12 / $cols_per_row);

			$posts .= '<div class="trending-posts font-size-reset'.($attr['class'] ? ' '.esc_attr($attr['class']) : '').'"'.
			          ($attr['style'] ? ' style="'.esc_attr($attr['style']).'"' : '').'>';

			if($attr['heading']) // Including a heading?
				$posts .= '<h4 class="no-t-margin b-margin">'.$attr['heading'].'</h4>';

			foreach($trending_posts as $_post /* Iterate trending posts. */)
			{
				if($col_counter === 0)
					$posts .= '<div class="row">';

				$posts .= ' <div class="col-md-'.esc_attr($col_size).' col-sm-'.esc_attr(ceil($col_size * 2)).'">';
				$posts .= '    <div class="thumbnail text-center"'. // `thumbnail` class remains; no matter.
				          '     title="'.esc_attr($_post->title).'"'.($attr['tooltips'] ? ' data-toggle="tooltip"' : '').'>';

				if($attr['thumbs']) // Display post image thumbnail?
				{
					$posts .= '    <a href="'.esc_attr($_post->url).'">'.
					          '       <img src="'.esc_attr(set_url_scheme($_post->thumb)).'" alt="'.esc_attr($_post->title).'"'.
					          '        class="border-top-radius" style="width:'.esc_attr($attr['thumb_width']).'px;" />'.
					          '    </a>';
				}
				if($attr['titles'] || $attr['summaries'])
				{
					$posts .= '    <div class="caption'.(!$attr['summaries'] ? ' no-b-padding' : '').'">';
					if($attr['titles'])
						$posts .= '    <a href="'.esc_attr($_post->url).'">'. // Anchor tag as wrapper so this works with `-webkit-line-clamp`.
						          '       <h4 class="no-t-margin'.(!$attr['summaries'] ? ' no-b-margin' : '').' font-110 line-clamp line-clamp-'.esc_attr($attr['title_line_clamp']).'">'.
						          '          '.esc_html($this->theme->clip($_post->title, $attr['title_clip'])).
						          '       </h4>'.
						          '    </a>';
					if($attr['summaries'])
						$posts .= '    <p class="'.(!$attr['titles'] ? 'no-t-margin ' : '').'no-b-margin font-90 line-clamp line-clamp-'.esc_attr($attr['summary_line_clamp']).'">'.
						          '       '.$this->theme->clip($_post->summary, $attr['summary_clip']).
						          '       '.($attr['summary_more'] ? ' <a href="'.esc_attr($_post->url).'">'.$attr['summary_more'].'</a>' : '').
						          '    </p>';
					$posts .= '    </div>';
				}
				$posts .= '    </div>';
				$posts .= ' </div>';

				if(($col_counter = $col_counter + 1) >= $cols_per_row)
				{
					$posts .= '</div>'; // Close this row.
					$col_counter = 0; // Reset; start a new row.
				}
				if(($counter = $counter + 1) >= $attr['max'])
					break; // Done; got what we need here.
			}
			unset($_post); // Housekeeping.

			$posts .= '</div>';

			return apply_filters(__METHOD__, $posts, get_defined_vars());
		}
	}
}
