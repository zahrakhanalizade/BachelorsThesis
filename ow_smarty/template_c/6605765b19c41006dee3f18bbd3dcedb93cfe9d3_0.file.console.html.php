<?php
/* Smarty version 3.1.31, created on 2017-08-05 23:27:41
  from "D:\xampp\htdocs\motoshub\ow_system_plugins\base\views\components\console.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5986b6ddbd9f12_87983261',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6605765b19c41006dee3f18bbd3dcedb93cfe9d3' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_system_plugins\\base\\views\\components\\console.html',
      1 => 1466402692,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5986b6ddbd9f12_87983261 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_block_style')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\block.style.php';
$_smarty_tpl->smarty->_cache['_tag_stack'][] = array('style', array());
$_block_repeat=true;
echo smarty_block_style(array(), null, $_smarty_tpl, $_block_repeat);
while ($_block_repeat) {
ob_start();
?>


html div.ow_console_list_wrapper
{
    min-height: 0px;
    max-height: 257px;
}

<?php $_block_repeat=false;
echo smarty_block_style(array(), ob_get_clean(), $_smarty_tpl, $_block_repeat);
}
array_pop($_smarty_tpl->smarty->_cache['_tag_stack']);?>


<div class="ow_console clearfix">
    <div class="ow_console_body clearfix">
        <div class="ow_console_items_wrap">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['items']->value, 'item');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
?>
                <?php echo $_smarty_tpl->tpl_vars['item']->value;?>

            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

        </div>
    </div>
</div><?php }
}
