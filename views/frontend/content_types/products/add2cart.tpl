<div id="products_add2cart">
	<form action="" method="post">
		<input type="hidden" name="state" value="add2cart" />
		<input type="hidden" name="item_id" value="{$selected_item.id}" />
		<input type="hidden" name="item_quantity" value="{$selected_item.quantity}">

		{if $element.modifications}
			<div class="modifications">
				<h4>{if $element.modification_field_name}{$element.modification_field_name}{else}{t('-modification-title-')}{/if}: {if $selected_modification}{$selected_modification.name}{else}{t('pasirinkite')}{/if}</h4>

				{if $element.modifications|count > 1}
					<div class="modification_selector">
						{foreach $element.modifications as $modification_id => $modification}
							{$item_url = (($selected_item_modifications[$modification_id]) ? "item=`$selected_item_modifications[$modification_id]`" : "modification=`$modification_id`")}

							<a href="?{$item_url}" class="modification {if $modification_id == $selected_modification.id}active{/if}" data-id="{$modification_id}" data-toggle="tooltip" title="{$modification.name}">
								{if $modification.photo}
									<img class="img-fluid" src="{$modification.photo}"/>
                                {else}

								{/if}
							</a>
						{/foreach}
					</div>
				{/if}
				{$h->descriptive_filters($selected_modification.filters)}
			</div>
		{/if}

		{$item_field_name = (($element.item_field_name!='') ? $element.item_field_name : t('-item-title-'))}
		{if $modification_items}
			<div class="items">
				{if !$selected_item || $selected_item.name}
					<h4>{$item_field_name}: {if $selected_item}{$selected_item.name}{else}{t('pasirinkite')}{/if}</h4>
				{/if}
				{if $modification_items|count > 1}
					<div class="item_selector">
						<select class="selectpicker" data-style="rounded-0 btn-outline-custom">
							<option value="">...</option>
							{foreach $modification_items as $item}
								<option value="{$item.id}" {if !$item.quantity}disabled="disabled"{/if} {if $selected_item.id == $item.id}selected="selected"{/if}>{$item.name} {if !$item.quantity}({t('Laikinai nėra')}){/if}</option>
							{/foreach}
						</select>
					</div>
				{/if}
				{$h->descriptive_filters($selected_item.filters)}
			</div>
		{/if}

		{if $price_info.quantity > 0}
			<div class="quantity">
				<h4>{t('Kiekis')}:</h4>
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
		{/if}

		{$h->show_messages('add2cart')}

		<div class="price_info">
			<div class="price {if $price_info.regular_price > $price_info.price}has_discount{/if}">
				{if $price_info.quantity <= 0}
					<div class='current_price'>{t('Nėra prekyboje')}</div>
				{else}
					<div class='current_price'>
						{if $price_info.price_differs}{t('Nuo')} {/if}{$price_info.price|fprice}
					</div>
					{if $price_info.regular_price > $price_info.price}
						<span class='old_price'>{$price_info.regular_price|fprice}</span>
					{/if}
				{/if}
			</div>
			{if $price_info.quantity > 0}
				<button id='add2cart_button' class="btn btn-primary btn-lg rounded-0"><span class="icon-cart"></span> {t('Į krepšelį')}</button>
			{/if}
		</div>

	</form>

	<script>
		$alert_message = '{sprintf(t('Nepasirinkta reikšmė: %s.'), strtolower($item_field_name))}';
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
							alert('{sprintf(t('Apgailestaujame, tačiau šiuo metu galima užsisakyti tik %s vnt. šios prekės.'), $selected_item.quantity)}');
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

				{if $h->get_setting('api/facebook_pixel/pixel_id')}
					if (typeof fbq == 'function') {
						fbq('track', 'AddToCart', {
							content_ids: ['{if $selected_item}{$selected_item.id}{else}{$first_item = reset($modification_items)}{$first_item.id}{/if}'],
							content_type: 'product',
							value: {if $price_info.regular_price > $price_info.price}{$price_info.regular_price}{else}{$price_info.price}{/if},
							currency: "{$frontend->project.currency}"
						});
					}
				{/if}

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
