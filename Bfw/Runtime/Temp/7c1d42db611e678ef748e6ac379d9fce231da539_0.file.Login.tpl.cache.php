<?php
/* Smarty version 3.1.29, created on 2016-05-05 17:18:23
  from "F:\www\boframework\app\View\Cms\Member\Login.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => true,
  'version' => '3.1.29',
  'unifunc' => 'content_572b0fdf35d2b8_32160628',
  'file_dependency' => 
  array (
    '7c1d42db611e678ef748e6ac379d9fce231da539' => 
    array (
      0 => 'F:\\www\\boframework\\app\\View\\Cms\\Member\\Login.tpl',
      1 => 1456407203,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../Common/header.tpl' => 1,
    'file:../Common/footer.tpl' => 1,
  ),
),false)) {
function content_572b0fdf35d2b8_32160628 ($_smarty_tpl) {
if (!is_callable('smarty_modifier_capitalize')) require_once 'F:\\www\\boframework\\app\\Lib\\Smarty\\plugins\\modifier.capitalize.php';
if (!is_callable('smarty_modifier_date_format')) require_once 'F:\\www\\boframework\\app\\Lib\\Smarty\\plugins\\modifier.date_format.php';
if (!is_callable('smarty_function_html_select_date')) require_once 'F:\\www\\boframework\\app\\Lib\\Smarty\\plugins\\function.html_select_date.php';
if (!is_callable('smarty_function_html_select_time')) require_once 'F:\\www\\boframework\\app\\Lib\\Smarty\\plugins\\function.html_select_time.php';
echo '/*%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/<?php $_smarty = $_smarty_tpl->smarty; if (!is_callable(\'smarty_function_html_options\')) require_once \'F:\\\\www\\\\boframework\\\\app\\\\Lib\\\\Smarty\\\\plugins\\\\function.html_options.php\';
?>/*/%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/';
$_smarty_tpl->compiled->nocache_hash = '18069572b0fdf18c4d6_19094786';
$_smarty_tpl->smarty->ext->configLoad->_loadConfigFile($_smarty_tpl, "test.conf", "setup", 0);
?>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:../Common/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array('title'=>'foo'), 0, false);
?>


<PRE>
<?php echo ACLINKForS(array('c'=>'title','a'=>'sdd','p'=>"d"),$_smarty_tpl);
echo '/*%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/<?php echo aclink($_smarty_tpl->tpl_vars[\'Name\']->value,\'1\',"2");?>
/*/%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/';
echo '/*%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/<?php echo $_smarty_tpl->tpl_vars[\'Named\']->value[\'ss\'];?>
/*/%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/';?>

<?php echo '/*%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/<?php if ($_smarty_tpl->tpl_vars[\'Name\']->value == 1) {?>/*/%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/';?>

123
<?php echo '/*%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/<?php } else { ?>/*/%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/';?>

321
<?php echo '/*%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/<?php }?>/*/%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/';?>


    <?php if ($_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'bold')) {?><b><?php }?>
        
        Title: <?php echo smarty_modifier_capitalize($_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'title'));?>

        <?php if ($_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'bold')) {?></b><?php }?>

    The 111122current date and time is <?php echo smarty_modifier_date_format(time(),"%Y-%m-%d %H:%M:%S");?>


    The value of global assigned variable $SCRIPT_NAME is <?php echo $_smarty_tpl->tpl_vars['SCRIPT_NAME']->value;?>


    Example of accessing server environment variable SERVER_NAME: <?php echo $_SERVER[SERVER_NAME];?>


    The value of {$Name} is <b><?php echo '/*%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/<?php echo $_smarty_tpl->tpl_vars[\'Name\']->value;?>
/*/%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/';?>
</b>

variable modifier example of {$Name|upper}

<b><?php echo '/*%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/<?php echo mb_strtoupper($_smarty_tpl->tpl_vars[\'Name\']->value, \'UTF-8\');?>
/*/%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/';?>
</b>


An example of a section loop:

    <?php echo '/*%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/<?php
