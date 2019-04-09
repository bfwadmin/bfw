<?php
use Lib\Bfw;
use Lib\Util\StringUtil;
?>
<div class="gal_wrap clearfix">
	<div class="maybelike">
		<span class="mbspan"></span>猜你喜欢
	</div>
	<div class="bodyCon08">
		<!--学员-->
		<div class="students">

			<div id="four_flash">
				<div class="flashBg">
					<ul class="mobile">
								<?php
        $i = 0;
        foreach ($itemdata as $item) {
            $i ++;
            ?>
	
					<li><a href="<?=Bfw::ACLINK("Page","ArtworkDetail",'id='.$item['id'])?>"><img
								src="<?=$item['productimg']?>" width="250" /></a>
							<h3>
								<a href="<?=Bfw::ACLINK("Page","ArtworkDetail",'id='.$item['id'])?>"><?=$item['productname']?></a>
							</h3>
							<p>
								<span class="gay_color f_l"><?=StringUtil::cut_str($item['productname'], 8)?>，<?=$item['artwidth']?>*<?=$item['artheight']?>cm</span><span
									class="f16 f_r red_color">￥<?=$item['saleprice']?></span>
							</p></li>
												
						<?php }?>
											</ul>
				</div>
				<div class="but_left"></div>
				<div class="but_right"></div>
			</div>

		</div>
	</div>
</div>
<script type="text/javascript">
//学员
var _index5=0;
$("#four_flash .but_right").click(function(){
	_index5++;
	var len=$(".flashBg ul.mobile li").length;
	if(_index5+5>len){
		$("#four_flash .flashBg ul.mobile").stop().append($("ul.mobile").html());
	}
	$("#four_flash .flashBg ul.mobile").stop().animate({left:-_index5*275},1000);
	});

	
$("#four_flash .but_left").click(function(){
	if(_index5==0){
		$("ul.mobile").prepend($("ul.mobile").html());
		$("ul.mobile").css("left","-1380px");
		_index5=6
	}
	_index5--;
	$("#four_flash .flashBg ul.mobile").stop().animate({left:-_index5*275},1000);
	});
</script>