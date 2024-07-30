jQuery.fn.pagePreviewSlider = function() {

	

	return this.each(function(){

		var wrapper = $(this);

		var header 	= $(this).find('div.header');

		var body 	= $(this).find('div.body');

		

		header.find('ul.level_1 > li').addClass('inactive_preview').removeClass('active_preview');

		body.find('ul.level_1 > li').addClass('active_preview');

		

		var showItems = wrapper.attr('show');

		if (showItems > 0) {

			if (body.find('span.left').length < 1) {

				body.prepend('<span class="left"><a href="#" class="arrow_left fa fa-3x fa-chevron-circle-right" aria-hidden="true"></a></span><span class="right"><a href="#" class="arrow_right fa fa-3x fa-chevron-circle-left" aria-hidden="true"></a></span>');

			}

			var left 	= body.find('a.arrow_left');

			var right 	= body.find('a.arrow_right');

			

			var wrapperWidth = body.find('ul.level_1').outerWidth();

			var itemWidth = body.find('ul.level_1 > li:first-child').width()

			var calculatedItems = Math.floor(wrapperWidth / itemWidth);

			

			if (calculatedItems < showItems) {

				showItems = calculatedItems;

			}

		} else {

			showItems = 0;

		}



		body.find('ul.level_1 > li').addClass('inactive_preview').removeClass('active_preview');



		var slideLeft = function(el){

			el.preventDefault();

			body.css('textAlign', 'right');

			var first_element = body.find('ul.level_1 > li').first();

			body.find('li.active_preview').last().next('li').hide().removeClass('inactive_preview').addClass('active_preview').show('slow');

			first_element.hide('slow').removeClass('active_preview').appendTo(body.find('ul.level_1'));

			first_element.addClass('inactive_preview');

			active_el = [];

			body.find('ul.level_1 > li.active_preview').each(function() {

				var rel = $(this).attr('rel');

				active_el.push(rel);

			});			

			header.find('ul.level_1 > li').each(function() {

				if (active_el.indexOf($(this).attr('rel')) >= 0) {

					$(this).addClass('active_preview').removeClass('inactive_preview');

				} else {

					$(this).removeClass('active_preview').addClass('inactive_preview');

				}

			});

		};

			

		var slideRight = function(el){

			el.preventDefault();

			body.css('textAlign', 'left');

			var last_element = body.find('ul.level_1 > li').last();

			last_element.hide().removeClass('inactive_preview').prependTo(body.find('ul.level_1'));

			body.find('li.active_preview').last().hide('slow').removeClass('active_preview').addClass('inactive_preview');

			last_element.addClass('active_preview').show('slow');

			active_el = [];

			body.find('ul.level_1 > li.active_preview').each(function() {

				var rel = $(this).attr('rel');

				active_el.push(rel);

			});			

			header.find('ul.level_1 > li').each(function() {

				if (active_el.indexOf($(this).attr('rel')) >= 0) {

					$(this).addClass('active_preview').removeClass('inactive_preview');

				} else {

					$(this).removeClass('active_preview').addClass('inactive_preview');

				}

			});

		};

		

		if (showItems > 0) {

			

			var counter = 0;

			

			body.find('ul.level_1 > li').each(function() {

				counter = counter + 1;

				if (counter <= showItems) {

					$(this).addClass('active_preview').removeClass('inactive_preview');

				} else {

					$(this).removeClass('active_preview').addClass('inactive_preview');

				}

			});

			var active_el = [];

			body.find('ul.level_1 > li.active_preview').each(function() {

				var rel = $(this).attr('rel');

				active_el.push(rel);

			});			

			header.find('ul.level_1 > li').each(function() {

				if (active_el.indexOf($(this).attr('rel')) >= 0) {

					$(this).addClass('active_preview').removeClass('inactive_preview');

				} else {

					$(this).removeClass('active_preview').addClass('inactive_preview');

				}

			});

			



			

			left.unbind('click');

			right.unbind('click');

			

			left.bind('click', slideLeft);

			right.click('click', slideRight);

		}

	});

}

