<?php
/* Smarty version 3.1.31, created on 2017-08-08 08:01:01
  from "D:\xampp\htdocs\motoshub\ow_system_plugins\base\decorators\box.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5989d22d691ee0_39186774',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a11d5f319c3afa1dfa7f685abed3c3ca501d33aa' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_system_plugins\\base\\decorators\\box.html',
      1 => 1493557652,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5989d22d691ee0_39186774 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_decorator')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.decorator.php';
if ($_smarty_tpl->tpl_vars['data']->value['capEnabled']) {?>
<div class="ow_box_cap<?php echo $_smarty_tpl->tpl_vars['data']->value['capAddClass'];?>
">
	<div class="ow_box_cap_right">
		<div class="ow_box_cap_body">
			<h3 class="<?php echo $_smarty_tpl->tpl_vars['data']->value['iconClass'];?>
"><?php echo $_smarty_tpl->tpl_vars['data']->value['label'];?>
</h3><?php echo $_smarty_tpl->tpl_vars['data']->value['capContent'];?>

		</div>
	</div>
</div>
	<?php if ($_smarty_tpl->tpl_vars['data']->value['capAddClass'] == '_empty' || $_smarty_tpl->tpl_vars['data']->value['capAddClass'] == '_empty ow_dnd_configurable_component clearfix' || isset($_smarty_tpl->tpl_vars['data']->value['noCollapsible'])) {?>
	<?php } else { ?>
		<span class="page_collapsible collapse-open" style="display: none;" ></span>
	<?php }
}?>
<div class="ow_box<?php echo $_smarty_tpl->tpl_vars['data']->value['addClass'];?>
 ow_break_word container"<?php if (!empty($_smarty_tpl->tpl_vars['data']->value['style'])) {?> style="<?php echo $_smarty_tpl->tpl_vars['data']->value['style'];?>
"<?php }?>>
<?php echo $_smarty_tpl->tpl_vars['data']->value['content'];?>

<?php if (!empty($_smarty_tpl->tpl_vars['data']->value['toolbar'])) {?>
    <div class="ow_box_toolbar_cont clearfix">
	<?php echo smarty_function_decorator(array('name'=>'box_toolbar','itemList'=>$_smarty_tpl->tpl_vars['data']->value['toolbar']),$_smarty_tpl);?>

    </div>
<?php }
if (empty($_smarty_tpl->tpl_vars['data']->value['type'])) {?>
	<div class="ow_box_bottom_left"></div>
	<div class="ow_box_bottom_right"></div>
	<div class="ow_box_bottom_body"></div>
	<div class="ow_box_bottom_shadow"></div>
<?php }?>
</div><?php }
}
