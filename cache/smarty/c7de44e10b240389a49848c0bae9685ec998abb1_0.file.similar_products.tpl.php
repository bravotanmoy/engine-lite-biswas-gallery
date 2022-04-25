<?php
/* Smarty version 3.1.44, created on 2022-03-16 19:48:50
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/similar_products.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_62322302f02256_58207460',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c7de44e10b240389a49848c0bae9685ec998abb1' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/similar_products.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62322302f02256_58207460 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['similar_products']->value) {?>
	<div id="similar_products">
		<div class="container-fluid text-center product-slider-container">
			<h2 class="title"><?php echo t('Panašios prekės');?>
</h2>
			<div class="product_listing">
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['similar_products']->value, 'element');
$_smarty_tpl->tpl_vars['element']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['element']->value) {
$_smarty_tpl->tpl_vars['element']->do_else = false;
?>
					<?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('products/element',$_smarty_tpl->tpl_vars['element']->value);?>

				<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
			</div>
		</div>
	</div>
<?php }
}
}
