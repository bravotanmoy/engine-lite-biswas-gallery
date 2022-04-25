{if $elements}
	<div id="banners_small">
		<div class="container-fluid">
			<div id="banners-small-slider">
				{$visible_mobile_count = 0}
				{foreach $elements as $key => $element}
					{if $element.visible_mobile}
						{$visible_mobile_count = $visible_mobile_count+1}
					{/if}
					<div class="banner{if $element.visible_mobile} mobile{/if}{if $element.visible_desktop} desktop{/if}{if !$element.visible_desktop} d-md-none{elseif !$element.visible_mobile} d-none d-md-block{/if}{if $visible_mobile_count==1 && $element.visible_mobile} active{/if}">
						<div class="zoom">
							{if $element.link}
							<a href="{$element.link}" {if $element.blank}target="_blank"{/if}>
								{/if}
								<div class="banner-image">
									{$h->picture($element.photo, "width=590&height=394&fill=1&bg_color=FFFFFF&mode=resize&quality=90", "{$element.photo.name}")}
								</div>
								{if !$element.hide_text}
									<div class="banner-title">
										{strip}
											<p class="title">{$element.name}</p>
											<p class="desc">{$element.description|nl2br}</p>
											{if $element.link}<button class="btn btn-custom rounded-0" href="{$element.link}">{t('Plačiau')}</button>{/if}
										{/strip}
									</div>
								{/if}
								{if $element.link}</a>{/if}
						</div>
					</div>
				{/foreach}
				<div class="owl-nav owl-out-nav"></div>
			</div>
		</div>
		{if false && $visible_mobile_count>1}
			<div id="banners_small_navigation">
				<div class="prev"><span class="icon icon-left"></span></div>
				<div class="next"><span class="icon icon-right"></span></div>
			</div>
			<script>
				$(function() {
					$('#banners_small_navigation > div').click(function(){
						var $banners = $('#banners_small div.banner.mobile');
						var $current = $banners.filter('.active');
						var index = $banners.index($current);
						var $prev = $banners.eq(index >= 1 ? index-1 : -1);
						var $next = $banners.eq(index < $banners.length-1 ? index+1 : 0);
						if ($(this).is('.next')) {
							$next.addClass('animating').css({ left:'100%', 'z-index':3 }).animate({ left:0, 'z-index':2 }).addClass('active').removeClass('animating');
							$current.addClass('animating').css({ left:0, 'z-index':3 }).animate({ left:'-100%', 'z-index':1 }).removeClass('active').removeClass('animating');
						} else if ($(this).is('.prev')) {
							$prev.addClass('animating').css({ left:'-100%', 'z-index':3 }).animate({ left:0, 'z-index':2 }).addClass('active').removeClass('animating');
							$current.addClass('animating').css({ left:0, 'z-index':3 }).animate({ left:'100%', 'z-index':1 }).removeClass('active').removeClass('animating');
						}
					});
				});
			</script>
		{/if}
		<script>
			$(function(){
				$('#banners-small-slider').each(function () {
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
						responsiveClass: true,
						navContainer: '#banners_small .owl-out-nav',
						slideBy: 'page',
						dots: false,
						responsive: {
							0: {
								items: 1
							},
							750: {
								items: 2
							},
							970: {
								items: 2
							},
							1170: {
								items: 2
							}
						}
					});
				});
			});
		</script>
	</div>
{/if}
