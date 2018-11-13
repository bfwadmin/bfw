<?php
use App\Lib\Bfw;
use App\Lib\Util\HtmlUtil;

?>
<!DOCTYPE html>
<html>
<head>
<title>提交报备单</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport"
	content="width=device-width, initial-scale=1, user-scalable=no">
<meta name="description" content="报备信息">
<link rel="stylesheet" href="static/lib/weui.min.css">
<link rel="stylesheet" href="static/css/jquery-weui.css">
</head>
<body ontouchstart>
<?=Bfw::Widget("Menu")?>
      <div class="weui-cells__title">报备信息</div>
        <div class="weui-cells">
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
            <div class="weui-cell__ft"><?=$itemdata['look_time']?></div>
          </div>
          
          <div class="weui-cell">
            <div class="weui-cell__bd">
              <p>确认时间</p>
            </div>
            <div class="weui-cell__ft"><?=$itemdata['confirm_time']?></div>
          </div>
        
 </div>
<script src="static/js/jquery-weui.js"></script>	
</body>
</html>
