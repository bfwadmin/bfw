<?php
use App\Lib\Util\HtmlUtil;
use App\Lib\Bfw;
?>
<!DOCTYPE html>
<html>
<head>
<title>报备确认</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport"
	content="width=device-width, initial-scale=1, user-scalable=no">
<meta name="description" content="我的报备单">
<link rel="stylesheet" href="static/lib/weui.min.css">
<link rel="stylesheet" href="static/css/jquery-weui.css">
</head>
<body ontouchstart>
<?=Bfw::Widget("Menu")?>

    <div class="page__pd">
      <div class="weui-search-bar" id="searchBar">
        <form class="weui-search-bar__form" method="get" action="/index.php">
        
         <?=HtmlUtil::Input(CONTROL_NAME, "Baobei",array('type'=>"hidden"))?>
         <?=HtmlUtil::Input(ACTION_NAME, "ListDataAdm",array('type'=>"hidden"))?>
         <?=HtmlUtil::Input(DOMIAN_NAME, "Xdweb",array('type'=>"hidden"))?>
          <div class="weui-search-bar__box">
            <i class="weui-icon-search"></i>
            <input value="<?=Bfw::GPC("keyword")?>" type="search" name="keyword" class="weui-search-bar__input" id="searchInput" placeholder="输入报备人姓名进行搜索" required="">
            <a href="<?=Bfw::ACLINK("Baobei","ListDataAdm")?>" class="weui-icon-clear" id="searchClear"></a>
          </div>
          
          <label class="weui-search-bar__label" id="searchText" style="transform-origin: 0px 0px 0px; opacity: 1; transform: scale(1, 1);">
            <i class="weui-icon-search"></i>
            <span>搜索</span>
          </label>
        
        </form>
        <a href="javascript:" class="weui-search-bar__cancel-btn" id="searchCancel">取消</a>
      </div>
      
     
      <div class="weui-panel weui-panel_access">
      
    
        <div class="weui-panel__bd" id="list_cont">
        <?php 
        if(empty($itemdata)){
        ?>
        <div style="padding-top:100px;text-align:center;">
        <p><img src="static/images/icon_nav_calendar.png" /></p>
        <p> 暂无待处理报备信息</p>
      
        </div>
        <?php }else{?>
         <?php
         
            foreach ($itemdata as $item) {
        ?>  
           
          <a href="javascript:void(0);" nowid="<?=$item['id']?>" status="<?=$item['status']?>" phone="<?=$item['reg_phone']?>" class="weui-media-box weui-media-box_appmsg open-popup item_baobei" >
            <div class="weui-media-box__hd">
              <img class="weui-media-box__thumb" src="/static/images/avatar<?=$item['cus_sex']?>.png" alt="">
                     <?php if($item['look_time_str']=="今天"){?>
              <span class="weui-badge" style="position: absolute;top: -.4em;right: -.4em;">today</span>
              <?php }?>
            </div>
            <div class="weui-media-box__bd">
              <h4 class="weui-media-box__title"><?=$item['reg_name']?>
                <span class="weui-media-box__title-after"><?=$item['status']?></span>
              </h4>
              <p class="weui-media-box__desc">已预约 <span style="color:green;"><?=$item['look_time_str']?><?=$item['look_time_str_det']?></span> 来 <span style="color:red;"><?=$item['housename']?></span> 看房</p>
            </div>
          </a>
          
         <?php 
             }
        }
         ?> 
      
        </div>
      </div>
    </div>
        <?php 
        if(!empty($itemdata)&&count($itemdata)==10){
        ?>
      <div class="weui-loadmore">
        <i class="weui-loading" id="weui-loading_icon"></i>
        <span class="weui-loadmore__tips" id="loadmore__tips">正在加载</span>
     </div>
     <?php }?>
   
    <style>
      .weui-panel {
        margin: 0;
      }
      .weui-media-box {
        padding: 8px 15px;
      }
      .weui-panel__bd .weui-media-box__hd {
        width: 50px;
        height: 50px;
        line-height: 50px;
        position: relative;
      }
      .weui-media-box__desc {
        -webkit-line-clamp: 1;
      }
      .weui-media-box__title {
        margin-top: -4px;
      }
      #tabbar_cont{
      	z-index:1;
      	position: fixed;
      	bottom:0;
      }
    </style>
    
   <a href="javascript:;" id="click_a_detail" class="weui-btn weui-btn_primary open-popup" data-target="#full"></a>
   <div id="full" class='weui-popup__container'>
      <div class="weui-popup__overlay"></div>
      <div class="weui-popup__modal">
        <article class="weui-article" id="baobei_art_cont">
        <div class="weui-cells__title">报备信息</div>
        <div class="weui-cells">
        
           <div class="weui-cell">
            <div class="weui-cell__bd">
              <p>项目名称</p>
            </div>
            <div class="weui-cell__ft" id="art_loupan_d"></div>
          </div>
          <div class="weui-cell">
            <div class="weui-cell__bd">
              <p>客户姓名</p>
            </div>
            <div class="weui-cell__ft" id="art_cus_name_d"></div>
          </div>
            <div class="weui-cell">
            <div class="weui-cell__bd">
              <p>客户电话</p>
            </div>
            <div class="weui-cell__ft" id="art_cus_phone_d"></div>
          </div>
                    
          <div class="weui-cell">
            <div class="weui-cell__bd">
              <p>提交时间</p>
            </div>
            <div class="weui-cell__ft" id="art_reg_time_d"></div>
          </div>
          <div class="weui-cell">
            <div class="weui-cell__bd">
              <p>预约时间</p>
            </div>
            <div class="weui-cell__ft" id="art_look_time_d"></div>
          </div>
          
          <div class="weui-cell">
            <div class="weui-cell__bd">
              <p>确认时间</p>
            </div>
            <div class="weui-cell__ft" id="art_confirm_time_d"></div>
          </div>
        
      </div>
        </article>
        <a href="javascript:;" class="weui-btn weui-btn_primary close-popup">关闭</a>
      </div>
    </div>
