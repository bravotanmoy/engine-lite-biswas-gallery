<?php
/* Smarty version 3.1.44, created on 2022-03-15 19:54:03
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/_filter_category_tree_ul.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_6230d2bba2dfd2_92062610',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c584125e2991434b21ce70455dc38254a7614614' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/_filter_category_tree_ul.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6230d2bba2dfd2_92062610 (Smarty_Internal_Template $_smarty_tpl) {
?><ul>
	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['items']->value, 'item');
$_smarty_tpl->tpl_vars['item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->do_else = false;
?>
		<li id="cat_<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
" class="f<?php if ($_smarty_tpl->tpl_vars['item']->value['class']) {
echo $_smarty_tpl->tpl_vars['item']->value['class'];
}
if ($_smarty_tpl->tpl_vars['checked']->value[$_smarty_tpl->tpl_vars['item']->value['id']]) {?> active<?php }
if ($_smarty_tpl->tpl_vars['item']->value['childs']) {?> parent<?php }?>" data-ftype="categories" data-fvalue="<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
">
			<span class="check"></span><span class="text"><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
</span><span class="arr"></span>
			<?php if ($_smarty_tpl->tpl_vars['item']->value['childs']) {?>
				<?php $_smarty_tpl->_subTemplateRender($_smarty_tpl->tpl_vars['h']->value->get_view_path('frontend/content_types/products/_filter_category_tree_ul.tpl'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('items'=>$_smarty_tpl->tpl_vars['item']->value['childs']), 0, true);
?>
			<?php }?>
		</li>
	<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</ul>
<?php }
}
