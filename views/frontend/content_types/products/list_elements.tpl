{if $elements}
	<div class="product_listing">
		{foreach $elements as $element}
			{$frontend->view('products/element', $element)}
		{/foreach}
	</div>
{/if}