<ul id="menu_information" class="horizontal-desktop">
	{foreach $top_menu as $menu_item}
	<li>
		{if $menu_item.menu_items}
		<div class="list-dropdown list-collapse-mobile">
			<a class="title" href="{$menu_item.full_url}" >
				{$menu_item.name}
			</a>
			<ul>
				{foreach $menu_item.menu_items as $item}
				<li><a href="{$item.full_url}">{$item.name}</a></li>
				{/foreach}
			</ul>
		</div>
		{else}
			<a class="title" href="{$menu_item.full_url}">{$menu_item.name}</a>
		{/if}
	</li>
	{/foreach}
</ul>