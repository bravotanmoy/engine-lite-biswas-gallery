<?php
/* Smarty version 3.1.44, created on 2022-03-16 19:40:08
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/add2cart.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_623220f8f0f182_76125769',
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
  'cache_lifetime' => 3600,
),true)) {
function content_623220f8f0f182_76125769 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="products_add2cart">
	<form action="" method="post">
		<input type="hidden" name="state" value="add2cart" />
		<input type="hidden" name="item_id" value="242" />
		<input type="hidden" name="item_quantity" value="1">

					<div class="modifications">
				<h4>-modification-title-: MH 445</h4>

									<div class="modification_selector">
													
							<a href="?item=242" class="modification active" data-id="203" data-toggle="tooltip" title="MH 445">
																	<img class="img-fluid" src="https://gallery-api.engine.lt/api/gallery/catalog-image/203.jpg"/>
                                							</a>
													
							<a href="?modification=204" class="modification " data-id="204" data-toggle="tooltip" title="MH 445 R">
																	<img class="img-fluid" src="https://gallery-api.engine.lt/api/gallery/catalog-image/204.jpg"/>
                                							</a>
											</div>
								
			</div>
		
							<div class="items">
									<h4>-item-title-: MH 445</h4>
												
			</div>
		
					<div class="quantity">
				<h4>Kiekis:</h4>
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
		
		

		<div class="price_info">
			<div class="price ">
									<div class='current_price'>
						659 €
					</div>
												</div>
							<button id='add2cart_button' class="btn btn-primary btn-lg rounded-0"><span class="icon-cart"></span> Į krepšelį</button>
					</div>

	</form>

	<script>
		$alert_message = 'Nepasirinkta reikšmė: -item-title-.';
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
							alert('Apgailestaujame, tačiau šiuo metu galima užsisakyti tik 1 vnt. šios prekės.');
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
	</script>
</div>
<?php }
}
