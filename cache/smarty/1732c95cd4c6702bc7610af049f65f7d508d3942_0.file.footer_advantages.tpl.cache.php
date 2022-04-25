<?php
/* Smarty version 3.1.44, created on 2022-03-16 19:40:09
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/pages/footer_advantages.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_623220f9117f63_85416419',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1732c95cd4c6702bc7610af049f65f7d508d3942' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/pages/footer_advantages.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_623220f9117f63_85416419 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '1258999625623220f91148c6_53076738';
if ($_smarty_tpl->tpl_vars['infoblocks']->value) {?>
	<div id="footer-advantages">
		<div class="container-fluid">
			<div class="row">
				<h2 class="text-center col-12"><?php echo t('Mes garantuojame');?>
</h2>
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['infoblocks']->value, 'infoblock');
$_smarty_tpl->tpl_vars['infoblock']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['infoblock']->value) {
$_smarty_tpl->tpl_vars['infoblock']->do_else = false;
?>
					<div class="col-6 col-md-3 text-center">
						<a href="<?php echo $_smarty_tpl->tpl_vars['infoblock']->value['url'];?>
" class="text-center">
							<p class="title"><?php echo $_smarty_tpl->tpl_vars['infoblock']->value['name'];?>
</p>
						</a>
					</div>
				<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
			</div>
		</div>
	</div>
<?php }
}
}
