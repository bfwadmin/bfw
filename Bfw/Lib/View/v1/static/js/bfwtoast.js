jQuery.Bfw.toast=function(msg,intval){
	if($(".bfwtoastdiv").length>0){
		//alert(msg);
		$(".bfwtoastdiv").html(msg);
	}else{
		$(document.body).append("<div class=\"bfwtoastdiv\"> "+msg+" </div>"); 
	} 
	$('.bfwtoastdiv').fadeIn();
	setTimeout(function(){$('.bfwtoastdiv').fadeOut();},intval);
};
jQuery.Bfw.toast.close=function(){
	$('.bfwtoastdiv').hide();
};