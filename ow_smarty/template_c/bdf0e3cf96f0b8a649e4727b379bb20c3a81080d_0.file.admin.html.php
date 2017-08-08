<?php
/* Smarty version 3.1.31, created on 2017-08-08 08:01:01
  from "D:\xampp\htdocs\motoshub\ow_themes\iissocialcity\master_pages\admin.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5989d22d72a494_07395088',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bdf0e3cf96f0b8a649e4727b379bb20c3a81080d' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_themes\\iissocialcity\\master_pages\\admin.html',
      1 => 1472299808,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5989d22d72a494_07395088 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_block_style')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\block.style.php';
if (!is_callable('smarty_function_component')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.component.php';
if (!is_callable('smarty_function_add_content')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.add_content.php';
if (!is_callable('smarty_block_block_decorator')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\block.block_decorator.php';
if (!is_callable('smarty_function_text')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.text.php';
if (!is_callable('smarty_function_decorator')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.decorator.php';
if (!is_callable('smarty_block_script')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\block.script.php';
$_smarty_tpl->smarty->_cache['_tag_stack'][] = array('style', array());
$_block_repeat=true;
echo smarty_block_style(array(), null, $_smarty_tpl, $_block_repeat);
while ($_block_repeat) {
ob_start();
?>

    #console-notifications-wrapper {
        position: absolute; 
        z-index: 100; 
        display: block;
    }

    #console-notifications-wrapper .console_tooltip {
        opacity: 1; 
        top: 22px !important; 
        display: none;
    }

    #console-notifications-wrapper .ow_count_wrap {
        width: auto;
    }

    #console-notifications-wrapper .ow_count_wrap .ow_count {
        right: 0px;
    }
    
    #console-notifications-wrapper .ow_console_list li {
        margin: 0 0 4px;
        border: 1px solid #ececec;
        border-radius: 2px;
    }

    .ow_footer .ow_canvas .ow_page {
        padding: 20px 0;
    }

    .ow_footer .ow_canvas .ow_page .ow_remark_wrapper{
        float:left;
    }

    .ow_footer .ow_canvas .ow_page .ow_powered_by{
        float:right;
        line-height:15px;
        font-size:11px;
    }
<?php $_block_repeat=false;
echo smarty_block_style(array(), ob_get_clean(), $_smarty_tpl, $_block_repeat);
}
array_pop($_smarty_tpl->smarty->_cache['_tag_stack']);?>


<div class="ow_admin_page_wrap">
    <div class="ow_admin_page_padding">
        <!-- head wrapper -->
        <div class="ow_header ow_admin_header clearfix">
            <?php echo smarty_function_component(array('class'=>'BASE_CMP_Console'),$_smarty_tpl);?>

            <div class="ow_site_panel clearfix">
                <a class="ow_admin_home_btn ow_admin_menu_item home ow_left" href="<?php echo $_smarty_tpl->tpl_vars['siteUrl']->value;?>
">
                    <span class="ow_admin_menu_item_label"><?php echo $_smarty_tpl->tpl_vars['siteName']->value;?>
</span>
                </a>
                <?php echo smarty_function_add_content(array('key'=>'admin.site_panel_left_content'),$_smarty_tpl);?>

                <div class="ow_admin_console_update">
                    <a id="admin_console_update_link" class="ow_admin_console_update_link" href="#"></a>
                    <?php if (!empty($_smarty_tpl->tpl_vars['notifications']->value)) {?>           
                        <span class="ow_count_wrap">
                            <span class="ow_count_bg">
                                <span class="ow_count ow_count_active"><?php echo count($_smarty_tpl->tpl_vars['notifications']->value);?>
</span>
                            </span>
                        </span>
                    <?php }?>
                    <div id="console-notifications-wrapper" class="OW_ConsoleItemContent">
                        <?php $_smarty_tpl->smarty->_cache['_tag_stack'][] = array('block_decorator', array('name'=>"tooltip",'addClass'=>"console_tooltip ow_tooltip_top_left"));
$_block_repeat=true;
echo smarty_block_block_decorator(array('name'=>"tooltip",'addClass'=>"console_tooltip ow_tooltip_top_left"), null, $_smarty_tpl, $_block_repeat);
while ($_block_repeat) {
ob_start();
?>

                            <div class="ow_console_list_wrapper">
                                <?php if (!empty($_smarty_tpl->tpl_vars['notifications']->value)) {?>
                                    <ul class="ow_console_list">
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['notifications']->value, 'notification');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['notification']->value) {
?>
                                            <li class="ow_console_list_item ow_admin_config_item ow_admin_console_type_<?php echo $_smarty_tpl->tpl_vars['notification']->value['type'];?>
">
                                                <div class="ow_admin_notification_pic"></div>
                                                <div class="ow_admin_notification_text ow_left ow_small"><?php echo $_smarty_tpl->tpl_vars['notification']->value['message'];?>
</div>
                                            </li>
                                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

                                    </ul>
                                <?php } else { ?>
                                    <div class="ow_nocontent"><?php echo smarty_function_text(array('key'=>"base+no_items"),$_smarty_tpl);?>
</div>
                                <?php }?>
                            </div>
                            <div class="ow_console_tooltip_btns clearfix">
                                <div class="ow_console_tooltip_btn_wrap ow_loadig"><a href="<?php echo $_smarty_tpl->tpl_vars['checkUpdatesUrl']->value;?>
"><?php echo smarty_function_text(array('key'=>"admin+check_updates"),$_smarty_tpl);?>
</a></div>
                            </div>
                        <?php $_block_repeat=false;
echo smarty_block_block_decorator(array('name'=>"tooltip",'addClass'=>"console_tooltip ow_tooltip_top_left"), ob_get_clean(), $_smarty_tpl, $_block_repeat);
}
array_pop($_smarty_tpl->smarty->_cache['_tag_stack']);?>

                    </div>
                </div>
                <?php echo smarty_function_add_content(array('key'=>'admin.site_panel_right_content'),$_smarty_tpl);?>

            </div>
        </div>
        <!-- end of head wrapper -->

        <!-- left menu wrapper -->
        <div id="main_left_menu" class="ow_admin_menu_wrap sticky">
            <div class="ow_admin_menu">
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['menuArr']->value, 'item', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['item']->value) {
?>
                    <div class="ow_admin_menu_item <?php echo $_smarty_tpl->tpl_vars['item']->value['key'];
if ($_smarty_tpl->tpl_vars['item']->value['isActive']) {?> active<?php }?>">
                        <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['firstLink'];?>
" class="ow_admin_menu_item_label"><?php echo $_smarty_tpl->tpl_vars['item']->value['label'];?>
</a>
                        <div class="ow_admin_menu_arrow"></div>
                        <?php echo $_smarty_tpl->tpl_vars['item']->value['sub_menu'];?>

                    </div>
                    <?php if ($_smarty_tpl->tpl_vars['item']->value['isActive']) {?>
                        <?php echo $_smarty_tpl->tpl_vars['item']->value['active_sub_menu'];?>

                    <?php }?>
                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

            </div>
        </div>
        <!-- end of left menu wrapper -->

        <!-- content wrapper -->
        <div class="ow_page_container ow_admin ">
            <div class="ow_canvas">
                <div class="ow_page">
                    <?php if (!empty($_smarty_tpl->tpl_vars['heading']->value)) {?>
                        <h1 class="ow_stdmargin <?php echo $_smarty_tpl->tpl_vars['heading_icon_class']->value;?>
">
                            <?php echo $_smarty_tpl->tpl_vars['heading']->value;?>

                        </h1>
                    <?php }?>
                    <div class="ow_content">
                        <?php echo smarty_function_add_content(array('key'=>'admin.add_page_top_content'),$_smarty_tpl);?>

                            <?php echo $_smarty_tpl->tpl_vars['content']->value;?>

                        <?php echo smarty_function_add_content(array('key'=>'admin.add_page_bottom_content'),$_smarty_tpl);?>

                    </div>
                </div>
            </div>
        </div>
        <!-- end of content wrapper -->
    </div>
</div>
<!-- footer wrapper -->
<div class="ow_footer ow_admin">
    <div class="ow_canvas">
        <div class="ow_page clearfix">
            <div class="ow_remark_wrapper">
                <?php if (empty($_smarty_tpl->tpl_vars['ow_plugin_xp']->value)) {?>
                    <div class="ow_right ow_small ow_remark"><?php echo $_smarty_tpl->tpl_vars['softVersion']->value;?>
</div>
                <?php }?>
            </div>
            <div class="ow_powered_by">
                <?php echo $_smarty_tpl->tpl_vars['bottomPoweredByLink']->value;?>

            </div>
        </div>
    </div>
</div>
<!-- end of footer wrapper -->

<?php echo smarty_function_decorator(array('name'=>'floatbox'),$_smarty_tpl);?>


<?php $_smarty_tpl->smarty->_cache['_tag_stack'][] = array('script', array());
$_block_repeat=true;
echo smarty_block_script(array(), null, $_smarty_tpl, $_block_repeat);
while ($_block_repeat) {
ob_start();
?>

    // process main menu height
    var $mainLeftMenu = $("#main_left_menu > .ow_admin_menu");
    if ( $mainLeftMenu.innerHeight() > $(window).innerHeight() ) {
        $mainLeftMenu.parent().removeClass("sticky");
    }

    $(document).ready(function(){
        // console
        var $tooltip = $("#console-notifications-wrapper .ow_tooltip");
        var $consoleLink = $("#admin_console_update_link");

        $consoleLink.on("click", function(e) 
        {
            e.preventDefault();

            // show the notifications
            if (!$tooltip.hasClass("active")) {
                $tooltip.addClass("active").show();
                OW.addScroll("#console-notifications-wrapper .ow_console_list_wrapper");

                return;
            }

            // hide the notifications
            $tooltip.removeClass("active").hide();
            OW.removeScroll("#console-notifications-wrapper .ow_console_list_wrapper");
        });

        $(document).mouseup(function(e)
        {
            if ($(e.target).parents("#console-notifications-wrapper").length 
                    || $consoleLink.is(e.target)) {

                return;
            }

            if ($tooltip.is(":visible")) {
                $tooltip.removeClass("active").hide();
                OW.removeScroll("#console-notifications-wrapper .ow_console_list_wrapper");
            }
        });
    });
<?php $_block_repeat=false;
echo smarty_block_script(array(), ob_get_clean(), $_smarty_tpl, $_block_repeat);
}
array_pop($_smarty_tpl->smarty->_cache['_tag_stack']);
}
}