$__section_outer_0_saved = isset($_smarty_tpl->tpl_vars[\'__smarty_section_outer\']) ? $_smarty_tpl->tpl_vars[\'__smarty_section_outer\'] : false;
$__section_outer_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars[\'FirstName\']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_outer_0_total = $__section_outer_0_loop;
$_smarty_tpl->tpl_vars[\'__smarty_section_outer\'] = new Smarty_Variable(array());
if ($__section_outer_0_total != 0) {
for ($__section_outer_0_iteration = 1, $_smarty_tpl->tpl_vars[\'__smarty_section_outer\']->value[\'index\'] = 0; $__section_outer_0_iteration <= $__section_outer_0_total; $__section_outer_0_iteration++, $_smarty_tpl->tpl_vars[\'__smarty_section_outer\']->value[\'index\']++){
$_smarty_tpl->tpl_vars[\'__smarty_section_outer\']->value[\'rownum\'] = $__section_outer_0_iteration;
?>/*/%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/';?>

        <?php echo '/*%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/<?php if ((1 & (isset($_smarty_tpl->tpl_vars[\'__smarty_section_outer\']->value[\'index\']) ? $_smarty_tpl->tpl_vars[\'__smarty_section_outer\']->value[\'index\'] : null) / 2)) {?>/*/%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/';?>

            <?php echo '/*%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/<?php echo (isset($_smarty_tpl->tpl_vars[\'__smarty_section_outer\']->value[\'rownum\']) ? $_smarty_tpl->tpl_vars[\'__smarty_section_outer\']->value[\'rownum\'] : null);?>
/*/%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/';?>
 . <?php echo '/*%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/<?php echo $_smarty_tpl->tpl_vars[\'FirstName\']->value[(isset($_smarty_tpl->tpl_vars[\'__smarty_section_outer\']->value[\'index\']) ? $_smarty_tpl->tpl_vars[\'__smarty_section_outer\']->value[\'index\'] : null)];?>
/*/%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/';?>
 <?php echo '/*%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/<?php echo $_smarty_tpl->tpl_vars[\'LastName\']->value[(isset($_smarty_tpl->tpl_vars[\'__smarty_section_outer\']->value[\'index\']) ? $_smarty_tpl->tpl_vars[\'__smarty_section_outer\']->value[\'index\'] : null)];?>
/*/%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/';?>

        <?php echo '/*%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/<?php } else { ?>/*/%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/';?>

            <?php echo '/*%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/<?php echo (isset($_smarty_tpl->tpl_vars[\'__smarty_section_outer\']->value[\'rownum\']) ? $_smarty_tpl->tpl_vars[\'__smarty_section_outer\']->value[\'rownum\'] : null);?>
/*/%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/';?>
 * <?php echo '/*%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/<?php echo $_smarty_tpl->tpl_vars[\'FirstName\']->value[(isset($_smarty_tpl->tpl_vars[\'__smarty_section_outer\']->value[\'index\']) ? $_smarty_tpl->tpl_vars[\'__smarty_section_outer\']->value[\'index\'] : null)];?>
/*/%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/';?>
 <?php echo '/*%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/<?php echo $_smarty_tpl->tpl_vars[\'LastName\']->value[(isset($_smarty_tpl->tpl_vars[\'__smarty_section_outer\']->value[\'index\']) ? $_smarty_tpl->tpl_vars[\'__smarty_section_outer\']->value[\'index\'] : null)];?>
/*/%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/';?>

        <?php echo '/*%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/<?php }?>/*/%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/';?>

        <?php echo '/*%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/<?php }} else {
 ?>/*/%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/';?>

        none
    <?php echo '/*%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/<?php
}
if ($__section_outer_0_saved) {
$_smarty_tpl->tpl_vars[\'__smarty_section_outer\'] = $__section_outer_0_saved;
}
?>/*/%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/';?>


    An example of section looped key values:

    <?php echo '/*%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/<?php
$__section_sec1_1_saved = isset($_smarty_tpl->tpl_vars[\'__smarty_section_sec1\']) ? $_smarty_tpl->tpl_vars[\'__smarty_section_sec1\'] : false;
$__section_sec1_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars[\'contacts\']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_sec1_1_total = $__section_sec1_1_loop;
$_smarty_tpl->tpl_vars[\'__smarty_section_sec1\'] = new Smarty_Variable(array());
if ($__section_sec1_1_total != 0) {
for ($__section_sec1_1_iteration = 1, $_smarty_tpl->tpl_vars[\'__smarty_section_sec1\']->value[\'index\'] = 0; $__section_sec1_1_iteration <= $__section_sec1_1_total; $__section_sec1_1_iteration++, $_smarty_tpl->tpl_vars[\'__smarty_section_sec1\']->value[\'index\']++){
?>/*/%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/';?>

        phone: <?php echo '/*%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/<?php echo $_smarty_tpl->tpl_vars[\'contacts\']->value[(isset($_smarty_tpl->tpl_vars[\'__smarty_section_sec1\']->value[\'index\']) ? $_smarty_tpl->tpl_vars[\'__smarty_section_sec1\']->value[\'index\'] : null)][\'phone\'];?>
/*/%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/';?>

        <br>

            fax: <?php echo '/*%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/<?php echo $_smarty_tpl->tpl_vars[\'contacts\']->value[(isset($_smarty_tpl->tpl_vars[\'__smarty_section_sec1\']->value[\'index\']) ? $_smarty_tpl->tpl_vars[\'__smarty_section_sec1\']->value[\'index\'] : null)][\'fax\'];?>
/*/%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/';?>

        <br>

            cell: <?php echo '/*%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/<?php echo $_smarty_tpl->tpl_vars[\'contacts\']->value[(isset($_smarty_tpl->tpl_vars[\'__smarty_section_sec1\']->value[\'index\']) ? $_smarty_tpl->tpl_vars[\'__smarty_section_sec1\']->value[\'index\'] : null)][\'cell\'];?>
/*/%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/';?>

        <br>
    <?php echo '/*%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/<?php
}
}
if ($__section_sec1_1_saved) {
$_smarty_tpl->tpl_vars[\'__smarty_section_sec1\'] = $__section_sec1_1_saved;
}
?>/*/%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/';?>

    <p>

        testing strip tags
        <table border=0> <tr> <td> <A HREF="<?php echo $_smarty_tpl->tpl_vars['SCRIPT_NAME']->value;?>
"> <font color="red">This is a test </font> </A> </td> </tr> </table>

</PRE>

This is an example of the html_select_date function:

<form>
    <?php echo smarty_function_html_select_date(array('start_year'=>1998,'end_year'=>2010),$_smarty_tpl);?>

</form>

This is an example of the html_select_time function:

<form>
    <?php echo smarty_function_html_select_time(array('use_24_hours'=>false),$_smarty_tpl);?>

</form>

This is an example of the html_options function:

<form>
    <select name=states>
        <?php echo '/*%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/<?php echo smarty_function_html_options(array(\'values\'=>$_smarty_tpl->tpl_vars[\'option_values\']->value,\'selected\'=>$_smarty_tpl->tpl_vars[\'option_selected\']->value,\'output\'=>$_smarty_tpl->tpl_vars[\'option_output\']->value),$_smarty_tpl);?>
/*/%%SmartyNocache:18069572b0fdf18c4d6_19094786%%*/';?>

    </select>
</form>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:../Common/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php }
}
