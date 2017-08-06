<?php
/* Smarty version 3.1.31, created on 2017-08-05 23:27:41
  from "D:\xampp\htdocs\motoshub\ow_system_plugins\base\views\components\responsive_menu.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5986b6ddbf5491_62311843',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7507d5dbfd59babf8b199c354cc161e01626e52b' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_system_plugins\\base\\views\\components\\responsive_menu.html',
      1 => 1463982332,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5986b6ddbf5491_62311843 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="ow_responsive_menu ow_left" id="<?php echo $_smarty_tpl->tpl_vars['uniqId']->value;?>
">
    <ul class="ow_main_menu clearfix" data-el="list"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['data']->value, 'item');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
?><li class="<?php echo $_smarty_tpl->tpl_vars['item']->value['class'];
if (!empty($_smarty_tpl->tpl_vars['item']->value['active'])) {?> active<?php }?>" data-el="item"><a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['url'];?>
"<?php if ($_smarty_tpl->tpl_vars['item']->value['new_window']) {?> target="_blank"<?php }?>><span><?php echo $_smarty_tpl->tpl_vars['item']->value['label'];?>
</span></a></li><?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>
</ul>
    <div class="ow_menu_more_wrap ow_cursor_pointer">
        <div class="ow_menu_more">
            <div class="ow_menu_more_cont">
                <ul class="ow_menu_more_list" data-el="more-list">

                </ul>
            </div>
        </div>
    </div>
</div>
<?php }
}
