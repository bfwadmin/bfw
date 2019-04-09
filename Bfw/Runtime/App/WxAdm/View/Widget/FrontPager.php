<?php foreach ($widgetdata['pagedata'] as $_item){?>
     <?php if($_item['urltype']=="prev"){?>
<a class="pp" href="<?=$_item['url']?>"><i class="prev-triangle"></i>上一页</a>
<?php }elseif ($_item['urltype']=="first"){?>
<a class="fp" href="<?=$_item['url']?>"><span><img
		src='<?=STATIC_FILE_PATH?>/statics/images/recommend/lt.jpg'> </span></a>
<?php }elseif ($_item['urltype']=="page"){?>
<a href="<?=$_item['url']?>" class="<?=$_item['current']?"current":""?>"><?=$_item['pagenum']?></a>

<?php }elseif ($_item['urltype']=="last"){?>
<a class="lp" href="<?=$_item['url']?>"><span><img
		src='<?=STATIC_FILE_PATH?>/statics/images/recommend/rt.jpg'> </span></a>
		<?php }elseif ($_item['urltype']=="next"){?>
<a class="np" href="<?=$_item['url']?>">下一页<i class="next-triangle"></i></a>

<?php }?>
<?php }?>
<span> <?=$widgetdata['totalpage']?>页</span>