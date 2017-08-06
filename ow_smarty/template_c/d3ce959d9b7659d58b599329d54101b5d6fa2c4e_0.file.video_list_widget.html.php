<?php
/* Smarty version 3.1.31, created on 2017-08-06 02:53:32
  from "D:\xampp\htdocs\motoshub\ow_plugins\video\views\components\video_list_widget.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5986e71cf2ccb1_61700553',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd3ce959d9b7659d58b599329d54101b5d6fa2c4e' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_plugins\\video\\views\\components\\video_list_widget.html',
      1 => 1466402692,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5986e71cf2ccb1_61700553 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_block_script')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\block.script.php';
if (!is_callable('smarty_function_text')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.text.php';
if (!is_callable('smarty_function_url_for')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.url_for.php';
if (!is_callable('smarty_function_url_for_route')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.url_for_route.php';
if (!is_callable('smarty_function_decorator')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.decorator.php';
?>

<?php $_smarty_tpl->smarty->_cache['_tag_stack'][] = array('script', array());
$_block_repeat=true;
echo smarty_block_script(array(), null, $_smarty_tpl, $_block_repeat);
while ($_block_repeat) {
ob_start();
?>


    $(document).ready(function(){
        var $tb_container = $(".ow_box_toolbar_cont", $("#video_list_widget").parents('.ow_box, .ow_box_empty').get(0));

        $("#video-widget-menu-featured").click(function(){
            $tb_container.html($("div#video-widget-toolbar-featured").html());
        });

        $("#video-widget-menu-latest").click(function(){
            $tb_container.html($("div#video-widget-toolbar-latest").html());
        });

        $("#video-widget-menu-toprated").click(function(){
            $tb_container.html($("div#video-widget-toolbar-toprated").html());
        });
    });

<?php $_block_repeat=false;
echo smarty_block_script(array(), ob_get_clean(), $_smarty_tpl, $_block_repeat);
}
array_pop($_smarty_tpl->smarty->_cache['_tag_stack']);?>


<div id="video_list_widget">

    <?php if ($_smarty_tpl->tpl_vars['latest']->value || $_smarty_tpl->tpl_vars['featured']->value || $_smarty_tpl->tpl_vars['toprated']->value) {?> <?php if (isset($_smarty_tpl->tpl_vars['menu']->value)) {
echo $_smarty_tpl->tpl_vars['menu']->value;
}?> <?php }?>
    
    <?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'default', 'nocontent', null);?>
       <div class="ow_nocontent"><?php echo smarty_function_text(array('key'=>'video+no_video'),$_smarty_tpl);?>
, <a href="<?php echo smarty_function_url_for(array('for'=>"VIDEO_CTRL_Add:index"),$_smarty_tpl);?>
"><?php echo smarty_function_text(array('key'=>'video+add_new'),$_smarty_tpl);?>
</a></div>
    <?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);?>

	<div id="<?php echo $_smarty_tpl->tpl_vars['items']->value['latest']['contId'];?>
">
	<?php if ($_smarty_tpl->tpl_vars['showTitles']->value) {?>
	   <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['latest']->value, 'c', false, NULL, 'clips', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['c']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_clips']->value['iteration']++;
?>
	   <div class="clearfix ow_smallmargin">
            <div class="ow_other_video_thumb ow_left">
                <a href="<?php echo smarty_function_url_for_route(array('for'=>"view_clip:[id=>".((string)$_smarty_tpl->tpl_vars['c']->value['id'])."]"),$_smarty_tpl);?>
">
                    <?php if ($_smarty_tpl->tpl_vars['c']->value['thumb'] != 'undefined') {?><img src="<?php echo $_smarty_tpl->tpl_vars['c']->value['thumb'];?>
" alt="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['c']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
" /><?php }?>
                </a>
            </div>
            <div class="ow_other_video_item_title ow_small">
                <a href="<?php echo smarty_function_url_for_route(array('for'=>"view_clip:[id=>".((string)$_smarty_tpl->tpl_vars['c']->value['id'])."]"),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['c']->value['title'];?>
</a>
            </div>
	   </div>
	   <?php
}
} else {
?>

          <?php echo $_smarty_tpl->tpl_vars['nocontent']->value;?>

	   <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

	<?php } else { ?>
	<div class="clearfix ow_center">
	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['latest']->value, 'c', false, NULL, 'clips', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['c']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_clips']->value['iteration']++;
?>
		<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_clips']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_clips']->value['iteration'] : null) == 1) {?>
		   <div class="ow_smallmargin"><?php echo $_smarty_tpl->tpl_vars['c']->value['code'];?>
</div>
		<?php } else { ?>
            <div class="ow_other_video_thumb video_thumb_no_title ow_left">
				<a class="ow_other_video_floated" href="<?php echo smarty_function_url_for_route(array('for'=>"view_clip:[id=>".((string)$_smarty_tpl->tpl_vars['c']->value['id'])."]"),$_smarty_tpl);?>
" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['c']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
">
					<?php if ($_smarty_tpl->tpl_vars['c']->value['thumb'] != 'undefined') {?><img src="<?php echo $_smarty_tpl->tpl_vars['c']->value['thumb'];?>
" alt="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['c']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
" /><?php }?>
				</a>
			</div>
		<?php }?>
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
	</div>
	
	<?php if ($_smarty_tpl->tpl_vars['featured']->value) {?>
	<div id="<?php echo $_smarty_tpl->tpl_vars['items']->value['featured']['contId'];?>
" style="display: none">
    <?php if ($_smarty_tpl->tpl_vars['showTitles']->value) {?>
       <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['featured']->value, 'c', false, NULL, 'clips', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['c']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_clips']->value['iteration']++;
?>
       <div class="clearfix ow_smallmargin">
            <div class="ow_other_video_thumb ow_left">
                <a href="<?php echo smarty_function_url_for_route(array('for'=>"view_clip:[id=>".((string)$_smarty_tpl->tpl_vars['c']->value['id'])."]"),$_smarty_tpl);?>
">
                    <?php if ($_smarty_tpl->tpl_vars['c']->value['thumb'] != 'undefined') {?><img src="<?php echo $_smarty_tpl->tpl_vars['c']->value['thumb'];?>
" alt="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['c']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
" /><?php }?>
                </a>
            </div>
            <div class="ow_other_video_item_title ow_small">
                <a href="<?php echo smarty_function_url_for_route(array('for'=>"view_clip:[id=>".((string)$_smarty_tpl->tpl_vars['c']->value['id'])."]"),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['c']->value['title'];?>
</a>
            </div>
       </div>
       <?php
}
} else {
?>

          <?php echo $_smarty_tpl->tpl_vars['nocontent']->value;?>

       <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

    <?php } else { ?>
    <div class="clearfix ow_center">
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['featured']->value, 'c', false, NULL, 'clips', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['c']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_clips']->value['iteration']++;
?>
        <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_clips']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_clips']->value['iteration'] : null) == 1) {?>
           <div class="ow_smallmargin"><?php echo $_smarty_tpl->tpl_vars['c']->value['code'];?>
</div>
        <?php } else { ?>
	        <a class="ow_other_video_floated" href="<?php echo smarty_function_url_for_route(array('for'=>"view_clip:[id=>".((string)$_smarty_tpl->tpl_vars['c']->value['id'])."]"),$_smarty_tpl);?>
" title="<?php echo $_smarty_tpl->tpl_vars['c']->value['title'];?>
">
	            <?php if ($_smarty_tpl->tpl_vars['c']->value['thumb'] != 'undefined') {?><img src="<?php echo $_smarty_tpl->tpl_vars['c']->value['thumb'];?>
" alt="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['c']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
" /><?php }?>
	        </a>
        <?php }?>
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
    </div>
    <?php }?>
	
	<div id="<?php echo $_smarty_tpl->tpl_vars['items']->value['toprated']['contId'];?>
" style="display: none">
    <?php if ($_smarty_tpl->tpl_vars['showTitles']->value) {?>
       <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['toprated']->value, 'c', false, NULL, 'clips', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['c']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_clips']->value['iteration']++;
?>
       <div class="clearfix ow_smallmargin">
            <div class="ow_other_video_thumb ow_left">
                <a href="<?php echo smarty_function_url_for_route(array('for'=>"view_clip:[id=>".((string)$_smarty_tpl->tpl_vars['c']->value['id'])."]"),$_smarty_tpl);?>
">
                    <?php if ($_smarty_tpl->tpl_vars['c']->value['thumb'] != 'undefined') {?><img src="<?php echo $_smarty_tpl->tpl_vars['c']->value['thumb'];?>
" alt="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['c']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
" /><?php }?>
                </a>
            </div>
            <div class="ow_other_video_item_title ow_small">
                <a href="<?php echo smarty_function_url_for_route(array('for'=>"view_clip:[id=>".((string)$_smarty_tpl->tpl_vars['c']->value['id'])."]"),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['c']->value['title'];?>
</a>
            </div>
       </div>
       <?php
}
} else {
?>

          <?php echo $_smarty_tpl->tpl_vars['nocontent']->value;?>

       <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

    <?php } else { ?>
    <div class="clearfix ow_center">
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['toprated']->value, 'c', false, NULL, 'clips', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['c']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_clips']->value['iteration']++;
?>
        <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_clips']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_clips']->value['iteration'] : null) == 1) {?>
           <div class="ow_smallmargin"><?php echo $_smarty_tpl->tpl_vars['c']->value['code'];?>
</div>
        <?php } else { ?>
	        <a class="ow_other_video_floated" href="<?php echo smarty_function_url_for_route(array('for'=>"view_clip:[id=>".((string)$_smarty_tpl->tpl_vars['c']->value['id'])."]"),$_smarty_tpl);?>
" title="<?php echo $_smarty_tpl->tpl_vars['c']->value['title'];?>
">
	            <?php if ($_smarty_tpl->tpl_vars['c']->value['thumb'] != 'undefined') {?><img src="<?php echo $_smarty_tpl->tpl_vars['c']->value['thumb'];?>
" alt="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['c']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
" /><?php }?>
	        </a>
        <?php }?>
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
    </div>
	
	<?php if ($_smarty_tpl->tpl_vars['latest']->value) {?><div id="video-widget-toolbar-latest" style="display: none"><?php echo smarty_function_decorator(array('name'=>'box_toolbar','itemList'=>$_smarty_tpl->tpl_vars['toolbars']->value['latest']),$_smarty_tpl);?>
</div><?php }?>
    <?php if ($_smarty_tpl->tpl_vars['featured']->value) {?><div id="video-widget-toolbar-featured" style="display: none"><?php echo smarty_function_decorator(array('name'=>'box_toolbar','itemList'=>$_smarty_tpl->tpl_vars['toolbars']->value['featured']),$_smarty_tpl);?>
</div><?php }?>  
    <?php if ($_smarty_tpl->tpl_vars['toprated']->value) {?><div id="video-widget-toolbar-toprated" style="display: none"><?php echo smarty_function_decorator(array('name'=>'box_toolbar','itemList'=>$_smarty_tpl->tpl_vars['toolbars']->value['toprated']),$_smarty_tpl);?>
</div><?php }?>
    
</div><?php }
}
