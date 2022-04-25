<div id="content_layout" class="content_layout_{block name=layout_name}default{/block}">
	<div class="overlay"></div>
	{block name=head}
		<header>
			<div class="container-fluid">
				<div id="burger-icon" onclick="rotateMenuIcon(this)">
					<div class="menu-bar"></div>
					<div class="menu-bar"></div>
					<div class="menu-bar"></div>
				</div>

				<div id="logo">
					<a href="{$smarty.const.PROJECT_URL}">
						{*<img src="{$h->tr_image("`$smarty.const.PROJECT_URL`images/logo.png", "width=300&height=70&mode=resize")}" alt="{$config.engine.project_name}" />*}
						<img src="{$smarty.const.PROJECT_URL}images/logo.png" alt="{$config.engine.project_name}" />
					</a>
				</div>
				{$frontend->view('search/quick_search')}
				{$frontend->view('pages/languages_menu')}
			</div>
		</header>

		<nav class="nav-down">
			<div class="container-fluid">
				{$frontend->view('pages/mega_menu')}
			</div>
		</nav>
		<div id="head_placeholder"></div>
	{/block}

	<div id="content_wrapper">
		{block name=content}
			<div class="container-fluid">
				{$h->show_messages('frontend')}
				{$h->breadcrumb()}
			</div>
			<div class="content_body">
				<div class="container-fluid">
					<div class="row">
						{$context_menu = $frontend->view('pages/context_menu')}
						{if $context_menu}
							<div class="col-context_menu col-md-3">
								{$context_menu}
							</div>
						{/if}
						<div class="col-content {if $context_menu}col-md-9{else}col-12{/if}">
							{$frontend->display_content_type()}
						</div>
					</div>
				</div>
			</div>
		{/block}
	</div>
</div>

{$frontend->view('pages/footer')}

{block name=script}
<script>
	$(function () {
		$('#burger-icon').on('click', function () {
			$('body').toggleClass('main-nav-active');
			$('nav').toggleClass('nav-mobile');
		});
	})
</script>
{/block}