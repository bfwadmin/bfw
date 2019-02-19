jQuery.Bfw.loading=function(msg){
	if($(".bfwloadingdiv").length>0){
		$(".bfwloadingdiv").html(msg);
		//return ;
	}else{
		$(document.body).append("<div class=\"bfwmaskdiv\"  style=\"display:none;\"> </div><div class=\"bfwloadingdiv\"> "+msg+" </div>"); 
	}
    $(".bfwmaskdiv").width($(document).width()); 
    $('.bfwmaskdiv').height($(document).height()); 
    $('.bfwmaskdiv').css('left',0); 
    $('.bfwmaskdiv').css('top',0); 
	$('.bfwmaskdiv').show();
	$('.bfwloadingdiv').show();
};
jQuery.Bfw.loading.close=function(){
	$('.bfwmaskdiv').hide();
	$('.bfwloadingdiv').hide();
};