/**
 * @file
 * Contains the definition of the behaviour main.js.
 */

(function ($, Drupal, drupalSettings) {

  'use strict';

  Drupal.behaviors.renderAD = {
  	attach: function(context, settings) {

			if (drupalSettings.advertising.advertisingJS.data_entity === '') {

				$('.content-div', context).once('emptyoption').append('<div class="empty-option">there are still no configuration entities created.</div>');

			} else {

				var dataEntity = drupalSettings.advertising.advertisingJS.data_entity;
				var breakpoints = drupalSettings.advertising.advertisingJS.data_breakpoints;

				// Devices width
				var devicesWidth = [415, 770, 3840];

				$('[ class^="content-div"]', context).once('noemptyoption').each(function() {

          $.each(dataEntity, function( position ) {

            $('.content-div-' + dataEntity[position]['id_ad'] + '', context).append('<div id="div-container" class=' + dataEntity[position]['id_ad'] + '></div>');
  					$('[ class^="' + dataEntity[position]['id_ad'] + '"]', context).append('<iframe frameborder="0" scrolling="no" src=' + dataEntity[position]['url_ad'] + '></iframe>');

            $.each(breakpoints, function(index){

  						if ($(window).width() <= devicesWidth[0]) {

  							if (breakpoints[position]['form'][index]['width'] <= devicesWidth[0]) {
  								$('[ class^="' + dataEntity[position]['id_ad'] + '"]', context).css({
  									'maxWidth': breakpoints[position]['form'][index]['width'] + 'px',
  									'height': breakpoints[position]['form'][index]['height'] + 'px',
  								});

  								return false;
  							}
  						}

  						if ($(window).width() > devicesWidth[0] && $(window).width() <= devicesWidth[1]) {

  							if (breakpoints[position]['form'][index]['width'] > devicesWidth[0] && breakpoints[position]['form'][index]['width'] <= devicesWidth[1]) {
  								$('[ class^="' + dataEntity[position]['id_ad'] + '"]', context).css({
  									'maxWidth': breakpoints[position]['form'][index]['width'] + 'px',
  									'height': breakpoints[position]['form'][index]['height'] + 'px',
  								});

  								return false;
  							}
  						}

  						if ($(window).width() > devicesWidth[1]) {

  							if (breakpoints[position]['form'][index]['width'] > devicesWidth[1] ) {
  								$('[ class^="' + dataEntity[position]['id_ad'] + '"]', context).css({
  									'maxWidth': breakpoints[position]['form'][index]['width'] + 'px',
  									'height': breakpoints[position]['form'][index]['height'] + 'px',
  								});

  								return false;
  							}
  						}

  					});
  				});
        });

					{/* https://www.youtube.com/embed/v3dclL2grbs */}

			}
  	}
  };

})(jQuery, Drupal, drupalSettings);
