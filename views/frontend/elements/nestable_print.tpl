{if $hierarchy}
<ul>
	{foreach $hierarchy as $element}
		<li class="{if $element.full_url == $smarty.const.FULL_URL_TRUNC}active{/if}">
			<a href="{$element.full_url}">{$element.name}</a>	
			{if !empty($element.childs) AND is_array($element.childs)}
				{include file=$h->get_view_path('frontend/elements/nestable_print.tpl') hierarchy=$element.childs}
			{/if}
		</li>
	{/foreach}
</ul>
{/if}
		
	