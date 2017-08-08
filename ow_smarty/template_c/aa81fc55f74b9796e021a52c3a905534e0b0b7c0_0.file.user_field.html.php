<?php
/* Smarty version 3.1.31, created on 2017-08-08 08:00:10
  from "D:\xampp\htdocs\motoshub\ow_plugins\mailbox\views\components\user_field.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5989d1fa60d6f6_50209952',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'aa81fc55f74b9796e021a52c3a905534e0b0b7c0' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_plugins\\mailbox\\views\\components\\user_field.html',
      1 => 1463982332,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5989d1fa60d6f6_50209952 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_text')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.text.php';
echo $_smarty_tpl->tpl_vars['input']->value;?>

<input name="mailbox_new_message_user" id="mailbox_new_message_user" type="text" class="invitation userFieldInputControl" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" placeholder="<?php echo smarty_function_text(array('key'=>'mailbox+to'),$_smarty_tpl);?>
" />
<div style="display: block;" class="ow_mailchat_autocomplete" id="userFieldAutocompleteControl">
    <div class="ow_mailchat_autocomplete_inner">
        <ul style="display: block;" id="userFieldUserList">
            <li class="clearfix" style="display: none;" id="userFieldUserListItem-notfound">
                <span class="ow_chat_user_not_found"><?php echo smarty_function_text(array('key'=>"mailbox+user_not_found"),$_smarty_tpl);?>
</span>
            </li>
        </ul>
    </div>
</div>

<ul style="display: none;" id="userFieldUserListPrototype">
    <li style="display: none;" id="userFieldUserListItemPrototype" class="userFieldUserListItem">
        <a class="clearfix ow_chat_item" href="javascript://">
            <span class="ow_chat_item_photo_wrap">
                <span class="ow_chat_item_photo">
                    <span class="ow_chat_in_item_photo"><img width="32px" height="32px" alt="" src="<?php echo $_smarty_tpl->tpl_vars['defaultAvatarUrl']->value;?>
" id="userFieldUserListItemAvatarUrl"></span>
                </span>
            </span>
            <span class="ow_chat_item_author">
                <span class="ow_chat_in_item_author" id="userFieldUserListItemUsername"></span>
            </span>
        </a>
    </li>
</ul>
<?php }
}
