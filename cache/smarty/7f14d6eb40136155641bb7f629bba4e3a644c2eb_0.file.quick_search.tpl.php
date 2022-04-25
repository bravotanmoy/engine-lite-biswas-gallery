<?php
/* Smarty version 3.1.44, created on 2022-03-16 19:48:49
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/search/quick_search.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_62322301dabfb1_67775904',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7f14d6eb40136155641bb7f629bba4e3a644c2eb' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/search/quick_search.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62322301dabfb1_67775904 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="quick_search">
	<a href="" rel="nofollow" id="quick_search_show">
		<i class="icon icon-search"></i>
			</a>

	<form action="<?php echo $_smarty_tpl->tpl_vars['frontend']->value->page_types['search']['full_url'];?>
" method="get">
		<div class='input-group'>
			<input type="text" class="form-control" name="search" placeholder="<?php echo t('Paieška');?>
" />
			<span class="input-group-btn">
				<button type="submit"><i class="icon icon-search"></i></button>
				<button id="quick_search_hide" type="button"><i class="icon icon-close"></i></button>
			</span>
		</div>
	</form>

	<?php echo '<script'; ?>
>
		$(function() {
			$('#quick_search_show').click(function (e) {
				e.preventDefault();
				$('#quick_search').addClass('active');
				setTimeout(function() {
					$('#quick_search input').focus();
				}, 100);
			});
			$('#quick_search_hide').click(function (e) {
				e.preventDefault();
				$('#quick_search').removeClass('active');
			});
		});
	<?php echo '</script'; ?>
>
</div><?php }
}
