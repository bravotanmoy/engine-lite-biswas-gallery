{if $smarty.session.customer.registered}
	<div id="profile_menu" class="dropdown d-none  d-md-block">
		<a class="title" data-toggle="dropdown">
			<i class="icon icon-profile"></i>
			<span class="title dropdown-toggle">
				{if $smarty.session.customer.first_name}
					{$smarty.session.customer.first_name}
				{else}
					{$smarty.session.customer.email}
				{/if}
			</span>
		</a>

		<ul class="dropdown-menu">
			<li class="dropdown-item"><a href="{$frontend->page_types.orders.full_url}">{t('Mano užsakymai')}</a></li>
			<li class="dropdown-item"><a href="{$frontend->page_types.customers.full_url}">{t('Asmeniniai nustatymai')}</a></li>
			<li class="dropdown-item"><a href="?logout">{t('Atsijungti')}</a></li>
		</ul>
	</div>
{else}
	{$profile = $frontend->page_types.alias_account|ifnull:$frontend->page_types.orders}
	{if $profile}
		<div id="profile_menu" class="d-none  d-md-block">
			<a class="title{if !$smarty.session.customer} need2login{/if}" href="{$profile.full_url}">
				<i class="icon icon-profile"></i>
			</a>
		</div>
	{/if}
{/if}

<script>
	$(function () {
		let profile_menu = $('#profile_menu');

		// Add slideDown animation to Bootstrap dropdown when expanding.
		profile_menu.on('show.bs.dropdown', function() {
			$(this).find('.dropdown-menu').first().stop(true, true).slideDown();
		});

		// Add slideUp animation to Bootstrap dropdown when collapsing.
		profile_menu.on('hide.bs.dropdown', function() {
			$(this).find('.dropdown-menu').first().stop(true, true).slideUp();
		});
	})
</script>