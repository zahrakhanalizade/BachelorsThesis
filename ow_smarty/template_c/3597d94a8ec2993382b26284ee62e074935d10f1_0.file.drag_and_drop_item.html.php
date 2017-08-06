<?php
/* Smarty version 3.1.31, created on 2017-08-06 02:53:22
  from "D:\xampp\htdocs\motoshub\ow_system_plugins\base\views\components\drag_and_drop_item.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5986e7122514f8_56273462',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3597d94a8ec2993382b26284ee62e074935d10f1' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_system_plugins\\base\\views\\components\\drag_and_drop_item.html',
      1 => 1463982332,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5986e7122514f8_56273462 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_block_block_decorator')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\block.block_decorator.php';
?>
<div class="ow_dnd_widget <?php echo $_smarty_tpl->tpl_vars['box']->value['uniqName'];?>
">

    <?php $_smarty_tpl->smarty->_cache['_tag_stack'][] = array('block_decorator', array('name'=>'box','capEnabled'=>$_smarty_tpl->tpl_vars['box']->value['show_title'],'capContent'=>$_smarty_tpl->tpl_vars['box']->value['capContent'],'capAddClass'=>"ow_dnd_configurable_component clearfix",'label'=>$_smarty_tpl->tpl_vars['box']->value['title'],'iconClass'=>$_smarty_tpl->tpl_vars['box']->value['icon'],'type'=>$_smarty_tpl->tpl_vars['box']->value['type'],'addClass'=>"ow_stdmargin clearfix ".((string)$_smarty_tpl->tpl_vars['box']->value['uniqName']),'toolbar'=>$_smarty_tpl->tpl_vars['box']->value['toolbar']));
$_block_repeat=true;
echo smarty_block_block_decorator(array('name'=>'box','capEnabled'=>$_smarty_tpl->tpl_vars['box']->value['show_title'],'capContent'=>$_smarty_tpl->tpl_vars['box']->value['capContent'],'capAddClass'=>"ow_dnd_configurable_component clearfix",'label'=>$_smarty_tpl->tpl_vars['box']->value['title'],'iconClass'=>$_smarty_tpl->tpl_vars['box']->value['icon'],'type'=>$_smarty_tpl->tpl_vars['box']->value['type'],'addClass'=>"ow_stdmargin clearfix ".((string)$_smarty_tpl->tpl_vars['box']->value['uniqName']),'toolbar'=>$_smarty_tpl->tpl_vars['box']->value['toolbar']), null, $_smarty_tpl, $_block_repeat);
while ($_block_repeat) {
ob_start();
?>


        <?php echo $_smarty_tpl->tpl_vars['content']->value;?>

    <?php $_block_repeat=false;
echo smarty_block_block_decorator(array('name'=>'box','capEnabled'=>$_smarty_tpl->tpl_vars['box']->value['show_title'],'capContent'=>$_smarty_tpl->tpl_vars['box']->value['capContent'],'capAddClass'=>"ow_dnd_configurable_component clearfix",'label'=>$_smarty_tpl->tpl_vars['box']->value['title'],'iconClass'=>$_smarty_tpl->tpl_vars['box']->value['icon'],'type'=>$_smarty_tpl->tpl_vars['box']->value['type'],'addClass'=>"ow_stdmargin clearfix ".((string)$_smarty_tpl->tpl_vars['box']->value['uniqName']),'toolbar'=>$_smarty_tpl->tpl_vars['box']->value['toolbar']), ob_get_clean(), $_smarty_tpl, $_block_repeat);
}
array_pop($_smarty_tpl->smarty->_cache['_tag_stack']);?>

    
</div><?php }
}
