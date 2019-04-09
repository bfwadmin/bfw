<?php
use Lib\Bfw;
use Lib\Util\HtmlUtil;
use Lib\Util\StringUtil;
?>
<?=Bfw::Widget("Header")?>
<?=Bfw::Widget("Menu")?>
   <div class="tpl-content-wrapper">
            <div class="tpl-content-page-title">
                权限列表
            </div>
            <ol class="am-breadcrumb">
                <li><a href="#" class="am-icon-home">首页</a></li>
                <li class="am-active">列表</li>
            </ol>
            <div class="tpl-portlet-components">
                <div class="portlet-title">
                    <div class="caption font-green bold">
                        <span class="am-icon-code"></span> 列表
                    </div>
                    <div class="tpl-portlet-input tpl-fz-ml">
                        <div class="portlet-input input-small input-inline">
                            <div class="input-icon right">
                                <i class="am-icon-search"></i>
                                <input type="text" class="form-control form-control-solid" placeholder="搜索..."> </div>
                        </div>
                    </div>


                </div>
                <div class="tpl-block">
                    <div class="am-g">
                        <div class="am-u-sm-12 am-u-md-6">
                            <div class="am-btn-toolbar">
                                <div class="am-btn-group am-btn-group-xs">
                                    <button type="button" onclick="location.href='<?=Bfw::ACLINK("Power","AddData")?>';" class="am-btn am-btn-default am-btn-success"><span class="am-icon-plus"></span> 新增</button>
                                  <!--   <button type="button" class="am-btn am-btn-default am-btn-secondary"><span class="am-icon-save"></span> 保存</button>
                                    <button type="button" class="am-btn am-btn-default am-btn-warning"><span class="am-icon-archive"></span> 审核</button>
                                    <button type="button" class="am-btn am-btn-default am-btn-danger"><span class="am-icon-trash-o"></span> 删除</button>
                              -->   </div>
                            </div>
                        </div>
                    <!--  <div class="am-u-sm-12 am-u-md-3">
                            <div class="am-form-group">
                                <select data-am-selected="{btnSize: 'sm'}">
              <option value="option1">所有类别</option>
              <option value="option2">IT业界</option>
              <option value="option3">数码产品</option>
              <option value="option3">笔记本电脑</option>
              <option value="option3">平板电脑</option>
              <option value="option3">只能手机</option>
              <option value="option3">超极本</option>
            </select>
                            </div>
                        </div>-->
                        <div class="am-u-sm-12 am-u-md-3">
                            <div class="am-input-group am-input-group-sm">
                                <input type="text" class="am-form-field">
                                <span class="am-input-group-btn">
            <button class="am-btn  am-btn-default am-btn-success tpl-am-btn-success am-icon-search" type="button"></button>
          </span>
                            </div>
                        </div>
                    </div>
                    <div class="am-g">
                        <div class="am-u-sm-12">
                            <form class="am-form">
                                <table class="am-table am-table-striped am-table-hover table-main">
                                    <thead>
                                        <tr>
                                            <th class="table-check"><input type="checkbox" class="tpl-table-fz-check"></th>
                                            <th class="table-id">ID</th>
                                            <th class="table-title">标题</th>
                                         
                                            <th class="table-author am-hide-sm-only">动作器</th>
                                          <th class="table-author am-hide-sm-only">控制器</th>
                                            <th class="table-author am-hide-sm-only">域</th>
                                             <th class="table-author am-hide-sm-only">上级</th>
                                                <th class="table-author am-hide-sm-only">菜单</th>
                                            <th class="table-set">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                     <?php
    foreach ($itemdata as $item) {
        ?>  
           
				  <tr>
                                            <td><input type="checkbox"></td>
                                            <td><?=$item['id']?></td>
                                            <td><a href="#"><?=$item['powername']?></a></td>
                             
                                            <td class="am-hide-sm-only"><?=$item['controlname']?></td>
                                       
                                        <td class="am-hide-sm-only"><?=$item['actionname']?></td>
                                         <td class="am-hide-sm-only"><?=$item['domianname']?></td>
                                          <td class="am-hide-sm-only"><?=$item['pid']?></td>
                                            <td class="am-hide-sm-only"><?=$item['ismenu']==0?"":"是"?></td>
                                            <td>
                                                <div class="am-btn-toolbar">
                                                    <div class="am-btn-group am-btn-group-xs">
                                                    
                                                    					<a href="<?=Bfw::ACLINK("Power","EditData","id=".$item['id'])?>">编辑</a>
					<a href="<?=Bfw::ACLINK("Power","DelData","id=".$item['id'])?>">删除</a>
                                                        <!--<button class="am-btn am-btn-default am-btn-xs am-text-secondary"><span class="am-icon-pencil-square-o"></span> 编辑</button>
                                                        <button class="am-btn am-btn-default am-btn-xs am-hide-sm-only"><span class="am-icon-copy"></span>申请</button>
                                                          <button class="am-btn am-btn-default am-btn-xs am-hide-sm-only"><span class="am-icon-copy"></span> 已分配</button>
                                                            <button class="am-btn am-btn-default am-btn-xs am-hide-sm-only"><span class="am-icon-copy"></span> 分配牌号</button>
                                                        <button class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only"><span class="am-icon-trash-o"></span> 删除</button>
                                                    -->  </div>
                                                </div>
                                            </td>
                                        </tr>

        
                        <?php
    }
    
    ?>
     
                                      
                                  
                                    </tbody>
                                </table>
                                   <?=Bfw::Widget("Pager",$pagerdata)?>
        
                                <hr>

                            </form>
                        </div>

                    </div>
                </div>
                <div class="tpl-alert"></div>
            </div>

<?=Bfw::Widget("Footer")?>