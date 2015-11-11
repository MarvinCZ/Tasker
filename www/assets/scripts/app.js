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
	$('.page-left').on( "swipeleft", show_middle );
	$('.page-middle').on( "swipeleft", show_right );
	$('.page-middle').on( "swiperight", show_left );
	$('.page-right').on( "swiperight", show_middle );
});