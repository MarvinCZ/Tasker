var time = 300;
var left_done = function(){
	$('.page-right').css("display", "none");
	$('.page-middle').css("display", "none");
}
var middle_done = function(){
	$('.page-left').css("display", "none");
	$('.page-right').css("display", "none");
}
var right_done = function(){
	$('.page-left').css("display", "none");
	$('.page-middle').css("display", "none");
	$('.page-right .filter_submit').slideDown(100);
}
var show_left = function(){
	$('.page-right .filter_submit').hide();
	$('.page-left').css("display", "block");
	$('.page-right').css("display", "block");
	$('.page-middle').css("display", "block");
	$('.page-left').animate({
    	left: "0"
  	}, time);
	$('.page-middle').animate({
    	left: "100%"
  	}, time);
	$('.page-right').animate({
    	left: "200%"
  	}, time, left_done);
	$('.page-left .header').animate({
    	left: "0"
  	}, time);
	$('.page-middle .header').animate({
    	left: "100%"
  	}, time);
	$('.page-right .header').animate({
    	left: "200%"
  	}, time);
}
var show_middle = function(){
	$('.page-right .filter_submit').hide();
	$('.page-left').css("display", "block");
	$('.page-right').css("display", "block");
	$('.page-middle').css("display", "block");
	$('.page-left').animate({
    	left: "-100%"
  	}, time);
	$('.page-middle').animate({
    	left: "0"
  	}, time);
	$('.page-right').animate({
    	left: "100%"
  	}, time, middle_done);
	$('.page-left .header').animate({
    	left: "-100%"
  	}, time);
	$('.page-middle .header').animate({
    	left: "0"
  	}, time);
	$('.page-right .header').animate({
    	left: "100%"
  	}, time);
}
var show_right = function(){
	$('.page-right .filter_submit').hide();
	$('.page-left').css("display", "block");
	$('.page-right').css("display", "block");
	$('.page-middle').css("display", "block");
	$('.page-left').animate({
    	left: "-200%"
  	}, time);
	$('.page-middle').animate({
    	left: "-100%"
  	}, time);
	$('.page-right').animate({
    	left: "0"
  	}, time, right_done);
	$('.page-left .header').animate({
    	left: "-200%"
  	}, time);
	$('.page-middle .header').animate({
    	left: "-100%"
  	}, time);
	$('.page-right .header').animate({
    	left: "0"
  	}, time);
}
var timer = null;

var countdown_count = function(){
	$('.countdown').each(function(){
		if ($('.tab-select input:checked').val() == "done"){
			$(this).html('');
			$(this).parent().removeClass('late');
		}
		else{
			var array = $(this).data('to').split('.');
			var now = new Date();
			var date = new Date(array[2], array[1] - 1, array[0], array[3], array[4]);
			var diff = date - now;
			var message = 'Zbyva ';
			if(diff < 0){
				message = 'Zpozdeni '
				diff = -diff;
				$(this).parent().addClass('late');
			}
			var cd = 24 * 60 * 60 * 1000;
			var ch = 60 * 60 * 1000;
			var d = Math.floor(diff / cd);
			var h = Math.floor( (diff - d * cd) / ch);
			var m = Math.round( (diff - d * cd - h * ch) / 60000);
			$(this).html(message + d + ' dni ' + h + ' hodin ' + m + ' minut');
		}
	});
}

var countdown = function(){
	timer = setInterval(function(){
		countdown_count();
	},1000);
}

var move_state = function(){
	if($(window).width() >= 992){
		$('.select-state').insertBefore("#shared-with");
	}
	else{
		$('.select-state').insertBefore("#comments");
	}
}

