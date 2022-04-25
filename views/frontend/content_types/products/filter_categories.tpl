<div id="categories_list" class="filter-group list-default list-collapse-mobile">
	<h5 class="title">{$title}</h5>
	<ul>
		{foreach from=$categories item="category_item"}
			<li class="{if $frontend->category && $category_item.id==$frontend->category.id} active{/if}">
				<a href="{$category_item.full_url}">
					<span class="name">{$category_item.name}</span>
				</a>
			</li>
		{/foreach}
	</ul>
	{if $back_category}
		<a class="ajax btn btn-outline-secondary btn-xs" href="{$back_category.full_url}"><i class="icon-left"></i> {$back_category.name}</a>
	{/if}
</div>
<script type="text/javascript">
	$('#categories_list a.ajax').click(function(e){
		e.preventDefault();
		var url = this.href;
		if (productFilter.filterHash != '') {
			//url += '?filter=' + productFilter.filterHash;
		}
		// pakeiciam adresa narsykles address-bar'e
		window.history.replaceState({},'',url);
		// uzkraunam nauja turini su ajax
		productFilter.reload(url);
		// isvalom visas senas filtru reiksmes
		productFilter.write([],false);
	});
</script>
		