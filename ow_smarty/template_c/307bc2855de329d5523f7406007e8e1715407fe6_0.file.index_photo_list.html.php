<?php
/* Smarty version 3.1.31, created on 2017-08-06 02:53:32
  from "D:\xampp\htdocs\motoshub\ow_plugins\photo\views\components\index_photo_list.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5986e71ce61a84_21230526',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '307bc2855de329d5523f7406007e8e1715407fe6' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_plugins\\photo\\views\\components\\index_photo_list.html',
      1 => 1463982332,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5986e71ce61a84_21230526 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_block_style')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\block.style.php';
if (!is_callable('smarty_function_text')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.text.php';
if (!is_callable('smarty_function_url_for_route')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.url_for_route.php';
if (!is_callable('smarty_function_decorator')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.decorator.php';
if (!is_callable('smarty_block_block_decorator')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\block.block_decorator.php';
$_smarty_tpl->smarty->_cache['_tag_stack'][] = array('style', array());
$_block_repeat=true;
echo smarty_block_style(array(), null, $_smarty_tpl, $_block_repeat);
while ($_block_repeat) {
ob_start();
?>

.ow_photo_item_widget {
    width: 72px;
    height: 72px;
    background-size: cover;
    background-repeat: no-repeat;
}
<?php $_block_repeat=false;
echo smarty_block_style(array(), ob_get_clean(), $_smarty_tpl, $_block_repeat);
}
array_pop($_smarty_tpl->smarty->_cache['_tag_stack']);?>

<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'default', 'cmp', null);?>
    <div id="photo_list_cmp<?php echo $_smarty_tpl->tpl_vars['uniqId']->value;?>
">
        <?php if ($_smarty_tpl->tpl_vars['latest']->value || $_smarty_tpl->tpl_vars['featured']->value || $_smarty_tpl->tpl_vars['toprated']->value) {?>
            <?php if (isset($_smarty_tpl->tpl_vars['menu']->value)) {
echo $_smarty_tpl->tpl_vars['menu']->value;
}?>
        <?php }?>

        <?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'default', 'nocontent', null);?>
            <div class="ow_nocontent"><?php echo smarty_function_text(array('key'=>'photo+no_photo'),$_smarty_tpl);?>
, <a href="javascript:<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
()"><?php echo smarty_function_text(array('key'=>'photo+add_new'),$_smarty_tpl);?>
</a></div>
        <?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);?>

        <div class="ow_lp_photos ow_center" id="<?php echo $_smarty_tpl->tpl_vars['items']->value['latest']['contId'];?>
">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['latest']->value, 'p');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['p']->value) {
?>
                <a class="ow_lp_wrapper" rel="<?php echo $_smarty_tpl->tpl_vars['p']->value['id'];?>
" href="<?php echo smarty_function_url_for_route(array('for'=>"view_photo:[id=>".((string)$_smarty_tpl->tpl_vars['p']->value['id'])."]"),$_smarty_tpl);?>
" list-type="latest">
                   <div class="ow_photo_item_widget<?php if (!empty($_smarty_tpl->tpl_vars['p']->value['class'])) {?> <?php echo $_smarty_tpl->tpl_vars['p']->value['class'];
}?>" style="background-image: url(<?php echo $_smarty_tpl->tpl_vars['p']->value['url'];?>
);" data-url="<?php echo $_smarty_tpl->tpl_vars['p']->value['url'];?>
"></div>
                </a>
            <?php
}
} else {
?>

                <?php echo $_smarty_tpl->tpl_vars['nocontent']->value;?>

            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

        </div>

        <?php if ($_smarty_tpl->tpl_vars['featured']->value) {?>
            <div class="ow_lp_photos ow_center" id="<?php echo $_smarty_tpl->tpl_vars['items']->value['featured']['contId'];?>
" style="display: none">
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['featured']->value, 'p');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['p']->value) {
?>
                    <a class="ow_lp_wrapper" rel="<?php echo $_smarty_tpl->tpl_vars['p']->value['id'];?>
" href="<?php echo smarty_function_url_for_route(array('for'=>"view_photo:[id=>".((string)$_smarty_tpl->tpl_vars['p']->value['id'])."]"),$_smarty_tpl);?>
" list-type="featured">
                       <div class="ow_photo_item_widget<?php if (!empty($_smarty_tpl->tpl_vars['p']->value['class'])) {?> <?php echo $_smarty_tpl->tpl_vars['p']->value['class'];
}?>" style="background-image: url(<?php echo $_smarty_tpl->tpl_vars['p']->value['url'];?>
);" data-url="<?php echo $_smarty_tpl->tpl_vars['p']->value['url'];?>
"></div>
                    </a>
                <?php
}
} else {
?>

                    <?php echo $_smarty_tpl->tpl_vars['nocontent']->value;?>

                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

            </div>
        <?php }?>

        <div class="ow_lp_photos ow_center" id="<?php echo $_smarty_tpl->tpl_vars['items']->value['toprated']['contId'];?>
" style="display: none">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['toprated']->value, 'p');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['p']->value) {
?>
                <a class="ow_lp_wrapper" rel="<?php echo $_smarty_tpl->tpl_vars['p']->value['id'];?>
" href="<?php echo smarty_function_url_for_route(array('for'=>"view_photo:[id=>".((string)$_smarty_tpl->tpl_vars['p']->value['id'])."]"),$_smarty_tpl);?>
" list-type="toprated">
                   <div class="ow_photo_item_widget<?php if (!empty($_smarty_tpl->tpl_vars['p']->value['class'])) {?> <?php echo $_smarty_tpl->tpl_vars['p']->value['class'];
}?>" style="background-image: url(<?php echo $_smarty_tpl->tpl_vars['p']->value['url'];?>
);" data-url="<?php echo $_smarty_tpl->tpl_vars['p']->value['url'];?>
"></div>
                </a>
            <?php
}
} else {
?>

                <?php echo $_smarty_tpl->tpl_vars['nocontent']->value;?>

            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

        </div>

        <?php if ($_smarty_tpl->tpl_vars['showToolbar']->value) {?>
            <?php if ($_smarty_tpl->tpl_vars['latest']->value) {?><div id="photo-cmp-toolbar-latest-<?php echo $_smarty_tpl->tpl_vars['uniqId']->value;?>
" style="display: none"><?php echo smarty_function_decorator(array('name'=>'box_toolbar','itemList'=>$_smarty_tpl->tpl_vars['toolbars']->value['latest']),$_smarty_tpl);?>
</div><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['featured']->value) {?><div id="photo-cmp-toolbar-featured-<?php echo $_smarty_tpl->tpl_vars['uniqId']->value;?>
" style="display: none"><?php echo smarty_function_decorator(array('name'=>'box_toolbar','itemList'=>$_smarty_tpl->tpl_vars['toolbars']->value['featured']),$_smarty_tpl);?>
</div><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['toprated']->value) {?><div id="photo-cmp-toolbar-top-rated-<?php echo $_smarty_tpl->tpl_vars['uniqId']->value;?>
" style="display: none"><?php echo smarty_function_decorator(array('name'=>'box_toolbar','itemList'=>$_smarty_tpl->tpl_vars['toolbars']->value['toprated']),$_smarty_tpl);?>
</div><?php }?>
        <?php }?>

    </div>
<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);?>

<?php if ($_smarty_tpl->tpl_vars['wrapBox']->value) {
$_smarty_tpl->smarty->_cache['_tag_stack'][] = array('block_decorator', array('name'=>'box','capEnabled'=>$_smarty_tpl->tpl_vars['showTitle']->value,'langLabel'=>'photo+photo_list_widget','iconClass'=>'ow_ic_picture','type'=>$_smarty_tpl->tpl_vars['boxType']->value,'addClass'=>"ow_stdmargin clearfix",'toolbar'=>$_smarty_tpl->tpl_vars['toolbars']->value['latest']));
$_block_repeat=true;
echo smarty_block_block_decorator(array('name'=>'box','capEnabled'=>$_smarty_tpl->tpl_vars['showTitle']->value,'langLabel'=>'photo+photo_list_widget','iconClass'=>'ow_ic_picture','type'=>$_smarty_tpl->tpl_vars['boxType']->value,'addClass'=>"ow_stdmargin clearfix",'toolbar'=>$_smarty_tpl->tpl_vars['toolbars']->value['latest']), null, $_smarty_tpl, $_block_repeat);
while ($_block_repeat) {
ob_start();
?>


    <?php echo $_smarty_tpl->tpl_vars['cmp']->value;?>


<?php $_block_repeat=false;
echo smarty_block_block_decorator(array('name'=>'box','capEnabled'=>$_smarty_tpl->tpl_vars['showTitle']->value,'langLabel'=>'photo+photo_list_widget','iconClass'=>'ow_ic_picture','type'=>$_smarty_tpl->tpl_vars['boxType']->value,'addClass'=>"ow_stdmargin clearfix",'toolbar'=>$_smarty_tpl->tpl_vars['toolbars']->value['latest']), ob_get_clean(), $_smarty_tpl, $_block_repeat);
}
array_pop($_smarty_tpl->smarty->_cache['_tag_stack']);?>

<?php } else { ?>
    <?php echo $_smarty_tpl->tpl_vars['cmp']->value;?>

    <?php if ($_smarty_tpl->tpl_vars['latest']->value) {?><div class="ow_box_toolbar_cont clearfix"><?php echo smarty_function_decorator(array('name'=>'box_toolbar','itemList'=>$_smarty_tpl->tpl_vars['toolbars']->value['latest']),$_smarty_tpl);?>
</div><?php }
}
}
}
