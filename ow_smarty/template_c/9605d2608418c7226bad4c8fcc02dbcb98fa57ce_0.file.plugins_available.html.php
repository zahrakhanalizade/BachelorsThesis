<?php
/* Smarty version 3.1.31, created on 2017-08-08 08:01:08
  from "D:\xampp\htdocs\motoshub\ow_system_plugins\admin\views\controllers\plugins_available.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5989d234528327_46131040',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9605d2608418c7226bad4c8fcc02dbcb98fa57ce' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_system_plugins\\admin\\views\\controllers\\plugins_available.html',
      1 => 1463982332,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5989d234528327_46131040 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_block_style')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\block.style.php';
if (!is_callable('smarty_block_block_decorator')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\block.block_decorator.php';
if (!is_callable('smarty_function_text')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.text.php';
$_smarty_tpl->smarty->_cache['_tag_stack'][] = array('style', array());
$_block_repeat=true;
echo smarty_block_style(array(), null, $_smarty_tpl, $_block_repeat);
while ($_block_repeat) {
ob_start();
?>


.ow_active_plugins tr, .ow_inactive_plugins tr{
    border-top:1px solid #ccc;
}
.ow_plugin_controls{
    display:none;
}

<?php $_block_repeat=false;
echo smarty_block_style(array(), ob_get_clean(), $_smarty_tpl, $_block_repeat);
}
array_pop($_smarty_tpl->smarty->_cache['_tag_stack']);?>

<?php $_smarty_tpl->smarty->_cache['_tag_stack'][] = array('block_decorator', array('name'=>'box','type'=>'empty','addClass'=>'ow_stdmargin','iconClass'=>'ow_ic_trash','langLabel'=>'admin+manage_plugins_available_box_cap_label'));
$_block_repeat=true;
echo smarty_block_block_decorator(array('name'=>'box','type'=>'empty','addClass'=>'ow_stdmargin','iconClass'=>'ow_ic_trash','langLabel'=>'admin+manage_plugins_available_box_cap_label'), null, $_smarty_tpl, $_block_repeat);
while ($_block_repeat) {
ob_start();
?>

<?php if (empty($_smarty_tpl->tpl_vars['plugins']->value)) {?>
    <?php echo smarty_function_text(array('key'=>'admin+plugins_manage_no_available_items'),$_smarty_tpl);?>

<?php } else { ?>
   <table class="ow_superwide ow_inactive_plugins" style="margin:0 auto;">
      <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['plugins']->value, 'plugin');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['plugin']->value) {
?>
      <tr class="ow_high2" onmouseover="$('span.ow_plugin_controls', $(this)).css({display:'block'});" onmouseout="$('span.ow_plugin_controls', $(this)).css({display:'none'});">
         <td style="padding: 10px 15px;">
            <b><?php echo $_smarty_tpl->tpl_vars['plugin']->value['title'];?>
</b>
            <div class="ow_small"><?php echo $_smarty_tpl->tpl_vars['plugin']->value['description'];?>
</div>
         </td>
         <td class="ow_small" style="text-align:right;width:180px;vertical-align:middle;">
             <span class="ow_plugin_controls">
                <?php if ($_smarty_tpl->tpl_vars['plugin']->value['inst_url']) {?><a class="ow_lbutton ow_green" href="<?php echo $_smarty_tpl->tpl_vars['plugin']->value['inst_url'];?>
"><?php echo smarty_function_text(array('key'=>'admin+manage_plugins_install_button_label'),$_smarty_tpl);?>
</a><?php }?>
                <?php if ($_smarty_tpl->tpl_vars['plugin']->value['del_url']) {?><a class="ow_lbutton ow_red" href="<?php echo $_smarty_tpl->tpl_vars['plugin']->value['del_url'];?>
" onclick="return confirm('<?php echo smarty_function_text(array('key'=>'admin+manage_plugins_delete_confirm_message','pluginName'=>$_smarty_tpl->tpl_vars['plugin']->value['title']),$_smarty_tpl);?>
');"><?php echo smarty_function_text(array('key'=>'admin+manage_plugins_delete_button_label'),$_smarty_tpl);?>
</a><?php }?>
             </span>
         </td>
      </tr>
      <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

   </table>
<?php }
$_block_repeat=false;
echo smarty_block_block_decorator(array('name'=>'box','type'=>'empty','addClass'=>'ow_stdmargin','iconClass'=>'ow_ic_trash','langLabel'=>'admin+manage_plugins_available_box_cap_label'), ob_get_clean(), $_smarty_tpl, $_block_repeat);
}
array_pop($_smarty_tpl->smarty->_cache['_tag_stack']);
}
}
