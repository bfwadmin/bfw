<?php
use Lib\Bfw;
use Lib\Util\HtmlUtil;
?>
<?=Bfw::Widget("Header")?>
<?=HtmlUtil::ImportCss("@weburl/statics/css/new/style.css")?>
<style type="text/css">
body {
	background: #e6e6e6
}
.kindidradio label{
	padding:8px;
}
</style>
<div style= "position:absolute;left:0;top:0;width:100%;height:100%;z-index:20000; background-color:white;"></div>
<div class="log_regcon">
	<div class="log_or_reg" id="tab_reg_third">
		<a href="javascript:;" class="on" >
			无账号请完善资料
		</a>
		<a href="javascript:;" class="off">
			有账号绑定
		</a>
		
	</div>
	<div>
	<div id="bind_div">
	<!-- 
	<?=HtmlUtil::BeginForm("bfwform",Bfw::ACLINK("Member","BindThird",'thirdlogin=1'))?>	
		<div class="log_reg_item" align="center">
				<?=HtmlUtil::Img($userinfo['userimg'], ['style'=>'width:100px;height:100px;'])?>
		</div>
		<div class="log_reg_item">
		 <?=HtmlUtil::Input("nickname",$userinfo['nickname'], ['class'=>"l_input",'id'=>"nickname","type"=>"text",'placeholder'=>"请输入昵称(2~8个中文或4~15个英文字符)"])?>
         <?=HtmlUtil::Input("openid",$userinfo['openid'],['type'=>'hidden'])?>
         <?=HtmlUtil::Input("thirdname",$thirdname,['type'=>'hidden'])?>
         <?=HtmlUtil::Input("userimg",$userinfo['userimgid'],['type'=>'hidden'])?>
		</div>
		<div class="log_reg_item">
		<?=HtmlUtil::Input("mobile","", ['class'=>"l_input",'id'=>"mobile","type"=>"text",'placeholder'=>"手机号"])?>
		</div>
		<div class="log_reg_item">
			<?=HtmlUtil::Input("verifycode", "", ['id'=>"verifycode","type"=>"text",'placeholder'=>"验证码",'class'=>"l_input","maxlength"=>"6"])?>

			<?=HtmlUtil::Input("sendmsg", "获取验证码",['id'=>"sendmsg","type"=>"button","class"=>"get_code"])?>
		</div>
		
		<div class="log_btncon">
			<input type="submit" tabindex="305" id="logsubmit" name="logsubmit" class="subbtn" value="提交"/>
	
		</div>
		
		
	<?=HtmlUtil::EndForm()?> -->
	</div>
	<div id="reg_div" >
	<?=HtmlUtil::BeginForm("bfwform",Bfw::ACLINK("Member","Register",'thirdlogin=1'))?>	
		<div class="log_reg_item" align="center">
				<?=HtmlUtil::Img($userinfo['userimg'], ['style'=>'width:100px;height:100px;'])?>
		</div>
		 <div class="log_reg_item kindidradio">
        <?=HtmlUtil::Radio("kindid", ["1"=>'普通用户','2'=>'艺术家',"5"=>"画廊"],1)?>
		</div>
		<div class="log_reg_item">
		 <?=HtmlUtil::Input("nickname",$userinfo['nickname'], ['class'=>"l_input",'id'=>"nickname","type"=>"text",'placeholder'=>"请输入昵称(2~8个中文或4~15个英文字符)"])?>
         <?=HtmlUtil::Input("openid",$userinfo['openid'],['type'=>'hidden'])?>
         <?=HtmlUtil::Input("thirdname",$thirdname,['type'=>'hidden'])?>
           <?=HtmlUtil::Input("userimg",$userinfo['userimgid'],['type'=>'hidden'])?>
		</div>
		<div class="log_reg_item">
				<?=HtmlUtil::Input("mobile","", ['class'=>"l_input",'id'=>"mobile","type"=>"text",'placeholder'=>"手机号"])?>
		</div>
		<div class="log_reg_item">
			<?=HtmlUtil::Input("verifycode", "", ['id'=>"verifycode","type"=>"text",'placeholder'=>"验证码",'class'=>"l_input","maxlength"=>"6"])?>

			<?=HtmlUtil::Input("sendmsg", "获取验证码",['id'=>"sendmsg","type"=>"button","class"=>"get_code"])?>
		</div>
		
		<div class="log_btncon">
			<input type="submit" tabindex="305" id="logsubmit" name="logsubmit" class="subbtn" value="提交"/>
	
		</div>
		
		
	<?=HtmlUtil::EndForm()?>
</div>
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
	
     $("#tab_reg_third a").click(function(){
         $(this).siblings().removeClass('on').addClass("off");
         $(this).addClass('on');
         $("#bind_div div").eq($(this).index()).siblings().hide();
         $("#bind_div div").eq($(this).index()).show();
    });
	$("#sendmsg").click(function(){
		var thisobj=$(this);
    	var oldvar=$("#mobile").val();
   	    if(oldvar!=""){
   	    	$.ajax({ 
	   	           type: "get", 
	   	           url: "<?=Bfw::ACLINK("Helper","GetPhoneCode","mobile=")?>"+oldvar, 
	   	           dataType: "json", 
	   	           success: function (data) { 
		   	              if(data['err']){
			   	            	alert(data['data']); 
			   	            	//$(this).focus();
		   	              }else{
			   	              alert("发送成功");
				   	          waittime(thisobj,$("p"));
			   	            	//encyptstring=data['data'];
		   	              }
	   	                  
	   	           }, 
	   	           error: function (XMLHttpRequest, textStatus, errorThrown) { 
		   	        	//$(this).focus();
		   	            alert(errorThrown); 
			   	        	
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
<?=HtmlUtil::Script("$('form').submit();")?>

<?=Bfw::Widget("Footer")?>
