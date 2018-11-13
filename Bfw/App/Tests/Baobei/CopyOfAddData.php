<?php
use App\Lib\Util\HtmlUtil;
use App\Lib\Bfw;
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
<form action="" method="post" stype="margin:0;padding:0;">

	<!-- 	<div class="weui-cells__title">交通方式</div>
	<div class="weui-cells weui-cells_radio">
		<label class="weui-cell weui-check__label" for="x11">
			<div class="weui-cell__bd">
				<p>自驾</p>
			</div>
			<div class="weui-cell__ft">
				<input type="radio" class="weui-check" name="radio1" id="x11"> <span
					class="weui-icon-checked"></span>
			</div>
		</label> <label class="weui-cell weui-check__label" for="x12">

			<div class="weui-cell__bd">
				<p>大巴</p>
			</div>
			<div class="weui-cell__ft">
				<input type="radio" name="radio1" class="weui-check" id="x12"
					checked="checked"> <span class="weui-icon-checked"></span>
			</div>
		</label>
			 <a href="javascript:void(0);"
			class="weui-cell weui-cell_link">
			<div class="weui-cell__bd">添加更多</div>
		</a> -->
	</div>
	<!-- 	<div class="weui-cells__title">复选列表项</div>
	<div class="weui-cells weui-cells_checkbox">
		<label class="weui-cell weui-check__label" for="s11">
			<div class="weui-cell__hd">
				<input type="checkbox" class="weui-check" name="checkbox1" id="s11"
					checked="checked"> <i class="weui-icon-checked"></i>
			</div>
			<div class="weui-cell__bd">
				<p>standard is dealt for u.</p>
			</div>
		</label> <label class="weui-cell weui-check__label" for="s12">
			<div class="weui-cell__hd">
				<input type="checkbox" name="checkbox1" class="weui-check" id="s12">
				<i class="weui-icon-checked"></i>
			</div>
			<div class="weui-cell__bd">
				<p>standard is dealicient for u.</p>
			</div>
		</label>
			<a href="javascript:void(0);"
			class="weui-cell weui-cell_link">
			<div class="weui-cell__bd">添加更多</div>
		</a>
	</div>
 -->
	<div class="weui-cells__title">经纪人信息</div>
	<div class="weui-cells weui-cells_form">
		<div class="weui-cell">
			<div class="weui-cell__hd">
				<label class="weui-label">经纪人姓名</label>
			</div>
			<div class="weui-cell__bd">
				<input class="weui-input" type="text" 
					placeholder="请输入您的姓名" name="reg_name">
			</div>
		</div>
		<div class="weui-cell">
			<div class="weui-cell__hd">
				<label class="weui-label">经纪人电话</label>
			</div>
			<div class="weui-cell__bd">
				<input class="weui-input" type="number" pattern="[0-9]*"
					placeholder="请输入您的手机号码" name="reg_phone">
			</div>
		</div>
<!-- 		<div class="weui-cell weui-cell_vcode">
			<div class="weui-cell__hd">
				<label class="weui-label">手机号</label>
			</div>
			<div class="weui-cell__bd">
				<input class="weui-input" type="tel" placeholder="请输入手机号">
			</div>
			<div class="weui-cell__ft">
				<button class="weui-vcode-btn">获取验证码</button>
			</div>
		</div> -->
		
<!-- 		<div class="weui-cell">
			<div class="weui-cell__hd">
				<label for="" class="weui-label">时间</label>
			</div>
			<div class="weui-cell__bd">
				<input class="weui-input" type="datetime-local" value=""
					placeholder="">
			</div>
		</div>
		<div class="weui-cell weui-cell_vcode">
			<div class="weui-cell__hd">
				<label class="weui-label">验证码</label>
			</div>
			<div class="weui-cell__bd">
				<input class="weui-input" type="number" placeholder="请输入验证码">
			</div>
			<div class="weui-cell__ft">
				<img class="weui-vcode-img" src="./images/vcode.jpg">
			</div>
		</div> -->
	</div>
