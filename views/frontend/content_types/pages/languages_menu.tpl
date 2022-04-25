{if $languages}
	<ul id="languages_menu" class="d-none d-md-block">
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				<i class="icon-kalba icon"></i>
				<img src="{$smarty.const.PROJECT_URL}images/languages/{$frontend->lang_key|lower}.svg"/>
				<i class="icon-arrow_down arrow"></i>
			</a>

			<ul class="dropdown-menu" role="menu">
				{foreach $languages as $lang}
					<li class="dropdown-item"><a href="{$smarty.const.PROJECT_URL}{$lang.language|lower}">
							<img src="{$smarty.const.PROJECT_URL}images/languages/{$lang.language|lower}.svg"/>
						</a>
					</li>
				{/foreach}
			</ul>
		</li>
	</ul>

	<script>
		$(function () {
			let languages_menu = $('#languages_menu');

			// Add slideDown animation to Bootstrap dropdown when expanding.
			languages_menu.on('show.bs.dropdown', function() {
				$(this).find('.dropdown-menu').first().stop(true, true).slideDown();
			});

			// Add slideUp animation to Bootstrap dropdown when collapsing.
			languages_menu.on('hide.bs.dropdown', function() {
				$(this).find('.dropdown-menu').first().stop(true, true).slideUp();
			});
		})
	</script>
{/if}
