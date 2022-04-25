{assign var="page_path" value=$frontend->get_page_path()}
{foreach from=$page_path item="path_item" key="k"}
	{if $k} &gt; {/if}
	{$path_item.title}
{/foreach}