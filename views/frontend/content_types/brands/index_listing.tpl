{if $frontend->page.type != 'brands' && !empty($elements)}
	<div id="brands_index_listing">
		<div class="brands">
			<div class="container-fluid text-center">
				<div class="brand_listing">
					{foreach $elements as $element}
						{if $element.photo.src}
							<div class="brand">
								{if $element.full_url}<a href="{$element.full_url}">{/if}
									<img class="img-fluid" src="{$h->tr_image($element.photo.src, "width=134&height=54&fill=1&bgcolor=")}" title="{$element.name}" />
									{if $element.full_url}</a>{/if}
							</div>
						{/if}
					{/foreach}
				</div>
				<div class="owl-nav owl-out-nav"></div>
			</div>
		</div>
	</div>
{/if}

<script>
	$(function() {
		$('#brands_index_listing .container-fluid').addClass('owl-container');
		$('#brands_index_listing .brand_listing').each(function () {
			// Add main classes
			$(this).addClass('owl-carousel');

			// Options
			$(this).owlCarousel({
				autoHeight: true,
				loop: false,
				autoplay: false,
				//autoplayTimeout: 5000,
				//autoplayHoverPause: true,
				nav: true,
				navText: [
					"<span class='icon icon-left-big'></span>",
					"<span class='icon icon-right-big'></span>",
				],
				navContainer: '#brands_index_listing .owl-out-nav',
				responsiveClass: true,
				slideBy: 'page',
				dots: false,
				responsive: {
					0: {
						items: 3
					},
					750: {
						items: 4
					},
					970: {
						items: 6
					},
					1170: {
						items: 6
					}
				}
			});
		});
	});
</script>