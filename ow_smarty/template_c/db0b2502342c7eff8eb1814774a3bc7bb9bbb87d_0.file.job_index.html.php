<?php
/* Smarty version 3.1.31, created on 2017-08-08 08:00:10
  from "D:\xampp\htdocs\motoshub\ow_plugins\jobsearch\views\controllers\job_index.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5989d1fa45fb97_49146886',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'db0b2502342c7eff8eb1814774a3bc7bb9bbb87d' => 
    array (
      0 => 'D:\\xampp\\htdocs\\motoshub\\ow_plugins\\jobsearch\\views\\controllers\\job_index.html',
      1 => 1502193175,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5989d1fa45fb97_49146886 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->tpl_vars['i'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);
$_smarty_tpl->tpl_vars['i']->value = 0;
if ($_smarty_tpl->tpl_vars['i']->value < $_smarty_tpl->tpl_vars['count']->value) {
for ($_foo=true;$_smarty_tpl->tpl_vars['i']->value < $_smarty_tpl->tpl_vars['count']->value; $_smarty_tpl->tpl_vars['i']->value++) {
?>

<div class="ad-div right-ad-div">
    <img src="http://localhost/eceshub/ow_userfiles/plugins/jobads/girls-1000.png" class="ad-image">
    <label class="right-margin"><?php echo $_smarty_tpl->tpl_vars['allAds']->value[$_smarty_tpl->tpl_vars['i']->value]->description;?>
</label>
    <br>
    <br>
    <label class="right-margin">توانایی ها:</label>
    <br>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['allAds']->value[$_smarty_tpl->tpl_vars['i']->value]->skills, 'item');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
?>
    <div class="skills-div">
        <?php echo $_smarty_tpl->tpl_vars['item']->value;?>
</div>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

    <br>
    <?php if ($_smarty_tpl->tpl_vars['allAds']->value[$_smarty_tpl->tpl_vars['i']->value]->isOwner == true) {?>
    <a href="/eceshub/jobads/deletead/<?php echo $_smarty_tpl->tpl_vars['allAds']->value[$_smarty_tpl->tpl_vars['i']->value]->id;?>
">
        <div class="delete-ad">حذف</div>
    </a>
    <?php }?>
    <a href="http://localhost/eceshub/jobads/ad/<?php echo $_smarty_tpl->tpl_vars['allAds']->value[$_smarty_tpl->tpl_vars['i']->value]->id;?>
">
        <div class="more-info">اطلاعات بیشتر</div>
    </a>
</div>

<?php }
}
}
}
