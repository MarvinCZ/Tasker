$(document).ready(function(){
	$('.tab-select').bind('select', function(e, val){
		var parent = $(this);
		var input = parent.find('input[value="' + val + '"]');
		if(input.length > 0){
			parent.find('.tab-btn').removeClass('tab-active');
			input.prop('checked', true);
			input.parent().addClass('tab-active');
		}
		$(this).trigger('refresh');
	});
	$('.tab-select').bind('refresh', function(){
		var sel = $(this).find('input:checked');
		console.log($(this));
		var selected =  sel[0].value;
		var name = $.trim(sel.closest('.tab-btn').find('.text').html());
		$(this).data('selected', selected);
		$(this).data('selectedname', name);
	});
	$('.tab-select-one .tab-btn').click(function(e){
		var parent = $(this).closest('.tab-select');
		parent.find('.tab-btn').removeClass('tab-active');
		var was_checked = $(this).find('input[type="radio"]').prop('checked');
		parent.find('input[type="radio"]').prop('checked','');
		if(!was_checked || parent.data().required){
			$(this).addClass('tab-active');
			$(this).find('input[type="radio"]').prop('checked',true);
		}
		var sel = parent.find('input:checked');
		var selected = sel[0].value;
		var name = $.trim(sel.closest('.tab-btn').find('.text').html());
		parent.data('selected', selected);
		parent.data('selectedname', name);
		if(parent.data().onchange){
			$.ajax({
				url: parent.data().onchange,
				data: {selected: selected},
				dataType: 'text/html'
			})
		}
		e.stopPropagation();
	});
	$('.tab-multiselect .tab-btn').click(function(e){
		var parent = $(this).closest('.tab-select');
		$(this).toggleClass('tab-active');
		$(this).find('input[type="checkbox"]').prop('checked',$(this).hasClass('tab-active')?'checked':'');
		e.stopPropagation();
	});
	$('.tab-select .control-show').hide();
	$('.tab-select .tab-closed').hide();
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
			parent.find('.tab-closed').html('Vybráno: ' +  array.join(', '));
		}
		else{
			parent.find('.tab-closed').html('Nic nevybráno');
		}		
	});
	$('.tab-select .btn-nothing').click(function(e){
		var parent = $(this).closest('.tab-select');
		parent.find('.tab-btn').removeClass('tab-active');
		parent.find('input[type="checkbox"]').prop('checked','');
		e.stopPropagation();
	});
	$('.tab-select .btn-all').click(function(e){
		var parent = $(this).closest('.tab-select');
		parent.find('.tab-btn').addClass('tab-active');
		parent.find('input[type="checkbox"]').prop('checked','checked');
		e.stopPropagation();
	});
});