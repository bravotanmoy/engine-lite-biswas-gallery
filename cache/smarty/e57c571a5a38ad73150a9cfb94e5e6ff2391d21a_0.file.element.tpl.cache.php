<?php
/* Smarty version 3.1.44, created on 2022-03-16 19:40:09
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/element.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_623220f90a13e1_38235441',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e57c571a5a38ad73150a9cfb94e5e6ff2391d21a' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/element.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_623220f90a13e1_38235441 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '912548010623220f9081030_73947867';
?>
<div class="product_element <?php if (!$_smarty_tpl->tpl_vars['element']->value['quantity']) {?>no_stock<?php }?>">
	<a href="<?php echo $_smarty_tpl->tpl_vars['element']->value['full_url'];?>
">
		<?php if ($_smarty_tpl->tpl_vars['element']->value['tags'] || $_smarty_tpl->tpl_vars['element']->value['discount_percent']) {?>
			<span class="tags">
				<?php if ($_smarty_tpl->tpl_vars['element']->value['tags']) {?>
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['element']->value['tags'], 'tag');
$_smarty_tpl->tpl_vars['tag']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['tag']->value) {
$_smarty_tpl->tpl_vars['tag']->do_else = false;
?>
						<span class="tag tag-<?php echo $_smarty_tpl->tpl_vars['tag']->value['url'];?>
" <?php if ($_smarty_tpl->tpl_vars['tag']->value['color']) {?>style="background-color: #<?php echo $_smarty_tpl->tpl_vars['tag']->value['color'];?>
"<?php }?>>
							<?php if ($_smarty_tpl->tpl_vars['tag']->value['photo']) {?>
								<img src="<?php echo $_smarty_tpl->tpl_vars['tag']->value['photo']['src'];?>
" />
							<?php } else { ?>
								<?php echo $_smarty_tpl->tpl_vars['tag']->value['name'];?>

							<?php }?>
						</span>
					<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['element']->value['discount_percent']) {?>
					<span class="tag tag-discount-percent">
						-<?php echo $_smarty_tpl->tpl_vars['element']->value['discount_percent'];?>
%
					</span>
				<?php }?>
			</span>
		<?php }?>

		<span class="img-wrapper <?php if (!$_smarty_tpl->tpl_vars['element']->value['photo']) {?>placeholder-wrapper<?php }?>">
			<span class="img-bg">
				<?php if ($_smarty_tpl->tpl_vars['element']->value['photo']) {?>
 					<img src="<?php echo $_smarty_tpl->tpl_vars['element']->value['photo'];?>
" class="img-fluid"/>
				<?php } else { ?>
					<span class="placeholder ratio-1-1"></span>
				<?php }?>
			</span>
		</span>

		<span class="title">
			<span class="brand_name"><?php echo $_smarty_tpl->tpl_vars['element']->value['brand_name'];?>
</span>
			<span class="product_name"><?php echo $_smarty_tpl->tpl_vars['element']->value['product_name'];?>
</span>
			<span class="modification_name"><?php echo $_smarty_tpl->tpl_vars['element']->value['modification_name'];?>
</span>
			<span class="item_name"><?php echo $_smarty_tpl->tpl_vars['element']->value['item_name'];?>
</span>
		</span>

		<span class="price_info">
			<?php if (!$_smarty_tpl->tpl_vars['element']->value['quantity']) {?>
				<span class="na"><?php echo t('Laikinai nėra');?>
</span>
			<?php } else { ?>
				<span class="price <?php if ($_smarty_tpl->tpl_vars['element']->value['price'] < $_smarty_tpl->tpl_vars['element']->value['regular_price']) {?>discount<?php }?>">
					<?php if ($_smarty_tpl->tpl_vars['element']->value['price_differs']) {
echo t('nuo');
}?>
				<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'fprice' ][ 0 ], array( $_smarty_tpl->tpl_vars['element']->value['price'] ));?>

				</span>
				<?php if ($_smarty_tpl->tpl_vars['element']->value['price'] < $_smarty_tpl->tpl_vars['element']->value['regular_price']) {?>
				<span class="old_price">
						<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'fprice' ][ 0 ], array( $_smarty_tpl->tpl_vars['element']->value['regular_price'] ));?>

					</span>
			<?php }?>
			<?php }?>
		</span>
	</a>
</div><?php }
}
