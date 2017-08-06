<?php
/* Smarty version 3.1.31, created on 2017-08-06 02:53:32
  from "D:\xampp\htdocs\motoshub\ow_system_plugins\base\views\components\widget_menu.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5986e71cd6b8c4_57870604',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c4770a4a395eead28016dd76285bce1d4053f3ec' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_system_plugins\\base\\views\\components\\widget_menu.html',
      1 => 1463982332,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5986e71cd6b8c4_57870604 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="clearfix">
	<div class="ow_box_menu">
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['items']->value, 'tab');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['tab']->value) {
?>
			<a href="javascript://" id="<?php echo $_smarty_tpl->tpl_vars['tab']->value['id'];?>
"<?php if (isset($_smarty_tpl->tpl_vars['tab']->value['active']) && $_smarty_tpl->tpl_vars['tab']->value['active']) {?> class="active"<?php }?>><span><?php echo $_smarty_tpl->tpl_vars['tab']->value['label'];?>
</span></a>
		<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

	</div>
</div><?php }
}
