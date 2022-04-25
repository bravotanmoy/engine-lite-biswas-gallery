<div id='subscribers_subscribe'>
	{$h->show_messages('subscribers')}

	<h4 class="title">{t('Naujienlaiškio prenumerata')}</h4>
	<p>{t('Užsisakykite mūsų naujienlaiškį ir pirmieji gaukite naujausius pasiūlymus bei akcijas tiesiai į el. pašto dėžutę.')}</p>

	<form id="newsletter_form" method="post" data-ajaxnav='true' data-ajaxnav-template='content_types/subscribers/subscribe' data-ajaxnav-container="{$container}">
		<input type="hidden" name="state" value="subscribe" />
		<input type="hidden" value="{$container}" name="ajaxnav_container">
		<input type="hidden" value="{$popup|json_encode}" name="ajaxnav_popup_info">

		{$h->show_messages('subscribers')}

		<div class="input-group">
			<input type="text" name="email" class="form-control" placeholder="{t('El. pašto adresas')}" value='{$smarty.post.email}' />
			<span class="input-group-btn">
				<button class="btn btn-primary rounded-0" type="submit"><i class="icon icon-right-big"></i></button>
			</span>
		</div>
	</form>
</div>