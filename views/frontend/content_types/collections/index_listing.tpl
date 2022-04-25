{if $collections}
	<div id="collections_index_listing">
		{foreach $collections as $collection}
			<div class="collection">
				<div class="container-fluid text-center product-slider-container">
					<h2>{$collection.name}</h2>
					<div class="product_listing">
						{foreach $collection.items as $element}
							{$frontend->view('products/element', $element)}
						{/foreach}
					</div>
					{if $collection.full_url}
						<div class="view_all"><a class="btn btn-custom rounded-0"  href='{$collection.full_url}'>{t('Peržiūrėti viską')}</a></div>
					{/if}
					<div class="owl-nav owl-out-nav"></div>
				</div>
			</div>
		{/foreach}
	</div>
{/if}