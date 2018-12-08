<div class="pagin">
<div class="message">
共<i class="blue"><?=$widgetdata['totalsum']?></i>条记录，当前显示第<i class="blue">&nbsp;<?=$widgetdata['currentpage']?>&nbsp;</i>页
</div>
<ul class="paginList">
<?php foreach ($widgetdata['pagedata'] as $_item){?>
     <?php if($_item['urltype']=="prev"){?>
     <li class="paginItem"><a href="<?=$_item['url']?>" ><span class="pagepre"></span></a></li>
     <?php }elseif ($_item['urltype']=="page"){?>
     <li class="paginItem <?=$_item['current']?"current":""?>"><a href="<?=$_item['url']?>"><?=$_item['pagenum']?></a></li>
     <?php }elseif ($_item['urltype']=="next"){?>
     <li class="paginItem"><a href="<?=$_item['url']?>"><span class="pagenxt"></span></a></li>
     <?php }?>
<?php }?>
</ul>
</div>