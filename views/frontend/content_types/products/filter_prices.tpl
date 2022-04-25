{if $price_filter}
	<div id="filter_price" class="filter-group list-collapse-mobile ajax">
		<h5 class="title">{t('Kaina')}{if $frontend->filter.prices} <span class="clean" data-ftype="price"></span>{/if}</h5>
		<div>
			<div id="price_slider"></div>
			<div id="price_slider_info" class="clearfix">
				<span class="val1">&nbsp;</span>
				<span class="val2">&nbsp;</span>
			</div>

            {$h->add_js({$h->mix('price_filter.js','vendors')})}
			{$h->add_css({$h->mix('css/price_filter.scss','vendors')})}
			<script type="text/javascript">
				var price_map = {$price_filter.all_prices|@json_encode};
				var fprice_map = {$price_filter.all_fprices|@json_encode};
				$(function(){
					$("#price_slider").slider({
						range: true,
						min: 0, //{$price_filter.from},
						max: {$price_filter.all_prices|@count-1},//{$price_filter.to},
						step: 1,
						values: [{$price_filter.price1_flipped},{$price_filter.price2_flipped}],
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
			</script>
		</div>
	</div>
{/if}