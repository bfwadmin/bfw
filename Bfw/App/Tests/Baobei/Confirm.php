<?php
use App\Lib\Bfw;
use App\Lib\Util\HtmlUtil;

?>
<!DOCTYPE html>
<html>
<head>
<title>确认报备单</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport"
	content="width=device-width, initial-scale=1, user-scalable=no">
<meta name="description" content="报备单">
<link rel="stylesheet" href="static/lib/weui.min.css">
<link rel="stylesheet" href="static/css/jquery-weui.css">
</head>
<body ontouchstart>

<?=Bfw::Widget("Menu")?>

<div class="weui-cells__title">报备信息</div>
        <div class="weui-cells">
        
           <div class="weui-cell">
            <div class="weui-cell__bd">
              <p>报备人</p>
            </div>
            <div class="weui-cell__ft"><?=$itemdata['reg_name']?></div>
          </div>
          
                    
          <div class="weui-cell">
            <div class="weui-cell__bd">
              <p>报备人电话</p>
            </div>
            <div class="weui-cell__ft"><?=$itemdata['reg_phone']?></div>
          </div>
                  <div class="weui-cell">
            <div class="weui-cell__bd">
              <p>项目名称</p>
            </div>
            <div class="weui-cell__ft"><?=$itemdata['housename']?></div>
          </div>
          <div class="weui-cell">
            <div class="weui-cell__bd">
              <p>客户姓名</p>
            </div>
            <div class="weui-cell__ft"><?=$itemdata['cus_name']?></div>
          </div>
            <div class="weui-cell">
            <div class="weui-cell__bd">
              <p>客户电话</p>
            </div>
            <div class="weui-cell__ft">***********</div>
          </div>
          <div class="weui-cell">
            <div class="weui-cell__bd">
              <p>预约时间</p>
            </div>
            <div class="weui-cell__ft"><?=date("Y-m-d H:m",$itemdata['look_time'])?></div>
          </div>

        
 </div>
 <?php if($itemdata['confirm_time']==0){?>
<form action="" method="post" stype="margin:0;padding:0;">
	<div class="weui-cells__title">案场人员确认</div>
	<div class="weui-cells weui-cells_form">
		<div class="weui-cell">
			<div class="weui-cell__hd">
				<label class="weui-label">接待置业顾问</label>
			</div>
			<div class="weui-cell__bd">
				<input class="weui-input" type="text" 
					placeholder="请输入顾问的姓名" name="lou_zhigu" value="<?=Bfw::IIF($reg_data['reg_name'], $reg_data['reg_name'], "")?>">
			</div>
		</div>
		<div class="weui-cell">
			<div class="weui-cell__hd">
				<label class="weui-label">案场工作人员</label>
			</div>
			<div class="weui-cell__bd">
				<input class="weui-input" type="text" 
					placeholder="请输入您的姓名" name="lou_zhuli" value="">
			</div>
		</div>

		<div class="weui-cell">
			<div class="weui-cell__hd">
				<label class="weui-label">开发商/总监</label>
			</div>
			<div class="weui-cell__bd">
				<input class="weui-input" type="text" 
					placeholder="请输入开发商/案场总监姓名" name="lou_builder" value="">
			</div>
		</div>
	</div>

	<div class="weui-cells__title">备注信息</div>
    <div class="weui-cells weui-cells_form">
      <div class="weui-cell">
        <div class="weui-cell__bd">
          <textarea class="weui-textarea" name="memo" placeholder="请输入文本" rows="3"></textarea>
          <div class="weui-textarea-counter"><span></span>200</div>
        </div>
      </div>
    </div>
	

	<?=HtmlUtil::TokenInput()?>
	<div class="weui-btn-area">
		<input  class="weui-btn weui-btn_primary"  type="submit" value="提交" onclick="$.showLoading();" />
	</div>
	<a class="weui-btn">_</a>
</form>
<?php }?>
<script src="static/lib/jquery-2.1.4.js"></script>
<script src="static/js/form.js"></script>
<script src="static/lib/fastclick.js"></script>
<script>
  $(function() {
    FastClick.attach(document.body);
    $('form').ajaxForm({
    	success : showResponse
    });
	
  });

  function showResponse(responseText, statusText, xhr, $form) {
	    $.hideLoading();
		var resjson = eval("(" + responseText + ")");
		if(resjson['err']){
         $.toptip(resjson['data']);
		}else{
			if (resjson['data'].substr(0, 9) == "redirect:") {
				location.href = resjson['data'].substr(9);
			}else if(resjson['data'].substr(0, 12) =="msgredirect:"){
				var spos=resjson['data'].indexOf("---");
				if(spos>12){
					 $.toast(resjson['data'].substr(12, spos-12), function() {

						 var gotohref=resjson['data'].substr(spos+3);
							if(gotohref=="back"){
								history.back();
							}else{
								location.href = gotohref;
							}
						
			         });
				}
			}else if(resjson['data'].substr(0, 4) =="back"){
				history.back();
			}
			else {
				$.toptip(responseText);
			}

		}
}
</script>
<script src="static/js/jquery-weui.js"></script>	
</body>
</html>
