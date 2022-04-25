{foreach $filter_data as $filter}

	{if $filter.filter_items}
		<div id="filter_{$filter.filter_id}" class="filter-group list-checkboxes list-collapse-mobile ajax">
			<h5 class="title">{$filter.name}{if $frontend->filter.fmod[$filter.id]} <span class="clean" data-ftype="{$filter.filter_id}"></span>{/if}</h5>
			<ul>
				{if $filter.type == "color"}
					{foreach from=$filter.filter_items item="filter_item"}
						<li class="color-item f{if !$filter_item.count} count0{/if}{if $filter_item.selected} active{/if}" data-ftype="{$filter.filter_id}" data-fvalue="{$filter_item.id}" style="background-color:#{$filter_item.color}">
						</li>
					{/foreach}
				{else}
					{foreach from=$filter.filter_items item="filter_item"}
						<li class="menu-item f{if !$filter_item.count} count0{/if}{if $filter_item.selected} active{/if}" data-ftype="{$filter.filter_id}" data-fvalue="{$filter_item.id}">
							<span class="check"></span>
							<span class="text">{$filter_item.name}</span>
							<span class="count">{$filter_item.count}</span>
						</li>
					{/foreach}
				{/if}
			</ul>
		</div>
	{/if}
{/foreach}