(function($)
{
	'use strict'; // Standards.

	var theme = {}, $window = $(window), $document = $(document);

	theme.onReady = function() // jQuery DOM ready event handler.
	{
		var $menuPage = $('#theme-menu-page');

		var codeMirrors = [],
			cmOptions = {
				lineNumbers  : true,
				matchBrackets: true,
				theme        : 'twilight',
				tabSize      : 3, indentWithTabs: true
			};
		$('[data-cm-mode]', $menuPage)
			.each(function() // Setup CodeMirrors.
			      {
				      var $this = $(this),
					      $textarea = $('> textarea', $this),
					      cmMode = $this.data('cmMode');

				      if($textarea.length !== 1) return; // Invalid markup.

				      $this.addClass('cm'), // See `menu-pages.css` to customize styles.
					      codeMirrors.push(CodeMirror.fromTextArea($textarea[0], $.extend({}, cmOptions, {mode: cmMode})));
			      });
		var refreshCodeMirrors = function(/* Refresh CodeMirrors. */)
		{
			$.each(codeMirrors, function(i, codeMirror){ codeMirror.refresh(); });
		};
		$('.theme-menu-page-panels-open', $menuPage).on('click', function()
		{
			$('.theme-menu-page-panel-heading', $menuPage).addClass('open')
				.next('.theme-menu-page-panel-body').addClass('open'),
				refreshCodeMirrors(); // Refresh CodeMirrors also.
		});
		$('.theme-menu-page-panels-close', $menuPage).on('click', function()
		{
			$('.theme-menu-page-panel-heading', $menuPage).removeClass('open')
				.next('.theme-menu-page-panel-body').removeClass('open');
		});
		$('.theme-menu-page-panel-heading', $menuPage).on('click', function()
		{
			$(this).toggleClass('open') // Togglers.
				.next('.theme-menu-page-panel-body').toggleClass('open'),
				refreshCodeMirrors(); // Refresh CodeMirrors also.
		});
		$('[data-action]', $menuPage).on('click', function()
		{
			var $this = $(this), data = $this.data();
			if(typeof data.confirmation !== 'string' || confirm(data.confirmation))
				location.href = data.action;
		});
		$('select[name$="_enable\\]"], select[name$="_enable_flavor\\]"]', $menuPage).not('.no-if-enabled').on('change', function()
		{
			var $this = $(this), thisName = $this[0].name, thisValue = $this.val(),
				$thisPanel = $this.closest('.theme-menu-page-panel');

			if((thisName.indexOf('_enable]') !== -1 && (thisValue === '' || thisValue === '1'))
			   || (thisName.indexOf('_flavor]') !== -1 && thisValue !== '0')) // Enabled?
				$thisPanel.find('.theme-menu-page-panel-if-enabled').css('opacity', 1).find(':input').removeAttr('readonly');
			else $thisPanel.find('.theme-menu-page-panel-if-enabled').css('opacity', 0.4).find(':input').attr('readonly', 'readonly');
		})
			.trigger('change'); // Initialize.
	};
	$document.ready(theme.onReady); // On DOM ready.
})(jQuery);