{if $showpopup}
	<a id="hidden_editable_popup_link" data-toggle="modal" href="#editable_popup" style="display:none;">&nbsp;</a>

	<!-- Modal -->
	<div class="modal fade" id="editable_popup" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>

					<h4 class="modal-title" id="myModalLabel">{$popup.name}</h4>
				</div>

				<div class="modal-body">
					{if $popup.type == 1} {* Type: text *}
						<div>
							{if !empty($popup.photo)}
								<img src="{$popup.photo.src}"/>
							{/if}

							<div>{$h->display_html($info.content) }</div>
						</div>
					{elseif $popup.type == 2} {* Type: banner *}
						{if $popup.link}<a href="{$popup.link}">{/if}
						<img src="{$popup.photo.src}" style="width: 100%;"/>
						{if $popup.link}</a>{/if}
					{elseif $popup.type == 3} {* Type: pren.forma *}
						<div style="display: inline-block;">
							{if strlen($info.content) > 0}
								<div class="text col-12">{$h->display_html($info.content)}</div>
							{/if}

							<div class='col-12'>
								{$frontend->view("subscribers/subscribe", '#editable_popup #subscribers_subscribe', $popup_subscribe)}
							</div>
						</div>
					{elseif $popup.type == 4}
						{*Video*}
						<div>{$h->display_html($info.content) }</div>
					{/if}
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		{literal}
		$(function(){
			var expires_after = '{/literal}{$popup.expires_after}{literal}';

			$('#hidden_editable_popup_link').on('hide.bs.modal', function (e) {
				e.preventDefault();

				$('#editable_popup').remove();
				$('#hidden_editable_popup_link').remove();

				var url;
				save_cookie(url, expires_after);
			});

			setTimeout(function() {
				$("#hidden_editable_popup_link").trigger('click');
			}, {/literal}{$info.delay*1000}{literal});

			$('#editable_popup a').click(function(e){
				e.preventDefault();

				var el = $(this);

				setTimeout(function() {
					save_cookie(el.attr('href'), expires_after);
				}, 100);
			});

			$('#editable_popup form#newsletter_offer').submit(function(){
				var el = $(this);
				var url = el.attr('action');
				var email = $("input[name='email']", el).val();

				url += '?email=' + encodeURIComponent(email);

				setTimeout(function() {
					save_cookie(url, expires_after);
				}, 100);
				return false;
			});

			function save_cookie(redirect_link, expires_after) {
				$.ajax({
					type: 'POST',
					url: '?display=content_types/popups/save_cookie.tpl',
					data: {popupid: {/literal}{$info.id}{literal}, expires_after: expires_after },
					success: function(data) {
						if ('undefined' !== typeof redirect_link) {
							window.location = redirect_link;
						}
					}
				});
			}
		});
		{/literal}
	</script>
{/if}