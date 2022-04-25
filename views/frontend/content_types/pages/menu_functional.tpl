<ul id="menu_functional" class="horizontal-desktop">
	<li>
		{if $smarty.session.customer.registered}
			<div class="list-dropdown list-collapse-mobile">
			<span class="title">
				{if $smarty.session.customer.first_name}
					{$smarty.session.customer.first_name}
				{else}
					{$smarty.session.customer.email}
				{/if}
			</span>
				{if $smarty.session.customer.registered}
					<ul data-dropdown-align="right">
						<li><a href="{$frontend->page_types.orders.full_url}">{t('Mano užsakymai')}</a></li>
						<li><a href="{$frontend->page_types.customers.full_url}">{t('Asmeniniai nustatymai')}</a></li>
						<li><a href="?logout">{t('Atsijungti')}</a></li>
					</ul>
				{/if}
			</div>
		{else}
			<a class="title" href="{$frontend->page_types.orders.full_url}">
				<i class="icon-login"></i>
			</a>
		{/if}
	</li>
</ul>