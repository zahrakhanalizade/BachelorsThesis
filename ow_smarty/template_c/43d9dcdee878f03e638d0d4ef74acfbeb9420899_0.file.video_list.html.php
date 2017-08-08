<?php
/* Smarty version 3.1.31, created on 2017-08-08 08:29:40
  from "D:\xampp\htdocs\motoshub\ow_plugins\video\views\components\video_list.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5989d8e417a6d1_43690561',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '43d9dcdee878f03e638d0d4ef74acfbeb9420899' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_plugins\\video\\views\\components\\video_list.html',
      1 => 1463982332,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5989d8e417a6d1_43690561 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_decorator')) require_once 'D:\\xampp\\htdocs\\motoshub\\ow_smarty\\plugin\\function.decorator.php';
?>

<?php if (!$_smarty_tpl->tpl_vars['no_content']->value) {?>

	<div class="ow_video_list ow_stdmargin clearfix">
	
	    <?php $_smarty_tpl->_assignInScope('alt1', true);
?>
	    <?php $_smarty_tpl->_assignInScope('cnt', 0);
?>
	
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['clips']->value, 'clip', false, NULL, 'c', array (
  'iteration' => true,
  'last' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['clip']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_c']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_c']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_c']->value['iteration'] == $_smarty_tpl->tpl_vars['__smarty_foreach_c']->value['total'];
?>
		
	        <?php if ($_smarty_tpl->tpl_vars['cnt']->value == $_smarty_tpl->tpl_vars['count']->value) {?>
	            <?php if ($_smarty_tpl->tpl_vars['alt1']->value) {
$_smarty_tpl->_assignInScope('alt1', false);
} else {
$_smarty_tpl->_assignInScope('alt1', true);
}?>
	            <?php $_smarty_tpl->_assignInScope('cnt', 0);
?>
	        <?php }?>
	        
	        <?php $_smarty_tpl->_assignInScope('cnt', $_smarty_tpl->tpl_vars['cnt']->value+1);
?>
		
	        <?php $_smarty_tpl->_assignInScope('userId', $_smarty_tpl->tpl_vars['clip']->value['userId']);
?>
	
	        <?php if ($_smarty_tpl->tpl_vars['cnt']->value == 1) {?>
	            <div class="clearfix <?php if ($_smarty_tpl->tpl_vars['alt1']->value) {?>ow_alt1<?php } else { ?>ow_alt2<?php }?>">
	        <?php }?> 
	                
		    <?php echo smarty_function_decorator(array('name'=>'video_list_item','data'=>$_smarty_tpl->tpl_vars['clip']->value,'listType'=>$_smarty_tpl->tpl_vars['listType']->value,'username'=>$_smarty_tpl->tpl_vars['usernames']->value[$_smarty_tpl->tpl_vars['userId']->value],'displayName'=>$_smarty_tpl->tpl_vars['displayNames']->value[$_smarty_tpl->tpl_vars['userId']->value]),$_smarty_tpl);?>

	        
            <?php if ($_smarty_tpl->tpl_vars['cnt']->value == $_smarty_tpl->tpl_vars['count']->value && (isset($_smarty_tpl->tpl_vars['__smarty_foreach_c']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_c']->value['iteration'] : null) != 1 || (isset($_smarty_tpl->tpl_vars['__smarty_foreach_c']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_c']->value['last'] : null)) {?>
                </div>
            <?php }?>
	        
	    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

	    
	</div>
	
    <?php echo $_smarty_tpl->tpl_vars['paging']->value;?>

    
<?php } else { ?>
    <div class="ow_nocontent"><?php echo $_smarty_tpl->tpl_vars['no_content']->value;?>
</div>
<?php }
}
}
