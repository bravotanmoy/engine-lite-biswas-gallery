{extends $h->get_view_path('frontend/content_layouts/default.tpl')}
{block name=layout_name}index{/block}
{block name=content}
    <div class="content_body">
		<div class="container-fluid">{$h->show_messages('frontend')}</div>
        {$frontend->view('banners/hero')}
        {$frontend->view('banners/small')}
        {$frontend->view('collections/index_listing', 10)}
        {$frontend->view('brands/index_listing')}
	</div>
{/block}