/*! test 06-01-2017 */
jQuery.Bfw=function(){},jQuery.Bfw.buildurl=function(a,b,c){return"undefined"!=typeof _bfw_config&&("baseurl"in _bfw_config&&"routetype"in _bfw_config&&"contname"in _bfw_config&&"actname"in _bfw_config&&"domname"in _bfw_config&&(1==_bfw_config.routetype?_bfw_config.baseurl+"/index.php?"+_bfw_config.contname+"="+a+"&"+_bfw_config.actname+"="+b+"&"+_bfw_config.domname+"="+c:_bfw_config.baseurl+"/"+c+"/"+a+"/"+b))},jQuery.Bfw.actionfor=function(a,b,c){var d=jQuery.Bfw.buildurl(a,b,c);0!=d?location.href=d:alert("参数错误")},jQuery.Bfw.callact=function(a,b,c,d,e,f){var g=jQuery.Bfw.buildurl(a,b,c);0!=g?$.ajax({type:e,data:d,url:g,dataType:"json",beforeSend:function(a){a.setRequestHeader("bfwajax","1.0")},success:function(a){"function"==typeof f&&f(a)},error:function(a,b,c){"function"==typeof f&&f({err:!0,data:b})}}):alert("参数错误")},jQuery.Bfw.submitform=function(a,b,c,d){$(a).submit(function(a){var e=$(this).attr("method"),f=$(this).attr("action");""==f&&(f=window.location.href);var g=$(this).serializeArray(),h={},i=!0;return $.each(g,function(){if(void 0!==h[this.name])h[this.name].push||(h[this.name]=[h[this.name]]),h[this.name].push(this.value||"");else{if("[object Array]"==Object.prototype.toString.call(d))for(var a in d)if(d[a][0]==this.name)if("phone"==d[a][1]){if(!$.Bfw.regtest(/^1\d{10}$/,this.value))return $.Bfw.toastshow(d[a][2]),i=!1,!1}else if("number"==d[a][1]){if(!$.Bfw.regtest(/^[0-9]*$/,this.value))return $.Bfw.toastshow(d[a][2]),i=!1,!1}else if("email"==d[a][1]){if(!$.Bfw.regtest(/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/,this.value))return $.Bfw.toastshow(d[a][2]),i=!1,!1}else if("regex"==d[a][1]){if(!$.Bfw.regtest(new RegExp(d[a][2][1],"g"),this.value))return $.Bfw.toastshow(d[a][3]),i=!1,!1}else if("money"==d[a][1]){if(!$.Bfw.regtest(/(^[1-9]([0-9]+)?(\.[0-9]{1,2})?$)|(^(0){1}$)|(^[0-9]\.[0-9]([0-9])?$)/,this.value))return $.Bfw.toastshow(d[a][2]),i=!1,!1}else if("chinese"==d[a][1]){if(!$.Bfw.regtest(/^[\u0391-\uFFE5]+$/,this.value))return $.Bfw.toastshow(d[a][2]),i=!1,!1}else if("identity"==d[a][1]){if(!$.Bfw.regtest(/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/,this.value))return $.Bfw.toastshow(d[a][2]),i=!1,!1}else if("url"==d[a][1]){if(!$.Bfw.regtest(/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/,this.value))return $.Bfw.toastshow(d[a][2]),i=!1,!1}else if("username"==d[a][1]){if(!$.Bfw.regtest(/^[a-zA-Z0-9_]+$/,this.value))return $.Bfw.toastshow(d[a][2]),i=!1,!1}else if("qq"==d[a][1]){if(!$.Bfw.regtest(/^[1-9]\d{4,8}$/,this.value))return $.Bfw.toastshow(d[a][2]),i=!1,!1}else if("require"==d[a][1]){if(""==this.value)return $.Bfw.toastshow(d[a][2]),i=!1,!1}else if("len"==d[a][1]){if(this.value.length<d[a][2]||this.value.length>d[a][3])return $.Bfw.toastshow(d[a][4]),i=!1,!1}else if("in"==d[a][1]&&d[a][2].indexOf(this.value)==-1)return $.Bfw.toastshow(d[a][3]),i=!1,!1;h[this.name]=this.value||""}}),i===!0&&"function"==typeof b&&(i=b(h)),i===!0&&$.Bfw.loadjs("bfwloading","bfwloading",function(){$.Bfw.loading("处理中,请稍后……"),$.ajax({type:e,data:h,url:f,dataType:"json",beforeSend:function(a){a.setRequestHeader("bfwajax","1.0")},success:function(a){"function"==typeof c&&c(a),$.Bfw.loading.close()},error:function(a,b,d){"function"==typeof c&&c({err:!0,data:b+"1"+d}),$.Bfw.loading.close()}})}),!1})},jQuery.Bfw.regtest=function(a,b){return a.test(b)},jQuery.Bfw.loadjs=function(a,b,c){if(""==a)c();else{var d="";if("undefined"==typeof _bfw_config)return $.Bfw.toastshow("参数错误");if("jsbaseurl"in _bfw_config&&(d=_bfw_config.jsbaseurl),""!=b&&jQuery.Bfw.loadcss(b),"jsloadfiles"in _bfw_config){if(a in _bfw_config.jsloadfiles)return void c();_bfw_config.jsloadfiles[a]=1}else _bfw_config.jsloadfiles={modulename:1};$.ajax({url:d+a+".js",dataType:"script",cache:!0}).done(function(){c()})}},jQuery.Bfw.formatetpl=function(a,b){var c={name:function(a){return a}};return b.replace(/{(\w+)}/g,function(b,d){return d?c&&c[d]?c[d](a[d]):a[d]:""})},jQuery.Bfw.getdatabyformate=function(a,b){var c=[];return $.each(b,function(b,d){c.push(jQuery.Bfw.formatetpl(d,a))}),c},jQuery.Bfw.loadcss=function(a){if("cssloadfiles"in _bfw_config){if(a in _bfw_config.cssloadfiles)return;_bfw_config.cssloadfiles[a]=1}else _bfw_config.cssloadfiles={modulename:1};var b="";if("undefined"==typeof _bfw_config)return $.Bfw.toastshow("参数错误");"cssbaseurl"in _bfw_config&&(b=_bfw_config.cssbaseurl);var c=document.createElement("link");c.type="text/css",c.rel="stylesheet",c.href=b+a+".css",document.getElementsByTagName("head")[0].appendChild(c)},jQuery.Bfw.toastshow=function(a){$.Bfw.loadjs("bfwtoast","bfwtoast",function(){$.Bfw.toast(a,2e3)})};