<?php
/* Smarty version 3.1.31, created on 2017-08-05 23:27:41
  from "D:\xampp\htdocs\motoshub\ow_system_plugins\base\views\components\file_attachment.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5986b6dda999c4_56291789',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4612fdd891c09502e170470939a47fdd13813edc' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_system_plugins\\base\\views\\components\\file_attachment.html',
      1 => 1463982332,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5986b6dda999c4_56291789 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_text')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.text.php';
?>
<div id="<?php echo $_smarty_tpl->tpl_vars['data']->value['uid'];?>
">
    <div class="ow_file_attachment_preview clearfix"<?php if (empty($_smarty_tpl->tpl_vars['data']->value['showPreview'])) {?> style="display:none;"<?php }?>></div>
    <?php if (empty($_smarty_tpl->tpl_vars['data']->value['selector'])) {?>
    <div class="clearfix ow_status_update_btn_block">
        <span class="ow_attachment_icons">
            <div id="nfa-feed1" class="ow_attachments">
              <span class="buttons clearfix">
                  <a title='<?php echo smarty_function_text(array('key'=>"forum+attach_files"),$_smarty_tpl);?>
' href="javascript://" class="attach"></a>
              </span>
            </div>
        </span>
    </div>
    <?php }?>
</div><?php }
}
