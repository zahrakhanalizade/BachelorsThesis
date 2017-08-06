<?php
/* Smarty version 3.1.31, created on 2017-08-06 02:53:33
  from "D:\xampp\htdocs\motoshub\ow_system_plugins\base\views\controllers\component_panel.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5986e71d02cf41_56118781',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '871dac69f3951d5eb179d8ff57d4f84c238abb73' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_system_plugins\\base\\views\\controllers\\component_panel.html',
      1 => 1463982332,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5986e71d02cf41_56118781 (Smarty_Internal_Template $_smarty_tpl) {
if (!empty($_smarty_tpl->tpl_vars['permissionMessage']->value)) {?>
    <div class="ow_anno ow_center">
        <?php echo $_smarty_tpl->tpl_vars['permissionMessage']->value;?>

    </div>
<?php } else { ?>
	<?php if (isset($_smarty_tpl->tpl_vars['profileActionToolbar']->value)) {?>
		<?php echo $_smarty_tpl->tpl_vars['profileActionToolbar']->value;?>

	<?php }?>

	<?php echo $_smarty_tpl->tpl_vars['componentPanel']->value;?>

<?php }
}
}
