{if isset($photos) && $photos|count > 0}
<div class="photos_listing">
	{if $title}
	<h2>{$title}</h2>
	{/if}
	{foreach from=$photos item="photo"}
		<a href="{$photo.src}" rel='fancybox' class="image_link" title="{$photo.name}"><img src="{$h->tr_image($photo.src,"width=400&height=300&mode=crop")}" alt="{$photo.name}"/></a>
	{/foreach}
</div>
{/if}