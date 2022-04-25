<?php
/* Smarty version 3.1.44, created on 2022-03-16 19:40:07
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/pages/profile_menu_mobile.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_623220f7ddab22_71742393',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7ac83258795041125292d9eb815d922dd3698657' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/pages/profile_menu_mobile.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_623220f7ddab22_71742393 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '1716630688623220f7dca3c8_45403609';
if ($_SESSION['customer']['registered']) {?>
	<li class="d-md-none">
		<div class="list-dropdown list-collapse-mobile" data-hover-delay="100">
			<h4 class="title profile">
				<?php echo t('Mano profilis');?>


				<i class="icon icon-profile"></i>
			</h4>

			<div class="submenu_list">
				<ul>
					<li class="d-sm-none mobile-head list-collapse-mobile level-2">
						<h4 class="title">
							<?php echo t('Mano profilis');?>


							<i class="icon icon-profile no-float"></i>
						</h4>
					</li>
					<li>
						<a href="<?php echo $_smarty_tpl->tpl_vars['frontend']->value->page_types['orders']['full_url'];?>
">
							<h4 class="level-2"><?php echo t('Mano užsakymai');?>
</h4>
						</a>
					</li>
					<li>
						<a href="<?php echo $_smarty_tpl->tpl_vars['frontend']->value->page_types['customers']['full_url'];?>
">
							<h4 class="level-2"><?php echo t('Asmeniniai nustatymai');?>
</h4>
						</a>
					</li>
					<li>
						<a href="?logout">
							<h4 class="level-2"><?php echo t('Atsijungti');?>
</h4>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</li>
<?php } else { ?>
	<?php $_smarty_tpl->_assignInScope('profile', call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'ifnull' ][ 0 ], array( $_smarty_tpl->tpl_vars['frontend']->value->page_types['alias_account'],$_smarty_tpl->tpl_vars['frontend']->value->page_types['orders'] )));?>
	<?php if ($_smarty_tpl->tpl_vars['profile']->value) {?>
		<li class="d-md-none">
			<a href="<?php echo $_smarty_tpl->tpl_vars['profile']->value['full_url'];?>
" <?php if (!$_SESSION['customer']) {?>class="need2login"<?php }?>>
				<h4 class="title">
					<?php echo t('Prisijungti');?>


					<i class="icon icon-profile"></i>
				</h4>
			</a>
		</li>
	<?php }
}
}
}
