<?php 
use Lib\Bfw;
use Lib\Util\HtmlUtil;
?>
<div class="gal_wrap clearfix artist_t rel">
		<div class="f_l the_artist">
			<img width="150" height="150"
				src="<?=$userinfo['userimg']?>"
				alt="aritist" />
		</div>
		<div class="f_l artist_intro">
			<h3><?=$userinfo['nickname']?></h3>
			<p style="display: none;">
				<span class="tags"></span><span class="tags_span"></span><span
					class="tags_span"></span>
			</p>
			<p>
				<i class="icon iconfont">&#xe62c;</i><?=$userinfo['fanscount']?><span class="rig_bor"></span>
				<!-- <i class="icon iconfont">&#xe61a;</i><?=$userinfo['hits']?><span class="rig_bor"></span> -->
				<i class="icon iconfont">&#xe643;</i><?=$userinfo['focuscount']?>
			</p>
		</div>
		
		<a id="user_edi" class="usecenter_edi" href="<?=Bfw::ACLINK("Member","ChangeMyData")?>">账号设置</a>
		
	</div>
