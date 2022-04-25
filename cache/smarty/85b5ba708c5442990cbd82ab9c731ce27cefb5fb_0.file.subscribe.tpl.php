<?php
/* Smarty version 3.1.44, created on 2022-03-16 19:48:51
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/subscribers/subscribe.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_62322303097280_46959054',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '85b5ba708c5442990cbd82ab9c731ce27cefb5fb' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/subscribers/subscribe.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62322303097280_46959054 (Smarty_Internal_Template $_smarty_tpl) {
?><div id='subscribers_subscribe'>
	<?php echo $_smarty_tpl->tpl_vars['h']->value->show_messages('subscribers');?>


	<h4 class="title"><?php echo t('Naujienlaiškio prenumerata');?>
</h4>
	<p><?php echo t('Užsisakykite mūsų naujienlaiškį ir pirmieji gaukite naujausius pasiūlymus bei akcijas tiesiai į el. pašto dėžutę.');?>
</p>

	<form id="newsletter_form" method="post" data-ajaxnav='true' data-ajaxnav-template='content_types/subscribers/subscribe' data-ajaxnav-container="<?php echo $_smarty_tpl->tpl_vars['container']->value;?>
">
		<input type="hidden" name="state" value="subscribe" />
		<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['container']->value;?>
" name="ajaxnav_container">
		<input type="hidden" value="<?php echo json_encode($_smarty_tpl->tpl_vars['popup']->value);?>
" name="ajaxnav_popup_info">

		<?php echo $_smarty_tpl->tpl_vars['h']->value->show_messages('subscribers');?>


		<div class="input-group">
			<input type="text" name="email" class="form-control" placeholder="<?php echo t('El. pašto adresas');?>
" value='<?php echo $_POST['email'];?>
' />
			<span class="input-group-btn">
				<button class="btn btn-primary rounded-0" type="submit"><i class="icon icon-right-big"></i></button>
			</span>
		</div>
	</form>
</div><?php }
}
