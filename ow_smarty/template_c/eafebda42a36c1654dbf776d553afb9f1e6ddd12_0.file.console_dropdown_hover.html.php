<?php
/* Smarty version 3.1.31, created on 2017-08-05 23:27:41
  from "D:\xampp\htdocs\motoshub\ow_system_plugins\base\views\components\console_dropdown_hover.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5986b6ddb263e9_47984031',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'eafebda42a36c1654dbf776d553afb9f1e6ddd12' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_system_plugins\\base\\views\\components\\console_dropdown_hover.html',
      1 => 1473054010,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5986b6ddb263e9_47984031 (Smarty_Internal_Template $_smarty_tpl) {
?>
<a href="javascript://" <?php if (!empty($_smarty_tpl->tpl_vars['iconSrc']->value)) {?> style="border-radius: 5px;max-width: 28px; max-height: 28px;background-position: center center !important;background-size: contain;background-image: url('<?php echo $_smarty_tpl->tpl_vars['iconSrc']->value;?>
')" <?php }?> class="ow_console_item_link"><?php echo $_smarty_tpl->tpl_vars['label']->value;?>
</a>
<span class="ow_console_more"></span><?php }
}
