<?php
/* Smarty version 3.1.31, created on 2017-08-08 08:29:40
  from "D:\xampp\htdocs\motoshub\ow_plugins\video\views\controllers\video_view_list.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5989d8e4114db8_37515059',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '87bdefb857e2ff56e76f3b616af03711fa529cd3' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_plugins\\video\\views\\controllers\\video_view_list.html',
      1 => 1463982332,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5989d8e4114db8_37515059 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_add_content')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.add_content.php';
if (!is_callable('smarty_function_decorator')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.decorator.php';
if (!is_callable('smarty_function_component')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.component.php';
?>
<div class="clearfix"><?php echo smarty_function_add_content(array('key'=>'video.add_content.list.top','listType'=>$_smarty_tpl->tpl_vars['listType']->value),$_smarty_tpl);?>
</div>

<?php if ($_smarty_tpl->tpl_vars['showAddButton']->value) {?>
    <div class="ow_right"><?php echo smarty_function_decorator(array('name'=>'button','class'=>'ow_ic_add','id'=>'btn-add-new-video','langLabel'=>'video+add_new'),$_smarty_tpl);?>
</div>
<?php }?>

<?php echo $_smarty_tpl->tpl_vars['videoMenu']->value;?>


<?php echo smarty_function_add_content(array('key'=>'video.content.list.after_menu','listType'=>$_smarty_tpl->tpl_vars['listType']->value),$_smarty_tpl);?>


<?php echo smarty_function_component(array('class'=>'VIDEO_CMP_VideoList','type'=>$_smarty_tpl->tpl_vars['listType']->value,'count'=>5),$_smarty_tpl);?>


<?php echo smarty_function_add_content(array('key'=>'video.content.list.bottom','listType'=>$_smarty_tpl->tpl_vars['listType']->value),$_smarty_tpl);
}
}
