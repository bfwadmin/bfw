jQuery.Bfw.cart=function(){
	
};
jQuery.Bfw.cart.add = function(prodid,prodcount,callback) {
	$.Bfw.callact("Cart","AddData","Cms",{prodid:prodid,count:prodcount},'post',function(data){
		if(data['err']){
			return $.Bfw.toastshow(data['data']);
		}
		callback(data);
	});
};
jQuery.Bfw.cart.changenum = function(cartid,incnumber,isadd,callback) {
		$.Bfw.callact("Cart","EditData","Cms",{id:cartid,incnumber:incnumber,isadd:isadd},'post',function(data){
		if(data['err']){
			return $.Bfw.toastshow(data['data']);
		}
		callback();
		//setTimeout(function(){location.reload();},2000);
		//$.Bfw.loadjs("bfwalert","bfwalert",function(){$.Bfw.alert("操作成功");});
		//$.Bfw.actionfor("Cart","MyList",'Cms');
	});
};
jQuery.Bfw.cart.del = function(cartid,callback) {
		$.Bfw.callact("Cart","DelData","Cms",{id:cartid},'post',function(data){
		if(data['err']){
			return $.Bfw.toastshow(data['data']);
		}
		callback();
		//setTimeout(function(){location.reload();},2000);
		//$.Bfw.loadjs("bfwalert","bfwalert",function(){$.Bfw.alert("操作成功");});
		//$.Bfw.actionfor("Cart","MyList",'Cms');
	});
};
jQuery.Bfw.cart.gettotal = function(callback) {
		$.Bfw.callact("Cart","Count","Cms",{},'post',function(data){
		if(data['err']){
			return $.Bfw.toastshow(data['data']);
		}
		callback(data['data']);
		//setTimeout(function(){location.reload();},2000);
		//$.Bfw.loadjs("bfwalert","bfwalert",function(){$.Bfw.alert("操作成功");});
		//$.Bfw.actionfor("Cart","MyList",'Cms');
	});
};
jQuery.Bfw.buy = function(prodid,prodcount,special) {
		//$.Bfw.callact("Cart","AddData","Cms",{prodid:prodid,count:prodcount},'post',function(data){
		//if(data['err']){
		//	return alert(data['data']);
		//}
		location.href=$.Bfw.buildurl("Order","Confirm",'Cms')+"/prodid/"+prodid+"/prodcount/"+prodcount+"/special/"+special;
		//$.Bfw.actionfor("Order","Confirm",'Cms'+"/prodid/"+prodid+"/prodcount/"+prodcount+"/special/"+special);
	//});
};
jQuery.Bfw.addfav = function(prodid,callback) {
	$.Bfw.callact("Favorite","Add","Cms",{prodid:prodid},'post',function(data){
		if(data['err']){
			return $.Bfw.toastshow(data['data']);
		}
		callback(data);
		//$.Bfw.actionfor("Cart","MyList",'Cms');
	});
};
jQuery.Bfw.cancelfav = function(prodid,callback) {
	$.Bfw.callact("Favorite","Cancel","Cms",{prodid:prodid},'post',function(data){
		if(data['err']){
			return $.Bfw.toastshow(data['data']);
		}
		callback(data);
		//$.Bfw.actionfor("Cart","MyList",'Cms');
	});
};
jQuery.Bfw.addlove = function(prodid,callback) {
	$.Bfw.callact("Love","Add","Cms",{prodid:prodid},'post',function(data){
		if(data['err']){
			return $.Bfw.toastshow(data['data']);
		}
		callback(data);
	});
};
jQuery.Bfw.addfans = function(artistid,callback) {
		$.Bfw.callact("Focus","Add","Cms",{artistid:artistid},'post',function(data){
		if(data['err']){
			return $.Bfw.toastshow(data['data']);
		}
		callback(data);
	});
};
jQuery.Bfw.gotodetail=function(itemid,isspecial){
	if(typeof(getartworkData)==="function"){
		getartworkData(itemid,isspecial);
		return false;
	}else{
       return true;
	}
}
