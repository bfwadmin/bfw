<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title>1111</title> 
</head> 
<body> 
<DIV><?php echo $this->vars['title']?> - <?php echo $this->vars['Name']?></DIV> 
<?php if (count((array)$this->vars['contact'] )) foreach((array)$this->vars['contact']  as $this->vars['key']=>$this->vars['val']) {?>  
    <?php echo $this->vars['key']?>: <?php echo $this->vars['val']?><br> 
<?php } ?>  
</body> 
</html> 