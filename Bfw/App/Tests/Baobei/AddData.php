<?php
use Lib\Bfw;
use Lib\Util\HtmlUtil;

?>
<!DOCTYPE html>
<html>
<head>
<title>提交报备单</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport"
	content="width=device-width, initial-scale=1, user-scalable=no">
<meta name="description" content="报备单提交">
<link rel="stylesheet" href="static/lib/weui.min.css">
<link rel="stylesheet" href="static/css/jquery-weui.css">
</head>
<body ontouchstart>

<?=Bfw::Widget("Menu")?>
<form action="" method="post" stype="margin:0;padding:0;">
	<div class="weui-cells__title">报备人信息</div>
	<div class="weui-cells weui-cells_form">
		<div class="weui-cell">
			<div class="weui-cell__hd">
				<label class="weui-label">报备人姓名</label>
			</div>
			<div class="weui-cell__bd">
				<input class="weui-input" type="text" 
					placeholder="请输入您的姓名" name="reg_name" value="<?=Bfw::IIF($reg_data['reg_name'], $reg_data['reg_name'], "")?>" required="required">
			</div>
		</div>
		<div class="weui-cell">
			<div class="weui-cell__hd">
				<label class="weui-label">报备人电话</label>
			</div>
			<div class="weui-cell__bd">
				<input class="weui-input" type="number" pattern="[0-9]*"
					placeholder="请输入您的手机号码" name="reg_phone" value="<?=Bfw::IIF($reg_data['reg_phone'], $reg_data['reg_phone'], "")?>" required="required">
			</div>
		</div>

	</div>

  	<div class="weui-cell weui-cell_select weui-cell_select-after">
			<div class="weui-cell__hd">
				<label for="" class="weui-label">项目信息</label>
			</div>
			<div class="weui-cell__bd">
			<?=HtmlUtil::Option("loupan_id",$loupandata,"",['class'=>'weui-select'])?>
			</div>
	</div>
		
	<div class="weui-cells__title">客户信息</div>
	<div class="weui-cells weui-cells_form">
		<div class="weui-cell">
			<div class="weui-cell__hd">
				<label class="weui-label">客户姓名</label>
			</div>
			<div class="weui-cell__bd">
				<input class="weui-input" type="text" 
					placeholder="请输入客户的姓名" name="cus_name" required="required">
			</div>
		</div>

		  	<div class="weui-cell weui-cell_select weui-cell_select-after">
			<div class="weui-cell__hd">
				<label for="" class="weui-label">客户性别</label>
			</div>
			<div class="weui-cell__bd">
				<select class="weui-select" name="cus_sex">
					<option value="1">男</option>
					<option value="0">女</option>
	
				</select>
			</div>
	</div>
		<div class="weui-cell">
			<div class="weui-cell__hd">
				<label class="weui-label">客户电话</label>
			</div>
			<div class="weui-cell__bd">
				<input class="weui-input" type="number" pattern="[0-9]*"
					placeholder="请输入客户的手机号码" name="cus_phone" required="required">
			</div>
		</div>
	  <div class="weui-cell">
        <div class="weui-cell__hd"><label for="time-format" class="weui-label">预约时间</label></div>
        <div class="weui-cell__bd">
          <input class="weui-input" id="time-format" type="text" value="" name="look_time" required="required">
        </div>
      </div>
		
	</div>
	<?=HtmlUtil::TokenInput()?>
	<div class="weui-btn-area">
		<input  class="weui-btn weui-btn_primary"  type="submit" value="提交" />
	</div>
</form>
<script src="static/lib/jquery-2.1.4.js"></script>
<script src="static/js/form.js"></script>
<script src="static/lib/fastclick.js"></script>
<script>
  $(function() {
    FastClick.attach(document.body);
    $('form').ajaxForm({
   	 beforeSubmit: showRequest,
    	success : showResponse
    });
	 $("#time-format").datetimePicker({
	        title: '预约时间',
	        yearSplit: '年',
	        monthSplit: '月',
	        dateSplit: '日',
	        times: function () {
	          return [  // 自定义的时间
	            {
	              values: (function () {
	                var hours = [];
	                for (var i=0; i<24; i++) hours.push(i > 9 ? i : '0'+i);
	                return hours;
	              })()
	            },
	            {
	              divider: true,  // 这是一个分隔符
	              content: '时'
	            },
	            {
	              values: (function () {
	                var minutes = [];
	                for (var i=0; i<59; i++) minutes.push(i > 9 ? i : '0'+i);
	                return minutes;
	              })()
	            },
	            {
	              divider: true,  // 这是一个分隔符
	              content: '分'
	            }
	          ];
	        },
	        onChange: function (picker, values, displayValues) {
	         //alert(values);
	        }
	      });
  });
  function showRequest(formData, jqForm, options){
	  $.showLoading();
	   //formData: 数组对象，提交表单时，Form插件会以Ajax方式自动提交这些数据，格式如：[{name:user,value:val },{name:pwd,value:pwd}]
	   //jqForm:   jQuery对象，封装了表单的元素   
	   //options:  options对象
	  // var queryString = $.param(formData);   //name=1&address=2
	   //var formElement = jqForm[0];              //将jqForm转换为DOM对象
	   //var address = formElement.address.value;  //访问jqForm的DOM元素
	   return true;  //只要不返回false，表单都会提交,在这里可以对表单元素进行验证
	};
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

						 $.confirm("您是否继续添加报备?", "继续报备?", function() {
					          location.reload(0);
					        }, function() {
					        	 var gotohref=resjson['data'].substr(spos+3);
									if(gotohref=="back"){
										history.back();
									}else{
										location.href = gotohref;
									}
					        });
						
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
