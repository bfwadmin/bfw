<?php
use Lib\Bfw;
use Lib\Util\HtmlUtil;
use App\Client\Cms\Client_Member;
?>
<?=Bfw::Widget("Header")?>
<?=HtmlUtil::ImportCss("@weburl/statics/css/new/style.css")?>
<style type="text/css">
	body{background:#e6e6e6}
</style>
<div class="log_regcon">
	<div class="log_or_reg">
		<a href="javascript:;" class="on">
			短信登录
		</a>
		<a href="<?=Bfw::ACLINK("Member","Register")?>">
			注册
		</a>
	</div>
	<?=HtmlUtil::BeginForm()?>	
			
		<div class="log_reg_item">
				<?=HtmlUtil::Input("mobile","", ['class'=>"l_input",'id'=>"mobile","type"=>"text",'placeholder'=>"手机号"])?>
		</div>
		<div class="log_reg_item">
			<?=HtmlUtil::Input("verifycode", "", ['id'=>"verifycode","type"=>"text",'placeholder'=>"验证码",'class'=>"l_input","maxlength"=>"6"])?>

			<?=HtmlUtil::Input("sendmsg", "获取验证码",['id'=>"sendmsg","type"=>"button","class"=>"get_code"])?>
		</div>
		<div class="log_btncon">
			<input type="submit" tabindex="305" id="logsubmit" name="logsubmit" class="subbtn" value="登录"/>
	
		</div>
		<div class="log_reg_item">
			<span class="f_l gay_color">
				<?=HtmlUtil::Tag("a", "账号密码登录",['href'=>Bfw::ACLINK("Member","Login",'method='.Client_Member::USER_PWD_LOGIN)])?>
			</span>
			  <a href="<?=Bfw::ACLINK("Member","FindPwd")?>" target="_blank" class="gay_color f_r">&nbsp; 忘记密码?</a>
		</div>
	<?=HtmlUtil::EndForm()?>
	<div class="other_log">
		<h3>第三方登录</h3>
			<ul>
			<li>
				<a href="<?=Bfw::ACLINK("Member","Login",'method='.Client_Member::QQ_LOGIN)?>">
					<img src="<?=STATIC_FILE_PATH?>/statics/images/new/qq.png" width="50" height="50" alt="qq"/>
				</a>
			</li>
			 <li>
				<a href="<?=Bfw::ACLINK("Member","Login",'method='.Client_Member::SINA_LOGIN)?>">
					<img src="<?=STATIC_FILE_PATH?>/statics/images/new/weibo.png" width="50" height="50" alt="qq"/>
				</a>
			</li>
			<li>
				<a href="<?=Bfw::ACLINK("Member","Login",'method='.Client_Member::WEIXIN_LOGIN)?>">
					<img src="<?=STATIC_FILE_PATH?>/statics/images/new/weixin.png" width="50" height="50" alt="qq"/>
				</a>
			</li> 
		</ul>
	</div>
</div>
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
   	   	    //弹出验证码

   	   	    
   	    	$.ajax({ 
	   	           type: "get", 
	   	           url: "<?=Bfw::ACLINK("Helper","GetPhoneCode","for=login&mobile=")?>"+oldvar, 
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
var password =function(p,k){
	   var str=md5(p).substr(3);
	     str=str.substr(0,str.length-3);
	     str = k.substr(0, 2) + str + k.substr(3, 5);
	     str=md5(str);
     return str;
 } 
function getToken(){
	$.ajax({ 
           type: "get", 
           url: "<?=Bfw::ACLINK("Member","GetFormToken")?>", 
           dataType: "json", 
           success: function (data) { 
   	              if(data['err']){
	   	            	alert(data['data']); 
   	              }else{
	   	            	encyptkey=data['data'];
   	              }
                  
           }, 
           error: function (XMLHttpRequest, textStatus, errorThrown) { 
   	        	
   	            alert(errorThrown); 
	   	        	
           } 
           });
}
</script>
<?=HtmlUtil::Script("jQuery.Bfw.submitform(\"form\",function(data){return true;},bfwResponse,".json_encode(Bfw::GetValidateArray("SmsLogin")).");")?>
<?=Bfw::Widget("Footer")?>
