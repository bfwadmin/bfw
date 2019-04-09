<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="/statics/css/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--

.btnCode {
	background:transparent url(/statics/js/xheditor/prettify/code.gif) no-repeat 16px 16px;
	background-position:2px 2px;
}
-->
</style>
<script type="text/javascript" src="/statics/js/xheditor/jquery/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="/statics/js/xheditor/xheditor-1.2.2.min.js"></script>
<script type="text/javascript" src="/statics/js/xheditor/xheditor_lang/zh-cn.js"></script>
<script src="/statics/js/form.js"></script>
<script>
var editor;
$(pageInit);
function pageInit()
{
	var allPlugin={
		Code:{c:'btnCode',t:'插入代码',h:1,e:function(){
			var _this=this;
			var htmlCode='<div><select id="xheCodeType"><option value="html">HTML/XML</option><option value="js">Javascript</option><option value="css">CSS</option><option value="php">PHP</option><option value="java">Java</option><option value="py">Python</option><option value="pl">Perl</option><option value="rb">Ruby</option><option value="cs">C#</option><option value="c">C++/C</option><option value="vb">VB/ASP</option><option value="">其它</option></select></div><div><textarea id="xheCodeValue" wrap="soft" spellcheck="false" style="width:300px;height:100px;" /></div><div style="text-align:right;"><input type="button" id="xheSave" value="确定" /></div>';			var jCode=$(htmlCode),jType=$('#xheCodeType',jCode),jValue=$('#xheCodeValue',jCode),jSave=$('#xheSave',jCode);
			jSave.click(function(){
				_this.loadBookmark();
				_this.pasteHTML('<pre class="prettyprint lang-'+jType.val()+'">'+_this.domEncode(jValue.val())+'</pre>');
				_this.hidePanel();
				return false;	
			});
			_this.saveBookmark();
			_this.showDialog(jCode);
		}}
	};
	editor=$('#elm1').xheditor({skin:'nostyle',internalStyle:true,inlineStyle:true,tools:'mfull',plugins:allPlugin,loadCSS:'<style>pre{margin-left:2em;border-left:3px solid #CCC;padding:0 1em;}</style>',shortcuts:{'ctrl+enter':submitForm}});
	
}
function submitForm(){$('#frmDemo').submit();}

    jQuery(function( $ ){
    	$('form').ajaxForm({
			// target: '#NoticeDiv',
			success : showResponse
		});
    });
	function showResponse(responseText, statusText, xhr, $form) {
		var resjson = eval("(" + responseText + ")");
		if(resjson['err']){
             alert(resjson['data']);
		}else{
			if (resjson['data'].substr(0, 9) == "redirect:") {
				location.href = resjson['data'].substr(9);
			}else if(resjson['data'].substr(0, 12) =="msgredirect:"){
				var spos=resjson['data'].indexOf("---");
				if(spos>12){
					alert(resjson['data'].substr(12, spos-12));
					var gotohref=resjson['data'].substr(spos+3);
					if(gotohref=="back"){
						history.back();
					}else{
						location.href = gotohref;
					}
				}
			}else if(resjson['data'].substr(0, 4) =="back"){
				history.back();
			}
			else {
				  alert(responseText);
			}

		}
		
	 }
</script>
</head>

<body>

	<div class="place">
    <span>位置：</span>
    <ul class="placeul">
  <li><a href="#">首页</a></li>
			<li><a href="#">文章</a></li>
			<li><a href="#">增加 </a></li>
    </ul>
    </div>
    
    <div class="formbody">
    
    <div class="formtitle"><span>增加文章</span></div>
      <form action="" method="post">
    <ul class="forminfo">
    <li><label>标题</label><input name="title" type="text" class="dfinput" /><i></i></li>
    <li><label>类别</label><input name="classname" type="text" class="dfinput" /><i> </i></li>
    <li style="height:200px;clear:both;"><label>内容</label>
    <textarea id="elm1" name="content" rows="12" cols="10" style="width:70%;"></textarea> </li>
    <li style="clear:both;"><label>&nbsp;</label><input name="" type="submit" class="btn" value="确认保存"/></li>
    </ul>
    </form>
    
    </div>


</body>

</html>