jQuery.Bfw = function() {
	
};
jQuery.Bfw.buildurl = function(cont,act,dom) {
	if(typeof(_bfw_config)=="undefined"){
		return false;
	}
	if('baseurl' in _bfw_config&&'routetype' in _bfw_config&&'contname' in _bfw_config&&'actname' in _bfw_config&&'domname' in _bfw_config){
		if(_bfw_config['routetype']==1){
			return _bfw_config['baseurl']+"/index.php?"+_bfw_config['contname']+"="+cont+"&"+_bfw_config['actname']+"="+act+"&"+_bfw_config['domname']+"="+dom;
		}else{
			return _bfw_config['baseurl']+"/"+dom+"/"+cont+"/"+act;
		}
	}else{
		return false;
	}
};
jQuery.Bfw.actionfor = function(cont,act,dom) {
	var _url=jQuery.Bfw.buildurl(cont,act,dom);
	if(_url!=false){
		location.href=_url;
	}else{
		alert("参数错误");
	}
};
jQuery.Bfw.callact = function(cont,act,dom,paradata,httpmethod,callback) {
	var _url=jQuery.Bfw.buildurl(cont,act,dom);
	if(_url!=false){
		 $.ajax({ 
	   	           type: httpmethod, 
				   data: paradata,
	   	           //data:{ref:r,mobile:u},
	   	           url: _url, 
	   	           dataType: "json", 
				   beforeSend: function(request) {
                        request.setRequestHeader("bfwajax", "1.0");
                   },
	   	           success: function (data) { 
				        if (typeof callback === "function"){
                           callback(data);
                        }
	   	           }, 
	   	           error: function (XMLHttpRequest, textStatus, errorThrown) { 
				   	   if (typeof callback === "function"){
                          callback({err:true,data:textStatus});
                        }
		   	        	  
	   	           } 
	 });
	}else{
		alert("参数错误");
	}
};
jQuery.Bfw.submitform = function(tag,beforesubmit,aftersubmit,validateform){
$(tag).submit(function(e){
	var httpmethod = $(tag).attr("method");
	var actionurl = $(tag).attr("action");
	if(actionurl==""){
		actionurl=window.location.href;
	}
	var formval = $(tag).serializeArray();
	var formobj = {};
	var boolret=true;
	$.each(formval, function () {
     if (formobj[this.name] !== undefined) {
      if (!formobj[this.name].push) {
         formobj[this.name] = [formobj[this.name]];
      }
      formobj[this.name].push(this.value || '');
     } else {
		 if(Object.prototype.toString.call(validateform) == '[object Array]'){
			 for(var ele in validateform){
				  if(validateform[ele][0]==this.name){
					 if(validateform[ele][1]=='phone'){
				         if(!$.Bfw.regtest(/^1\d{10}$/,this.value)){
						 $.Bfw.toastshow(validateform[ele][2]);
						 boolret=false;
						 return false;
					    }
			        }else if(validateform[ele][1]=='number'){
				       if(!$.Bfw.regtest(/^[0-9]*$/,this.value)){
						$.Bfw.toastshow(validateform[ele][2]);
						boolret=false;
						return false;
					   }
			       }else if(validateform[ele][1]=='email'){
				       if(!$.Bfw.regtest(/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/,this.value)){
						$.Bfw.toastshow(validateform[ele][2]);
						boolret=false;
						return false;
					   }
				   }else if(validateform[ele][1]=='regex'){
				       if(!$.Bfw.regtest(new RegExp(validateform[ele][2][1],"g"),this.value)){
						$.Bfw.toastshow(validateform[ele][3]);
						boolret=false;
						return false;
					   }
			       }else if(validateform[ele][1]=='money'){
				       if(!$.Bfw.regtest(/(^[1-9]([0-9]+)?(\.[0-9]{1,2})?$)|(^(0){1}$)|(^[0-9]\.[0-9]([0-9])?$)/,this.value)){
						$.Bfw.toastshow(validateform[ele][2]);
						boolret=false;
						return false;
					  }					 
			      }else if(validateform[ele][1]=='chinese'){
				    if(!$.Bfw.regtest(/^[\u0391-\uFFE5]+$/,this.value)){
						$.Bfw.toastshow(validateform[ele][2]);
						boolret=false;
						return false;
					 }	
				  }else if(validateform[ele][1]=='identity'){
				    if(!$.Bfw.regtest(/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/,this.value)){
						$.Bfw.toastshow(validateform[ele][2]);
						boolret=false;
						return false;
					 }	
				 }else if(validateform[ele][1]=='url'){
				    if(!$.Bfw.regtest(/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/,this.value)){
						$.Bfw.toastshow(validateform[ele][2]);
						boolret=false;
						return false;
					 }	
			      }else if(validateform[ele][1]=='username'){
				    if(!$.Bfw.regtest(/^[a-zA-Z0-9_]+$/,this.value)){
						$.Bfw.toastshow(validateform[ele][2]);
						boolret=false;
						return false;
					 }	
			      }else if(validateform[ele][1]=='qq'){
				    if(!$.Bfw.regtest(/^[1-9]\d{4,8}$/,this.value)){
						$.Bfw.toastshow(validateform[ele][2]);
						boolret=false;
						return false;
					 }						 
			      }else if(validateform[ele][1]=='require'){
				    if(this.value==""){
						$.Bfw.toastshow(validateform[ele][2]);
						boolret=false;
						return false;
					 }		
			     }else if(validateform[ele][1]=='len'){
				    if(this.value.length<validateform[ele][2]||this.value.length>validateform[ele][3]){
						$.Bfw.toastshow(validateform[ele][4]);
						boolret=false;
						return false;
					 }
			     
			     }else if(validateform[ele][1]=='in'){
				    if(validateform[ele][2].indexOf(this.value)==-1){
						$.Bfw.toastshow(validateform[ele][3]);
						boolret=false;
						return false;
					 }					 
			      }else{
			         
			      }
			   }	 
             }
		 }
		 formobj[this.name] = this.value || '';
     }
    }); 
	if(boolret===true){
		if (typeof beforesubmit === "function"){
          boolret=beforesubmit(formobj);
         }
	}

	if(boolret===true){
		 $.Bfw.loadjs("bfwloading","bfwloading",function(){
			  $.Bfw.loading("处理中,请稍后……");
			  $.ajax({ 
	   	           type: httpmethod, 
				   data: formobj,
	   	           url: actionurl, 
	   	           dataType: "json", 
				   beforeSend: function(request) {
                        request.setRequestHeader("bfwajax", "1.0");
                   },
	   	           success: function (data) { 
				        
				        if (typeof aftersubmit === "function"){
                           aftersubmit(data);
                        }
						$.Bfw.loading.close();
	   	           }, 
	   	           error: function (XMLHttpRequest, textStatus, errorThrown) { 
				       
				       if (typeof aftersubmit === "function"){
                          aftersubmit({err:true,data:textStatus+"1"+errorThrown});
                        }  
						$.Bfw.loading.close();
	   	           } 
	         });
			
		 });
		
	}
	return false;
});
};
jQuery.Bfw.regtest=function(reg,value){
	  return reg.test(value);				
};
jQuery.Bfw.loadjs=function(modulename,relatedcssmodulename,callback){
	if(modulename==""){
		callback();
	}else{
		var _base_url="";
	    if(typeof(_bfw_config)=="undefined"){
			return $.Bfw.toastshow("参数错误");
     	}else{
	       if('jsbaseurl' in _bfw_config){
		     _base_url=_bfw_config['jsbaseurl'];
	       }
	    }
		if(relatedcssmodulename!=""){
			jQuery.Bfw.loadcss(relatedcssmodulename);
		}
		if('jsloadfiles' in _bfw_config){
		   if(modulename in _bfw_config['jsloadfiles']){
			
			callback();
		    return;
		    }else{
			 _bfw_config['jsloadfiles'][modulename]=1;	  
		   }
	    }else{
		   _bfw_config['jsloadfiles']={modulename:1};	
		}
	    $.ajax({
         url:_base_url + modulename+".js",
         dataType: "script",
         cache: true
         }).done(function() {
           callback();
       });
	}
};
jQuery.Bfw.formatetpl = function(dta, tmpl) {  
        var format = {  
            name: function(x) {  
                return x  
            }  
        };  
        return tmpl.replace(/{(\w+)}/g, function(m1, m2) {  
            if (!m2)  
                return "";  
            return (format && format[m2]) ? format[m2](dta[m2]) : dta[m2];  
        });  
}
jQuery.Bfw.getdatabyformate = function(tpl, data) {  
       var arr = [];  
	   $.each(data, function(i, o) { arr.push(jQuery.Bfw.formatetpl(o, tpl)); }); 
	   return arr
} 
jQuery.Bfw.loadcss=function(modulename){
	if('cssloadfiles' in _bfw_config){
		if(modulename in _bfw_config['cssloadfiles']){
		    return;
		}else{
			_bfw_config['cssloadfiles'][modulename]=1;	  
		}
	}else{
		_bfw_config['cssloadfiles']={modulename:1};	  
	}
	var _base_url="";
	if(typeof(_bfw_config)=="undefined"){
		return $.Bfw.toastshow("参数错误");
    }else{
	    if('cssbaseurl' in _bfw_config){
		   _base_url=_bfw_config['cssbaseurl'];
	    }
	}
    var link = document.createElement('link');
    link.type = 'text/css';
    link.rel = 'stylesheet';
    link.href = _base_url+modulename+'.css';
    document.getElementsByTagName("head")[0].appendChild(link);
};
jQuery.Bfw.toastshow=function(msg){
	 $.Bfw.loadjs("bfwtoast","bfwtoast",function(){
		  $.Bfw.toast(msg,2000);
	 });
}






