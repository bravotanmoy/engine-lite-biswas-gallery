{if $elements}
<div id='news_index_listing'>
	<div class="container-fluid">
		<h2>{if $page}{$page.name}{else}{t('Aktualijos')}{/if}</h2>
		<div class='news_list'>
		{foreach $elements as $element}
			{$frontend->view('news/element', $element)}
		{/foreach}
		</div>
		{if $page}
		<div class="view_all">
			<a class="btn btn-outline-secondary rounded-0" href='{$page.full_url}'>{t('Peržiūrėti viską')}</a>
		</div>
		{elseif $frontend->page_types.alias_news.full_url}
		<div class="view_all">
			<a class="btn btn-outline-secondary btn-sm rounded-0" href='{$frontend->page_types.alias_news.full_url}'>{t('Peržiūrėti viską')}</a>
		</div>
		{/if}
	</div>
</div>
{/if}