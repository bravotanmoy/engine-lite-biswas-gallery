<div id="brands_listing">
	<h1 class='page_title'>{$frontend->get_title()}</h1>
	<div class="html_content">{$h->display_html($frontend->page.content)}</div>
	
	{if $elements}
		<div class="row">
			{foreach $elements as $element}
				<div class="col col-sm-3 col-6">
					{if $element.full_url}<a href="{$element.full_url}">{/if}
					<img class="img-fluid center-block" src="{$h->tr_image($element.photo.src, "width=134&height=54&fill=1&bg_color=FFFFFF")}" />
					{if $element.full_url}</a>{/if}
				</div>
			{/foreach}
		</div>
	{/if}
</div>