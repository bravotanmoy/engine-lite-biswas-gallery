<?php
/* Smarty version 3.1.44, created on 2022-03-16 19:40:07
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/pages/languages_menu.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_623220f7c4f589_79906643',
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
  'cache_lifetime' => 3600,
),true)) {
function content_623220f7c4f589_79906643 (Smarty_Internal_Template $_smarty_tpl) {
?>	<ul id="languages_menu" class="d-none d-md-block">
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				<i class="icon-kalba icon"></i>
				<img src="http://biswas.local/images/languages/lt.svg"/>
				<i class="icon-arrow_down arrow"></i>
			</a>

			<ul class="dropdown-menu" role="menu">
									<li class="dropdown-item"><a href="http://biswas.local/en">
							<img src="http://biswas.local/images/languages/en.svg"/>
						</a>
					</li>
							</ul>
		</li>
	</ul>

	<script>
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
	</script>
<?php }
}
