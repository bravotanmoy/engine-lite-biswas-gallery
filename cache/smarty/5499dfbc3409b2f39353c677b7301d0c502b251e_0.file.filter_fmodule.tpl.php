<?php
/* Smarty version 3.1.44, created on 2022-03-16 17:54:45
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/filter_fmodule.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_62320845337e84_36431312',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5499dfbc3409b2f39353c677b7301d0c502b251e' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/filter_fmodule.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62320845337e84_36431312 (Smarty_Internal_Template $_smarty_tpl) {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['filter_data']->value, 'filter');
$_smarty_tpl->tpl_vars['filter']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['filter']->value) {
$_smarty_tpl->tpl_vars['filter']->do_else = false;
?>

	<?php if ($_smarty_tpl->tpl_vars['filter']->value['filter_items']) {?>
		<div id="filter_<?php echo $_smarty_tpl->tpl_vars['filter']->value['filter_id'];?>
" class="filter-group list-checkboxes list-collapse-mobile ajax">
			<h5 class="title"><?php echo $_smarty_tpl->tpl_vars['filter']->value['name'];
if ($_smarty_tpl->tpl_vars['frontend']->value->filter['fmod'][$_smarty_tpl->tpl_vars['filter']->value['id']]) {?> <span class="clean" data-ftype="<?php echo $_smarty_tpl->tpl_vars['filter']->value['filter_id'];?>
"></span><?php }?></h5>
			<ul>
				<?php if ($_smarty_tpl->tpl_vars['filter']->value['type'] == "color") {?>
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['filter']->value['filter_items'], 'filter_item');
$_smarty_tpl->tpl_vars['filter_item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['filter_item']->value) {
$_smarty_tpl->tpl_vars['filter_item']->do_else = false;
?>
						<li class="color-item f<?php if (!$_smarty_tpl->tpl_vars['filter_item']->value['count']) {?> count0<?php }
if ($_smarty_tpl->tpl_vars['filter_item']->value['selected']) {?> active<?php }?>" data-ftype="<?php echo $_smarty_tpl->tpl_vars['filter']->value['filter_id'];?>
" data-fvalue="<?php echo $_smarty_tpl->tpl_vars['filter_item']->value['id'];?>
" style="background-color:#<?php echo $_smarty_tpl->tpl_vars['filter_item']->value['color'];?>
">
						</li>
					<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				<?php } else { ?>
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['filter']->value['filter_items'], 'filter_item');
$_smarty_tpl->tpl_vars['filter_item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['filter_item']->value) {
$_smarty_tpl->tpl_vars['filter_item']->do_else = false;
?>
						<li class="menu-item f<?php if (!$_smarty_tpl->tpl_vars['filter_item']->value['count']) {?> count0<?php }
if ($_smarty_tpl->tpl_vars['filter_item']->value['selected']) {?> active<?php }?>" data-ftype="<?php echo $_smarty_tpl->tpl_vars['filter']->value['filter_id'];?>
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
				<?php }?>
			</ul>
		</div>
	<?php }
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}
}