<script src="static/lib/jquery-2.1.4.js"></script>
<script src="static/lib/fastclick.js"></script>
<script>
  $(function() {
    FastClick.attach(document.body);
  });
</script>
<script>
$(function(){

    $(document).on("click", ".item_baobei", function() {
        var _obj=$(this);
        var _action=[];


        _action.push({
            text: "联系报备人",
            className: "color-primary",
            onClick: function() {
          	  $.alert("<a href='tel://"+_obj.attr("phone")+"'>"+_obj.attr("phone")+"</a>", "报备人电话");
            }
          });
        if(_obj.attr("status")=="待确认"){
            _action.push({
                text: "确认报备",
                className: "color-primary",
                onClick: function() {
               	  location.href="<?=Bfw::ACLINK("Baobei","Confirm","id=")?>"+_obj.attr("nowid");
                }
              });

        }
        $.actions({
          title: "选择操作",
          onClose: function() {
            console.log("close");
          },
          actions:_action
        });
      });
});
</script>
<script src="static/js/jquery-weui.js"></script>
<script src="static/js/jquery.tmpl.min.js"></script>
 <script>
      var loading = false;
      var nowid="";
      var page=1;
      var markup ='<a href="javascript:void(0);" nowid="${id}" status="${status}" phone="${reg_phone}" class="weui-media-box weui-media-box_appmsg open-popup item_baobei" ><div class="weui-media-box__hd"><img class="weui-media-box__thumb" src="/static/images/avatar${cus_sex}.png" alt=""></div><div class="weui-media-box__bd"><h4 class="weui-media-box__title">${reg_name}<span class="weui-media-box__title-after">${status}</span></h4><p class="weui-media-box__desc">已预约 <span style="color:green;">${look_time_str}</span> 来 <span style="color:red;">${housename}</span> 看房</p></div></a>';
      $.template( "baobeilist_temp", markup );
      $(document.body).infinite().on("infinite", function() {
        if(loading) return;
        loading = true;
        setTimeout(function() {

        	$.ajax({
                url: "<?=Bfw::ACLINK("Baobei","ListDataAdm","page=")?>"+page+"&keyword="+encodeURI("<?=Bfw::GPC("keyword")?>"),
                type: 'POST',   
                dataType:'json',  
                data:"{}",                                   
                success: function(data){
                        if(data.err){
                       	 	$.alert(data.data);
                        }else{
                            if(data.data.length==0){
                                $("#loadmore__tips").html("没有更多了");
                                $("#weui-loading_icon").remove();
                            }else{
                            	$.tmpl('baobeilist_temp',data.data).appendTo("#list_cont");
                            	page++;
                            	loading = false
                            }	
                       }
               },
                error: function(xhr, type,errorThrown){
                	loading = false
                    $.alert('Ajax error!'+type+errorThrown);
                }
            });

        }, 1000);

      });
    </script>

</body>
</html>
