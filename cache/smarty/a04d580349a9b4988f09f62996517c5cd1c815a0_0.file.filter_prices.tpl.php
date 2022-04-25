<?php
/* Smarty version 3.1.44, created on 2022-03-16 17:54:45
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/filter_prices.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_62320845000482_48566468',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a04d580349a9b4988f09f62996517c5cd1c815a0' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/filter_prices.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62320845000482_48566468 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['price_filter']->value) {?>
	<div id="filter_price" class="filter-group list-collapse-mobile ajax">
		<h5 class="title"><?php echo t('Kaina');
if ($_smarty_tpl->tpl_vars['frontend']->value->filter['prices']) {?> <span class="clean" data-ftype="price"></span><?php }?></h5>
		<div>
			<div id="price_slider"></div>
			<div id="price_slider_info" class="clearfix">
				<span class="val1">&nbsp;</span>
				<span class="val2">&nbsp;</span>
			</div>

            <?php ob_start();
echo $_smarty_tpl->tpl_vars['h']->value->mix('price_filter.js','vendors');
$_prefixVariable1 = ob_get_clean();
echo $_smarty_tpl->tpl_vars['h']->value->add_js($_prefixVariable1);?>

			<?php ob_start();
echo $_smarty_tpl->tpl_vars['h']->value->mix('css/price_filter.scss','vendors');
$_prefixVariable2 = ob_get_clean();
echo $_smarty_tpl->tpl_vars['h']->value->add_css($_prefixVariable2);?>

			<?php echo '<script'; ?>
 type="text/javascript">
				var price_map = <?php echo json_encode($_smarty_tpl->tpl_vars['price_filter']->value['all_prices']);?>
;
				var fprice_map = <?php echo json_encode($_smarty_tpl->tpl_vars['price_filter']->value['all_fprices']);?>
;
				$(function(){
					$("#price_slider").slider({
						range: true,
						min: 0, //<?php echo $_smarty_tpl->tpl_vars['price_filter']->value['from'];?>
,
						max: <?php echo count($_smarty_tpl->tpl_vars['price_filter']->value['all_prices'])-1;?>
,//<?php echo $_smarty_tpl->tpl_vars['price_filter']->value['to'];?>
,
						step: 1,
						values: [<?php echo $_smarty_tpl->tpl_vars['price_filter']->value['price1_flipped'];?>
,<?php echo $_smarty_tpl->tpl_vars['price_filter']->value['price2_flipped'];?>
],
						slide: function(event, ui ) {
							$( "#price_slider_info .val1" ).html(fprice_map[ui.values[0]]);
							$( "#price_slider_info .val2" ).html(fprice_map[ui.values[1]]);
						},
						change: function(event, ui) {
							if (ui.value==ui.values[0]) {
								// kaina nuo
								productFilter.set('price0', price_map[ui.value]);
							} else {
								// kaina iki
								productFilter.set('price1', price_map[ui.value]);
							}
						}
					});
					$( "#price_slider_info .val1" ).html(fprice_map[$( "#price_slider" ).slider("values",0)]);
					$( "#price_slider_info .val2" ).html(fprice_map[$( "#price_slider" ).slider("values",1)]);
				});
			<?php echo '</script'; ?>
>
		</div>
	</div>
<?php }
}
}
