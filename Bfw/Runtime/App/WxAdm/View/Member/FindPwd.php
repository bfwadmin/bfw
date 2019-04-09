<?php
use Lib\Bfw;
use Lib\Util\HtmlUtil;
?>
<?=Bfw::Widget("Header")?>
<?=HtmlUtil::ImportCss("@weburl/statics/css/new/style.css")?>
<?=HtmlUtil::ImportCss("@weburl/statics/css/new/form.css")?>
<style>
* {
	box-sizing: content-box;
}

#find_body {
	margin: 100px auto;
	width: 500px;
	padding: 20px;
	box-shadow: 0px 0px 10px #CCC;
}

#find_body h2 {
	text-align: center;
	margin: 20px;
}

#find_body h3 {
	padding: 0;
	margin: 0;
}

.note_txt {
	color: grey;
	line-height: 24px;
	font-size: 12px;
}

.note_txt h2 {
	font-size: 14px;
}
</style>
<script>
var wait=60;//时间
function waittime(o,p) {//o为按钮的对象，p为可选，这里是60秒过后，提示文字的改变
if (wait == 0) {
o.removeAttr("disabled");
o.val("点击发送验证码");//改变按钮中value的值
p.html("如果您在1分钟内没有收到验证码，请检查您填写的手机号码是否正确或重新发送");
wait = 60;
} else {
o.attr("disabled", true);//倒计时过程中禁止点击按钮
o.val(wait + "秒后重新获取验证码");//改变按钮中value的值
wait--;
//alert(wait);
setTimeout(function() {
waittime(o,p);//循环调用
},
1000)
}
} 

jQuery(function( $ ){
	
	$("#sendmsg").click(function(){
		var thisobj=$(this);
    	var oldvar=$("#mobile").val();
   	    if(oldvar!=""){
   	    	$.ajax({ 
	   	           type: "get", 
	   	           url: "<?=Bfw::ACLINK("Helper","GetPhoneCode","for=findpwd&mobile=")?>"+oldvar, 
	   	           dataType: "json", 
	   	           success: function (data) { 
		   	              if(data['err']){
			   	            	$.Bfw.toastshow(data['data']); 
			   	            	//$(this).focus();
		   	              }else{
			   	            	$.Bfw.toastshow("发送成功");
				   	          waittime(thisobj,$("p"));
			   	            	//encyptstring=data['data'];
		   	              }
	   	                  
	   	           }, 
	   	           error: function (XMLHttpRequest, textStatus, errorThrown) { 
		   	        	//$(this).focus();
		   	        	$.Bfw.toastshow(errorThrown); 
			   	        	
	   	           } 
	   	           });
   	   	  
   	    }
   	});
	
});
</script>
<div id="find_body">
	<h2>找回密码</h2>
	<?=HtmlUtil::BeginForm()?>
		<div class="receivebox">
            <div class="form_item">
			<label>手机号码</label> 
			<input type="text" name="mobile"
				class="form_input" 
				 id="mobile" /> &nbsp;<input
				class="form_input" type="button"
				id="sendmsg" value="获取验证码"
				name="button">
	    	</div>
		   <div class="form_item">
			<label>手机验证码</label> <input class="form_input" type="text" value=""
				name="verifycode"
				 style="width: 60px;" maxlength="6">
		   </div>
		</div>
		<div class="form_item">
			<label>新密码</label> <input class="form_input" type="password"
				name="userpwd" >
		</div>
		<div class="form_item">
			<label>确认新密码</label> <input class="form_input" type="password"
				name="reuserpwd" >
		</div>
		<div class="note_txt">
			<h3 style="display: block; float: left; width: 100%">密码找回说明</h3>
			<ol>
				<li>密码找回方式可分为：邮箱验证码找回以及手机验证码找回。</li>
				<li>密码找回时所使用的邮箱账号或者手机号码必须是已经注册的邮箱和手机。</li>
				<li>为保证验证码邮件或短信能够正常送达，点击获取验证码按钮后请注意查收邮件或手机短信。</li>
				<li>密码区分大小写，仅支持数字、字母、下划线，不支持中文和特殊字符。</li>
				<li>如在使用中遇到问题，请及时与我们的客服人员联系（邮箱：cs@88art.com）。</li>
			</ol>
		</div>
		<div class="form_item">
			<input type="submit" class="submit_btn" value="提交新密码">
		</div>
	<?=HtmlUtil::EndForm()?>
</div>
<?=HtmlUtil::Script("jQuery.Bfw.submitform(\"form\",function(data){if(data['reuserpwd']!=data['userpwd']){alert('两次输入的密码不一致');return false;}else{return true;}},bfwResponse,".json_encode(Bfw::GetValidateArray("FindPwd")).");")?>
<?=Bfw::Widget("Footer")?>