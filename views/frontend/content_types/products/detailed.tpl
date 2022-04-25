<div id="products_detailed">
	<div class="product_block">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 d-md-none summary_wrp">
					{block name="summary_mobile"}
						<h1>{$frontend->get_title()}</h1>

						{if $element.tags || $element.discount_percent || $modification_tags}
							{if $modification_tags}
								{$element.tags = $modification_tags}
							{/if}

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
					{/block}
				</div>
				<div class="col-md-7 col-12 photos_wrp">
					{$frontend->view('products/photos')}
				</div>
				<div class="col-md-5 col-12">
					<div class="col-12 summary_wrp d-none d-md-block">
					{block name="summary"}
						<h1>{$frontend->get_title()}</h1>

						{if $element.tags || $element.discount_percent || $modification_tags}
							{if $modification_tags}
								{$element.tags = $modification_tags}
							{/if}

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
					{/block}
					</div>
					<div class="col-12 add2cart_wrp">
						{$frontend->view('products/add2cart')}
					</div>
				</div>
			</div>
		</div>
	</div>

	{block name="description"}
		<div class="container-fluid">
			<div class="product_detailed_description_wrp">
				{$h->display_html($element.description)}
			</div>
		</div>
	{/block}

	{$frontend->view('products/similar_products', $element)}
	{$frontend->view('products/related_products', $element)}
</div>