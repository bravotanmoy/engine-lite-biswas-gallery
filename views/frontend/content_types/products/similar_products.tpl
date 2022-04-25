{if $similar_products}
	<div id="similar_products">
		<div class="container-fluid text-center product-slider-container">
			<h2 class="title">{t('Panašios prekės')}</h2>
			<div class="product_listing">
				{foreach $similar_products as $element}
					{$frontend->view('products/element', $element)}
				{/foreach}
			</div>
		</div>
	</div>
{/if}