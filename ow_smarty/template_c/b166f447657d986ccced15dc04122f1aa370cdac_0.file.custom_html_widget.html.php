<?php
/* Smarty version 3.1.31, created on 2017-08-06 02:53:32
  from "D:\xampp\htdocs\motoshub\ow_system_plugins\base\views\components\custom_html_widget.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5986e71ccb0096_64561980',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b166f447657d986ccced15dc04122f1aa370cdac' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_system_plugins\\base\\views\\components\\custom_html_widget.html',
      1 => 1463982332,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5986e71ccb0096_64561980 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_text')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.text.php';
?>
<div class="ow_custom_html_widget">
	<?php if ($_smarty_tpl->tpl_vars['content']->value) {?>
		<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

	<?php } else { ?>
            <div class="ow_nocontent">
                <?php echo smarty_function_text(array('key'=>"base+custom_html_widget_no_content"),$_smarty_tpl);?>

            </div>
	<?php }?>
</div><?php }
}
