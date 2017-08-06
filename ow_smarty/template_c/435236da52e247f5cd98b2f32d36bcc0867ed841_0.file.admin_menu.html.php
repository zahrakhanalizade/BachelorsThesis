<?php
/* Smarty version 3.1.31, created on 2017-08-06 01:04:54
  from "D:\xampp\htdocs\motoshub\ow_system_plugins\admin\views\components\admin_menu.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5986cda6dad444_63512676',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '435236da52e247f5cd98b2f32d36bcc0867ed841' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_system_plugins\\admin\\views\\components\\admin_menu.html',
      1 => 1463982332,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5986cda6dad444_63512676 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_text')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.text.php';
?>
<ul class="<?php if ($_smarty_tpl->tpl_vars['subMenuClass']->value) {
echo $_smarty_tpl->tpl_vars['subMenuClass']->value;
} else { ?>ow_admin_submenu_hover<?php }?>">
    <li class="ow_admin_submenu_title"><?php echo smarty_function_text(array('key'=>"admin+sidebar_".((string)$_smarty_tpl->tpl_vars['category']->value)),$_smarty_tpl);?>
</li>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['data']->value, 'item');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
?>
        <li <?php if ($_smarty_tpl->tpl_vars['item']->value['active']) {?>class="active"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['url'];?>
" <?php if (!empty($_smarty_tpl->tpl_vars['item']->value['new_window'])) {?> target="_blank"<?php }?>><?php echo $_smarty_tpl->tpl_vars['item']->value['label'];?>
</a></li>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

</ul><?php }
}
