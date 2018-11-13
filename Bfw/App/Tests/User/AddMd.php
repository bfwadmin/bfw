<?php
use App\Lib\Bfw;
use App\Lib\Util\HtmlUtil;

?>
<!DOCTYPE html>
<html>
<head>
<title>门店认证</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport"
	content="width=device-width, initial-scale=1, user-scalable=no">
<meta name="description" content="门店认证">
<link rel="stylesheet" href="static/lib/weui.min.css">
<link rel="stylesheet" href="static/css/jquery-weui.css">
</head>
<body ontouchstart>
<?=Bfw::Widget("Menu")?>
<form action="" method="post" stype="margin:0;padding:0;">
	<div class="weui-cells__title">门店信息</div>
	<div class="weui-cells weui-cells_form">
		<div class="weui-cell">
			<div class="weui-cell__hd">
				<label class="weui-label">店长姓名</label>
			</div>
			<div class="weui-cell__bd">
				<input class="weui-input" type="text" 
					placeholder="请输入您的姓名" name="shop_master"  >
			</div>
		</div>
		<div class="weui-cell">
			<div class="weui-cell__hd">
				<label class="weui-label">店长电话</label>
			</div>
			<div class="weui-cell__bd">
				<input class="weui-input" type="number" pattern="[0-9]*"
					placeholder="请输入您的手机号码" name="master_phone" required="required">
			</div>
		</div>

	</div>


			<div class="weui-cell">
			<div class="weui-cell__bd">
				<div class="weui-uploader">
					<div class="weui-uploader__hd">
						<p class="weui-uploader__title">营业执照上传</p>
						<div class="weui-uploader__info">1</div>
					</div>
					<div class="weui-uploader__bd">
						<ul class="weui-uploader__files" id="uploaderFiles">
	
						</ul>
						<div class="weui-uploader__input-box">
						   <input type="hidden" name="liense_img" id="lis_img">
							<input id="uploaderInput" class="weui-uploader__input"
								type="file" accept="image/*" multiple="">
						</div>
					</div>
				</div>
			</div>
		</div>

	<?=HtmlUtil::TokenInput()?>
	<div class="weui-btn-area">
		<input  class="weui-btn weui-btn_primary"  type="submit" value="提交"  />
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

						
			         });
				}
			}else if(resjson['data'].substr(0, 4) =="back"){
				history.back();
			}
			else {
				$.toast(resjson['data'], function() {	
		       });
			
			}

		}
}
</script>

	<script>
  $(function () {  
    // 允许上传的图片类型  
    var allowTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];  
    // 1024KB，也就是 1MB  
    var maxSize = 3072 * 1024;  
    // 图片最大宽度  
    var maxWidth = 10000;  
    // 最大上传图片数量  
    var maxCount = 1;  
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
                                    datatype:'json',
                                    success: function(data){
                                            if(data.err){
                                           	  $.alert(data.data);
                                            }else{
                                                $("#lis_img").val(data.data);
                                             	//$.alert();
                                                $preview.removeClass('weui-uploader__file_status');
                                                $.toast("上传成功", function() {
                                                     //console.log('close');
                                               });

                                           }
                                   },
                                error: function(xhr, type,ss){
                                     	$.alert('error!'+type+ss);
                                }
                             });
        
                      };  
                      
                                    
                    };  
                    
                  }  
                });  
  }); 
</script>
<script src="static/js/jquery-weui.js"></script>	
</body>
</html>
