<div id="filter">
	<div id="filters_popup_header">
		<a class="float-right filter_close"><span class="icon icon-close-lg"></span></a>
		<div class="h1">{t('Filtrai')}</div>
	</div>
	<div id="filters_popup_footer">
		<div class="row">
			<div class="col-6">
				<button class="btn btn-primary btn-lg btn-block filter_close">{t('Gerai')}</button>
			</div>
			<div class="col-6">
				<button class="btn btn-outline-secondary btn-lg btn-block clean_all filter_close">{t('Išvalyti viską')}</button>
			</div>
		</div>
	</div>
	<div id="filter_block" class="clearfix hidden-filter">
		<div class="body">
			{$frontend->view('products/filter_category_tree')}
			{$frontend->view('products/filter_collections')}
			{$frontend->view('products/filter_prices')}
			{if !$frontend->brand}
				{$frontend->view('products/filter_brands')}
			{/if}
			{$frontend->view('products/filter_fmodule')}
		</div>
	</div>
</div>

<script>
	$(function(){
		$('#filter_summary button').tooltip();

		$('#filter_block .footer a').click(function(e){
			e.preventDefault();
		});

		productFilter.init({
			replaceTemplate: 'content_types/products/listing.tpl',
			replaceContainer: '#products_listing',
			filterContainer: '#filter',
			filterToggleButton: '#filter_on, .filter_close',
			filterMenu: '#filter_block',
			filterClearAllButton: '#products_listing .clean_all',
			filterClearButton: '#filter span.clean',
			filterRemoveButton: '#products_listing .remove_filter',
			filterSetButton: '#filter .ajax li.f'
		});

		$('#products_and_filters .sort_block select').change(function(e){
			e.preventDefault();
			productFilter.updateURI({ sort_by: $(this).val() });
			productFilter.reload();
		});

		$('.pagination a:not(.dots)').click(function(e) {
			e.preventDefault();
			productFilter.updateURI(this.href.replace(/^.*\?/,'?'));
			productFilter.reload();
			if ($('#products_listing').length) {
				$('html, body').animate({
					scrollTop: $("#products_listing").offset().top
				}, 'fast');
			}
		});

		$('#products_block a.ref_link').click(function(e){
			var hash = window.location.hash.replace(/^.*#[_]?/, '');
			var params = {
				hash:hash,
				title:'{$frontend->get_title()}',
				return_url:window.location.href,
				ref_id:$(this).data('ref-id')
			};
			var url = '{$frontend->page.full_url}?set_ref=1';
			$('#ajax_loader').fadeIn();
			$.ajax({
				url: url,
				data: params,
				success: function(data){
					// console.log(data);
				},
				async: false
			});
		});
	});
</script>