<?php
use Lib\Bfw;
use Lib\Util\HtmlUtil;
use Lib\Util\StringUtil;
?>
<?=Bfw::Widget("Header")?>

   <div class="tpl-content-wrapper">
            <div class="tpl-content-page-title">
               密码修改
            </div>
            <ol class="am-breadcrumb">
                <li><a href="#" class="am-icon-home">首页</a></li>
                <li class="am-active">密码修改</li>
            </ol>
            <div class="tpl-portlet-components">
                <div class="portlet-title">
                    <div class="caption font-green bold">
                        <span class="am-icon-code"></span> 修改密码
                    </div>
                    <div class="tpl-portlet-input tpl-fz-ml">
                        <div class="portlet-input input-small input-inline">
                            <div class="input-icon right">
                                <i class="am-icon-search"></i>
                                <input type="text" class="form-control form-control-solid" placeholder="搜索..."> </div>
                        </div>
                    </div>


                </div>
                <div class="tpl-block ">

                    <div class="am-g tpl-amazeui-form">


                        <div class="am-u-sm-12 am-u-md-9">
                            <form id="myform" class="am-form am-form-horizontal" action="" method="post">
                                <div class="am-form-group">
                                    <label for="user-name" class="am-u-sm-3 am-form-label">原密码 / Old</label>
                                    <div class="am-u-sm-9">
                                        <input type="password" id="user-name" name="oldpwd" placeholder="原密码">
                                     
                                    </div>
                                </div>
                                      <div class="am-form-group">
                                    <label for="user-name" class="am-u-sm-3 am-form-label">新密码 / New</label>
                                    <div class="am-u-sm-9">
                                        <input type="password" id="user-name" name="newpwd" placeholder="新密码">
                                     
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
<?=Bfw::Widget("Footer")?>
