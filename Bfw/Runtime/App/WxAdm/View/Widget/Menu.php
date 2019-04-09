<?php 
use Lib\Bfw;
use Lib\Util\HtmlUtil;
?>
    <div class="tpl-left-nav tpl-left-nav-hover">
            <div class="tpl-left-nav-title">
                系统管理后台
            </div>
            <div class="tpl-left-nav-list">
                <ul class="tpl-left-nav-menu">
                 
                 <?php 
                 foreach ($data as $item){
                     if($item['pid']==0&&$item['ismenu']==1){
                 ?>
                 
                    <li class="tpl-left-nav-item">
                        <a href="#" class="nav-link">
                            <i class="am-icon-home"></i>
                            <span><?=$item['powername']?></span>
                        </a>
                 <?php 
                 foreach ($data as $subitem){
                     if($subitem['pid']==$item['id']&&$subitem['ismenu']==1){
                 ?>             
                           <ul class="tpl-left-nav-sub-menu"  style="display: block;">
                            <li>
                              

                                <a href="<?=Bfw::ACLINK($subitem['controlname'],$subitem['actionname'],null,$subitem['domianname'])?>" class="<?=CONTROL_VALUE."_".ACTION_VALUE==$subitem['controlname']."_".$subitem['actionname']?'active':''?>">
                                    <i class="am-icon-angle-right"></i>
                                    <span><?=$subitem['powername']?></span>
                                    <!--  <i class="tpl-left-nav-content tpl-badge-success">
               18
             </i>--> 
              </a>

               <?php

                     }
                 }
                 ?>
                            </li>
                        </ul>
                    </li>
                 <?php

                     }
                 }
                 ?>
                </ul>
            </div>
        </div>