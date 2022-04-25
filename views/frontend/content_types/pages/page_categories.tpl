{if $hierarchy.childs}
<div class="col col-md-3">
	<ul id="text_page_menu">
		<li class="title"><h5>{$hierarchy.name}</h5></li>
		{foreach $hierarchy.childs as $child}
			<li><a class="{if $child.full_url == $smarty.const.FULL_URL}active{/if}" href="{$child.full_url}">{$child.name}</a></li>
		{/foreach}
	</ul>
</div>
{/if}