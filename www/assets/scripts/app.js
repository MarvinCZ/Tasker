var shown = "middle";
var show_left = function(){
	$('.page-left').css("left", "0");
	$('.page-middle').css("left", "100%");
	$('.page-right').css("left", "200%");
}
var show_middle = function(){
	$('.page-left').css("left", "-100%");
	$('.page-middle').css("left", "0");
	$('.page-right').css("left", "100%");
}
var show_right = function(){
	$('.page-left').css("left", "-200%");
	$('.page-middle').css("left", "-100%");
	$('.page-right').css("left", "0");
}
$(document).ready(function(){
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
});