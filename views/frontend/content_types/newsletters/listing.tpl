<div id="news_listing">
	<h1 class='page_title'>{$frontend->get_title()}</h1>
	{$h->show_messages('newsletters')}
	<div class="html_content">{$h->display_html($frontend->page.content)}</div>
	<div class="news_list">
	{foreach $news.elements as $element}
		{$frontend->view('newsletters/element', $element)}
	{/foreach}
	</div>
	<div class="pagination-wrp">
		{$news.page_info.pages}
	</div>
</div>