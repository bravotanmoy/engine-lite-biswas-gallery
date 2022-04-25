<!-- Modal -->
<div class="modal fade" id="popup_n18" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

				<h4 class="modal-title" id="myModalLabel">N18</h4>
			</div>

			<div class="modal-body">
				<p style="font-size: 18px;">{t('Kad galėtumėte įeiti į alkoholio prekių grupę, Jūs turite būti sulaukę 18 metų.')}</p>
				<h4>{t('Ar Jums jau yra 18 metų?')}</h4>
				<a href="#" class="btn btn-primary">Taip</a> <a href="{$smarty.const.PROJECT_URL}" class="btn btn-outline-secondary">Ne</a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){

		$('#popup_n18').modal('show');

		$('#popup_n18 .btn-primary').on('click', function(e) {
			e.preventDefault();
			savecookie();
		});

		function savecookie() {
			$.ajax({
				type: 'POST',
				url: '?display=content_types/popups/save_cookie.tpl',
				data: { popupid: 'n18' },
				success: function(data) {
					$.fancybox.close();
				}
			});
		}
	});
</script>