<!-- 	<div class="weui-cells__tips">底部说明文字底部说明文字</div>

	<div class="weui-cells__title">表单报错</div>
	<div class="weui-cells weui-cells_form">
		<div class="weui-cell weui-cell_warn">
			<div class="weui-cell__hd">
				<label for="" class="weui-label">卡号</label>
			</div>
			<div class="weui-cell__bd">
				<input class="weui-input" type="number" pattern="[0-9]*"
					value="weui input error" placeholder="请输入卡号">
			</div>
			<div class="weui-cell__ft">
				<i class="weui-icon-warn"></i>
			</div>
		</div>
	</div>


	<div class="weui-cells__title">开关</div>
	<div class="weui-cells weui-cells_form">
		<div class="weui-cell weui-cell_switch">
			<div class="weui-cell__bd">标题文字</div>
			<div class="weui-cell__ft">
				<input class="weui-switch" type="checkbox">
			</div>
		</div>
		<div class="weui-cell weui-cell_switch">
			<div class="weui-cell__bd">兼容IE Edge的版本</div>
			<div class="weui-cell__ft">
				<label for="switchCP" class="weui-switch-cp"> <input id="switchCP"
					class="weui-switch-cp__input" type="checkbox" checked="checked">
					<div class="weui-switch-cp__box"></div>
				</label>
			</div>
		</div>
	</div>

	<div class="weui-cells__title">文本框</div>
	<div class="weui-cells">
		<div class="weui-cell">
			<div class="weui-cell__bd">
				<input class="weui-input" type="text" placeholder="请输入文本">
			</div>
		</div>
	</div>

	<div class="weui-cells__title">文本域</div>
	<div class="weui-cells weui-cells_form">
		<div class="weui-cell">
			<div class="weui-cell__bd">
				<textarea class="weui-textarea" placeholder="请输入文本" rows="3"></textarea>
				<div class="weui-textarea-counter">
					<span>0</span>/200
				</div>
			</div>
		</div>
	</div> -->

	<!-- <div class="weui-cells__title">选择</div>
	<div class="weui-cells">

		<div class="weui-cell weui-cell_select weui-cell_select-before">
			<div class="weui-cell__hd">
				<select class="weui-select" name="select2">
					<option value="1">+86</option>
					<option value="2">+80</option>
					<option value="3">+84</option>
					<option value="4">+87</option>
				</select>
			</div>
			<div class="weui-cell__bd">
				<input class="weui-input" type="number" pattern="[0-9]*"
					placeholder="请输入号码">
			</div>
		</div>
	</div> -->
	<!-- <div class="weui-cells__title">选择</div> -->
<!-- 	<div class="weui-cells">
		<div class="weui-cell weui-cell_select">
			<div class="weui-cell__bd">
				<select class="weui-select" name="select1">
					<option selected="" value="1">微信号</option>
					<option value="2">QQ号</option>
					<option value="3">Email</option>
				</select>
			</div>
		</div> -->
		<div class="weui-cell weui-cell_select weui-cell_select-after">
			<div class="weui-cell__hd">
				<label for="" class="weui-label">项目信息</label>
			</div>
			<div class="weui-cell__bd">
				<select class="weui-select" name="loupan_id">
					<option value="1">中冠</option>
					<option value="2">天宇</option>
	
				</select>
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
					placeholder="请输入客户的姓名" name="cus_name">
			</div>
		</div>
		<div class="weui-cell weui-cell_select weui-cell_select-after">
			<div class="weui-cell__hd">
				<label for="" class="weui-label">客户性别</label>
			</div>
			<div class="weui-cell__bd">
				<select class="weui-select" name="select2">
					<option value="1">男</option>
					<option value="2">女</option>
	
				</select>
			</div>
		</div>
		
		<div class="weui-cell">
			<div class="weui-cell__hd">
				<label class="weui-label">客户电话</label>
			</div>
			<div class="weui-cell__bd">
				<input class="weui-input" type="number" pattern="[0-9]*"
					placeholder="请输入客户的手机号码" name="cus_phone">
			</div>
		</div>
	  <div class="weui-cell">
        <div class="weui-cell__hd"><label for="time-format" class="weui-label">预约时间</label></div>
        <div class="weui-cell__bd">
          <input class="weui-input" id="time-format" type="text" value="" name="look_time">
        </div>
      </div>
		
	</div>
		<div class="weui-cell">
			<div class="weui-cell__bd">
				<div class="weui-uploader">
					<div class="weui-uploader__hd">
						<p class="weui-uploader__title">图片上传</p>
						<div class="weui-uploader__info">0/2</div>
					</div>
					<div class="weui-uploader__bd">
						<ul class="weui-uploader__files" id="uploaderFiles">
	
						</ul>
						<div class="weui-uploader__input-box">
							<input id="uploaderInput" class="weui-uploader__input"
								type="file" accept="image/*" multiple="">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?=HtmlUtil::TokenInput()?>
