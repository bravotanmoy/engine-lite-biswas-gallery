<?php
/* Smarty version 3.1.44, created on 2022-03-16 19:48:51
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/related_products.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_623223030803c2_05838394',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'cabda7337f9e1e4dfbda075142094420a5d78908' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/related_products.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_623223030803c2_05838394 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['related_products']->value) {?>
	<div id="similar_products">
		<div class="container-fluid text-center product-slider-container">
			<h2 class="title"><?php echo t('Tinkamos prekės');?>
</h2>
			<div class="product_listing">
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['related_products']->value, 'element');
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
