<?php
/* Smarty version 3.1.31, created on 2017-08-05 23:27:41
  from "D:\xampp\htdocs\motoshub\ow_system_plugins\base\views\components\menu.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5986b6dd95d2f6_31958047',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '87a850d6602b98804c53aec8d5f24dbb04aec53c' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_system_plugins\\base\\views\\components\\menu.html',
      1 => 1463982332,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5986b6dd95d2f6_31958047 (Smarty_Internal_Template $_smarty_tpl) {
?>
<ul class="<?php echo $_smarty_tpl->tpl_vars['class']->value;?>
 clearfix">
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['data']->value, 'item');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
?><li class="<?php echo $_smarty_tpl->tpl_vars['item']->value['class'];
if (!empty($_smarty_tpl->tpl_vars['item']->value['active'])) {?> active<?php }?>"><a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['url'];?>
"<?php if ($_smarty_tpl->tpl_vars['item']->value['new_window']) {?> target="_blank"<?php }?>><span><?php echo $_smarty_tpl->tpl_vars['item']->value['label'];?>
</span></a></li><?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

</ul><?php }
}
