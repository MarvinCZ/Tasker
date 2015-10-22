var main = function(){
	$('.tab-select-one .tab-btn').click(function(e){
		var parent = $(this).closest('.tab-select');
		parent.find('.tab-btn').removeClass('tab-active');
		$(this).addClass('tab-active');
		parent.find('input[type="radio"]').prop('checked','');
		$(this).find('input[type="radio"]').prop('checked',true);
		e.stopPropagation();
	});
	$('.tab-multiselect .tab-btn').click(function(e){
		var parent = $(this).closest('.tab-select');
		$(this).toggleClass('tab-active');
		$(this).find('input[type="checkbox"]').prop('checked',$(this).hasClass('tab-active')?'checked':'');
		e.stopPropagation();
	});
	$('.control-show').hide();
	$('.tab-closed').hide();
	$('.tab-select').click(function(){
		var parent = $(this);
		parent.find('.control-show').toggle();
		parent.find('.control-hide').toggle();
		parent.find('.tab-opend').toggle();
		parent.find('.tab-closed').toggle();
		var array = [];
		parent.find('input:checked').closest('.tab-btn').find('.text').each(function(){
			array.push($.trim($(this).html()));
		})
		if(array.length > 0){
			parent.find('.tab-closed').html('Selected: ' +  array.join(', '));
		}
		else{
			parent.find('.tab-closed').html('Nothing selected');
		}		
	});
};

$(document).ready(main);