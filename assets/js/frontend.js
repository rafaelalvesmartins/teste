(function($) {
	'use strict';

	$(document).ready(function() {

		// Parallax
		hivetheme.getComponent('parallax').each(function() {
			var container = $(this),
				firstImage = container.find('img:first-child'),
				lastImage = container.find('img:last-child'),
				speed = 0.15;

			if ($(window).width() >= 1024) {
				firstImage.css('top', $(window).scrollTop() * speed);
				lastImage.css('top', -$(window).scrollTop() * speed);

				$(window).on('scroll', function() {
					firstImage.css('top', $(window).scrollTop() * speed);
					lastImage.css('top', -$(window).scrollTop() * speed);
				});
			}
		});

		// Progress
		hivetheme.getComponent('progress').each(function() {
			var bar = $('<span />').appendTo($(this)),
				progress = (parseFloat($(this).data('value')).toFixed(2) / 5) * 100;

			bar.css('width', progress + '%');
		});

		// Buttons
		$('.button, button, input[type="submit"], .wp-block-button__link, .hp-feature__icon').each(function() {
			var button = $(this),
				color = button.css('background-color');

			if (button.css('box-shadow') === 'none' && color.indexOf('rgb(') === 0) {
				color = color.replace('rgb(', 'rgba(').replace(')', ',.35)');

				button.css('box-shadow', '0 5px 21px ' + color);
			}
		});

		// Columns
		if ($(window).width() < 768) {
			$('.wp-block-column:empty').remove();
		}
	});

	$('body').imagesLoaded(function() {

		// Slider
		hivetheme.getComponent('slider').each(function() {
			var container = $(this),
				slider = container.children('div:first'),
				settings = {
					prevArrow: '<i class="slick-prev fas fa-arrow-left"></i>',
					nextArrow: '<i class="slick-next fas fa-arrow-right"></i>',
					slidesToScroll: 1,
				};

			if (container.data('type') === 'carousel') {
				settings['slidesToShow'] = Math.round($(window).width() / 380);

				if ($(window).width() >= 768) {
					settings['centerMode'] = true;
				}
			} else {
				var arrows = $('<div class="slick-arrows" />').appendTo(container),
					width = $('#content').children('div:first').width();

				$.extend(settings, {
					appendArrows: arrows,
					slidesToShow: 1,
					variableWidth: true,
					centerMode: true,
					speed: 650,
				});

				slider.children('div').width(width);
			}

			if (container.data('pause')) {
				$.extend(settings, {
					autoplay: true,
					autoplaySpeed: parseInt(container.data('pause')),
				});
			}

			slider.slick(settings);
		});
	});
})(jQuery);
