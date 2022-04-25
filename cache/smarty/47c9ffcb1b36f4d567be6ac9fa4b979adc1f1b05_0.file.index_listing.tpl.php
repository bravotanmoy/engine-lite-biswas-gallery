<?php
/* Smarty version 3.1.44, created on 2022-03-16 17:46:33
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/brands/index_listing.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_623206599a5299_52641294',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '47c9ffcb1b36f4d567be6ac9fa4b979adc1f1b05' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/brands/index_listing.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_623206599a5299_52641294 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['frontend']->value->page['type'] != 'brands' && !empty($_smarty_tpl->tpl_vars['elements']->value)) {?>
	<div id="brands_index_listing">
		<div class="brands">
			<div class="container-fluid text-center">
				<div class="brand_listing">
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['elements']->value, 'element');
$_smarty_tpl->tpl_vars['element']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['element']->value) {
$_smarty_tpl->tpl_vars['element']->do_else = false;
?>
						<?php if ($_smarty_tpl->tpl_vars['element']->value['photo']['src']) {?>
							<div class="brand">
								<?php if ($_smarty_tpl->tpl_vars['element']->value['full_url']) {?><a href="<?php echo $_smarty_tpl->tpl_vars['element']->value['full_url'];?>
"><?php }?>
									<img class="img-fluid" src="<?php echo $_smarty_tpl->tpl_vars['h']->value->tr_image($_smarty_tpl->tpl_vars['element']->value['photo']['src'],"width=134&height=54&fill=1&bgcolor=");?>
" title="<?php echo $_smarty_tpl->tpl_vars['element']->value['name'];?>
" />
									<?php if ($_smarty_tpl->tpl_vars['element']->value['full_url']) {?></a><?php }?>
							</div>
						<?php }?>
					<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				</div>
				<div class="owl-nav owl-out-nav"></div>
			</div>
		</div>
	</div>
<?php }?>

<?php echo '<script'; ?>
>
	$(function() {
		$('#brands_index_listing .container-fluid').addClass('owl-container');
		$('#brands_index_listing .brand_listing').each(function () {
			// Add main classes
			$(this).addClass('owl-carousel');

			// Options
			$(this).owlCarousel({
				autoHeight: true,
				loop: false,
				autoplay: false,
				//autoplayTimeout: 5000,
				//autoplayHoverPause: true,
				nav: true,
				navText: [
					"<span class='icon icon-left-big'></span>",
					"<span class='icon icon-right-big'></span>",
				],
				navContainer: '#brands_index_listing .owl-out-nav',
				responsiveClass: true,
				slideBy: 'page',
				dots: false,
				responsive: {
					0: {
						items: 3
					},
					750: {
						items: 4
					},
					970: {
						items: 6
					},
					1170: {
						items: 6
					}
				}
			});
		});
	});
<?php echo '</script'; ?>
><?php }
}
