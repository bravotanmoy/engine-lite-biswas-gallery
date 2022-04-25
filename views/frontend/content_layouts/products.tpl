{extends $h->get_view_path('frontend/content_layouts/default.tpl')}
{block name=layout_name}products{/block}
{block name=content}
    <div class="container-fluid">
        {$h->show_messages('frontend')}
        {$h->breadcrumb()}
    </div>
    <div class="content_body">
        {$frontend->view($content_type)}
    </div>
{/block}