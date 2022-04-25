{if $smarty.session.customer.registered}
	<li class="d-md-none">
		<div class="list-dropdown list-collapse-mobile" data-hover-delay="100">
			<h4 class="title profile">
				{t('Mano profilis')}

				<i class="icon icon-profile"></i>
			</h4>

			<div class="submenu_list">
				<ul>
					<li class="d-sm-none mobile-head list-collapse-mobile level-2">
						<h4 class="title">
							{t('Mano profilis')}

							<i class="icon icon-profile no-float"></i>
						</h4>
					</li>
					<li>
						<a href="{$frontend->page_types.orders.full_url}">
							<h4 class="level-2">{t('Mano užsakymai')}</h4>
						</a>
					</li>
					<li>
						<a href="{$frontend->page_types.customers.full_url}">
							<h4 class="level-2">{t('Asmeniniai nustatymai')}</h4>
						</a>
					</li>
					<li>
						<a href="?logout">
							<h4 class="level-2">{t('Atsijungti')}</h4>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</li>
{else}
	{$profile = $frontend->page_types.alias_account|ifnull:$frontend->page_types.orders}
	{if $profile}
		<li class="d-md-none">
			<a href="{$profile.full_url}" {if !$smarty.session.customer}class="need2login"{/if}>
				<h4 class="title">
					{t('Prisijungti')}

					<i class="icon icon-profile"></i>
				</h4>
			</a>
		</li>
	{/if}
{/if}
