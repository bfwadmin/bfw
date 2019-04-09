<?php
use Lib\Bfw;
use Lib\Util\HtmlUtil;
use Lib\Util\StringUtil;
?>
<?=Bfw::Widget("Header")?>
<?=Bfw::Widget("Menu")?>

<div class="tpl-content-wrapper">
	<div class="tpl-content-page-title">修改组</div>
	<ol class="am-breadcrumb">
		<li><a href="/" class="am-icon-home">首页</a></li>
		<li class="am-active">增加</li>
	</ol>
	<div class="tpl-portlet-components">
		<div class="portlet-title">
			<div class="caption font-green bold">
				<span class="am-icon-code"></span> 增加
			</div>
			<div class="tpl-portlet-input tpl-fz-ml">
				<div class="portlet-input input-small input-inline">
					<div class="input-icon right">
						<i class="am-icon-search"></i> <input type="text"
							class="form-control form-control-solid" placeholder="搜索...">
					</div>
				</div>
			</div>


		</div>
		<div class="tpl-block ">

			<div class="am-g tpl-amazeui-form">


				<div class="am-u-sm-12 am-u-md-9">
					<form class="am-form am-form-horizontal" action="" method="post">
						<div class="am-form-group">
							<label for="user-name" class="am-u-sm-3 am-form-label">名称 / Name</label>
							<div class="am-u-sm-9">
								<input type="text" id="user-name" name="groupname"
									placeholder="名称" value="<?=$groupname?>">
							</div>
						</div>

						<div class="am-form-group">
							<label class="am-u-sm-3 am-form-label">分配权限 / Power</label>
							<div class="am-u-sm-9">

     <?php 
     $_powerdata=$powerdata;
     foreach ($powerdata as $item){
     if($item['pid']==0){
         ?>
                   <div class="am-u-sm-12" style="font-weight:bold;color:grey;font-size:14px;margin-top:10px;"><?=$item['powername']?></div>
            <ul class="am-u-sm-12" >
                     <?php
          foreach ($_powerdata as $subitem){
              
          if($subitem['pid']==$item['id']){
              ?>
              <li class="am-u-sm-3" style="float: left;">
               <input type="checkbox" name="grouppower[]"
									id="ck_<?=$subitem['id']?>"  <?=in_array($subitem['id'], $groupdata)?'checked':''?> value="<?=$subitem['id']?>" /><label
									for="ck_<?=$subitem['id']?>" class=" am-form-label"><?=$subitem['powername']?></label>
              </li>
              <?php 
          }
          }
              ?>
              
          </ul>
            
            
   
    <?php 
     }
     }
     ?>
                                     
                                    </div>
						</div>





						<div class="am-form-group">
							<div class="am-u-sm-9 am-u-sm-push-3">
								<button type="submit" class="am-btn am-btn-primary">提交</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

	</div>

	<script src="<?=STATIC_FILE_PATH?>js/laydate/laydate.js"></script>
	<script type="text/javascript">
var xhr;
var formData;
var uploadcallback;
function bo_upload_complete(data){
	uploadcallback(data);
}
function bo_upload(file,callback){
	 if(!window.FormData || !window.FileList){  
	    throw('您的浏览器不支持ajax upload');  
	 }  
	 uploadcallback = callback;
	 if(xhr==null){
		 xhr = new XMLHttpRequest();
		 xhr.upload.addEventListener("progress", function(evt){
			 if (evt.lengthComputable) {
			    //var percentComplete = Math.round(evt.loaded * 100 / evt.total);//$("#"+loaddingdivname).html('上传'+percentComplete.toString() + '%');
			 }else {
			     //$("#"+loaddingdivname).html('err');
			 }
		 }, false);
		 xhr.addEventListener("load", function(evt){var ret = eval('(' + evt.target.responseText + ')');bo_upload_complete(ret);}, false);
		 xhr.addEventListener("error", function(evt){alert("上传出错"+evt.toString());}, false);
		 xhr.addEventListener("abort", function(evt){alert("用户取消上传");}, false);
	 }
	
	 
	 if(formData==null){
		 formData = new FormData();
	 }
     formData.append('fileData', file);
     xhr.open('POST','<?=Bfw::ACLINK("Attach","AddData")?>');
     xhr.send(formData);
}

$("#meetingimgchoosefile").change(function () {
    bo_upload(this.files[0],function(data){
		 if(data.err){
             alert(data.data);
		 }else{
			 $("#meetingimg").val(data.data.attachid);
			 $("#meetingimg_img").attr('src','<?=IMG_FILE_PATH?>/200_200_3/'+data.data.attachpath);
			    // alert(ret.data.attachid);
		 }
	});
});

</script>
<?=Bfw::Widget("Footer")?>