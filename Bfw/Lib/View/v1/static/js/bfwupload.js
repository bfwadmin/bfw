var xhr;
var formData;
var uploadcallback;
var bo_upload_complete=function(data){
	uploadcallback(data);
}
jQuery.Bfw.bfwupload=function(file,callback,posturl){
	  if(!window.FormData || !window.FileList){  
	    throw('您的浏览器不支持ajax upload');  
	  }  
	  $.Bfw.loadjs("bfwloading","bfwloading",function(){
			
	  uploadcallback = callback;
	  if(xhr==null){
		 xhr = new XMLHttpRequest();
		 xhr.upload.addEventListener("progress", function(evt){
			 if (evt.lengthComputable) {
			    var percentComplete = Math.round(evt.loaded * 100 / evt.total);
				  $.Bfw.loading('上传'+percentComplete.toString() + '%');
			    //$("#"+loaddingdivname).html('上传'+percentComplete.toString() + '%');
			 }else {
			     //$("#"+loaddingdivname).html('err');
			 }
		 }, false);
		 xhr.addEventListener("load", function(evt){$.Bfw.loading.close();var ret = eval('(' + evt.target.responseText + ')');bo_upload_complete(ret);}, false);
		 xhr.addEventListener("error", function(evt){$.Bfw.loading.close();alert("上传出错"+evt.toString());}, false);
		 xhr.addEventListener("abort", function(evt){$.Bfw.loading.close();alert("用户取消上传");}, false);
	 }
	 if(formData==null){
		 formData = new FormData();
	 }
     formData.append('fileData', file);
     xhr.open('POST',posturl);
     xhr.send(formData);
	  });
	 
};