{if $parent && $childs}
<div class="list-default list-collapse-mobile">
	<h3 class="title">{$parent.name}</h3>
	<ul>
	{foreach $childs as $child}
		{if $child.alias && preg_match('/^icon-/', $child.alias)}
			{$icon = $child.alias}
		{else}
			{$icon = false}
		{/if}
		<li {if $icon}class='icon {$icon}'{/if}><a href="{$child.full_url}">{$child.name}</a></li>
	{/foreach}
	</ul>
</div>
{/if}