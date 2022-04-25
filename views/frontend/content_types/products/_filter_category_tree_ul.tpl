<ul>
	{foreach $items as $item}
		<li id="cat_{$item.id}" class="f{if $item.class}{$item.class}{/if}{if $checked[$item.id]} active{/if}{if $item.childs} parent{/if}" data-ftype="categories" data-fvalue="{$item.id}">
			<span class="check"></span><span class="text">{$item.name}</span><span class="arr"></span>
			{if $item.childs}
				{include $h->get_view_path('frontend/content_types/products/_filter_category_tree_ul.tpl') items=$item.childs}
			{/if}
		</li>
	{/foreach}
</ul>
