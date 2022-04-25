<?php
/* Smarty version 3.1.44, created on 2022-03-16 17:54:44
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/filter_simple.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_62320844f32ee1_49513835',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'aa7bf774860cfee0c4bc132755189d3186de9d1d' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/filter_simple.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62320844f32ee1_49513835 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['filter_items']->value) {?>
	<div id="filter_<?php echo $_smarty_tpl->tpl_vars['filter_id']->value;?>
" class="filter-group list-checkboxes list-collapse-mobile ajax">
		<h5 class="title"><?php echo $_smarty_tpl->tpl_vars['filter_title']->value;
if ($_smarty_tpl->tpl_vars['frontend']->value->filter[$_smarty_tpl->tpl_vars['filter_id']->value]) {?> <span class="clean" data-ftype="<?php echo $_smarty_tpl->tpl_vars['filter_id']->value;?>
"></span><?php }?></h5>
		<ul>
			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['filter_items']->value, 'filter_item');
$_smarty_tpl->tpl_vars['filter_item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['filter_item']->value) {
$_smarty_tpl->tpl_vars['filter_item']->do_else = false;
?>
				<li class="menu-item f<?php if (!$_smarty_tpl->tpl_vars['filter_item']->value['count']) {?> count0<?php }
if ($_smarty_tpl->tpl_vars['filter_item']->value['selected']) {?> active<?php }?>" data-ftype="<?php echo $_smarty_tpl->tpl_vars['filter_id']->value;?>
" data-fvalue="<?php echo $_smarty_tpl->tpl_vars['filter_item']->value['id'];?>
">
					<span class="check"></span>
					<span class="text"><?php echo $_smarty_tpl->tpl_vars['filter_item']->value['name'];?>
</span>
					<span class="count"><?php echo $_smarty_tpl->tpl_vars['filter_item']->value['count'];?>
</span>
				</li>
			<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
		</ul>
	</div>
<?php }
}
}
