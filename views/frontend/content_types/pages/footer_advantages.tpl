{if $infoblocks}
	<div id="footer-advantages">
		<div class="container-fluid">
			<div class="row">
				<h2 class="text-center col-12">{t('Mes garantuojame')}</h2>
				{foreach $infoblocks as $infoblock}
					<div class="col-6 col-md-3 text-center">
						<a href="{$infoblock.url}" class="text-center">
							<p class="title">{$infoblock.name}</p>
						</a>
					</div>
				{/foreach}
			</div>
		</div>
	</div>
{/if}