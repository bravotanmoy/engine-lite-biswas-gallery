<div id="quick_search">
	<a href="" rel="nofollow" id="quick_search_show">
		<i class="icon icon-search"></i>
		{*<span class="hidden-xs title">{t('Ko ieškote?')}</span>*}
	</a>

	<form action="{$frontend->page_types.search.full_url}" method="get">
		<div class='input-group'>
			<input type="text" class="form-control" name="search" placeholder="{t('Paieška')}" />
			<span class="input-group-btn">
				<button type="submit"><i class="icon icon-search"></i></button>
				<button id="quick_search_hide" type="button"><i class="icon icon-close"></i></button>
			</span>
		</div>
	</form>

	<script>
		$(function() {
			$('#quick_search_show').click(function (e) {
				e.preventDefault();
				$('#quick_search').addClass('active');
				setTimeout(function() {
					$('#quick_search input').focus();
				}, 100);
			});
			$('#quick_search_hide').click(function (e) {
				e.preventDefault();
				$('#quick_search').removeClass('active');
			});
		});
	</script>
</div>