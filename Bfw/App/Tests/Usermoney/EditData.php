<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>修改</title>
</head>
<body>
	<div class="container">
      
        <div id="main">
                
         
			<div class="mainContent">
				<div class="formView">
				<form method="post">
				<table>
				
				<tr>
				<td>money:</td>
                <td>
                <input name="money" type="text" value="<?=htmlspecialchars($itemdata['money'])?>" />
                </td>
                </tr>
				
						<tr>
							<td colspan="2" class="alignCenter buttonWrapper">
							<input type="submit"  value="提交" class="btn submit" /></td>
						</tr>
					</table>
					</form>
				</div>
			</div>
		</div>
                    
    </div>
</body>
</html>
