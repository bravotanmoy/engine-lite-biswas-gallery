<div id="news_detailed">
	<h1 class='page_title'>{$frontend->get_title()}</h1>
	<div class="info">
		<span class="category">{$element.category}</span>
		<span class="date">{$element.date|substr:0:10}</span>
	</div>
	{if $element.main_photo}
	<div class="main_photo">
	<img src="{$h->tr_image($element.main_photo.src, 'width=945&height=710')}"/>
	<span class="title">{$element.main_photo.name}</span>
	</div>
	{/if}
	<div class="text_style">
		{if $element.text|strip_tags}
			{$h->display_html($element.text)}
		{else}
			<p>{$element.description|nl2br}</p>
		{/if}
	</div>
	{$frontend->view('photos/listing', $element.id, 'news', 0)}
</div>