<!--     <label for="weuiAgree" class="weui-agree"> <input id="weuiAgree"
		type="checkbox" class="weui-agree__checkbox"> <span
		class="weui-agree__text"> 阅读并同意<a href="javascript:void(0);">《相关条款》</a>
	</span>
	</label> -->

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

  function showResponse(responseText, statusText, xhr, $form) {
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
	<script>
      $("#submit_btn").click(function() {
    	$.showLoading();
    	$.hideLoading();
    	 $.toast("操作成功", function() {
             console.log('close');
           });
        var tel = $('#tel').val();
        var code = $('#code').val();
        if(!tel || !/1[3|4|5|7|8]\d{9}/.test(tel)) $.toptip('请输入手机号');
        else if(!code || !/\d{6}/.test(code)) $.toptip('请输入六位手机验证码');
        else $.toptip('提交成功', 'success');
      });
    </script>
	<script>
  $(function () {  
    // 允许上传的图片类型  
    var allowTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];  
    // 1024KB，也就是 1MB  
    var maxSize = 1024 * 1024;  
    // 图片最大宽度  
    var maxWidth = 10000;  
    // 最大上传图片数量  
    var maxCount = 6;  
    $('#uploaderInput').on('change', function (event) {  
      var files = event.target.files;  
      //console.log(files);return false;
        // 如果没有选中文件，直接返回  
        if (files.length === 0) {  
          return;  
        }  
        
        for (var i = 0, len = files.length; i < len; i++) {  
          var file = files[i];  
          var reader = new FileReader();  
            if (allowTypes.indexOf(file.type) === -1) {  
                $.alert("该类型不允许上传！", "警告！");              
              continue;  
            }  
            
            if (file.size > maxSize) {  
                $.alert("图片太大，不允许上传", "警告！");              
              continue;  
            }  
            if ($('.weui-uploader__file').length >= maxCount) {  
           	 $.alert('最多只能上传' + maxCount + '张图片', "警告！");    
              return;  
            }  
            reader.readAsDataURL(file);  
            reader.onload = function (e) {
                //console.log(e);
              var img = new Image(); 
                img.src = e.target.result;         
                img.onload = function () {  
                    // 不要超出最大宽度  
                    var w = Math.min(maxWidth, img.width);  
                    // 高度按比例计算  
                    var h = img.height * (w / img.width);  
                    var canvas = document.createElement('canvas');  
                    var ctx = canvas.getContext('2d');  
                    // 设置 canvas 的宽度和高度  
                    canvas.width = w;  
                    canvas.height = h;  
                    ctx.drawImage(img, 0, 0, w, h); 
　　　　　　　　　　　　
                    var base64 = canvas.toDataURL('image/jpeg',0.8);  
                   //console.log(base64);
                    // 插入到预览区  
                    var $preview = $('<li class="weui-uploader__file weui-uploader__file_status" style="background-image:url(' + img.src + ')"><div class="weui-uploader__file-content">0%</div></li>');  
                    $('#uploaderFiles').append($preview);  
                    var num = $('.weui-uploader__file').length;  
                    $('.weui-uploader__info').text(num + '/' + maxCount);  
                    
                   
                     var formData = new FormData();
 
                    formData.append("images", base64);
                    //console.log(img.src);
                         $.ajax({
                                    url: "<?=Bfw::ACLINK("Files","Upload","","Helper")?>",
                                    type: 'POST',
                                    data: formData,                                             
                                    contentType:false,
                                    processData:false,
                                    success: function(data){
                                            if(data.err){
                                           	  $.alert(data.data);
                                            }else{
                                             	$.alert(data.data);
                                                $preview.removeClass('weui-uploader__file_status');
                                                $.toast("上传成功", function() {
                                                     //console.log('close');
                                               });

                                           }
                                   },
                                error: function(xhr, type){
                                     	$.alert('Ajax error!');
                                }
                             });
        
                      };  
                      
                                    
                    };  
                    
                  }  
                });  
  }); 
</script>
</body>
</html>
