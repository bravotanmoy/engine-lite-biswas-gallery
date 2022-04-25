<?php
/* Smarty version 3.1.44, created on 2022-03-16 19:48:49
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/pages/languages_menu.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_62322301dc0906_23929994',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '58f139a989a67f429b19c94d28ef2573995165b5' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/pages/languages_menu.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62322301dc0906_23929994 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['languages']->value) {?>
	<ul id="languages_menu" class="d-none d-md-block">
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				<i class="icon-kalba icon"></i>
				<img src="<?php echo (defined('PROJECT_URL') ? constant('PROJECT_URL') : null);?>
images/languages/<?php echo mb_strtolower($_smarty_tpl->tpl_vars['frontend']->value->lang_key, 'UTF-8');?>
.svg"/>
				<i class="icon-arrow_down arrow"></i>
			</a>

			<ul class="dropdown-menu" role="menu">
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['languages']->value, 'lang');
$_smarty_tpl->tpl_vars['lang']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['lang']->value) {
$_smarty_tpl->tpl_vars['lang']->do_else = false;
?>
					<li class="dropdown-item"><a href="<?php echo (defined('PROJECT_URL') ? constant('PROJECT_URL') : null);
echo mb_strtolower($_smarty_tpl->tpl_vars['lang']->value['language'], 'UTF-8');?>
">
							<img src="<?php echo (defined('PROJECT_URL') ? constant('PROJECT_URL') : null);?>
images/languages/<?php echo mb_strtolower($_smarty_tpl->tpl_vars['lang']->value['language'], 'UTF-8');?>
.svg"/>
						</a>
					</li>
				<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
			</ul>
		</li>
	</ul>

	<?php echo '<script'; ?>
>
		$(function () {
			let languages_menu = $('#languages_menu');

			// Add slideDown animation to Bootstrap dropdown when expanding.
			languages_menu.on('show.bs.dropdown', function() {
				$(this).find('.dropdown-menu').first().stop(true, true).slideDown();
			});

			// Add slideUp animation to Bootstrap dropdown when collapsing.
			languages_menu.on('hide.bs.dropdown', function() {
				$(this).find('.dropdown-menu').first().stop(true, true).slideUp();
			});
		})
	<?php echo '</script'; ?>
>
<?php }
}
}
