<div id="context_menu">
	<div class="list-default list-collapse-mobile">
		<h3 class="title">{$parent.name}</h3>
		<ul>
			{foreach $childs as $child}
				<li><a href="{$child.full_url}" {if $child.id == $frontend->page.id}class="active"{/if}>{$child.name}</a></li>
			{/foreach}
			{if $parent.alias == 'account'}
				<li><a href="?logout">{t('Atsijungti')}</a></li>
			{/if}
		</ul>
	</div>
</div>