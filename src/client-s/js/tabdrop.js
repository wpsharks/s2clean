/* =========================================================
 * bootstrap-tabdrop.js
 * http://www.eyecon.ro/bootstrap-tabdrop
 * MODIFIED by: JasWSInc <http://www.websharks-inc.com>
 * GitHub <https://github.com/JasWSInc/bootstrap-tabdrop>
 * =========================================================
 * Copyright 2012 Stefan Petre
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================= */

!function( $ ) {

	var WinReszier = (function(){
		var registered = [];
		var inited = false;
		var timer;
		var resize = function(ev) {
			clearTimeout(timer);
			timer = setTimeout(notify, 100);
		};
		var notify = function() {
			for(var i=0, cnt=registered.length; i<cnt; i++) {
				registered[i].apply();
			}
		};
		return {
			register: function(fn) {
				registered.push(fn);
				if (inited === false) {
					$(window).bind('resize', resize);
					inited = true;
				}
			},
			unregister: function(fn) {
				for(var i=0, cnt=registered.length; i<cnt; i++) {
					if (registered[i] == fn) {
						delete registered[i];
						break;
					}
				}
			}
		}
	}());

	var clipString = function(string, maxLength)
		{
			if(!string || !(string = String(string)))
				return string; // Nothing to do.

			maxLength = (maxLength < 4) ? 4 : maxLength;

			string = $.trim(string.replace(/\s+/g, ' '));

			if(string.length <= maxLength)
				return string; // Done.

			var fullString = string, // Working vars.
				halfMaxLength = Math.floor(maxLength / 2),
				firstClip, secondClip;

			firstClip = halfMaxLength - 3;
			string = (firstClip >= 1) // Something?
				? fullString.substr(0, firstClip) + '...'
				: '...'; // Ellipsis only.

			secondClip = fullString.length - (maxLength - string.length);
			string += (secondClip >= 0 && secondClip >= firstClip)
				? fullString.substr(secondClip)
				: ''; // Nothing more.

			return string;
		};

	var TabDrop = function(element, options) {
		this.element = $(element);
		this.dropdown = $('<li class="dropdown hide pull-right tabdrop"><a class="dropdown-toggle" data-toggle="dropdown" href="#">'+options.text+' <b class="caret"></b></a><ul class="dropdown-menu"></ul></li>')
							.prependTo(this.element);
		if (this.element.parent().is('.tabs-below')) {
			this.dropdown.addClass('dropup');
		}
		var _this = this; // These needed below.

		this.element.on('click', '> li:not(.tabdrop)', function(){
			_this.dropdown.find('.dropdown-toggle').html(options.text+' <b class="caret"></b>');
		});
		this.dropdown.on('click', '> .dropdown-menu > li', function(){
			_this.dropdown.find('.dropdown-toggle').html(clipString($(this).text(), 45)+' <b class="caret"></b>'), _this.layout();
		});
		WinReszier.register($.proxy(this.layout, this)), this.layout();
	};

	TabDrop.prototype = {
		constructor: TabDrop,

		layout: function() {
			var collection = [];
			this.dropdown.removeClass('hide');
			this.element
				.append(this.dropdown.find('li'))
				.find('>li')
				.not('.tabdrop')
				.each(function(){
					if(this.offsetTop > 0) {
						collection.push(this);
					}
				});
			if (collection.length > 0) {
				collection = $(collection);
				this.dropdown
					.find('ul')
					.empty()
					.append(collection);
				if (this.dropdown.find('.active').length == 1) {
					this.dropdown.addClass('active');
				} else {
					this.dropdown.removeClass('active');
				}
			} else {
				this.dropdown.addClass('hide');
			}
		}
	};

	$.fn.tabdrop = function ( option ) {
		return this.each(function () {
			var $this = $(this),
				data = $this.data('tabdrop'),
				options = typeof option === 'object' && option;
			if (!data)  {
				$this.data('tabdrop', (data = new TabDrop(this, $.extend({}, $.fn.tabdrop.defaults,options))));
			}
			if (typeof option == 'string') {
				data[option]();
			}
		})
	};

	$.fn.tabdrop.defaults = {
		text: '<i class="fa fa-bars"></i>'
	};

	$.fn.tabdrop.Constructor = TabDrop;

}( window.jQuery );