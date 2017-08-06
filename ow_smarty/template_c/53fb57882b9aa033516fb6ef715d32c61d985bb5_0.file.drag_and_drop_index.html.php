<?php
/* Smarty version 3.1.31, created on 2017-08-06 02:53:32
  from "D:\xampp\htdocs\motoshub\ow_system_plugins\base\views\components\drag_and_drop_index.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5986e71cc90c95_44254068',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '53fb57882b9aa033516fb6ef715d32c61d985bb5' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_system_plugins\\base\\views\\components\\drag_and_drop_index.html',
      1 => 1463982332,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5986e71cc90c95_44254068 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_block_script')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\block.script.php';
if (!is_callable('smarty_block_style')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\block.style.php';
if (!is_callable('smarty_function_add_content')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.add_content.php';
if (!is_callable('smarty_block_block_decorator')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\block.block_decorator.php';
if (!is_callable('smarty_function_decorator')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.decorator.php';
$_smarty_tpl->smarty->_cache['_tag_stack'][] = array('script', array());
$_block_repeat=true;
echo smarty_block_script(array(), null, $_smarty_tpl, $_block_repeat);
while ($_block_repeat) {
ob_start();
?>

    DND_InterfaceFix.fix('.place_section');
<?php $_block_repeat=false;
echo smarty_block_script(array(), ob_get_clean(), $_smarty_tpl, $_block_repeat);
}
array_pop($_smarty_tpl->smarty->_cache['_tag_stack']);?>


<?php $_smarty_tpl->smarty->_cache['_tag_stack'][] = array('style', array());
$_block_repeat=true;
echo smarty_block_style(array(), null, $_smarty_tpl, $_block_repeat);
while ($_block_repeat) {
ob_start();
?>

    #place_components .component {
        float: left;
    }

    .configurable_component .ow_box_icons {
        float: right;
        padding-top: 6px;
    }

    .configurable_component h3 {
        float: left;
    }

<?php $_block_repeat=false;
echo smarty_block_style(array(), ob_get_clean(), $_smarty_tpl, $_block_repeat);
}
array_pop($_smarty_tpl->smarty->_cache['_tag_stack']);?>


<?php echo smarty_function_add_content(array('key'=>'base.widget_panel.content.top','placeName'=>$_smarty_tpl->tpl_vars['placeName']->value),$_smarty_tpl);?>

<?php echo smarty_function_add_content(array('key'=>'base.`$placeName`.content.top'),$_smarty_tpl);?>


<?php if ($_smarty_tpl->tpl_vars['allowCustomize']->value) {?>
    <?php $_smarty_tpl->smarty->_cache['_tag_stack'][] = array('block_decorator', array('name'=>'box','addClass'=>'ow_highbox ow_stdmargin index_customize_box','type'=>"empty"));
$_block_repeat=true;
echo smarty_block_block_decorator(array('name'=>'box','addClass'=>'ow_highbox ow_stdmargin index_customize_box','type'=>"empty"), null, $_smarty_tpl, $_block_repeat);
while ($_block_repeat) {
ob_start();
?>

            <div class="ow_center">
                <?php echo smarty_function_decorator(array('name'=>'button','langLabel'=>'base+widgets_customize_btn','class'=>'ow_ic_gear_wheel','id'=>"goto_customize_btn"),$_smarty_tpl);?>

            </div>
    <?php $_block_repeat=false;
echo smarty_block_block_decorator(array('name'=>'box','addClass'=>'ow_highbox ow_stdmargin index_customize_box','type'=>"empty"), ob_get_clean(), $_smarty_tpl, $_block_repeat);
}
array_pop($_smarty_tpl->smarty->_cache['_tag_stack']);?>

<?php }?>

<div class="ow_dragndrop_sections ow_stdmargin" id="place_sections">

    <div class="clearfix">
        <div class="ow_dragndrop_content">

            <div class="place_section">

                <?php if (isset($_smarty_tpl->tpl_vars['componentList']->value['section']['top'])) {?>
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['componentList']->value['section']['top'], 'component');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['component']->value) {
?>
                        <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['dd_component'][0][0]->tplComponent(array('uniqName'=>$_smarty_tpl->tpl_vars['component']->value['uniqName'],'render'=>true),$_smarty_tpl);?>

                    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

                <?php }?>

            </div>

            <div class="clearfix" style="overflow: hidden;">

                <div class="ow_left place_section <?php if (isset($_smarty_tpl->tpl_vars['activeScheme']->value['leftCssClass'])) {
echo $_smarty_tpl->tpl_vars['activeScheme']->value['leftCssClass'];
}?>">

                    <?php if (isset($_smarty_tpl->tpl_vars['componentList']->value['section']['left'])) {?>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['componentList']->value['section']['left'], 'component');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['component']->value) {
?>
                            <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['dd_component'][0][0]->tplComponent(array('uniqName'=>$_smarty_tpl->tpl_vars['component']->value['uniqName'],'render'=>true),$_smarty_tpl);?>

                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

                    <?php }?>

                </div>

                <div class="ow_right place_section <?php if (isset($_smarty_tpl->tpl_vars['activeScheme']->value['rightCssClass'])) {
echo $_smarty_tpl->tpl_vars['activeScheme']->value['rightCssClass'];
}?>" ow_scheme_class="<?php if (isset($_smarty_tpl->tpl_vars['activeScheme']->value['rightCssClass'])) {
echo $_smarty_tpl->tpl_vars['activeScheme']->value['rightCssClass'];
}?>"  ow_place_section="right">

                    <?php if (isset($_smarty_tpl->tpl_vars['componentList']->value['section']['right'])) {?>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['componentList']->value['section']['right'], 'component');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['component']->value) {
?>
                            <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['dd_component'][0][0]->tplComponent(array('uniqName'=>$_smarty_tpl->tpl_vars['component']->value['uniqName'],'render'=>true),$_smarty_tpl);?>

                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

                    <?php }?>

                </div>

             </div>

            <div class="place_section">

                <?php if (isset($_smarty_tpl->tpl_vars['componentList']->value['section']['bottom'])) {?>
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['componentList']->value['section']['bottom'], 'component');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['component']->value) {
?>
                        <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['dd_component'][0][0]->tplComponent(array('uniqName'=>$_smarty_tpl->tpl_vars['component']->value['uniqName'],'render'=>true),$_smarty_tpl);?>

                    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

                <?php }?>

            </div>

        </div>
        <?php echo smarty_function_add_content(array('key'=>'index.add_content_bottom'),$_smarty_tpl);?>

    </div>
</div>

<?php echo smarty_function_add_content(array('key'=>'base.widget_panel.content.bottom','placeName'=>$_smarty_tpl->tpl_vars['placeName']->value),$_smarty_tpl);?>

<?php echo smarty_function_add_content(array('key'=>'base.`$placeName`.content.bottom'),$_smarty_tpl);?>

<?php }
}
