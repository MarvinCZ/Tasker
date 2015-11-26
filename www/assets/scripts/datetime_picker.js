var DaysInMonth = function(year, month){
	var monthStart = new Date(year, month - 1, 1);
	var monthEnd = new Date(year, month, 1);
	var monthLength = Math.round((monthEnd - monthStart) / (1000 * 60 * 60 * 24));
	return monthLength;
}
var GetMaxDays = function(element){
	return DaysInMonth(parseInt(element.find('.year').val()),parseInt(element.find('.month').val()));
}
var CheckDayMax = function(element){
	val = parseInt(element.find('.day').val());
	max = GetMaxDays(element);
	if(val > max){
		element.find('.day').val('1');
	}
}
var CheckDayMaxDown = function(element){
	val = parseInt(element.find('.day').val());
	max = GetMaxDays(element);
	if(val > max){
		element.find('.day').val(max);
	}
}

var FillInToday = function(element){
	var currentdate = new Date();
	element.find('.day').val(currentdate.getDate());
	element.find('.month').val(currentdate.getMonth()+1);
	element.find('.year').val(currentdate.getFullYear());
	element.find('.hour').val(currentdate.getHours());
	element.find('.minute').val(currentdate.getMinutes());
}
$(document).ready(function(){
	$('.controll-up').click(function(){
		var value = parseInt($(this).next('input').val());
		if(isNaN(value)){
			FillInToday($(this).closest('.datetime_picker'));
			var value = parseInt($(this).next('input').val());
		}
		$(this).next('input').val(value+1);
		$(this).next('input').change();
	});
	$('.controll-down').click(function(){
		var value = parseInt($(this).prev('input').val());
		if(isNaN(value)){
			FillInToday($(this).closest('.datetime_picker'));
			var value = parseInt($(this).next('input').val());
		}
		$(this).prev('input').val(value-1);
		$(this).prev('input').change();
	});
	$('.datetime_picker .day').change(function(){
		var value = parseInt($(this).val());
		var max = GetMaxDays($(this));
		if(value < 1){
			$(this).val(GetMaxDays($(this).closest('.datetime_picker')));
		}
		CheckDayMax($(this).closest('.datetime_picker'));
	});
	$('.datetime_picker .month').change(function(){
		var value = parseInt($(this).val());
		if(value < 1){
			$(this).val('12');
		}
		if(value > 12){
			$(this).val('1');
		}
		CheckDayMaxDown($(this).closest('.datetime_picker'));
	});
	$('.datetime_picker .year').change(function(){		
		CheckDayMaxDown($(this).closest('.datetime_picker'));
	});
	$('.datetime_picker .hour').change(function(){
		var value = parseInt($(this).val());
		if(value < 0){
			$(this).val('23');
		}
		if(value > 23){
			$(this).val('0');
		}
	});
	$('.datetime_picker .minute').change(function(){
		var value = parseInt($(this).val());
		if(value < 0){
			$(this).val('59');
		}
		if(value > 59){
			$(this).val('0');
		}
	});
	$('.datetime_picker input').change(function(){
		var parent = $(this).closest('.datetime_picker');
		var year = parent.find('.year').val();
		var month = parent.find('.month').val();
		var day = parent.find('.day').val();
		var hour = parent.find('.hour').val();
		var minute = parent.find('.minute').val();
		parent.find('.datetime').val(year+"-"+month+"-"+day+" "+hour+":"+minute);
	});
	$('.datetime_picker .clear_date').click(function(){
		var parent = $(this).closest('.datetime_picker');
		parent.find('input').val("");
	});
});