<?php
/* Smarty version 3.1.31, created on 2017-08-05 23:27:41
  from "D:\xampp\htdocs\motoshub\ow_system_plugins\base\views\components\console_dropdown_menu.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5986b6ddb1a860_09216203',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4803dad9e213544f0b35817a3664c0ed78be063a' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_system_plugins\\base\\views\\components\\console_dropdown_menu.html',
      1 => 1463982332,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5986b6ddb1a860_09216203 (Smarty_Internal_Template $_smarty_tpl) {
?>
<ul class="ow_console_dropdown">
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['items']->value, 'sitems', false, 'section', 'cddm', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['section']->value => $_smarty_tpl->tpl_vars['sitems']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_cddm']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_cddm']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_cddm']->value['iteration'] == $_smarty_tpl->tpl_vars['__smarty_foreach_cddm']->value['total'];
?>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['sitems']->value, 'item');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
?>
            <li class="<?php if (!empty($_smarty_tpl->tpl_vars['item']->value['class'])) {
echo $_smarty_tpl->tpl_vars['item']->value['class'];
}?> ow_dropdown_menu_item ow_cursor_pointer" >
                <div class="ow_console_dropdown_cont">
                    <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['label'];?>
</a>
                </div>
            </li>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>


        <?php if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_cddm']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_cddm']->value['last'] : null)) {?>
            <li><div class="ow_console_divider"></div></li>
        <?php }?>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

</ul><?php }
}
