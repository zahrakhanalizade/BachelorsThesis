<?php
/* Smarty version 3.1.31, created on 2017-08-08 08:00:10
  from "D:\xampp\htdocs\motoshub\ow_system_plugins\base\views\components\switch_language.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5989d1fa5136c2_08589342',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e5143e8b357b66f995988b2388b1d510cf11a7af' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_system_plugins\\base\\views\\components\\switch_language.html',
      1 => 1463982332,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5989d1fa5136c2_08589342 (Smarty_Internal_Template $_smarty_tpl) {
?>
<ul class="ow_console_lang">
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['languages']->value, 'language');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['language']->value) {
?>
        <li class="ow_console_lang_item" onclick="location.href='<?php echo $_smarty_tpl->tpl_vars['language']->value['url'];?>
';"><span class="<?php echo $_smarty_tpl->tpl_vars['language']->value['class'];?>
"><?php echo $_smarty_tpl->tpl_vars['language']->value['label'];?>
</span></li>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

</ul><?php }
}
