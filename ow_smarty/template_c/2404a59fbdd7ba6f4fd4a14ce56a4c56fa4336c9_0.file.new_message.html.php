<?php
/* Smarty version 3.1.31, created on 2017-08-08 08:00:10
  from "D:\xampp\htdocs\motoshub\ow_plugins\mailbox\views\components\new_message.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5989d1fa5fdcf3_91563036',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2404a59fbdd7ba6f4fd4a14ce56a4c56fa4336c9' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_plugins\\mailbox\\views\\components\\new_message.html',
      1 => 1494660052,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5989d1fa5fdcf3_91563036 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_block_form')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\block.form.php';
if (!is_callable('smarty_function_text')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.text.php';
if (!is_callable('smarty_function_input')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.input.php';
if (!is_callable('smarty_function_submit')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.submit.php';
?>
<!-- ----------------------MAILBOX SEND NEW MESSAGE--------------------------- -->
<div class="ow_chat_dialog ow_mailchat_new_message " id="newMessageWindow">
    <div class="ow_chat_block ow_mailchat_select_user_wrap">
        <?php $_smarty_tpl->smarty->_cache['_tag_stack'][] = array('form', array('name'=>"mailbox-new-message-form"));
$_block_repeat=true;
echo smarty_block_form(array('name'=>"mailbox-new-message-form"), null, $_smarty_tpl, $_block_repeat);
while ($_block_repeat) {
ob_start();
?>

        <!-- ---- NEW MESSAGE CAP ---->
        <!-- ----------------------- SELECTED USER CAP --------------------------- -->
        <div class="ow_mailchat_selected_user ow_author_block clearfix">
            <div aria-disabled="false" style="position: absolute;" class="ow_puller ui-draggable"></div>
            <a href="javascript://" target="_blank" class="ow_chat_in_item_author_href ow_chat_in_item_photo_wrap" id="userFieldProfileLink">
                <span class="ow_chat_in_item_photo"><img title="" alt="" src="<?php echo $_smarty_tpl->tpl_vars['defaultAvatarUrl']->value;?>
" height="32px" width="32px" id="userFieldAvatar"></span>
            </a>
            <a href="javascript://" class="ow_chat_item_author_wrap" id="newMessageWindowMinimizeBtn">
                <span class="ow_chat_item_author">
                    <span style="padding-top: 3px;" class="ow_chat_message_to"><?php echo smarty_function_text(array('key'=>'mailbox+new_message_to'),$_smarty_tpl);?>
</span>
                    <span style="background-color: #767676;padding: 4px 4px 0px 4px;border-radius: 3px;color: white" class="ow_chat_in_item_author" id="userFieldDisplayname"></span>
                </span>
                <span class="ow_mailchat_delete_receiver" id="userFieldDeleteBtn"></span>
            </a>
            <a class="ow_btn_close" id="newMessageWindowCloseBtn" href="javascript://"><span></span></a>
        </div>
        <!-- ----------------------- UNSELECTED USER CAP --------------------------- -->
        <div class="ow_mailchat_select_user ow_author_block">
            <div aria-disabled="false" style="position: absolute;" class="ow_puller ui-draggable"></div>
            <?php echo smarty_function_input(array('name'=>'opponentId'),$_smarty_tpl);?>

            <a href="javascript://" class="ow_chat_minimize_btn" id="newMessageWindowUnselectedCapMinimizeBtn"></a>
            <a class="ow_btn_close" id="newMessageWindowUnselectedCapCloseBtn" href="javascript://"><span></span></a>
            <div class="ow_mailchat_new_message_title"><?php echo smarty_function_text(array('key'=>"mailbox+new_message_title"),$_smarty_tpl);?>
</div>
        </div>
        <!-- ----------------------- END OF USER CAP --------------------------- -->
        <!-- ---- END OF NEW MESSAGE CAP ---->
        <div class="ow_chat_subject_block">
            <?php echo smarty_function_input(array('name'=>'subject','class'=>"newMessageWindowSubjectInputControl"),$_smarty_tpl);?>

        </div>
        <div class="ow_chat_mailchat_inputarea">
            <?php echo smarty_function_input(array('name'=>'message','class'=>"newMessageWindowMessageInputControl"),$_smarty_tpl);?>

            <?php if ($_smarty_tpl->tpl_vars['enableAttachments']->value) {?>
            <div class="ow_file_attachment_preview clearfix">
            <?php echo $_smarty_tpl->tpl_vars['attachments']->value;?>

            </div>
            <?php }?>
            <div class="ow_file_attachment_preview clearfix" id="newMessageWindowEmbedAttachmentsBlock"></div>
            <div class="ow_chat_mailchat_buttons clearfix">
                <span class="ow_attachment_btn">
                    <?php echo smarty_function_submit(array('name'=>"send"),$_smarty_tpl);?>

                </span>
                <?php if ($_smarty_tpl->tpl_vars['enableAttachments']->value) {?>
                <span class="ow_attachment_icons">
                    <div class="ow_attachments">
                        <span class="buttons clearfix">
                            <a class="attach" href="javascript://" title="Attach" id="newMessageWindowAttachmentsBtn"></a>
                        </span>
                    </div>
                </span>
                <?php }?>
            </div>
            
        </div>

        <?php $_block_repeat=false;
echo smarty_block_form(array('name'=>"mailbox-new-message-form"), ob_get_clean(), $_smarty_tpl, $_block_repeat);
}
array_pop($_smarty_tpl->smarty->_cache['_tag_stack']);?>

    </div>
</div>
<!-- -----------------------END OF SEND NEW MESSAGE---------------------------- --><?php }
}
