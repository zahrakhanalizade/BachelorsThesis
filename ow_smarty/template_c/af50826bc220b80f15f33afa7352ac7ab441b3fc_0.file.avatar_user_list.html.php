<?php
/* Smarty version 3.1.31, created on 2017-08-06 02:53:32
  from "D:\xampp\htdocs\motoshub\ow_system_plugins\base\views\components\avatar_user_list.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5986e71ccfe2b5_85730257',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'af50826bc220b80f15f33afa7352ac7ab441b3fc' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_system_plugins\\base\\views\\components\\avatar_user_list.html',
      1 => 1463982332,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5986e71ccfe2b5_85730257 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_text')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.text.php';
if (!is_callable('smarty_function_decorator')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.decorator.php';
?>
<div class="ow_lp_avatars<?php if (!empty($_smarty_tpl->tpl_vars['css_class']->value)) {?> <?php echo $_smarty_tpl->tpl_vars['css_class']->value;
}?>">
    <?php if (empty($_smarty_tpl->tpl_vars['users']->value)) {?>
        <div class="ow_nocontent"><?php echo smarty_function_text(array('key'=>'base+empty_user_avatar_list'),$_smarty_tpl);?>
</div>
    <?php } else { ?>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['users']->value, 'user');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['user']->value) {
echo smarty_function_decorator(array('name'=>'avatar_item','data'=>$_smarty_tpl->tpl_vars['user']->value),$_smarty_tpl);
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
if (!empty($_smarty_tpl->tpl_vars['view_more_array']->value)) {?><a href="<?php echo $_smarty_tpl->tpl_vars['view_more_array']->value['url'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['view_more_array']->value['title'];?>
" class="avatar_list_more_icon"></a><?php }?>
    <?php }?>
    
</div><?php }
}
