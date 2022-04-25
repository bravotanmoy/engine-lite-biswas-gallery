{extends $h->get_view_path('frontend/content_layouts/default.tpl')}
{block name=layout_name}plain{/block}
{block name=content}
    <div class="content_body">
        <div class="container-fluid">
            {$h->show_messages('frontend')}
            {$frontend->display_content_type()}
        </div>
    </div>
{/block}