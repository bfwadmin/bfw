<?php
use Lib\Bfw;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>CONTMEMO列表页</title>
</head>
<body>
	<div class="container">
         
        <div id="main">
          
			<div class="mainContent">
<form method="post" name="form1" action="<?=Bfw::ACLINK("CONTNAME","DelData")?>">
				<table >
					<thead>
						<tr>
					    	<th ><input type="checkbox" name="selectall" /></th>
							  <temp>
				
				          <td>MEMO</td>
              
				            </temp>										
							
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
                    <?php																				
					if (is_array ( $itemdata)) {
						foreach ( $itemdata as $item ) {
					?>
                        <tr>
                        <td><input type="checkbox" name="check[]"  value="<?=$item['id']?>" /></td>
				           <temp>
				
				          <td><?=$item['FIELDNAME']?></td>
              
				            </temp>
                            <td>
							  <a href="<?=Bfw::ACLINK("CONTNAME","AddData")?>"> 增加</a>
							  <a href="<?=Bfw::ACLINK("CONTNAME","DelData","id=".$item['id'])?>"> 删除</a>
							  <a href="<?=Bfw::ACLINK("CONTNAME","EditData","id=".$item['id'])?>">修改</a>
							</td>
						</tr>
                        <?php
							}
						}
						?>						
                       
					</tbody>
				</table>	
						
				<div class="add" >
					<span class="a right"><a href="<?=ACLINK("CONTNAME","AddData")?>"> 增加</a>  </span>
                </div>
              	</form>
			 <?=Bfw::include_file("Pager","Common")?>
			</div>
		</div>
	</div>
    </div>
</body>
</html>