$(document).ready(function(){
	countdown_count();
	countdown();
	middle_done();
	if($('.select-state').length > 0){
		move_state();
		$(window).resize(move_state);
	}
	if($('.page-left').length > 0){
		var left = new Hammer($('.page-left')[0]);
		left.on( "swipeleft", show_middle );
	}
	if($('.page-middle').length > 0){
		var middle = new Hammer($('.page-middle')[0]);
		middle.on( "swipeleft", show_right );
		middle.on( "swiperight", show_left );
	}
	if($('.page-right').length > 0){
		var right = new Hammer($('.page-right')[0]);
		right.on( "swiperight", show_middle );
	}
	$('.page-show-middle').click(function(){
		show_middle();
	});
	$('.page-show-left').click(function(){
		show_left();
	});
	$('.page-show-right').click(function(){
		show_right();
	});
	$('.page-hide-right').click(function(){
		$(this).closest('.page').removeClass('page-right-show');
		$('.over').hide();
	});
	$('.show-filter, .show-share').click(function(e){
		$('.page-right').addClass('page-right-show');		
		$('.page-right .filter_submit').show();
		$('.over').show();
		e.stopPropagation();
	});
	$('.over').click(function(){
		if($('.page-right').hasClass('page-right-show')){
			$('.page-right').removeClass('page-right-show');
			$('.page-right .filter_submit').hide();
		}
		$('.over').hide();
		$('.login').hide();
	});
	$('body').click(function(){
		$('.user-menu').hide();
	});
	$('.header-img').click(function(e){
		$('.user-menu').toggle();
		e.stopPropagation();
	});
	$('.clear-input').click(function(){
		$(this).siblings('input').val("")
	});
	if($('#importance-from').length > 0){
		var slider = document.getElementById('importance-from');
		var value = parseInt($('input[name="importance_from"]').val());
		noUiSlider.create(slider, {
			start: value,
			range: {
				'min': 0,
				'max': 10
			},
			step: 1
		});
		var inputFormat = document.getElementById('importance-from-field');
		slider.noUiSlider.on('update', function( values, handle ) {
			inputFormat.value = parseInt(values[handle]);
		});
	}
	var page = 1;

	if($('.next_page').length > 0){
		$('.next_page').click(function(){
			page = $(this).data().nextpage;
			$.ajax({
				url: window.location.href,
				data: {page: $(this).data().nextpage},
				dataType: 'script'
			})
		});
	}

	$(window).scroll(function() {
		if(($(document).height() - $(window).height() - $(window).scrollTop()) > 200 && $('.next_page').length > 0 && $('.next_page').data().nextpage != page) {
			page = $('.next_page').data().nextpage;
			$.ajax({
				url: window.location.href,
				data: {page: $('.next_page').data().nextpage},
				dataType: 'script'
			});
		}
	});

	$('.go-back').click(function(){
		window.history.back();
	});

	$('.sent-message').click(function(){
		var id = $('.sent-message').data('note');
		var path = "notes/" + id + "/comment";
		var message = $('.sent-message').parent().prev('input').val().trim();
		if(message){
			$.ajax({
				url: path,
				data: {msg: message},
				dataType: 'script',
				method: 'post'
			});
		}
		else{
			//TODO: handle empty message
		}
	});

	$('.tab').click(function(){
		if ($(this).attr('data-part')) {
			var parent = $(this).parent();
			parent.find('.tab').attr("data-selected", "false");
			$(this).attr("data-selected", "true");
			parent.parent().find('.parts>*').hide();
			parent.parent().find('.parts>.' + $(this).data('part')).show();
		}
	});

	$('.show-login').click(function(){
		$('.over').show();
		$('.login').show();
	});

	$('form.remote').submit(function(e){
		$.ajax({url: $(this).attr('action'), type: 'POST', data: $(this).serialize()});
		e.preventDefault();
	});

	$('.share-form .edit-share').click(function(){
		var parent = $(this).closest('.share-form');
		parent.find('.show-info').hide();
		parent.find('.form').show();
	});

	$('.complete-user').autocomplete({
		serviceUrl: "/share/possible"
	});

});