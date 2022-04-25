<div id="product_reviews">
	<div class="container-fluid sm">
		<h2 class="title">{t('Pirkėjų atsiliepimai')}</h2>

		<div class="reviews content">
			{foreach $reviews as $rev}
				<div class="review row review">
					<div class="col-sm-1">
						<span class="name">{$rev.name}</span>
					</div>
					<div class="col-sm-11">
						<div class="head_wrp clearfix">
							<div class="raiting float-left">
								<span class="stars"><span style="width:{$rev.raiting*22}px;">&nbsp;</span></span><br/>
							</div>
							<span class="date float-right">{$rev.date|substr:0:10}</span>
						</div>
	
						<p class="text_style">{$h->display_html($rev.review)}</p>
					</div>
				</div>
			{/foreach}
		</div>

		<div class="btn-wrp">
			<div id="lazy_loader"><span class="icon-spin fa-spin"></span></div>
			<a class="btn btn-outline-secondary" href="#">{t('Daugiau')}</a>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		var $page = 2;
		if($page > {$pages_count}){
			$('#product_reviews .btn').hide();
		}
		$('#product_reviews .btn').click(function(e){
			e.preventDefault();
			$('#product_reviews #lazy_loader').show();
			$('#product_reviews .btn').hide();
			
			$.get('{$smarty.const.FULL_URL_TRUNC}?display=content_types/products/product_reviews&page='+$page, function(data){
				$page++;
				$('#product_reviews .reviews').append($(data).find('.reviews .review'));
				$('#product_reviews #lazy_loader').hide();
				if($page <= {$pages_count}){
					$('#product_reviews .btn').show();
				}
				
			});
		});
	});
</script>