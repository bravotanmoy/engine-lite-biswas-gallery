<div id="products_listing">
	<div class="container-fluid">
		{block name="description"}
			<h1 class="page_title text-center">{$frontend->get_title()}</h1>
			<div class="html_content">{$h->display_html($frontend->page.content)}</div>
			{if $frontend->collection && $frontend->collection.description}
				<div class="description">{$h->display_html($frontend->collection.description)}</div>
			{elseif $frontend->subcategory && $frontend->subcategory.description}
				<div class="description">{$h->display_html($frontend->subcategory.description)}</div>
			{elseif $frontend->category && $frontend->category.description}
				<div class="description">{$h->display_html($frontend->category.description)}</div>
			{elseif $frontend->brand && $frontend->brand.description}
				<div class="description">{$h->display_html($frontend->brand.description)}</div>
			{/if}

			{if $frontend->category.photos_count}
				<div class="banner container-fluid">
					<img class="img-fluid center-block" src="{$frontend->category.photo.src}" />
				</div>
			{elseif $frontend->subcategory.photos_count}
				<div class="banner container-fluid">
					<img class="img-fluid center-block" src="{$frontend->subcategory.photo.src}" />
				</div>
			{/if}
		{/block}

		<div id="products_and_filters">
			{capture assign="filters_html"}
				{$frontend->view('products/filters', $elements)}
			{/capture}

			<div class="row">
				<div id="filters_column" class="col-12 col-sm-auto">
					{block name="filters"}
						{$filters_html}
					{/block}
				</div>
				<div id="products_column" class="col-12 col-sm-auto">
					{block name="listing"}
						<button id="filter_on" class="pf-toggle-menu btn btn-primary rounded-0">{t('Filtrai')} <span class="icon icon-filter"></span></button>
						<div class="sort_block">
							<select class="selectpicker" title="{t('Rikiuoti pagal')}" data-style="rounded-0 btn-outline-custom">
								{foreach from=$entity_config.sort_options item="v" key="k"}
									<option title="{t('Rikiuoti pagal')}" value="{$k}" {if $entity_config.sort_by==$k}selected{/if}>{$v}</option>
								{/foreach}
							</select>
						</div>
						{block name="filter_summary"}
							{strip}
								<div id="filter_summary" class="clearfix">
									{if !empty($frontend->filter)}
										<button class="btn btn-outline-secondary btn-xs filter-btn clean_all" title="{t('Išvalyti visus pasirinktus filtrus')}"></button>
									{/if}
									{foreach from=$frontend->filter key="k1" item="v1"}
										{if $k1=='prices'}
											{if $v1.0!==null}
												<button class="btn btn-outline-secondary btn-xs remove_filter filter-btn" title="{t('Išvalyti minimalios kainos filtrą')}" data-ftype="price0" data-fvalue="{$v1.0}">
													{t('Nuo')} {$v1.0|fprice}</span>
												</button>
											{/if}

											{if $v1.1!==null}
												<button class="btn btn-outline-secondary btn-xs remove_filter filter-btn" title="{t('Išvalyti maksimalios kainos filtrą')}" data-ftype="price1" data-fvalue="{$v1.1}">
													{t('Iki')} {$v1.1|fprice}</span>
												</button>
											{/if}
										{else}
											{foreach $v1 as $k2 => $v2}
												{if $k1 == 'fmod'}
													{foreach $v2 as $k3 => $v3}
														<button class="btn btn-outline-secondary btn-xs remove_filter filter-btn" title="{sprintf(t('Išvalyti filtrą: %s'), $frontend->filter_info["fmod_{$k2}"].options.$k3.title)}" data-ftype="{$k1}_{$k2}" data-fvalue="{$k3}">
															{$frontend->filter_info["fmod_{$k2}"].options.$k3.title}</span>
														</button>
													{/foreach}
												{else}
													<button class="btn btn-outline-secondary btn-xs remove_filter filter-btn" title="{sprintf(t('Išvalyti filtrą: %s'), $frontend->filter_info.$k1.options.$k2.title)}" data-ftype="{$k1}" data-fvalue="{$k2}">
														{$frontend->filter_info.$k1.options.$k2.title}</span>
													</button>
												{/if}
											{/foreach}
										{/if}
									{/foreach}
								</div>
							{/strip}
						{/block}
						{if $elements}
							<div class="product_listing">
								<div class="clearfix">
									{foreach $elements as $element}
										{$frontend->view('products/element', $element)}
										{if $element@iteration % 2 == 0}
											<div class="clearfix-xs"></div>
										{/if}
										{if $element@iteration % 3 == 0}
											<div class="clearfix-sm"></div>
										{/if}
										{if $element@iteration % 4 == 0}
											<div class="clearfix-md clearfix-lg"></div>
										{/if}
									{/foreach}
								</div>
							</div>
							<div class="pagination-wrp">
								{if count($entity_config['available_page_sizes']) > 1}
									<div class="page_sizes">
										{t('Rodyti po')}:
										{foreach from=$entity_config['available_page_sizes'] item="size"}
											<a {if $size==$page_size}class="active"{/if} data-ajaxnav="true" data-ajaxnav-template="content_types/products/listing" data-ajaxnav-container="#products_listing" href="?filter={$smarty.get.filter|urlencode}&page_size={$size}">{$size}</a>
										{/foreach}
									</div>
								{/if}
								{if $lazy_load || ($elements_info.page_info.pages_count > 1)}
									<div class="lazy">
										{if !$lazy_load}
											<a data-ajaxnav="true" data-ajaxnav-template="content_types/products/listing" data-ajaxnav-container="#products_listing" href="?filter={$smarty.get.filter|urlencode}&page_size=0">{t('Rodyti viską')}</a>
										{else}
											<a id="lazy_load_off" data-ajaxnav="true" data-ajaxnav-template="content_types/products/listing" data-ajaxnav-container="#products_listing" href="?filter={$smarty.get.filter|urlencode}&page_size={$default_page_size}">{t('Rodyti puslapius')}</a>
										{/if}
									</div>
								{/if}
								{if !$lazy_load}
									<div class="pages">{$elements_info.pages}</div>
								{/if}
							</div>
						{else}
							<div class="product_listing">
								{if $frontend->filter}
									<div class="alert alert-warning">{t('Apgailestaujame, tačiau šioje kategorijeje nėra prekių, atitinkančių pasirinktus filtro kriterijus.')}</div>
								{else}
									<div class="alert alert-warning">{t('Apgailestaujame, tačiau prekių šioje kategorijoje nėra.')}</div>
								{/if}
							</div>
						{/if}
						<div id="lazy_loader"><span class="icon-spin fa-spin"></span></div>
					{/block}
				</div>
			</div>
		</div>

	</div>
	{block name="scripts"}
		<script>
			{if $elements}
			var products_listing_filter = '{$smarty.get.filter}';
			var page = {$elements_info.page_info.page} + 1;
			var max_page = {$elements_info.page_info.pages_count};
			{/if}

			var dolazy = {if $lazy_load}true{else}false{/if};
			(function(){
				var xhr;

				$(function(){
					lazyload();
				});

				$(window).resize(function(){
					lazyload();
				});

				$(window).scroll(function(){
					lazyload()
				});

				$(document).keyup(function(e) {
					if (e.keyCode == 27) { // escape key maps to keycode `27`
						console.log('escape');
						if(xhr && xhr.readyState != 4){
							$('#lazy_loader').hide();
							xhr.abort();
						}
					}
				});

				function lazyload() {
					var full_height = $(document).height();
					var window_height = $(window).height();
					var scrollTop = $(window).scrollTop();
					if ( max_page >= page && dolazy && scrollTop + window_height >= full_height - 500 ) {
						dolazy = false;
						$('#lazy_loader').show();
						xhr = $.ajax({
							url : '?&page='+page+'&display=content_types/products/listing.tpl&filter='+encodeURIComponent(products_listing_filter),
							type : 'post',
							success : function(data) {
								page++;
								$('#lazy_loader').hide();
								var a = $(data).find('.product_listing');
								$('.product_listing').append( a.html() );
								dolazy = true;
							}
						});
					}
				}
			})();
		</script>
	{/block}
</div>