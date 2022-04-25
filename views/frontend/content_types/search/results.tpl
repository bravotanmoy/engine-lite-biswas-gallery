<div id='search_results'>
	<h1 class='page_title'>{$frontend->get_title()}</h1>
	<div class="html_content">{$h->display_html($frontend->page.content)}</div>
	{if !$search.query}
		<div class="alert alert-warning">{t('Įveskite paieškos frazę.')}</div>
	{else}
		<div id="accordion">
			<div class="top_description">
				<h3>{t('Paieškos frazė:')} {$search.query}</h3>
			</div>
			{if $search.results}
				{foreach from=$search.results item="entity_results" key="entity_name"}
					<div class="{$entity_name}">
						<h3>
							{$search_entities.$entity_name} ({if $entity_name == 'products'}{$pages_info.total}{else}{$entity_results|@count}{/if})
						</h3>
						<div {if $entity_name=='product_items'}class="product_listing"{/if}>
							{foreach from=$entity_results item="result"}
								{capture assign="sr"}
									<div class="title"><a href="{$result.full_url}">{$result.name}</a></div>
									{if $result.description}<div class="description">{$h->truncate($result.description, 300)}</div>{/if}
									<div class="url"><a href="{$result.full_url}">{$result.full_url}</a></div>
								{/capture}
								{if $entity_name=='product_items'}
									{$frontend->view('products/element', $result)}
								{else}
									<div class="result">{$sr}</div>
								{/if}
							{/foreach}
						</div>
					</div>
				{/foreach}
			{else}
				<div class="alert alert-warning">{t('Nėra rezultatų pagal įvestą paieškos frazę.')}</div>
			{/if}
		</div>
	{/if}
</div>