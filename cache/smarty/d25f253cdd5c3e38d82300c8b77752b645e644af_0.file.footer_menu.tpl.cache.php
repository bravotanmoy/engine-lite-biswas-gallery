<?php
/* Smarty version 3.1.44, created on 2022-03-16 19:40:09
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/pages/footer_menu.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_623220f912fcd7_49351847',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd25f253cdd5c3e38d82300c8b77752b645e644af' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/pages/footer_menu.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_623220f912fcd7_49351847 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '1334976149623220f912c5b6_54259592';
?>
<div id="pages_footer_menu">
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['elements']->value, 'menu_group');
$_smarty_tpl->tpl_vars['menu_group']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['menu_group']->value) {
$_smarty_tpl->tpl_vars['menu_group']->do_else = false;
?>
        <?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('pages/footer_menu_list',$_smarty_tpl->tpl_vars['menu_group']->value);?>

    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</div><?php }
}
