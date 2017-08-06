<?php
/* Smarty version 3.1.31, created on 2017-08-06 02:53:32
  from "D:\xampp\htdocs\motoshub\ow_plugins\photo\views\components\photo_floatbox.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5986e71cde88e7_54345826',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'dc3457e25f246612a503ab44fb25fb0a55a7ad54' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_plugins\\photo\\views\\components\\photo_floatbox.html',
      1 => 1466402692,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5986e71cde88e7_54345826 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_text')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.text.php';
if (!is_callable('smarty_function_add_content')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.add_content.php';
?>


<div class="ow_hidden">
    <div id="ow-photo-view" class="ow_photoview_wrap clearfix ow_bg_color">
        <?php if (!empty($_smarty_tpl->tpl_vars['authError']->value)) {?>
            <div id="ow-photo-view-error" style="padding: 45px 10px 65px">
                <div class="ow_anno ow_nocontent"><?php echo $_smarty_tpl->tpl_vars['authError']->value;?>
</div>
            </div>
        <?php } else { ?>
            <div class="ow_photoview_stage_wrap<?php if ($_smarty_tpl->tpl_vars['layout']->value == 'page') {?> ow_smallmargin<?php }?>">
                <img alt="" src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs%3D" class="ow_photo_img ow_photo_view" />
                <div class="ow_photoview_bottom_menu_wrap">
                    <div class="ow_photoview_bottom_menu clearfix">
                        <span class="ow_photo_album_icon"></span>
                        <a  href="javascript://" class="ow_photoview_albumlink">
                            '': 
                        </a>
                        <div class="ow_photoview_fullscreen_toolbar_wrap">
                            <div class="ow_photoview_play_btn" title="<?php echo smarty_function_text(array('key'=>'photo+play_pause'),$_smarty_tpl);?>
"></div>
                            <div class="ow_photoview_slide_settings" style="float: none">
                                <div class="ow_photoview_slide_settings_btn" title="<?php echo smarty_function_text(array('key'=>'photo+slideshow_settings'),$_smarty_tpl);?>
"></div>
                                <div class="ow_photoview_slide_settings_controls clearfix">
                                    <div class="ow_photoview_slide_time" title="<?php echo smarty_function_text(array('key'=>'photo+slideshow_interval'),$_smarty_tpl);?>
3"></div>
                                    <div class="ow_photoview_slide_settings_effects">
                                        <div class="ow_photoview_slide_settings_effect ow_small active" effect="fade" title="<?php echo smarty_function_text(array('key'=>'photo+effects'),$_smarty_tpl);?>
"><?php echo smarty_function_text(array('key'=>'photo+effect_fade'),$_smarty_tpl);?>
</div>
                                        <div class="ow_photoview_slide_settings_effect ow_small" effect="slide" title="<?php echo smarty_function_text(array('key'=>'photo+effects'),$_smarty_tpl);?>
"><?php echo smarty_function_text(array('key'=>'photo+effect_slide'),$_smarty_tpl);?>
</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="javascript://" class="ow_photoview_info_btn open ow_right"></a>
                        <a href="javascript://" class="ow_photoview_fullscreen ow_right"></a>
                    </div>
                </div>
                <div class="ow_photo_context_action" style="display: none"></div>
                <a class="ow_photoview_arrow_left" href="javascript://"><i></i></a>
                <a class="ow_photoview_arrow_right" href="javascript://"><i></i></a>
                <img alt="" src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs%3D" style="display: none" class="ow_photo_img slide" />
            </div>
            <div class="ow_photoview_info_wrap">
                <div class="ow_photoview_info<?php echo $_smarty_tpl->tpl_vars['class']->value;?>
">
                    <div class="ow_photo_scroll_cont">
                        <div class="ow_photoview_user ow_smallmargin clearfix">
                            <div class="ow_user_list_picture">
                                <div class="ow_avatar">
                                    <a href="javascript://"><img alt="" src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs%3D" style="max-width: 100%; display: none"></a>
                                </div>
                            </div>
                            <div class="ow_user_list_data">
                                <a href="javascript://" class="ow_photo_avatar_url"></a>
                                <div class="ow_small ow_timestamp"></div>
                                <a href="javascript://" class="ow_small ow_photo_album_url">
                                    <span class="ow_photo_album_icon"></span>
                                    <span class="ow_photo_album_name"></span>
                                </a>
                            </div>
                        </div>
                        
                        <?php echo smarty_function_add_content(array('key'=>"photo.content.betweenInfoAndDescription"),$_smarty_tpl);?>

                        
                        <div class="ow_photoview_description ow_small">
                            <span id="photo-description"></span>
                        </div>
                        
                        <?php echo smarty_function_add_content(array('key'=>"photo.content.betweenDescriptionAndRates"),$_smarty_tpl);?>

                        
                        <div class="ow_rates_wrap ow_small ow_hidden">
                            <span><?php echo smarty_function_text(array('key'=>'photo+rating'),$_smarty_tpl);?>
:</span>
                            <div class="ow_rates">
                                <div class="rates_cont clearfix">
                                    <a class="rate_item" href="javascript://">&nbsp;</a>
                                    <a class="rate_item" href="javascript://">&nbsp;</a>
                                    <a class="rate_item" href="javascript://">&nbsp;</a>
                                    <a class="rate_item" href="javascript://">&nbsp;</a>
                                    <a class="rate_item" href="javascript://">&nbsp;</a>
                                </div>
                                <div class="inactive_rate_list">
                                    <div style="width:0%;" class="active_rate_list"></div>
                                </div>
                            </div>
                            <span style="font-style: italic;" class="rate_title"></span>
                        </div>
                        <div class="ow_photo_share"></div>
                        
                        <?php echo smarty_function_add_content(array('key'=>"photo.content.betweenRatesAndComments"),$_smarty_tpl);?>

                        
                        <div class="ow_feed_comments ow_small"></div>
                    </div>
                </div>
                <div class="ow_feed_comments_input_sticky"></div>
            </div>
        <?php }?>
    </div>
</div>
<?php }
}
