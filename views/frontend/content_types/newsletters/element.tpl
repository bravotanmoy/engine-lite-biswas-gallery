<div class="news_element">
	<a href="{$element.full_url}">
		{if $element.photo}
		<img class="img-fluid center-block" src="{$h->tr_image($element.photo.src, 'width=295&height=166&mode=crop&quality=90')}" />
		{else}
		<span class='placeholder'></span>
		{/if}
		<span class="content">
			<span class="category {strtolower($element.category)}">{$element.category}</span>
			<span class="date">{$element.date|substr:0:10}</span>
			<span class="name">{$element.name}</span>
		</span>
	</a>
</div>
