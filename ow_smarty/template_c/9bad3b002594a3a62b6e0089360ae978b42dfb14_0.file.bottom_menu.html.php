<?php
/* Smarty version 3.1.31, created on 2017-08-05 23:27:41
  from "D:\xampp\htdocs\motoshub\ow_system_plugins\base\views\components\bottom_menu.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5986b6dd978885_30780570',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9bad3b002594a3a62b6e0089360ae978b42dfb14' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_system_plugins\\base\\views\\components\\bottom_menu.html',
      1 => 1463982332,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5986b6dd978885_30780570 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="ow_footer_menu">
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['data']->value, 'item', false, NULL, 'bottom_menu', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_bottom_menu']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_bottom_menu']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_bottom_menu']->value['iteration'] == $_smarty_tpl->tpl_vars['__smarty_foreach_bottom_menu']->value['total'];
?>
	<a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['url'];?>
"<?php if ($_smarty_tpl->tpl_vars['item']->value['active']) {?> class="active"<?php }
if ($_smarty_tpl->tpl_vars['item']->value['new_window']) {?> target="_blank"<?php }?>><?php echo $_smarty_tpl->tpl_vars['item']->value['label'];?>
</a><?php if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_bottom_menu']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_bottom_menu']->value['last'] : null)) {?> | <?php }
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

</div><?php }
}
