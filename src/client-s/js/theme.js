(function($)
{
	'use strict'; // Standards.

	window.theme = window.theme || {};

	var theme = window.theme, // Local refs.
		$window = $(window), $document = $(document);

	theme.vars = $('#theme-vars').data('json'); // Dynamics.

	theme.locationHost = document.location.hostname.toLowerCase();
	theme.locationPath = '/' + location.pathname.replace(/^\//, '').replace(/\/comment\-page\-1\//, '/');
	theme.locationSearch = location.search; // Query string.

	theme.hashQueryMarker = '#!'; // Identifies query strings.
	theme.hashTrackingMarker = '#.'; // Identifies tracking markers.
	theme.delayedHashAnchors = ['#respond']; // Delay these.
	theme.cache = {$$: {}}; // Static cache container.

	theme.log = function(value)
	{
		console.log('---- Theme log entry ----'),
			console.log(value);
	};
	theme.returnTrue = function(){ return true; },
		theme.returnFalse = function(){ return false; };
	theme.preventDefault = function(event){ event.preventDefault(); };

	theme.isset = function(args___ /* Unlimited arguments. */)
	{
		for(var undefinedVar, i = 0; i < arguments.length; i++)
			if(arguments[i] === undefinedVar || arguments[i] === null)
				return false;
		return true; // All set :-)
	};

	theme.empty = function(value /* One argument only. */)
	{
		if(value === '' || value === 0 || value === '0'
		   || value === null || value === false || typeof value === 'undefined')
			return true; // Any of these indicate an empty value.

		if(typeof value === 'object')
		{
			for(var p in value) return false;
			return true; // No properties.
		}
		return false; // Not empty :-)
	};

	theme.lTrim = function(string) // See also: `$.trim()`.
	{
		return String(string).replace(/^\s+/g, '');
	};

	theme.rTrim = function(string) // See also: `$.trim()`.
	{
		return String(string).replace(/\s+$/g, '');
	};

	theme.isHtml = function(string)
	{
		return /[&<>]/.test(String(string));
	};

	theme.escHtml = theme.escAttr = function(string)
	{
		if(/[&\<\>"']/.test(string = String(string)))
			string = string.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;'),
				string = string.replace(/"/g, '&quot;').replace(/'/g, '&#039;');
		return string;
	};

	theme.escjQAttr = function(string)
	{
		return String(string).replace(/([.:\[\]])/g, '\\$1');
	};

	theme.pregQuote = function(string, delimiter)
	{
		delimiter = (!theme.empty(delimiter)) ? '\\' + String(delimiter) : '';
		return String(string).replace(new RegExp('[.\\\\+*?[\\^\\]$(){}=!<>|:\\-' + delimiter + ']', 'g'), '\\$&');
	};

	theme.mtRand = function(min, max)
	{
		min = (theme.isset(min)) ? Number(min) : 0;
		max = (theme.isset(max)) ? Number(max) : 2147483647;
		return Math.floor(Math.random() * (max - min + 1)) + min;
	};

	theme.ucWords = function(string)
	{
		return String(string).replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g,
		                              function($1){ return $1.toUpperCase(); });
	};

	theme.stripTitles = function(string)
	{
		return String(string).replace(/\<(h[0-9]|header)(?:\s+[^>]*)?\>[\s\S]*?\<\/\\1\>/gi, '');
	};

	theme.clip = function(string, maxLength, stripTitles, forceEllipsis)
	{
		if(!string || !(string = String(string)))
			return string; // Nothing to do.

		maxLength = (maxLength < 4) ? 4 : maxLength;

		if(stripTitles) // Strip titles?
			string = theme.stripTitles(string);

		string = $.trim(string.replace(/\s+/g, ' '));

		if(string.length > maxLength)
			return string.substr(0, maxLength - 3) + '...';

		if(forceEllipsis && string.length + 3 > maxLength)
			return string.substr(0, maxLength - 3) + '...';

		return string + ((forceEllipsis) ? '...' : '');
	};

	theme.midClip = function(string, maxLength, stripTitles)
	{
		if(!string || !(string = String(string)))
			return string; // Nothing to do.

		maxLength = (maxLength < 5) ? 5 : maxLength;

		if(stripTitles) // Strip titles?
			string = theme.stripTitles(string);

		string = $.trim(string.replace(/\s+/g, ' '));

		if(string.length <= maxLength)
			return string; // Done.

		var fullString = string, // Working vars.
			halfMaxLength = Math.floor(maxLength / 2),
			firstClip, secondClip;

		firstClip = halfMaxLength - 5;
		string = (firstClip >= 1) // Something?
			? fullString.substr(0, firstClip) + ' ... '
			: ' ... '; // Ellipsis only.

		secondClip = fullString.length - (maxLength - string.length);
		string += (secondClip >= 0 && secondClip >= firstClip)
			? fullString.substr(secondClip)
			: ''; // Nothing more.

		return string; // Mid-clipped :-)
	};

	theme.$$ = function(selector, $$context, contextName)
	{
		selector = String(selector); // Force string.
		if(!($$context instanceof jQuery)) $$context = $document;
		if(typeof contextName !== 'string') contextName = '';

		if(theme.cache.$$[selector + contextName] instanceof jQuery)
			return theme.cache.$$[selector + contextName];
		return (theme.cache.$$[selector + contextName] = $(selector, $$context));
	};

	theme.navigatorEngine = function()
	{
		if(typeof theme.cache.navigatorEngine === 'string')
			return theme.cache.navigatorEngine; // Already in cache.

		var regexEngine; // Initialize regex result array; e.g. the engine name.
		var regexEngines = /(msie|trident|gecko|webkit|presto|konqueror|playstation)[\/ ][0-9\.]+/i;

		if(!theme.empty(navigator.userAgent) && (regexEngine = regexEngines.exec(navigator.userAgent)))
			theme.cache.navigatorEngine = regexEngine[1].toLowerCase(); // Engine name.
		else theme.cache.navigatorEngine = ''; // Default value (empty string).

		return theme.cache.navigatorEngine;
	};

	theme.isEmbedAnchor = function() // Apply to DOM object.
	{
		if(this.parentNode.nodeName.toLowerCase() === 'p' && this.parentNode.childNodes.length === 1)
			if(this.nodeName.toLowerCase() === 'a' && typeof this.href === 'string' && typeof this.text === 'string')
				if(this.href === this.text) return true;
		return false;
	};

	theme.isHashAnchorHere = function() // Apply to DOM object.
	{
		if(this.nodeName.toLowerCase() !== 'a')
			return false; // Not an anchor tag.

		if(!theme.hashHasAnchor(this.hash))
			return false; // Not a hash anchor.

		if(!theme.empty(this.hostname) && this.hostname.toLowerCase() !== theme.locationHost)
			return false; // Not in the current host.

		if(!theme.empty(this.pathname) && this.pathname !== 'blank'
		   && '/' + this.pathname.replace(/^\//, '').replace(/\/comment\-page\-1\//, '/') !== theme.locationPath)
			return false; // Not in the current path.

		if(!theme.empty(this.search) && this.search !== theme.locationSearch && !$(this).hasClass('comment-reply-link'))
		// Comment reply links SHOULD be considered "here"; even if they contain a different query string; e.g. `?replytocom=123`.
			return false; // Not this dynamic page; based on query string.

		return true; // On-site.
	};

	theme.hashHasAnchor = function(hash)
	{
		if(!theme.empty(hash = String(hash)) && hash[0] === '#' && hash !== '#')
			if(!theme.hashHasQuery(hash) && !theme.hashHasQueryTraits(hash) && !theme.hashHasTracking(hash))
				if(/^#[a-z0-9_\-]/i.test(hash)) // A valid name/ID?
					return true; // Hash is an anchor.
		return false; // Default value.
	};

	theme.hashHasQuery = function(hash)
	{
		var slice = String(hash).slice(0, theme.hashQueryMarker.length);

		return slice === theme.hashQueryMarker;
	};

	theme.hashHasQueryTraits = function(hash)
	{
		return String(hash).indexOf('=') !== -1;
	};

	theme.hashHasTracking = function(hash)
	{
		var slice = String(hash).slice(0, theme.hashTrackingMarker.length);

		return slice === theme.hashTrackingMarker;
	};

	theme.getQueryVar = function(queryVar, from)
	{
		var qsPairs = []; // Initialize name=value pairs.

		if(!(from instanceof Array)) from = ['query', 'hash'];

		if($.inArray('query', from) !== -1) // From query string?
			qsPairs = qsPairs.concat(location.search.slice(1).split('&'));

		if($.inArray('hash', from) !== -1 && theme.hashHasQuery(location.hash))
			qsPairs = qsPairs.concat(location.hash.slice(theme.hashQueryMarker.length).split('&'));

		for(var i = 0, qsPair = ''; i < qsPairs.length; i++) // Find query var.
		{
			qsPair = qsPairs[i].split('='); // Split name=value pair.

			if(qsPair.length === 2 && decodeURIComponent(qsPair[0]) === queryVar)
				return $.trim(decodeURIComponent(qsPair[1].replace(/\+/g, ' ')));
		}
		return ''; // Default return value.
	};

	theme.fullscreenObject = function()
	{
		return document['fullscreenElement']
		       || document['webkitFullscreenElement']
		       || document['mozFullScreenElement']
		       || document['msFullscreenElement'];
	};

	theme.fullscreenToggle = function() // Apply to DOM object.
	{
		var $this = $(this); // What to toggle; or the toggler itself.
		if($this.data('toggle') === 'fullscreen' && $this.data('target'))
			$this = $('#' + $this.data('target')); // On target.

		if(theme.fullscreenObject()) // If enabled, disable.
		{
			if(document['exitFullscreen']) document['exitFullscreen']();
			else if(document['webkitExitFullscreen']) document['webkitExitFullscreen']();
			else if(document['mozCancelFullScreen']) document['mozCancelFullScreen']();
			else if(document['msExitFullscreen']) document['msExitFullscreen']();
			return; // We're all done in this case (fullscreen now off).
		}
		if($this[0]['requestFullscreen']) $this[0]['requestFullscreen']();
		else if($this[0]['webkitRequestFullscreen']) $this[0]['webkitRequestFullscreen']();
		else if($this[0]['mozRequestFullScreen']) $this[0]['mozRequestFullScreen']();
		else if($this[0]['msRequestFullscreen']) $this[0]['msRequestFullscreen']();
	};

	theme.winOpen = function(url, width, height, name)
	{
		url = (!theme.empty(url)) ? String(url) : '';

		width = (!theme.empty(width) && Number(width) <= screen.width)
			? Number(width) : ((screen.width - 200 >= 500) ? screen.width - 200 : 500);

		height = (!theme.empty(height) && Number(height) <= screen.height)
			? Number(height) : ((screen.height - 200 >= 300) ? screen.height - 200 : 300);

		name = (!theme.empty(name)) ? String(name) : '_winopen';

		var params = 'scrollbars=yes,resizable=yes,centerscreen=yes,modal=yes' +
		             ',width=' + width + ',height=' + height + // Width, height, positions.
		             ',top=' + ((screen.height - height) / 2) + ',left=' + ((screen.width - width) / 2) +
		             ',screenY=' + ((screen.height - height) / 2) + ',screenX=' + ((screen.width - width) / 2);

		var openWin; // Initialize.
		if((openWin = open(url, name, params)))
			openWin.focus();

		return openWin;
	};

	theme.selectAll = function() // Apply to DOM object.
	{
		var $this = $(this); // Works on w/ the `focus` event.

		if($.inArray(this.nodeName.toLowerCase(), ['input', 'textarea']) !== -1)
			$this.one('mouseup', function(e){ e.preventDefault(); }).select();
		else $this.select(); // jQuery love :-)
	};

	theme.resizeIframe = function(object) // Apply to DOM object; or pass one.
	{
		var $iframe = (typeof object === 'object') ? $(object) : $(this);

		if($iframe[0].nodeName.toLowerCase() !== 'iframe')
			return; // Not an iframe tag.

		var lastHeight = $iframe.data('lastHeight') || 0;
		var $iframeHtml = $iframe.contents().find('html');
		var currentHeight = $iframeHtml.outerHeight(true);

		if(currentHeight === lastHeight) return; // Nothing to do.

		$iframeHtml.css('overflow-y', 'hidden'); // Hide IFRAME's vertical scrollbar.
		// This is necessary; otherwise the height could be thrown off due to word wrapping.

		$iframe.data('lastHeight', currentHeight), $iframe.height(currentHeight); // Considers `box-sizing`.
		// See: <http://blog.jquery.com/2012/08/16/jquery-1-8-box-sizing-width-csswidth-and-outerwidth/>
	};

	theme.scrollTo = function(object) // Apply to DOM object; or pass one.
	{
		var $object = (typeof object === 'object') ? $(object) : $(this);

		var fullscreenObject = theme.fullscreenObject();
		var $scroller = (fullscreenObject) ? $(fullscreenObject) : theme.$$('html, body');

		$scroller.animate({scrollTop: ($object.offset().top - 100) + 'px'}, 'fast');
	};

	theme.scrollToHashAnchor = function(hash)
	{
		theme.scrollToHashedAnchor.apply($('<a href="' + theme.escAttr(hash) + '"></a>')[0]);
	};

	theme.scrollToHashedAnchor = function(event, runningAfterDelay) // Apply to DOM object.
	{
		if(this.nodeName.toLowerCase() !== 'a')
			return; // Not an anchor tag.

		if(!runningAfterDelay) // Only check before a delay occurs.
			if($.inArray(this.hash, theme.delayedHashAnchors) !== -1)
			{
				var _this = this; // Need this below (for timeout handler).
				setTimeout(function() // Nothing for now; wait for delay.
				           { theme.scrollToHashedAnchor.apply(_this, [event, true]); }, 100);
				return; // Nothing more to do in this case.
			}
		var hash = this.hash, completed = false, $target, callback;
		if(!theme.isHashAnchorHere.apply(this) || !($target = $(hash)).length)
			return; // Not here; unable to locate target.

		var fullscreenObject = theme.fullscreenObject();
		var $scroller = fullscreenObject ? $(fullscreenObject) : theme.$$('html, body');

		$scroller.animate({scrollTop: ($target.offset().top - 100) + 'px'}, 250, function()
		{
			if(completed) // Animation runs twice against both `html,body`.
			// This completion routine should be fired once only though.
				return; // Nothing more to do here.

			if(location.hash !== hash && typeof history.pushState === 'function')
				history.pushState({}, '', hash);

			if((callback = $target.data('hashCallback')))
				if(typeof window[callback] === 'function')
					window[callback]($target, hash);

			completed = true; // Complete now.
		});
	};

	theme.alert = function(title, body, callback, isError, isConfirm)
	{
		var ac = (isConfirm)
			? 'confirm' : 'alert';

		var $modal = theme.$$('#' + ac);
		var $title = theme.$$('#' + ac + '-title');
		var $body = theme.$$('#' + ac + '-body');
		var $okBtn = theme.$$('#' + ac + '-ok-btn');
		var $noBtn = theme.$$('#' + ac + '-no-btn');
		var $yesBtn = theme.$$('#' + ac + '-yes-btn');
		var $content = $body.closest('.modal-content');
		var $header = $content.find('.modal-header');
		var response = ''; // Initialize response.

		if(theme.empty(title))
			$title.html(''), $header.hide();
		else $title.html(title), $header.show();

		if(isError) // An error?
			$content.addClass('modal-danger');
		else $content.removeClass('modal-danger');

		$body.html(body), $modal.modal('show');
		var $contentForms = $('form', $content);

		$okBtn.add($noBtn).add($yesBtn).off('click.theme');
		$contentForms.off('submit.theme'); // Reset each of these.
		$modal.off('shown.bs.modal'), $modal.off('hide.bs.modal');

		$okBtn.on('click.theme', function(){ response = 'ok'; });
		$noBtn.on('click.theme', function(){ response = 'no'; });
		$yesBtn.on('click.theme', function(){ response = 'yes'; });

		$contentForms.on('submit.theme', // Handle form submissions too.
		                 function(){ response = 'submit', $modal.modal('hide'); });

		$modal.on('shown.bs.modal', // Focus first input field (if applicable).
		          function(){ $contentForms.find(':input').first().focus(); });

		if(typeof callback === 'function') // Hide event handler.
			$modal.on('hide.bs.modal', function(){ callback(response); });
	};

	theme.errorAlert = function(title, body, callback)
	{
		return theme.alert(title, body, callback, true, false);
	};

	theme.confirm = function(title, body, callback, isError)
	{
		return theme.alert(title, body, callback, isError, true);
	};

	theme.addModalBackdrop = function(onClick)
	{
		var $backdrop = $('<div class="modal-backdrop fade"></div>');
		if(typeof onClick === 'function') $backdrop.on('click', onClick);

		theme.$$('body').append($backdrop), $backdrop[0].offsetWidth, $backdrop.addClass('in');
	};

	theme.removeModalBackdrop = function()
	{
		var $backdrop = $('> .modal-backdrop', theme.$$('body')).last();
		$backdrop.removeClass('fade in').fadeOut(200, function(){ $backdrop.remove(); });
	};

	theme.bodyPaddingResize = function()
	{
		var $body = theme.$$('body');
		var $navbar = theme.$$('#navbar');

		if(!$navbar.length) return; // Nothing to do.

		$body.css('padding-top', ($navbar.height() + 30) + 'px');
	};

	theme.initialSearchFocusSelection = function()
	{
		var $s = theme.$$('#navbar #s');

		if(!$s.length || theme.hashHasAnchor(location.hash))
			return; // Nothing to do in these cases.

		if(!theme.empty($s.val()) || theme.$$('#content').hasClass('error'))
			$s.focus().select(); // Focus #s field in #navbar.
	};

	theme.initialActiveNavTab = function()
	{
		var tab = theme.getQueryVar('tab').toLowerCase();
		if(theme.empty(tab)) return; // Nothing to do.

		$('ul.nav.nav-tabs').first().find('> li')
			.each(function() // Check all tabs.
			      {
				      var $tabAnchor = $(this).find('> a');
				      if($.trim($tabAnchor.text().toLowerCase()) !== tab)
					      return; // Not this one.

				      $tabAnchor.click(); // Simulate.
				      return false; // Stop the loop now.
			      });
	};

	theme.sidebarToggle = function()
	{
		var $sidebar = theme.$$('#sidebar');
		var $icon = theme.$$('> .toggle > button > i', $sidebar, 'sidebar');

		if($sidebar.hasClass('open')) // Close.
			theme.removeModalBackdrop(), $sidebar.css({left: '32%'}),
				$icon.removeClass('fa-flip-horizontal'), $sidebar.removeClass('open');

		else // Open (it's currently closed).
			theme.addModalBackdrop(theme.sidebarToggle), $sidebar.css({left: '-38%'}),
				$icon.addClass('fa-flip-horizontal'), $sidebar.addClass('open');
	};

	theme.sidebarHeightResize = function()
	{
		var $sidebar = theme.$$('#sidebar');
		var $sidebarWidgets = theme.$$('#sidebar > .widgets');

		if(!$sidebar.length || !$sidebarWidgets.length)
			return; // Nothing to do in these cases.

		var sidebarWidgetsScrollHeight = $sidebarWidgets[0].scrollHeight;
		var p80WindowHeight = ($window.height() / 100) * 80;

		if(sidebarWidgetsScrollHeight < p80WindowHeight)
			$sidebar.css('height', (sidebarWidgetsScrollHeight + 2) + 'px');
		else $sidebar.css('height', '80%');
	};

	theme.footbarStickyResize = function()
	{
		var $html = theme.$$('html');
		var $container = theme.$$('#container');
		var $footbar = theme.$$('#footbar');

		if(!$container.length || !$footbar.length)
			return; // Nothing to do in these cases.

		var containerMinimumHeight = // Window - HTML top margin - container offset and #footbar.
			// Add HTML top margin because we have an HTML height of 100% plus margin-top when admin bar is in use.
			$window.height() + parseInt($html.css('margin-top')) - $container.offset().top - $footbar.outerHeight(true);

		$container.css('min-height', containerMinimumHeight + 'px');
	};

	theme.imageHrefsFilter = function() // Apply to DOM object; only as jQuery filter.
	{
		return (typeof this.href === 'string' && /\.(?:gif|jpg|jpeg|png)(?:[?#].*)?$/i.test(this.href));
	};

	theme.preEmbedlyFilter = function() // Apply to DOM object; only as jQuery filter.
	{
		if(this.nodeName.toLowerCase() !== 'a')
			return false; // Not an anchor tag.

		var matches; // Holds regex results; as seen below.

		if((matches = /\/\/codepen\.io\/([a-zA-Z0-9]+)\/pen\/([a-zA-Z0-9]+)$/i.exec(this.href)))
			return theme.preEmbedlyCodepen(matches[0], matches[1], matches[2], this);

		if((matches = /\/\/gist\.github\.com\/([a-zA-Z0-9]+)\/([a-zA-Z0-9]+)$/i.exec(this.href)))
			return theme.preEmbedlyGist(matches[0], matches[1], matches[2], this);

		return true; // Filter through; e.g. use Embedly.
	};

	theme.preEmbedlyCodepen = function(pen, user, slug, anchor) // jQuery sub-filter.
	{
		pen = String(pen), user = String(user), slug = String(slug);

		var embed = pen.replace('/pen/', '/embed/') + '?height=268&amp;slug-hash=' + encodeURIComponent(slug) + '&amp;user=' + encodeURIComponent(user);

		var $iframe = '<iframe id="codepen-embed-' + theme.escAttr(slug + theme.mtRand()) + '" src="' + theme.escAttr(embed) + '"' +
		              ' class="codepen-embed-iframe" scrolling="no" frameborder="0" allowtransparency="true" style="height:268px;"></iframe>';

		$(anchor).parent('p').replaceWith($iframe); // Custom IFRAME embed code.

		return false; // Custom exclusion (e.g. we are embedding this one).
	};

	theme.preEmbedlyGist = function(gist, user, slug, anchor) // jQuery sub-filter.
	{
		gist = String(gist), user = String(user), slug = String(slug);

		var $iframe = $('<iframe id="gist-embed-' + theme.escAttr(slug + theme.mtRand()) + '" src="about:blank"' +
		                ' class="gist-embed-iframe" scrolling="no" frameborder="0" allowtransparency="true" style="height:268px;"></iframe>');

		$(anchor).parent('p').replaceWith($iframe); // Custom IFRAME embed code.

		var iframeContent = '<!DOCTYPE html>' + // Write <script>.
		                    '<html>' +
		                    '   <head><style>body, .gist, .gist-file { margin:0 !important; }</style></head>' +
		                    '   <body onload="parent.theme.resizeIframe.apply(parent.theme.$$(\'#' + theme.escAttr($iframe[0].id) + '\')[0]);">' +
		                    '      <script type="text/javascript" src="' + theme.escAttr(gist + '.js') + '"></script>' +
		                    '   </body>' +
		                    '</html>', iframeDoc = $iframe[0].contentWindow.document;

		iframeDoc.open(), iframeDoc.write(iframeContent), iframeDoc.close();

		return false; // Custom exclusion (e.g. we are embedding this one).
	};

	theme.addthisShare = function() // Apply to DOM object.
	{
		var data = $(this).data(), shareLink, winSize;

		if(typeof data.publisher !== 'string' || theme.empty(data.publisher))
			data.publisher = theme.vars['addthisPublisher'];

		if(typeof data.service !== 'string' || theme.empty(data.service))
			data.service = 'more'; // Default value.

		if(typeof data.title !== 'string' || theme.empty(data.title)
		   || data.title === 'document.title')
		{
			var ogTitle = $('head > meta[property="og:title"]').attr('content');
			data.title = !theme.empty(ogTitle) ? ogTitle : document.title;
		}
		if(typeof data.url !== 'string' || theme.empty(data.url)
		   || data.url === 'document.URL')
		{
			var ogUrl = $('head > meta[property="og:url"]').attr('content');
			data.url = !theme.empty(ogUrl) ? ogUrl : document.URL;
		}
		if(typeof data.description !== 'string' || theme.empty(data.description)
		   || data.description === 'document.description')
		{
			var ogDescription = $('head > meta[property="og:description"]').attr('content');
			var metaDescription = $('head > meta[name="description"]').attr('content');
			data.description = !theme.empty(ogDescription) ? ogDescription : (metaDescription ? metaDescription : '');
		}
		if(typeof data.image !== 'string' || theme.empty(data.image)
		   || data.image === 'document.image')
		{
			var ogImage = $('head > meta[property="og:image"]').attr('content');
			data.image = !theme.empty(ogImage) ? ogImage : '';
		}
		var serviceWinSizes = {
			google_plus: {width: 500, height: 400},
			linkedin   : {width: 520, height: 500},
			wordpress  : {width: 700, height: 355},
			email      : {width: 520, height: null},
			more       : {width: 490, height: 500}
		};
		if(typeof serviceWinSizes[data.service] === 'object')
			winSize = serviceWinSizes[data.service];
		else winSize = {width: 800, height: 400};

		var serviceTranslations = {
			google_plus: 'google_plusone_share',
			pinterest  : 'pinterest_share',
			amazon     : 'amazonwishlist'
		};
		if(typeof serviceTranslations[data.service] === 'string')
			data.service = serviceTranslations[data.service];

		shareLink = // <http://support.addthis.com/customer/portal/articles/381265-addthis-sharing-endpoints#services>
			'http://api.addthis.com/oexchange/0.8' + (data.service === 'more' ? '' : '/forward/' + encodeURIComponent(data.service)) + '/offer?ct=1' +
			'&pubid=' + encodeURIComponent(data.publisher) + '&url=' + encodeURIComponent(data.url) + '&title=' + encodeURIComponent(data.title) +
			'&description=' + encodeURIComponent(data.description) + '&image=' + encodeURIComponent(data.image) + '&screenshot=' + encodeURIComponent(data.image);

		theme.winOpen(shareLink, winSize.width, winSize.height);
	};

	theme.popover = function() // Apply to DOM object.
	{
		var $this = $(this), themeContentSource = $this.data('themeContentSource');

		if(!theme.empty(themeContentSource) && typeof theme[themeContentSource] === 'function')
			$this.popover({content: theme[themeContentSource]});

		else $this.popover(); // Default behavior (no theme content source).
	};
	// Wrapping functions for popovers...
	theme.wrapMdSyntax = function(){ return theme.wrapPopoverMarkup(theme.vars['mdSyntax']); };
	theme.wrapAllowedTags = function(){ return theme.wrapPopoverMarkup(theme.vars['allowedTags']); };
	theme.wrapEmbedlySyntax = function(){ return theme.wrapPopoverMarkup(theme.vars['embedlySyntax']); };
	theme.wrapPopoverMarkup = function(markup)
	{
		var $markup = // Constructs HTML wrapper in jQuery.
			$('<div>' + // Temporary outer wrapper.
			  '  <div class="no-margin text-center font-80">' +
			  '      ' + markup +
			  '   </div>' +
			  '</div>');
		if(typeof hljs === 'object') // Highlight.js.
			$markup.find('pre > code').not('.no-highlight')
				.each(function(index, object){ hljs.highlightBlock(object); });
		return $markup.html(); // Markup.
	};

	theme.winpop = function() // Apply to DOM object.
	{
		var $this = $(this), href = $this.attr('href'),
			width = $this.data('popWidth'), height = $this.data('popHeight'),
			targetName = $this.data('popTargetName');

		theme.winOpen(href, width, height, targetName);
	};

	theme.reCAPTCHADisplay = function() // Apply to DOM object.
	{
		var $this = $(this), _theme = $this.data('theme'), lang = $this.data('lang');

		if(typeof Recaptcha === 'object') // Is reCAPTCHA even available?
			Recaptcha.create(theme.vars['reCAPTCHAPublicKey'], this, {theme: _theme, lang: lang});
	};

	theme.zeroClipConfigured = false, theme.zeroClip = function() // Apply to DOM object.
	{
		if(typeof ZeroClipboard !== 'function')
			return; // Nothing to do.

		if(!theme.zeroClipConfigured) // Configure ZeroClip.
		{
			ZeroClipboard.config({
				moviePath     : theme.vars['zeroClipSwfUrl'], // Deprecated in v2.x.
				swfPath       : theme.vars['zeroClipSwfUrl'], // New way in v2.x+.
				trustedDomains: [location.host, 'cdnjs.cloudflare.com'],
				hoverClass    : 'hover', activeClass: 'active',
				cacheBust     : false, forceHandCursor: true
			});
			theme.$$('#alert').on('shown.bs.modal', function()
			{
				var $zeroClipFallback = $('.zeroclip-fallback', theme.$$('#alert'));
				if($zeroClipFallback.length) theme.selectAll.apply($zeroClipFallback[0]);
			});
			theme.zeroClipConfigured = true; // Configured now.
		}
		var $this = $(this), zeroClip, clipboardText = String($this.data('clipboardText'));

		(zeroClip = new ZeroClipboard(this)) // Tooltip events.
			.on('mouseover', function()
			    {
				    var hasTooltip = $this.data('hasTooltip');
				    if(hasTooltip) $this.tooltip('destroy'); // Destroy existing.
				    $this.tooltip({trigger: 'manual', title: theme.vars['zeroClip_i18nCopy']});
				    $this.data('hasTooltip', true), $this.tooltip('show'); // Configured; now show.
			    })
			.on('mouseout', function(){ if($this.data('hasTooltip')) $this.tooltip('hide'); })

			.on('complete', function() // Now display confirmation; copied :-)
			    {
				    var hasTooltip = $this.data('hasTooltip');
				    if(hasTooltip) $this.tooltip('destroy'); // Destroy existing.
				    $this.tooltip({trigger: 'manual', title: theme.vars['zeroClip_i18nCopied']});
				    $this.data('hasTooltip', true), $this.tooltip('show'), setTimeout(function(){ $this.tooltip('hide'); }, 1000);
			    });
		$this.on('click', function(event) // Fallback.
		{
			event.preventDefault(); // Prevent default behavior.

			theme.alert(theme.vars['zeroClip_i18nFallback'],
			            '<form class="form-group">' +
			            '  <div class="input-group">' +
			            '     <span class="input-group-addon"><i class="fa fa-clipboard"></i></span>' +
			            '     <textarea class="zeroclip-fallback form-control" spellcheck="false" rows="1">' + theme.escAttr(clipboardText) + '</textarea>' +
			            '  </div>' +
			            '</form>');
		});
	};

	theme.togetherJS = function(event) // Apply to DOM object.
	{
		if(typeof TogetherJS !== 'function')
			return; // Nothing to do.

		event.preventDefault(); // Prevent default behavior.

		if(TogetherJS.running) TogetherJS(); // Toggle off in this case.

		else if(theme.fullscreenObject()) // Does not work in fullscreen mode.
			theme.errorAlert('', theme.vars['togetherJS_i18nFserror']);

		else theme.confirm(theme.vars['togetherJS_i18nTitle'],
		                   '<div>' +
		                   '  <i class="fa fa-group fa-4x pull-right l-margin b-margin"></i>' +
		                   '     ' + theme.vars['togetherJS_i18nInfo'] +
		                   '  </div>' +
		                   '</div>',
		                   function(response)
		                   {
			                   if(response !== 'yes')  return;
			                   TogetherJS(); // Start TogetherJS.
		                   });
	};

	theme.loginBoxAjaxSetup = false, theme.loginBoxHandler = function() // Fires on the `shown.bs.modal` (Bootstrap).
	{
		var $loginBox = theme.$$('#login-box'); // Use theme cache for this to optimize.

		$('.username input', $loginBox).focus(); // Focus username.

		if(!theme.vars['navbarLoginRegistrationViaAjax'] || theme.loginBoxAjaxSetup)
			return; // Nothing more to do here.

		$loginBox.find('form').on('submit', function(event)
		{
			event.preventDefault(); // Via AJAX (do NOT submit).

			var username = $.trim($loginBox.find('input[name="log"]').val());
			var password = $.trim($loginBox.find('input[name="pwd"]').val());
			var remember = ($loginBox.find('input[name="rememberme"]:checked').length) ? 1 : 0;

			var postVars = {
				theme: {
					ajax_login: {
						username: username,
						password: password,
						remember: remember
					}
				}
			};
			$loginBox.find('.modal-body').find('.alert').remove(); // Remove any previous alerts.
			$loginBox.find('button[type="submit"]').attr('disabled', 'disabled').find('i.fa').addClass('fa-spinner fa-spin');

			$.ajax({
				type: 'POST', url: theme.vars['ajaxLoginRegistrationUrl'], data: postVars, xhrFields: {withCredentials: true}, success: function(response)
				{
					$loginBox.find('button[type="submit"]').removeAttr('disabled', 'disabled').find('i.fa').removeClass('fa-spinner fa-spin');

					if(typeof response !== 'object') // AJAX connection error; or unexpected server error.
						$loginBox.find('.modal-body').prepend('<div class="alert alert-danger"><ul class="list-unstyled"><li>' + theme.vars['ajax_i18nError'] + '</li></ul></div>');

					else if(response.errors.length) // Unsuccessfull; i.e. we have errors in the response?
						$loginBox.find('.modal-body').prepend('<div class="alert alert-danger"><ul class="list-unstyled"><li>' + response.errors.join('</li><li>') + '</li></ul></div>');

					else $loginBox.modal('hide'), theme.$$('#navbar').html($(response['navbar']).html()), $loginBox.trigger('loggedIn', response.user);
				}
			});
		}), theme.loginBoxAjaxSetup = true; // Flag as true; all setup now.
	};

	theme.registrationBoxAjaxSetup = false, theme.registrationBoxHandler = function() // Fires on the `shown.bs.modal` (Bootstrap).
	{
		var $loginBox = theme.$$('#login-box'); // Use theme cache for this to optimize.
		var $registrationBox = theme.$$('#registration-box'); // Cache this too.

		$('.email input', $registrationBox).focus(); // Focus email address.

		if(!theme.vars['navbarLoginRegistrationViaAjax'] || theme.registrationBoxAjaxSetup)
			return; // Nothing more to do here.

		$registrationBox.find('form').on('submit', function(event)
		{
			event.preventDefault(); // Via AJAX (do NOT submit).

			if(typeof Recaptcha !== 'object') return; // Not configured properly.

			var email = $.trim($registrationBox.find('input[name="email"]').val());
			var username = $.trim($registrationBox.find('input[name="username"]').val());
			var password = $.trim($registrationBox.find('input[name="password"]').val());
			var password2 = $.trim($registrationBox.find('input[name="password2"]').val());
			var remember = ($registrationBox.find('input[name="remember"]:checked').length) ? 1 : 0;

			var recaptchaChallenge = $.trim($registrationBox.find('input[name="recaptcha_challenge_field"]').val());
			var recaptchaResponse = $.trim($registrationBox.find('input[name="recaptcha_response_field"]').val());

			var postVars = {
				theme: {
					ajax_registration: {
						email                    : email, username: username,
						password                 : password, password2: password2,
						remember                 : remember, // Remember?
						recaptcha_challenge_field: recaptchaChallenge,
						recaptcha_response_field : recaptchaResponse
					}
				}
			};
			$registrationBox.find('.modal-body').find('.alert').remove(); // Remove any previous alerts.
			$registrationBox.find('button[type="submit"]').attr('disabled', 'disabled').find('i.fa').addClass('fa-spinner fa-spin');

			$.ajax({
				type: 'POST', url: theme.vars['ajaxLoginRegistrationUrl'], data: postVars, xhrFields: {withCredentials: true}, success: function(response)
				{
					$registrationBox.find('button[type="submit"]').removeAttr('disabled', 'disabled').find('i.fa').removeClass('fa-spinner fa-spin');

					if(typeof response !== 'object') // AJAX connection error; or unexpected server error.
						$registrationBox.find('.modal-body').prepend('<div class="alert alert-danger"><ul class="list-unstyled"><li>' + theme.vars['ajax_i18nError'] + '</li></ul></div>'), Recaptcha.reload();

					else if(response.errors.length) // Unsuccessfull; i.e. we have errors in the response?
						$registrationBox.find('.modal-body').prepend('<div class="alert alert-danger"><ul class="list-unstyled"><li>' + response.errors.join('</li><li>') + '</li></ul></div>'), Recaptcha.reload();

					else $registrationBox.modal('hide'), theme.$$('#navbar').html($(response['navbar']).html()), $loginBox.trigger('loggedIn', response.user);
				}
			});
		}), theme.registrationBoxAjaxSetup = true; // Flag as true; all setup now.
	};

	theme.commentMessagePreview = function()
	{
		var $message = theme.$$('#comment-message');
		var $preview = theme.$$('#comment-message-preview');

		$preview.html(''); // Always clear before new preview.
		var postVars = {singular_id: theme.vars['singularId'], message: $message.val()};
		postVars = {theme: {ajax_comment_preview: postVars}}; // Wrappers.

		if(!theme.empty(postVars.theme.ajax_comment_preview.message))
			$preview.load(theme.vars['ajaxUrl'], postVars, function()
			{
				theme.scrollToHashAnchor('#comment-message' + // Consider viewport.
				                         (($window.height() < 500) ? '-preview' : ''));
				theme.onReady(null, $preview.parent('div'));
			});
	};

	theme.commentMessagePreviewClose = function()
	{
		theme.$$('#comment-message-preview').html(''), theme.scrollToHashAnchor('#respond');
	};

	theme.cancelCommentReply = function()
	{
		if(typeof window.addComment === 'object') theme.$$('#cancel-comment-reply-link').click();
	};

	theme.onReady = function(_, $context) // jQuery DOM ready event handler; and reprocessor.
		// This can also be called with a specific `$context`; in order to reprocess ready event handlers.
		// Reprocessing is helpful in cases where content is generated dynamically; or via AJAX calls on-site.
	{
		var isInitial = true; // Default value.
		if(theme.isset($context)) // In a specific context?
			isInitial = false; // Not the default/initial context in this case.
		else $context = $document; // Default context.

		var cache = {$$: {}}; // Cache.

		/** @return {jQuery} For IDEs. */
		var $$ = function(selector, $$context, contextName)
		{
			selector = String(selector); // Force string.
			if(!($$context instanceof jQuery)) $$context = $context;
			if(typeof contextName !== 'string') contextName = '';

			if(cache.$$[selector + contextName] instanceof jQuery)
				return cache.$$[selector + contextName];
			return (cache.$$[selector + contextName] = $(selector, $$context));
		};
		var handlers = [ // Array w/ all handlers (in order of precedence).

			function() // Handle initial active tab.
			{
				if(!isInitial) return; // Initial context only.

				theme.initialActiveNavTab(); // Set initial active tab.
			},
			function() // Handle initial hash anchor.
			{
				if(!isInitial) return; // Initial context only.

				if(theme.hashHasAnchor(location.hash)) // Scroll :-)
					theme.scrollToHashAnchor(location.hash);
			},
			function() // Handle body padding w/ #navbar adjustments.
			{
				if(!isInitial) return; // Initial context only.

				if(theme.$$('#navbar').length) // Only if there's a #navbar.
					$window.on('load resize', theme.bodyPaddingResize);
			},
			function() // Handle #s focus (on searches & content errors).
			{
				if(!isInitial) return; // Initial context only.

				if(theme.$$('#navbar #s').length) // Only if there's an #s.
					theme.initialSearchFocusSelection(); // Intelligently.
			},
			function() // Handle slide-in sidebar toggle.
			{
				if(!isInitial) return; // Initial context only.

				if(theme.$$('#sidebar').length) // Only if there's a #sidebar.
					theme.$$('#sidebar > .toggle').on('click', theme.sidebarToggle),
						$window.on('load resize', theme.sidebarHeightResize);
			},
			function() // Handle sticky #footbar (push to the bottom).
			{
				if(!isInitial) return; // Initial context only.

				if(theme.$$('#footbar').length) // Only if there's a #footbar.
					$window.on('load resize', theme.footbarStickyResize),
						setInterval(theme.footbarStickyResize, 1000);
			},
			function() // Handle `pre > code` markers.
			{
				$$('pre > code').addClass('pre').parent().addClass('code');
			},
			function() // Handle gallery captions on mobile devices.
			{
				$$('dd.gallery-caption, dd.wp-gallery-caption').addClass('hidden-sm hidden-xs');
			},
			function() // Handle click events related to comment message previews.
			{
				$$('#comment-message-preview-button').on('click', theme.commentMessagePreview);
				$$('#comment-message-preview-close').on('click', theme.commentMessagePreviewClose);
			},
			function() // Handle default `#` anchors; prevent default behavior on these.
			{
				$$('a[href*="#"]').filter('[href="#"]').on('click', theme.preventDefault);
			},
			function() // Focus username and setup/configure `#login-box`.
			{
				$$('#login-box').on('shown.bs.modal', theme.loginBoxHandler);
			},
			function() // Focus email and setup/configure `#registration-box`.
			{
				$$('#registration-box').on('shown.bs.modal', theme.registrationBoxHandler);
			},
			function() // Auto-cancel replies w/ anchors pointing to `#respond`.
			{
				$$('a[href*="#"]').filter('[href="#respond"]')
					.not('#cancel-comment-reply-link, .comment-reply-link')
					.on('click', theme.cancelCommentReply);
			},
			function() // Handle hash anchors; auto-scroll to anchor's target.
			{
				$$('a[href*="#"]').filter(theme.isHashAnchorHere)
					.not('#cancel-comment-reply-link, [data-slide], [data-toggle~="modal"], [data-toggle~="tab"], .no-scroll')
					.on('click', theme.preventDefault).on('click', theme.scrollToHashedAnchor);
			},
			function() // Enable input select alls.
			{
				$$('input[data-toggle~="select-all"]').on('focus', theme.selectAll);
			},
			function() // Enable Bootstrap tooltips.
			{
				$$('[data-toggle~="tooltip"]').data('container', 'body').tooltip();
			},
			function() // Enable Bootstrap popovers; w/ custom theme sources.
			{
				$$('[data-toggle~="popover"]').data('container', 'body').each(theme.popover);
			},
			function() // Enable theme popup windows; w/ custom width/height.
			{
				$$('[data-toggle~="winpop"]').on('click', theme.preventDefault).on('click', theme.winpop);
			},
			function() // Handle social networking share buttons.
			{
				$$('[data-toggle~="share"]').on('click', theme.addthisShare);
			},
			function() // tabDrop; for long lines of tabs/pills.
			{
				if(typeof $.fn.tabdrop !== 'function')
					return; // Nothing to do.

				$$('.nav-tabs, .nav-pills').filter('[data-toggle~="tabdrop"]')
					// tabDrop reads offsetHeight against it's relative parent; so they MUST be in display.
					.removeClass('hide no-initial-display').tabdrop({text: '<i class="fa fa-bars"></i>'});
			},
			function() // Textarea elements that support tabs.
			{
				if(typeof tabOverride !== 'object')
					return; // Nothing to do.

				tabOverride.tabSize(theme.vars['taboverrideSize']);
				tabOverride.set($$('textarea[data-toggle~="taboverride"]'));
			},
			function() // Handle fancybox; including galleries.
			{
				if(typeof $.fancybox !== 'function')
					return; // Nothing to do.

				$$('[data-toggle~="fancybox"]').fancybox();
				$$('.single-format-image #content > * > .data a').filter(theme.imageHrefsFilter).fancybox();
				$$('.single-attachment #content > * > .data .attachment a').filter(theme.imageHrefsFilter).fancybox();
				var $galleryAnchors = $$('[data-toggle~="fancybox-gallery"] a, .gallery a').filter(theme.imageHrefsFilter);
				var $galleryImg1 = $galleryAnchors.first().find('> img');
				var galleryOptions = {
					helpers: {
						title  : {type: 'inside'},
						buttons: {position: 'top'}
					}
				};
				if($galleryAnchors.length >= 15) // Only use thumbnails when applicable.
					galleryOptions.helpers.thumbs = {width: $galleryImg1.width(), height: $galleryImg1.height(), position: 'bottom'};

				$galleryAnchors.attr('data-fancybox-group', 'gallery').fancybox(galleryOptions);
			},
			function() // Handle embedly; e.g. `p > a:only-child` links.
			{
				if(typeof $.embedly !== 'object')
					return; // Nothing to do.

				$.embedly.defaults.key = theme.vars['embedlyKey'];

				$.embedly.defaults.method = 'replaceParent';

				$.embedly.defaults.className = 'embedly';
				$.embedly.defaults.className += ' no-x-overflow';
				$.embedly.defaults.className += ' clearfix';

				$.embedly.defaults.query = {
					maxwidth: 1200,
					chars   : 100,
					wmode   : 'transparent'
				};
				$$('p > a:only-child').filter(theme.isEmbedAnchor).not('.no-embed')
					.filter(theme.preEmbedlyFilter).embedly();
			},
			function() // Handle reCAPTCHA fields.
			{
				if(typeof Recaptcha !== 'object')
					return; // Nothing to do.

				$$('[data-toggle~="recaptcha"]').each(theme.reCAPTCHADisplay);
			},
			function() // Handle `pre > code` syntax highlighting.
			{
				if(typeof hljs !== 'object')
					return; // Nothing to do.

				$$('pre > code.language-wpsc').removeClass('language-wpsc').addClass('language-ini'),
					$$('pre > code').not('.no-highlight, .nohighlight, .language-none, .language-txt, .language-text')
						.each(function(index, object){ hljs.highlightBlock(object); });
			},
			function() // Handle zeroClip copiers.
			{
				if(typeof ZeroClipboard !== 'function')
					return; // Nothing to do.

				$$('[data-toggle~="zeroclip"][data-clipboard-text]').not('.no-zeroclip').each(theme.zeroClip);
			},
			function() // Handle fullscreen togglers.
			{
				$$('[data-toggle~="fullscreen"][data-target]').not('.no-fullscreen').on('click', theme.fullscreenToggle);
			},
			function() // Handle TogetherJS togglers.
			{
				if(typeof TogetherJS !== 'function')
					return; // Nothing to do.

				$$('[data-toggle~="togetherjs"]').not('.no-togetherjs').on('click', theme.togetherJS);

				if(!isInitial) return; // All done in this case.

				TogetherJS.on('ready', function()
				{
					$('button[data-toggle~="togetherjs"]').addClass('active');
				});
				TogetherJS.on('close', function()
				{
					$('button[data-toggle~="togetherjs"]').removeClass('active');
				});
			}];
		$.each(handlers, function(index, handler){ handler(); }); // Fire handlers in order.
	};
	$.fn.modal.Constructor.prototype.enforceFocus = function() // Bootstrap/ZeroClipboard fix.
		// See also <https://github.com/zeroclipboard/zeroclipboard/blob/master/docs/instructions.md>
	{
		$document.off('focusin.bs.modal') // Guard against infinite focus loop.
			.on('focusin.bs.modal', $.proxy(function(e)
			                                {
				                                if(this.$element[0] !== e.target && !this.$element.has(e.target).length
				                                   && !$(e.target).closest('.global-zeroclipboard-container').length)
					                                this.$element.focus();
			                                }, this));
	};
	$document.ready(theme.onReady); // On DOM ready (initial).
})(jQuery); // End closure.