<div id="product_path">
{assign var="page_path" value=$frontend->get_page_path()}
{foreach $page_path as $path_item}
	{if !$path_item@first}<span class="separator">/</span>{/if}
	
	{if $path_item.url}
		<a class="{if $path_item@last}last{/if}" href="{$path_item.url}">{$h->truncate($path_item.title, 50)}</a>
	{else}
		<span class="{if $path_item@last}last{/if}">{$h->truncate($path_item.title, 50)}</span>
	{/if}
	
{/foreach}
</div>