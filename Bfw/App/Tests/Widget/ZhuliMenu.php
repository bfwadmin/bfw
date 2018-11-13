<?php 
use Lib\Bfw;
use Lib\Util\HtmlUtil;
?>
<style>

      #tabbar_cont{
      	z-index:1;
      	position: fixed;
      	bottom:0;
      }
    </style>
   <div class="weui-tabbar" id="tabbar_cont">

      <a href="<?=Bfw::ACLINK("Baobei","ListDataAdm")?>" class="weui-tabbar__item <?=Bfw::IIF(CONTROL_VALUE=='Boabei'&&ACTION_VALUE=='ListDataAdm', "weui-bar__item--on", "")?>">
          <div class="weui-tabbar__icon">
            <img src="/static/images/icon_nav_msg.png" alt="">
          </div>
          <p class="weui-tabbar__label">报备确认</p>
        </a> 
        <a href="<?=Bfw::ACLINK("Baobei","AddData")?>" class="weui-tabbar__item <?=Bfw::IIF(CONTROL_VALUE=='Boabei'&&ACTION_VALUE=='AddData', "weui-bar__item--on", "")?>">
          <div class="weui-tabbar__icon">
            <img src="/static/images/icon_nav_add.png" alt="">
          </div>
          <p class="weui-tabbar__label">添加报备</p>
        </a>
      <a href="<?=Bfw::ACLINK("Baobei","ListData")?>" class="weui-tabbar__item <?=Bfw::IIF(CONTROL_VALUE=='Boabei'&&ACTION_VALUE=='ListData', "weui-bar__item--on", "")?>">
          <div class="weui-tabbar__icon">
            <img src="/static/images/icon_nav_cell.png" alt="">
          </div>
          <p class="weui-tabbar__label">我的报备</p>
        </a> 
      </div>
