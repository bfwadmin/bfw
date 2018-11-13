<?php 

class BoTemplate  
{  
   
    var $vars = array();  
      
    function parse($tpl)  
    {  
        // load template file //  
        $fp   = @fopen($tpl, 'r');  
        $text = fread($fp, filesize($tpl));  
        fclose($fp);  
        // repalce template tag to PHP tag //  
        $text        = str_replace('{/if}', '<?php } ?>', $text);  
        $text        = str_replace('{/loop}', '<?php } ?>', $text);  
        $text        = str_replace('{foreachelse}', '<?php } else {?>', $text);  
        $text        = str_replace('{/foreach}', '<?php } ?>', $text);  
        $text        = str_replace('{else}', '<?php } else {?>', $text);  
        $text        = str_replace('{loopelse}', '<?php } else {?>', $text);  
        // template pattern tags //  
        $pattern     = array(  
            '/\$(\w*[a-zA-Z0-9_])/',  
            '/\$this\-\>vars\[\'(\w*[a-zA-Z0-9_])\'\]+\.(\w*[a-zA-Z0-9])/',  
            '/\{include file=(\"|\'|)(\w*[a-zA-Z0-9_\.][a-zA-Z]\w*)(\"|\'|)\}/',  
            '/\{\$this\-\>vars(\[\'(\w*[a-zA-Z0-9_])\'\])(\[\'(\w*[a-zA-Z0-9_])\'\])?\}/',  
            '/\{if (.*?)\}/',  
            '/\{elseif (.*?)\}/',  
            '/\{loop \$(.*) as (\w*[a-zA-Z0-9_])\}/',  
            '/\{foreach \$(.*) (\w*[a-zA-Z0-9_])\=\>(\w*[a-zA-Z0-9_])\}/' 
        );  
        // replacement PHP tags //  
        $replacement = array(  
            '$this->vars[\'\1\']',  
            '$this->vars[\'\1\'][\'\2\']',  
            '<?php $this->display(\'\2\')?>',  
            '<?php echo \$this->vars\1\3?>',  
            '<?php if(\1) {?>',  
            '<?php } elseif(\1) {?>',  
            '<?php if (count((array)\$\1)) foreach((array)\$\1 as \$this->vars[\'\2\']) {?>',  
            '<?php if (count((array)\$\1)) foreach((array)\$\1 as \$this->vars[\'\2\']=>$this->vars[\'\3\']) {?>' 
        );  
        // repalce template tags to PHP tags //  
        $text = preg_replace($pattern, $replacement, $text);  
          
        // create compile file //  
        $compliefile = TEMPLATE_COMPILE_DIR ."/". md5($tpl) . '.php';  
       /* if ($fp = @fopen($compliefile, 'w')) {  
            fputs($fp, $text);  
            fclose($fp);  
        } */ 
    }  
      
    /*  
     * assigns values to template variables  
     * @param array|string $k the template variable name(s)  
     * @param mixed $v the value to assign  
     */ 
    function assign($k, $v = null)  
    {  
        $this->vars[$k] = $v;  
    }  
      
    function actionlink($c,$a,$p){
    	return APPSELF . "?" . CONTROL_NAME . '=' . $c . '&' . ACTION_NAME . '=' . $a . '&' . DOMIAN_NAME . '=' . DOMIAN_VALUE .  '&' . $p;
    }
      
    /*  
     * executes & displays the template results  
     * @param string $tpl (template file)  
     */ 
    function display($tpl)  
    {  
        $tplfile = TEMPLATE_DIR ."/".$tpl.".html";  
        if (!file_exists($tplfile)) {  
            throw new Exception ( VIEW_NOT_FOUND . $tplfile );
        }  
        $compliefile = TEMPLATE_COMPILE_DIR."/" . md5($tplfile) . '.php';  
        if (!file_exists($compliefile) || filemtime($tplfile) > filemtime($compliefile)) {  
            $this->parse($tplfile);  
        }  
        include_once($compliefile);  
    }  
}
?>