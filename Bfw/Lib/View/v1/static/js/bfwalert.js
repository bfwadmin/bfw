jQuery.Bfw.alert=function(msg){
	if($(".bfwalertdiv").length>0){
	}else{
		$(document.body).append("<div class=\"bfwmaskdiv\"  style=\"display:none;\"> </div><div class=\"bfwtitlediv\"> "+msg+" </div>"); 
		
	} 
    $(".bfwmaskdiv").width($(document).width()); 
    $('.bfwmaskdiv').height($(document).height()); 
    $('.bfwmaskdiv').css('left',0); 
    $('.bfwmaskdiv').css('top',0); 
	$('.bfwmaskdiv').show();
	$('.bfwtitlediv').show();
};
jQuery.Bfw.alert.close=function(){
	$('.bfwmaskdiv').hide();
	$('.bfwtitlediv').hide();
};