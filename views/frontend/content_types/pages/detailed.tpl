<div id='pages_detailed'>
	<h1 class='page_title'>{$frontend->get_title()}</h1>
	<div class="html_content">{$h->display_html($frontend->page.content)}</div>
	{$frontend->view('photos/listing', $frontend->page.id, 'pages', 0)}
</div>