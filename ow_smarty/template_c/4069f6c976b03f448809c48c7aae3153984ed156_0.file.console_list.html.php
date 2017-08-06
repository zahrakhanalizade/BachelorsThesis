<?php
/* Smarty version 3.1.31, created on 2017-08-05 23:27:41
  from "D:\xampp\htdocs\motoshub\ow_system_plugins\base\views\components\console_list.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5986b6ddb7c2f7_47781924',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4069f6c976b03f448809c48c7aae3153984ed156' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_system_plugins\\base\\views\\components\\console_list.html',
      1 => 1463982332,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5986b6ddb7c2f7_47781924 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_text')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.text.php';
?>
<div class="ow_console_list_wrapper OW_ConsoleListContainer">
    <div class="ow_nocontent OW_ConsoleListNoContent"><?php echo smarty_function_text(array('key'=>'base+no_items'),$_smarty_tpl);?>
</div>
    <ul class="ow_console_list OW_ConsoleList">

    </ul>
    <div class="ow_preloader_content ow_console_list_preloader OW_ConsoleListPreloader" style="visibility: hidden"></div>
</div>

<?php if (!empty($_smarty_tpl->tpl_vars['viewAll']->value)) {?>
    <div class="ow_console_view_all_btn_wrap"><a href="<?php echo $_smarty_tpl->tpl_vars['viewAll']->value['url'];?>
" class="ow_console_view_all_btn"><?php echo $_smarty_tpl->tpl_vars['viewAll']->value['label'];?>
</a></div>
<?php }
}
}
