<div id="news_listing">
	<div class="html_content">{$h->display_html($frontend->page.content)}</div>
	<div class="news_list">
		{foreach $news.elements as $element}
			{$frontend->view('news/element', $element)}
		{/foreach}
	</div>
	<div class="pagination-wrp">
		{$news.page_info.pages}
	</div>
</div>