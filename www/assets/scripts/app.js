function getParameterByName(name) {
    var url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
var time = 300;
var left_done = function(){
	$('.page-right').css("display", "none");
	$('.page-middle').css("display", "none");
	$('.page-right-2').css("display", "none");
}
var middle_done = function(){
	$('.page-left').css("display", "none");
	$('.page-right').css("display", "none");
	$('.page-right-2').css("display", "none");
}
var right_done = function(){
	$('.page-left').css("display", "none");
	$('.page-middle').css("display", "none");
	$('.page-right-2').css("display", "none");
	$('.page-right .filter_submit').slideDown(100);
}
var right_2_done = function(){
	$('.page-left').css("display", "none");
	$('.page-middle').css("display", "none");
	$('.page-right').css("display", "none");
}
var show_left = function(){
	$('.page-right .filter_submit').hide();
	$('.page-left').css("display", "block");
	$('.page-right').css("display", "block");
	$('.page-middle').css("display", "block");	
	$('.page-right-2').css("display", "block");
	$('.page-left').animate({
    	left: "0"
  	}, time);
	$('.page-middle').animate({
    	left: "100%"
  	}, time);
	$('.page-right').animate({
    	left: "100%"
  	}, time);
	$('.page-right-2').animate({
    	left: "100%"
  	}, time, left_done);
	$('.page-left .header').animate({
    	left: "0"
  	}, time);
	$('.page-middle .header').animate({
    	left: "100%"
  	}, time);
	$('.page-right .header').animate({
    	left: "100%"
  	}, time);
	$('.page-right-2 .header').animate({
    	left: "100%"
  	}, time);
}
var show_middle = function(){
	$('.page-right .filter_submit').hide();
	$('.page-left').css("display", "block");
	$('.page-right').css("display", "block");
	$('.page-middle').css("display", "block");
	$('.page-right-2').css("display", "block");
	$('.page-left').animate({
    	left: "-100%"
  	}, time);
	$('.page-middle').animate({
    	left: "0"
  	}, time);
	$('.page-right').animate({
    	left: "100%"
  	}, time);
	$('.page-right-2').animate({
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
	$('.page-right-2 .header').animate({
    	left: "100%"
  	}, time);
}
var show_right = function(){
	$('.page-right .filter_submit').hide();
	$('.page-left').css("display", "block");
	$('.page-right').css("display", "block");
	$('.page-middle').css("display", "block");
	$('.page-right-2').css("display", "block");
	$('.page-left').animate({
    	left: "-100%"
  	}, time);
	$('.page-middle').animate({
    	left: "-100%"
  	}, time);
	$('.page-right').animate({
    	left: "0"
  	}, time);
	$('.page-right-2').animate({
    	left: "100%"
  	}, time, right_done);
	$('.page-left .header').animate({
    	left: "-100%"
  	}, time);
	$('.page-middle .header').animate({
    	left: "-100%"
  	}, time);
	$('.page-right .header').animate({
    	left: "0"
  	}, time);
	$('.page-right-2 .header').animate({
    	left: "100%"
  	}, time);
}
var show_right_2 = function(){
	$('.page-right .filter_submit').hide();
	$('.page-left').css("display", "block");
	$('.page-right').css("display", "block");
	$('.page-middle').css("display", "block");
	$('.page-right-2').css("display", "block");
	$('.page-left').animate({
    	left: "-300%"
  	}, time);
	$('.page-middle').animate({
    	left: "-200%"
  	}, time);
	$('.page-right').animate({
    	left: "-100%"
  	}, time);
	$('.page-right-2').animate({
    	left: "0"
  	}, time, right_2_done);
	$('.page-left .header').animate({
    	left: "-300%"
  	}, time);
	$('.page-middle .header').animate({
    	left: "-200%"
  	}, time);
	$('.page-right .header').animate({
    	left: "-100%"
  	}, time);
	$('.page-right-2 .header').animate({
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
	moment.locale('cs');
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
		if($('.page-right').length > 0){
			middle.on( "swipeleft", show_right );
		}
		if($('.page-left').length > 0){
			middle.on( "swiperight", show_left );
		}
	}
	if($('.page-right').length > 0){
		var right = new Hammer($('.page-right')[0]);
		right.on( "swiperight", show_middle );
		if($('.page-right-2').length > 0){
			right.on( "swipeleft", show_right_2 );
		}
	}
	if($('.page-right-2').length > 0){
		var right = new Hammer($('.page-right-2')[0]);
		right.on( "swiperight", show_right );
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
	$('.page-show-right-2').click(function(){
		show_right_2();
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
	$('.show-sharing').click(function(e){
		$('.page-right-2').addClass('page-right-show');
		$('.over').show();
		e.stopPropagation();
	});
	$('.over').click(function(){
		if($('.page-right').hasClass('page-right-show')){
			$('.page-right').removeClass('page-right-show');
			$('.page-right .filter_submit').hide();
		}
		if($('.page-right-2').hasClass('page-right-show')){
			$('.page-right-2').removeClass('page-right-show');
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

	var opened = getParameterByName('selected');
	if(opened != ""){
		var tab = $('.tab[data-part="' + opened + '"]');
		var parent = tab.parent();
		parent.find('.tab').attr("data-selected", "false");
		tab.attr("data-selected", "true");
	}

	$('.tab').click(function(){
		if ($(this).attr('data-part')) {
			var parent = $(this).parent();
			parent.find('.tab').attr("data-selected", "false");
			$(this).attr("data-selected", "true");
			parent.parent().find('.parts>*').hide();
			parent.parent().find('.parts>.' + $(this).data('part')).show();
		}
	});

	$('.tab').each(function(i, e){
		if(!$(this).data('selected') && $(this).attr('data-part')){
			$('.parts>.' + $(this).data('part')).hide();
		}
	})

	$('.show-login').click(function(){
		$('.over').show();
		$('.login').show();
	});

	$('form.remote').submit(function(e){
		var form = $(this);
		$.ajax({
			url: $(this).attr('action'),
			type: $(this).attr('method'),
			data: $(this).serialize(),
			dataType: 'JSON',
			success: function(data){
				form.find('.error-box').html('');
				form.find('.has-error').removeClass('has-error');
				if(data.hasOwnProperty('redirect')){
					window.location = data.redirect;
				}
				else{
					$.each(data, function(key, value){
						var el = form.find('.error-' + value['path']);
						el.html(value['message']);
						el.closest('.form-group').addClass('has-error');
					});
				}
			}
		});
		e.preventDefault();
	});

	$('.share-form .edit-share').click(function(){
		var parent = $(this).closest('.share-form');
		parent.find('.show-info').hide();
		parent.find('.form').show();
	});

	$('.share-form .close-edit-share').click(function(){
		var parent = $(this).closest('.share-form');
		parent.find('.show-info').show();
		parent.find('.form').hide();
	});

	$('.complete-user-group').autocomplete({
		serviceUrl: "/share/possible"
	});

	$('.complete-user').autocomplete({
		serviceUrl: "/users/auto-complete"
	});

	$('div[data-newuser]').click(function(){
		var id = $(this).data('newuser');
		$(this).data('newuser', id+1);
		$(this).closest('.row').before('<div class="input-group"><input type="hidden" name="[' + id + '][rights]" value="0"><input type="text" class="form-control" name="user[' + id + '][name]" placeholder="Jméno uživatele"><span class="input-group-btn"><span class="btn btn-danger remove-user-field"><i class="fa fa-trash"></i></span></span></div>');
		$(this).closest('.row').prev().find('input').autocomplete({
			serviceUrl: "/users/auto-complete"
		});
		$(this).closest('.row').prev().find('.remove-user-field').click(function(){
			$(this).closest('.input-group').remove();
		});

	});

	$('.color-select .small-block').click(function(){
		var parent = $(this).closest('.color-select');
		parent.find('.small-block').removeClass('block-selected');
		$(this).addClass('block-selected');
		parent.find('input[name="category_color"]').val($(this).data('color'));
	});
	$('.edit-category').click(function(){
		var form = $(this).closest('.categories').siblings('.category-form');
		form.find('input[name="id"]').val($(this).data('id'));
		form.find('input[name="category_name"]').val($(this).data('name'));
		form.find('.small-block').removeClass('block-selected');
		var color = $(this).data('color');
		form.find('.block-' + color).addClass('block-selected');
		form.find('input[name="category_color"]').val(color);
		$('.new-category, .category-edit').show();
		$('.category-new').hide();
	});

	$('.new-category').click(function(){
		var form = $(this).closest('form');
		form.find('input[name="id"]').val('');
		form.find('input[name="category_name"]').val('');
		form.find('.small-block').removeClass('block-selected');
		form.find('.block-none').addClass('block-selected');
		form.find('input[name="category_color"]').val('');
		$('.new-category, .category-edit').hide();
		$('.category-new').show();
	});

	$('.new-category, .category-edit').hide();

	$('.datetime-picker').each(function(i, e){
		var date = $(this).data('date');
		$(this).datetimepicker({
			inline: true,
			sideBySide: true
		});
		if(!date || 0 === date.length || date === "null"){
			date = null;
		}
		else{
			date = moment(date, 'Y-M-D H:m');
		}
		$(this).data("DateTimePicker").date(date);
	});

	$('.datetime-picker').on("dp.change", function(e){
		var parent = $(this).closest('.datetime_picker');
		var date = e.date;
		if (date){
			date = date.format('Y-M-D H:m');
		}
		else{
			date = null
		}
		parent.find('input').val(date);
	});

	$('.clear_date').click(function(){
		var parent = $(this).closest('.datetime_picker');
		parent.find('.datetime-picker').data("DateTimePicker").date(null);
	});

	$('input[name="deadline_from"]').closest('.datetime_picker').find('.datetime-picker').on("dp.change", function(e){
		var picker = $('input[name="deadline_to"]').closest('.datetime_picker').find('.datetime-picker').data("DateTimePicker");
		var prev = picker.date();
		picker.minDate(e.date);
		if(prev == null){
			picker.date(null);
		}
	});

	$('input[name="deadline_to"]').closest('.datetime_picker').find('.datetime-picker').on("dp.change", function(e){
		var picker = $('input[name="deadline_from"]').closest('.datetime_picker').find('.datetime-picker').data("DateTimePicker");
		var prev = picker.date();
		picker.maxDate(e.date);
		if(prev == null){
			picker.date(null);
		}
	});

	$('.edit-user').click(function(){
		$('.add-user-form').hide();
		var form = $('.edit-user-form');
		form.show();
		form.find('input[name="user_name"]').val($(this).data('name'));
		form.find('input[name="user_id"]').val($(this).data('id'));
		form.find('.tab-select').trigger('select', $(this).data('rights'));

	});

	$('.add-user').click(function(){
		$('.edit-user-form').hide();
		$('.add-user-form').show();
	});
	$('.edit-user-form').hide();

	var editentry = function(){
		var form = $('.edit-user-form');
		var parent = $(this).closest('tr');
		$('.add-user-form').hide();
		form.show();
		form.find('.tab-select').trigger('select', parent.find('.user-rights').val());
		form.find('input[name="user_name"]').val(parent.find('.user-name').val());
		form.find('input[name="entry_id"]').val(parent.data('id'));
	};

	var removeentry = function(){
		$(this).closest('tr').remove();
	}

	$('.editentry').click(editentry);

	$('.edituserentry').click(function(){
		var parent = $(this).closest('.edit-user-form');
		var id = parent.find('input[name="entry_id"]').val();
		var entry = $('table.new-group-users tr[data-id=' + id + ']');
		entry.find('.user-rights').val(parent.find('.tab-select').data('selected'));
		entry.find('.rights').html(parent.find('.tab-select').data('selectedname'));
		$('.edit-user-form').hide();
		$('.add-user-form').show();
	});

	$('.adduserentry').click(function(){
		var id = $(this).data('id');
		$(this).data('id', id + 1);
		var parent = $(this).closest('.add-user-form');
		parent.find('.tab-select').trigger('refresh');
		var rights = parent.find('.tab-select').data('selected');
		var rightsname = parent.find('.tab-select').data('selectedname');
		var nick = parent.find('input[name="user"]').val();
		parent.find('.tab-select').trigger('select', 0);
		parent.find('input[name="user"]').val('');
		var html = '<tr data-id="'+id+'"><td>' + nick + '</td><td class="rights">' + rightsname + '</td><td><div class="btn-group"><div class="btn btn-primary editentry"><i class="fa fa-pencil"></i></div><div class="btn btn-danger removeentry"><i class="fa fa-trash"></i></div></div></td><input type="hidden" class="user-name" name="user['+id+'][name]" value="' + nick + '"><input type="hidden" class="user-rights" name="user['+id+'][rights]" value="' + rights + '"></tr>';
		$('table.new-group-users').append(html);
		$('table.new-group-users tr:last-child .editentry').click(editentry);
		$('table.new-group-users tr:last-child .removeentry').click(removeentry);
	});
});
