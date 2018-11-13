
<html>
<head><title>index</title></head>
<body>
<form method="post">
<input type="text" name="username" />
<input type="text" name="password" />
<input type="submit" value="submit" />
<?php
echo time();

?>
<?php if (count((array)$this->vars['contact'] )) foreach((array)$this->vars['contact']  as $this->vars['key']=>$this->vars['val']) {?>  
    <?php echo $this->vars['key']?>: sss<?php echo $this->vars['val']?><br> 
<?php } ?>  
</form>
</body>
</html>