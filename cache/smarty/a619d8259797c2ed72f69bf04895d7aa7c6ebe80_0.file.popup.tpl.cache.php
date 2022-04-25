<?php
/* Smarty version 3.1.44, created on 2022-03-16 19:40:09
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/popups/popup.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_623220f91e9b76_67005506',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a619d8259797c2ed72f69bf04895d7aa7c6ebe80' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/popups/popup.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_623220f91e9b76_67005506 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '551621282623220f91d2154_33175437';
if ($_smarty_tpl->tpl_vars['showpopup']->value) {?>
	<a id="hidden_editable_popup_link" data-toggle="modal" href="#editable_popup" style="display:none;">&nbsp;</a>

	<!-- Modal -->
	<div class="modal fade" id="editable_popup" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>

					<h4 class="modal-title" id="myModalLabel"><?php echo $_smarty_tpl->tpl_vars['popup']->value['name'];?>
</h4>
				</div>

				<div class="modal-body">
					<?php if ($_smarty_tpl->tpl_vars['popup']->value['type'] == 1) {?> 						<div>
							<?php if (!empty($_smarty_tpl->tpl_vars['popup']->value['photo'])) {?>
								<img src="<?php echo $_smarty_tpl->tpl_vars['popup']->value['photo']['src'];?>
"/>
							<?php }?>

							<div><?php echo $_smarty_tpl->tpl_vars['h']->value->display_html($_smarty_tpl->tpl_vars['info']->value['content']);?>
</div>
						</div>
					<?php } elseif ($_smarty_tpl->tpl_vars['popup']->value['type'] == 2) {?> 						<?php if ($_smarty_tpl->tpl_vars['popup']->value['link']) {?><a href="<?php echo $_smarty_tpl->tpl_vars['popup']->value['link'];?>
"><?php }?>
						<img src="<?php echo $_smarty_tpl->tpl_vars['popup']->value['photo']['src'];?>
" style="width: 100%;"/>
						<?php if ($_smarty_tpl->tpl_vars['popup']->value['link']) {?></a><?php }?>
					<?php } elseif ($_smarty_tpl->tpl_vars['popup']->value['type'] == 3) {?> 						<div style="display: inline-block;">
							<?php if (strlen($_smarty_tpl->tpl_vars['info']->value['content']) > 0) {?>
								<div class="text col-12"><?php echo $_smarty_tpl->tpl_vars['h']->value->display_html($_smarty_tpl->tpl_vars['info']->value['content']);?>
</div>
							<?php }?>

							<div class='col-12'>
								<?php echo $_smarty_tpl->tpl_vars['frontend']->value->view("subscribers/subscribe",'#editable_popup #subscribers_subscribe',$_smarty_tpl->tpl_vars['popup_subscribe']->value);?>

							</div>
						</div>
					<?php } elseif ($_smarty_tpl->tpl_vars['popup']->value['type'] == 4) {?>
												<div><?php echo $_smarty_tpl->tpl_vars['h']->value->display_html($_smarty_tpl->tpl_vars['info']->value['content']);?>
</div>
					<?php }?>
				</div>
			</div>
		</div>
	</div>

	<?php echo '<script'; ?>
 type="text/javascript">
		
		$(function(){
			var expires_after = '<?php echo $_smarty_tpl->tpl_vars['popup']->value['expires_after'];?>
';

			$('#hidden_editable_popup_link').on('hide.bs.modal', function (e) {
				e.preventDefault();

				$('#editable_popup').remove();
				$('#hidden_editable_popup_link').remove();

				var url;
				save_cookie(url, expires_after);
			});

			setTimeout(function() {
				$("#hidden_editable_popup_link").trigger('click');
			}, <?php echo $_smarty_tpl->tpl_vars['info']->value['delay']*1000;?>
);

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
					data: {popupid: <?php echo $_smarty_tpl->tpl_vars['info']->value['id'];?>
, expires_after: expires_after },
					success: function(data) {
						if ('undefined' !== typeof redirect_link) {
							window.location = redirect_link;
						}
					}
				});
			}
		});
		
	<?php echo '</script'; ?>
>
<?php }
}
}
