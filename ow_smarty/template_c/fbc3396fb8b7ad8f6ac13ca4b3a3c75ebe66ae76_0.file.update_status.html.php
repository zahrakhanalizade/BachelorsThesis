<?php
/* Smarty version 3.1.31, created on 2017-08-06 02:53:22
  from "D:\xampp\htdocs\motoshub\ow_plugins\newsfeed\views\components\update_status.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5986e712212ce8_00994512',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'fbc3396fb8b7ad8f6ac13ca4b3a3c75ebe66ae76' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_plugins\\newsfeed\\views\\components\\update_status.html',
      1 => 1494660052,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5986e712212ce8_00994512 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_block_style')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\block.style.php';
if (!is_callable('smarty_block_script')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\block.script.php';
if (!is_callable('smarty_block_form')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\block.form.php';
if (!is_callable('smarty_function_input')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.input.php';
if (!is_callable('smarty_function_submit')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.submit.php';
if (!is_callable('smarty_function_label')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.label.php';
if (!is_callable('smarty_function_error')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.error.php';
$_smarty_tpl->smarty->_cache['_tag_stack'][] = array('style', array());
$_block_repeat=true;
echo smarty_block_style(array(), null, $_smarty_tpl, $_block_repeat);
while ($_block_repeat) {
ob_start();
?>


textarea.ow_newsfeed_status_input {
    height: 50px;
}

textarea.ow_newsfeed_status_input.invitation {
    height: 20px;
}

.newsfeed-attachment-preview {
    width: 95%;
}
.ow_side_preloader {
	float: right;
	padding: 0px 4px 0px 0px;
	margin-top: 6px;
}
.ow_side_preloader {
	display: inline-block;
	width: 16px;
	height: 16px;
	background-repeat: no-repeat;
}
<?php $_block_repeat=false;
echo smarty_block_style(array(), ob_get_clean(), $_smarty_tpl, $_block_repeat);
}
array_pop($_smarty_tpl->smarty->_cache['_tag_stack']);?>


<?php $_smarty_tpl->smarty->_cache['_tag_stack'][] = array('script', array());
$_block_repeat=true;
echo smarty_block_script(array(), null, $_smarty_tpl, $_block_repeat);
while ($_block_repeat) {
ob_start();
?>

	
		function checkRtl( character ) {
			var RTL = ['','ا','ب','پ','ت','س','ج','چ','ح','خ','د','ذ','ر','ز','ژ','س','ش','ص','ض','ط','ظ','ع','غ','ف','ق','ک','گ','ل','م','ن','و','ه','ی'];
			return RTL.indexOf( character ) > -1;
		}

		function checkInput(){
			jQuery( this ).css( 'direction', checkRtl( jQuery( this ).val().trim().substr( 0, 1 ) ) ? 'rtl' : 'ltr' );
		}
		$('textarea').change( checkInput );
		$('textarea').keydown( checkInput );
		$('textarea').keyup( checkInput );
		$('input').change( checkInput );
		$('input').keydown( checkInput );
		$('input').keyup( checkInput );
	
<?php $_block_repeat=false;
echo smarty_block_script(array(), ob_get_clean(), $_smarty_tpl, $_block_repeat);
}
array_pop($_smarty_tpl->smarty->_cache['_tag_stack']);?>

<?php $_smarty_tpl->smarty->_cache['_tag_stack'][] = array('form', array('name'=>"newsfeed_update_status"));
$_block_repeat=true;
echo smarty_block_form(array('name'=>"newsfeed_update_status"), null, $_smarty_tpl, $_block_repeat);
while ($_block_repeat) {
ob_start();
?>

	<div class="form_auto_click">
		<div class="clearfix">
			<div class="newsfeed_update_status_picture">
			</div>
			<div class="newsfeed_update_status_info">
				<div class="ow_smallmargin"><?php echo smarty_function_input(array('name'=>"status",'class'=>"ow_newsfeed_status_input"),$_smarty_tpl);?>
</div>
			</div>
		</div>
		
                <div id="attachment_preview_<?php echo $_smarty_tpl->tpl_vars['uniqId']->value;?>
-oembed" class="newsfeed-attachment-preview ow_smallmargin" style="display: none;"></div>
                
                    <?php echo $_smarty_tpl->tpl_vars['attachment']->value;?>

                
            
		<div class="ow_submit_auto_click" style="text-align: left;">
			<div class="clearfix ow_status_update_btn_block">
				<span class="ow_attachment_btn"><?php echo smarty_function_submit(array('name'=>"save"),$_smarty_tpl);?>
</span>
                                <span class="ow_attachment_icons">
                                    <span class="ow_attachments" id="<?php echo $_smarty_tpl->tpl_vars['uniqId']->value;?>
-btn-cont" >
                                        <span class="buttons clearfix">
                                            <a class="image" id="<?php echo $_smarty_tpl->tpl_vars['uniqId']->value;?>
-btn" href="javascript://"></a>
                                        </span>
                                    </span>
                                </span>
				<?php if (isset($_smarty_tpl->tpl_vars['statusPrivacyField']->value)) {?>
				<?php echo smarty_function_label(array('name'=>'statusPrivacy'),$_smarty_tpl);?>

				<?php echo smarty_function_input(array('name'=>'statusPrivacy'),$_smarty_tpl);?>

				<?php echo smarty_function_error(array('name'=>'statusPrivacy'),$_smarty_tpl);?>

				<?php }?>
				<?php if (isset($_smarty_tpl->tpl_vars['statusPrivacyLabel']->value)) {?>
					<?php echo $_smarty_tpl->tpl_vars['statusPrivacyLabel']->value;?>

				<?php }?>
				<span class="ow_side_preloader_wrap"><span class="ow_side_preloader ow_inprogress newsfeed-status-preloader" style="display: none;"></span></span>
			</div>
		</div>
	</div>
<?php $_block_repeat=false;
echo smarty_block_form(array('name'=>"newsfeed_update_status"), ob_get_clean(), $_smarty_tpl, $_block_repeat);
}
array_pop($_smarty_tpl->smarty->_cache['_tag_stack']);
}
}
