<?php
use Lib\Bfw;
use Lib\Util\HtmlUtil;
?>
<div class="onespro">
	<ul class="tabs-list" style="width: 1000px;">
		<li class="tabs-option <?=Bfw::IIF(CONTROL_VALUE=='Artwork'&&ACTION_VALUE=='MyList', "selected", "")?>"><?=HtmlUtil::Tag("a", "作品",['href'=>Bfw::ACLINK("Artwork","MyList")])?></li>
		<li class="tabs-option <?=Bfw::IIF(CONTROL_VALUE=='Order'&&ACTION_VALUE=='MyList'&&Bfw::GPC("type")=='seller', "selected", "")?>"> <?=HtmlUtil::Tag("a", "卖出作品",['href'=>Bfw::ACLINK("Order","MyList","type=seller")])?></li>
		<li class="tabs-option <?=Bfw::IIF(CONTROL_VALUE=='Order'&&ACTION_VALUE=='MyList'&&Bfw::GPC("type")==='', "selected", "")?>"><?=HtmlUtil::Tag("a", "买到作品",['href'=>Bfw::ACLINK("Order","MyList")])?></li>
		<li class="tabs-option  <?=Bfw::IIF(CONTROL_VALUE=='Focus'&&ACTION_VALUE=='MyList', "selected", "")?>"><?=HtmlUtil::Tag("a", "关注",['href'=>Bfw::ACLINK("Focus","MyList")])?></li>
		<li class="tabs-option  <?=Bfw::IIF(CONTROL_VALUE=='Focus'&&ACTION_VALUE=='MyFans', "selected", "")?>"><?=HtmlUtil::Tag("a", "粉丝",['href'=>Bfw::ACLINK("Focus","MyFans")])?></li>
		<li class="tabs-option  <?=Bfw::IIF(CONTROL_VALUE=='Favorite'&&ACTION_VALUE=='MyList', "selected", "")?>"> <?=HtmlUtil::Tag("a", "收藏",['href'=>Bfw::ACLINK("Favorite","MyList")])?></li>
		<li class="tabs-option  <?=Bfw::IIF(CONTROL_VALUE=='Wallet'&&ACTION_VALUE=='MyMoney', "selected", "")?>"> <?=HtmlUtil::Tag("a", "钱包",['href'=>Bfw::ACLINK("Wallet","MyMoney")])?></li>
		<li class="tabs-option  <?=Bfw::IIF(CONTROL_VALUE=='Address'&&ACTION_VALUE=='MyList', "selected", "")?>"><?=HtmlUtil::Tag("a", "地址",['href'=>Bfw::ACLINK("Address","MyList")])?></li>
	</ul>
</div>