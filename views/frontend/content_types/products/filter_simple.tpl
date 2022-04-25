{if $filter_items}
	<div id="filter_{$filter_id}" class="filter-group list-checkboxes list-collapse-mobile ajax">
		<h5 class="title">{$filter_title}{if $frontend->filter.$filter_id} <span class="clean" data-ftype="{$filter_id}"></span>{/if}</h5>
		<ul>
			{foreach from=$filter_items item="filter_item"}
				<li class="menu-item f{if !$filter_item.count} count0{/if}{if $filter_item.selected} active{/if}" data-ftype="{$filter_id}" data-fvalue="{$filter_item.id}">
					<span class="check"></span>
					<span class="text">{$filter_item.name}</span>
					<span class="count">{$filter_item.count}</span>
				</li>
			{/foreach}
		</ul>
	</div>
{/if}