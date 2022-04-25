<div class="product_element {if !$element.quantity}no_stock{/if}">
	<a href="{$element.full_url}">
		{if $element.tags || $element.discount_percent}
			<span class="tags">
				{if $element.tags}
					{foreach $element.tags as $tag}
						<span class="tag tag-{$tag.url}" {if $tag.color}style="background-color: #{$tag.color}"{/if}>
							{if $tag.photo}
								<img src="{$tag.photo.src}" />
							{else}
								{$tag.name}
							{/if}
						</span>
					{/foreach}
				{/if}
				{if $element.discount_percent}
					<span class="tag tag-discount-percent">
						-{$element.discount_percent}%
					</span>
				{/if}
			</span>
		{/if}

		<span class="img-wrapper {if !$element.photo}placeholder-wrapper{/if}">
			<span class="img-bg">
				{if $element.photo}
 					<img src="{$element.photo}" class="img-fluid"/>
				{else}
					<span class="placeholder ratio-1-1"></span>
				{/if}
			</span>
		</span>

		<span class="title">
			<span class="brand_name">{$element.brand_name}</span>
			<span class="product_name">{$element.product_name}</span>
			<span class="modification_name">{$element.modification_name}</span>
			<span class="item_name">{$element.item_name}</span>
		</span>

		<span class="price_info">
			{if !$element.quantity}
				<span class="na">{t('Laikinai nėra')}</span>
			{else}
				<span class="price {if $element.price < $element.regular_price}discount{/if}">
					{if $element.price_differs}{t('nuo')}{/if}
				{$element.price|fprice}
				</span>
				{if $element.price < $element.regular_price}
				<span class="old_price">
						{$element.regular_price|fprice}
					</span>
			{/if}
			{/if}
		</span>
	</a>
</div>