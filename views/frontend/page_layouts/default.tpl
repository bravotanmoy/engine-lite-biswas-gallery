<!DOCTYPE html>
<html>
{capture name="body"}
	{$h->include_file("frontend/content_layouts/`$content_layout`")}
{/capture}
<head>
	{block name=meta}
		<title>{if $frontend->page.meta_title}{$frontend->page.meta_title}{else}{$frontend->get_title()}{/if}</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="description" content="{$frontend->page.meta_description}"/>
		<meta name="keywords" content="{$frontend->page.meta_keywords}"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link rel="shortcut icon" href="{$smarty.const.PROJECT_URL}images/favicon.ico">
		{if $frontend->page.noindex OR $frontend->page.nofollow}
			<meta name="robots" content="{if $frontend->page.noindex}no{/if}index,{if $frontend->page.nofollow}no{/if}follow">
		{/if}
		{if $frontend->page.canonical}
			<link rel="canonical" href="{$frontend->page.canonical}" />
		{/if}
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta http-equiv="Content-Language" content="{$frontend->lang_key}"/>
	{/block}

	{block name=css}
		<link rel="preload" href="/public/fonts/stylesheet.css" as="style">
		<link rel="preload" href="/public/vendors/css/fancybox.min.css" as="style">
		{foreach from=$frontend->css|@array_unique item=stylesheet}
			<link rel="stylesheet" type="text/css" href="{$stylesheet}"/>
		{/foreach}
		<link rel="stylesheet" href="{$h->mix('frontend.css','frontend')}" />
		<link rel="stylesheet" href="{$h->mix('css/frontend.scss','vendors')}" />
	{/block}

	{block name=js}
		<script type="text/javascript" src="{$h->mix('frontend.js','vendors')}"></script>
		<script type="text/javascript" src="{$h->mix('frontend.js','frontend')}"></script>

		{foreach from=$frontend->js|@array_unique item=script}
			<script type="text/javascript" src="{$script}"></script>
		{/foreach}

	{/block}

	{block name=vendors}
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	{/block}
</head>
<body id="type_{$frontend->page.type}" {if $smarty.get.layout}class="layout_{$smarty.get.layout}"{/if}>
	{$smarty.capture.body}
	{block name=after_content}
		<div id="ajax_loader"></div>
		<div id="scrollup"><span class="icon icon-up"></span></div>
		{$frontend->view('popups/popup')}
	{/block}
</body>
</html>