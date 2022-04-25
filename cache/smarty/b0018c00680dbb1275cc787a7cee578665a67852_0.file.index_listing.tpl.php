<?php
/* Smarty version 3.1.44, created on 2022-03-16 17:46:33
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/collections/index_listing.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_623206599457e7_19429010',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b0018c00680dbb1275cc787a7cee578665a67852' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/collections/index_listing.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_623206599457e7_19429010 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['collections']->value) {?>
	<div id="collections_index_listing">
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['collections']->value, 'collection');
$_smarty_tpl->tpl_vars['collection']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['collection']->value) {
$_smarty_tpl->tpl_vars['collection']->do_else = false;
?>
			<div class="collection">
				<div class="container-fluid text-center product-slider-container">
					<h2><?php echo $_smarty_tpl->tpl_vars['collection']->value['name'];?>
</h2>
					<div class="product_listing">
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['collection']->value['items'], 'element');
$_smarty_tpl->tpl_vars['element']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['element']->value) {
$_smarty_tpl->tpl_vars['element']->do_else = false;
?>
							<?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('products/element',$_smarty_tpl->tpl_vars['element']->value);?>

						<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
					</div>
					<?php if ($_smarty_tpl->tpl_vars['collection']->value['full_url']) {?>
						<div class="view_all"><a class="btn btn-custom rounded-0"  href='<?php echo $_smarty_tpl->tpl_vars['collection']->value['full_url'];?>
'><?php echo t('Peržiūrėti viską');?>
</a></div>
					<?php }?>
					<div class="owl-nav owl-out-nav"></div>
				</div>
			</div>
		<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
	</div>
<?php }
}
}
