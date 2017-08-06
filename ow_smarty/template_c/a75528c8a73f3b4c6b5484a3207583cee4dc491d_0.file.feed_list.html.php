<?php
/* Smarty version 3.1.31, created on 2017-08-06 02:53:22
  from "D:\xampp\htdocs\motoshub\ow_plugins\newsfeed\views\components\feed_list.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5986e7121d8350_35910214',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a75528c8a73f3b4c6b5484a3207583cee4dc491d' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_plugins\\newsfeed\\views\\components\\feed_list.html',
      1 => 1463982332,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5986e7121d8350_35910214 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_text')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.text.php';
?>
<li <?php if (count($_smarty_tpl->tpl_vars['feed']->value)) {?>style="display: none;"<?php }?> class="ow_newsfeed_item ow_nocontent newsfeed_nocontent"><?php echo smarty_function_text(array('key'=>"newsfeed+empty_feed_message"),$_smarty_tpl);?>
</li>

<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['feed']->value, 'item', false, NULL, 'feed', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_feed']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_feed']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_feed']->value['iteration'] == $_smarty_tpl->tpl_vars['__smarty_foreach_feed']->value['total'];
?>
    <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['newsfeed_item'][0][0]->tplRenderItem(array('action'=>$_smarty_tpl->tpl_vars['item']->value,'lastItem'=>(isset($_smarty_tpl->tpl_vars['__smarty_foreach_feed']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_feed']->value['last'] : null)),$_smarty_tpl);?>

<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

<?php }
}
