<?php
/* Smarty version 3.1.44, created on 2022-03-16 19:40:08
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/add2cart.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_623220f8efffd5_19388802',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '53fab1f304a8f34a86c4ceda2008d3ecf9d829ce' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/add2cart.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_623220f8efffd5_19388802 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '121952058623220f8ed32e3_03115357';
?>
<div id="products_add2cart">
	<form action="" method="post">
		<input type="hidden" name="state" value="add2cart" />
		<input type="hidden" name="item_id" value="<?php echo $_smarty_tpl->tpl_vars['selected_item']->value['id'];?>
" />
		<input type="hidden" name="item_quantity" value="<?php echo $_smarty_tpl->tpl_vars['selected_item']->value['quantity'];?>
">

		<?php if ($_smarty_tpl->tpl_vars['element']->value['modifications']) {?>
			<div class="modifications">
				<h4><?php if ($_smarty_tpl->tpl_vars['element']->value['modification_field_name']) {
echo $_smarty_tpl->tpl_vars['element']->value['modification_field_name'];
} else {
echo t('-modification-title-');
}?>: <?php if ($_smarty_tpl->tpl_vars['selected_modification']->value) {
echo $_smarty_tpl->tpl_vars['selected_modification']->value['name'];
} else {
echo t('pasirinkite');
}?></h4>

				<?php if (count($_smarty_tpl->tpl_vars['element']->value['modifications']) > 1) {?>
					<div class="modification_selector">
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['element']->value['modifications'], 'modification', false, 'modification_id');
$_smarty_tpl->tpl_vars['modification']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['modification_id']->value => $_smarty_tpl->tpl_vars['modification']->value) {
$_smarty_tpl->tpl_vars['modification']->do_else = false;
?>
							<?php $_smarty_tpl->_assignInScope('item_url', ($_smarty_tpl->tpl_vars['selected_item_modifications']->value[$_smarty_tpl->tpl_vars['modification_id']->value] ? "item=".((string)$_smarty_tpl->tpl_vars['selected_item_modifications']->value[$_smarty_tpl->tpl_vars['modification_id']->value]) : "modification=".((string)$_smarty_tpl->tpl_vars['modification_id']->value)));?>

							<a href="?<?php echo $_smarty_tpl->tpl_vars['item_url']->value;?>
" class="modification <?php if ($_smarty_tpl->tpl_vars['modification_id']->value == $_smarty_tpl->tpl_vars['selected_modification']->value['id']) {?>active<?php }?>" data-id="<?php echo $_smarty_tpl->tpl_vars['modification_id']->value;?>
" data-toggle="tooltip" title="<?php echo $_smarty_tpl->tpl_vars['modification']->value['name'];?>
">
								<?php if ($_smarty_tpl->tpl_vars['modification']->value['photo']) {?>
									<img class="img-fluid" src="<?php echo $_smarty_tpl->tpl_vars['modification']->value['photo'];?>
"/>
                                <?php } else { ?>

								<?php }?>
							</a>
						<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
					</div>
				<?php }?>
				<?php echo $_smarty_tpl->tpl_vars['h']->value->descriptive_filters($_smarty_tpl->tpl_vars['selected_modification']->value['filters']);?>

			</div>
		<?php }?>

		<?php $_smarty_tpl->_assignInScope('item_field_name', ($_smarty_tpl->tpl_vars['element']->value['item_field_name'] != '' ? $_smarty_tpl->tpl_vars['element']->value['item_field_name'] : t('-item-title-')));?>
		<?php if ($_smarty_tpl->tpl_vars['modification_items']->value) {?>
			<div class="items">
				<?php if (!$_smarty_tpl->tpl_vars['selected_item']->value || $_smarty_tpl->tpl_vars['selected_item']->value['name']) {?>
					<h4><?php echo $_smarty_tpl->tpl_vars['item_field_name']->value;?>
: <?php if ($_smarty_tpl->tpl_vars['selected_item']->value) {
echo $_smarty_tpl->tpl_vars['selected_item']->value['name'];
} else {
echo t('pasirinkite');
}?></h4>
				<?php }?>
				<?php if (count($_smarty_tpl->tpl_vars['modification_items']->value) > 1) {?>
					<div class="item_selector">
						<select class="selectpicker" data-style="rounded-0 btn-outline-custom">
							<option value="">...</option>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['modification_items']->value, 'item');
$_smarty_tpl->tpl_vars['item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->do_else = false;
?>
								<option value="<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
" <?php if (!$_smarty_tpl->tpl_vars['item']->value['quantity']) {?>disabled="disabled"<?php }?> <?php if ($_smarty_tpl->tpl_vars['selected_item']->value['id'] == $_smarty_tpl->tpl_vars['item']->value['id']) {?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
 <?php if (!$_smarty_tpl->tpl_vars['item']->value['quantity']) {?>(<?php echo t('Laikinai nėra');?>
)<?php }?></option>
							<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</select>
					</div>
				<?php }?>
				<?php echo $_smarty_tpl->tpl_vars['h']->value->descriptive_filters($_smarty_tpl->tpl_vars['selected_item']->value['filters']);?>

			</div>
		<?php }?>

		<?php if ($_smarty_tpl->tpl_vars['price_info']->value['quantity'] > 0) {?>
			<div class="quantity">
				<h4><?php echo t('Kiekis');?>
:</h4>
				<div class="quantity_selector">
					<div class="quantity_control input-group">
						<span class="input-group-btn">
							<button class="btn btn-outline-custom minus rounded-0 border-right-0" type="button"><span class="icon-minus"></span></button>
						</span>
						<input type="text" name="amount" class="form-control text-center rounded-0" value="1">
						<span class="input-group-btn">
							<button class="btn btn-outline-custom plus rounded-0 border-left-0" type="button"><span class="icon-plus"></span></button>
						</span>
					</div>
				</div>
			</div>
		<?php }?>

		<?php echo $_smarty_tpl->tpl_vars['h']->value->show_messages('add2cart');?>


		<div class="price_info">
			<div class="price <?php if ($_smarty_tpl->tpl_vars['price_info']->value['regular_price'] > $_smarty_tpl->tpl_vars['price_info']->value['price']) {?>has_discount<?php }?>">
				<?php if ($_smarty_tpl->tpl_vars['price_info']->value['quantity'] <= 0) {?>
					<div class='current_price'><?php echo t('Nėra prekyboje');?>
</div>
				<?php } else { ?>
					<div class='current_price'>
						<?php if ($_smarty_tpl->tpl_vars['price_info']->value['price_differs']) {
echo t('Nuo');?>
 <?php }
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'fprice' ][ 0 ], array( $_smarty_tpl->tpl_vars['price_info']->value['price'] ));?>

					</div>
					<?php if ($_smarty_tpl->tpl_vars['price_info']->value['regular_price'] > $_smarty_tpl->tpl_vars['price_info']->value['price']) {?>
						<span class='old_price'><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'fprice' ][ 0 ], array( $_smarty_tpl->tpl_vars['price_info']->value['regular_price'] ));?>
</span>
					<?php }?>
				<?php }?>
			</div>
			<?php if ($_smarty_tpl->tpl_vars['price_info']->value['quantity'] > 0) {?>
				<button id='add2cart_button' class="btn btn-primary btn-lg rounded-0"><span class="icon-cart"></span> <?php echo t('Į krepšelį');?>
</button>
			<?php }?>
		</div>

	</form>

	<?php echo '<script'; ?>
>
		$alert_message = '<?php echo sprintf(t('Nepasirinkta reikšmė: %s.'),strtolower($_smarty_tpl->tpl_vars['item_field_name']->value));?>
';
		$(document).ready(function(){
			$('#products_add2cart .item_selector select').change(function(){
				var url = '?item=' + $(this).val();
				if ($('#soundestInShop-toolbar').length > 0) {
					document.location = url;
				} else {
					ajaxnav(url, '#products_detailed', 'content_types/products/detailed');
				}
			});

			$('#products_add2cart .modification_selector a').click(function(e){
				if ($('#soundestInShop-toolbar').length == 0) {
					e.preventDefault();
					ajaxnav(this.href, '#products_detailed', 'content_types/products/detailed');
				}
			});

			var total_quantity = parseInt($('#products_add2cart input[name=item_quantity]').val());
			if (total_quantity) {
				var current_quantity = 1;
				$('#products_add2cart .quantity_selector button').on('click', function () {
					if ($(this).hasClass('plus')) {
						if (current_quantity === total_quantity) {
							alert('<?php echo sprintf(t('Apgailestaujame, tačiau šiuo metu galima užsisakyti tik %s vnt. šios prekės.'),$_smarty_tpl->tpl_vars['selected_item']->value['quantity']);?>
');
							$(this).prop('disabled', true);
						} else {
							current_quantity = current_quantity + 1;
						}
					} else if ($(this).hasClass('minus')) {
						$('#products_add2cart .quantity_selector button.plus').prop('disabled', false);

						if (current_quantity !== 1)
							current_quantity = current_quantity - 1;
					}
				});
			}

			$('#products_add2cart > form').submit(function(e){
				e.preventDefault();

				// validacija
				if (!this.item_id.value) {
					alert ($alert_message);
					if ($('.items .bootstrap-select > button').length) {
						$('.items .bootstrap-select > button').focus();
					}
					return false;
				}

				<?php if ($_smarty_tpl->tpl_vars['h']->value->get_setting('api/facebook_pixel/pixel_id')) {?>
					if (typeof fbq == 'function') {
						fbq('track', 'AddToCart', {
							content_ids: ['<?php if ($_smarty_tpl->tpl_vars['selected_item']->value) {
echo $_smarty_tpl->tpl_vars['selected_item']->value['id'];
} else {
$_smarty_tpl->_assignInScope('first_item', reset($_smarty_tpl->tpl_vars['modification_items']->value));
echo $_smarty_tpl->tpl_vars['first_item']->value['id'];
}?>'],
							content_type: 'product',
							value: <?php if ($_smarty_tpl->tpl_vars['price_info']->value['regular_price'] > $_smarty_tpl->tpl_vars['price_info']->value['price']) {
echo $_smarty_tpl->tpl_vars['price_info']->value['regular_price'];
} else {
echo $_smarty_tpl->tpl_vars['price_info']->value['price'];
}?>,
							currency: "<?php echo $_smarty_tpl->tpl_vars['frontend']->value->project['currency'];?>
"
						});
					}
				<?php }?>

				ajaxnav({
					url: document.location.href,
					data: $(this).serialize(),
					method: 'POST',
					container: '#products_add2cart',
					template: 'content_types/products/add2cart',
					callback: function() {
						ajaxnav('', '#cart_info', 'content_types/carts/cart_info', false);
					}
				});
			});
		});
	<?php echo '</script'; ?>
>
</div>
<?php }
}
