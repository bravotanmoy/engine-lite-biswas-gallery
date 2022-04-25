<?php
/* Smarty version 3.1.44, created on 2022-03-16 17:54:44
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/filters.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_62320844f090d8_79928948',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '714071e5388ef40531ec5596b5a8669402fa7eee' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/filters.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62320844f090d8_79928948 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="filter">
	<div id="filters_popup_header">
		<a class="float-right filter_close"><span class="icon icon-close-lg"></span></a>
		<div class="h1"><?php echo t('Filtrai');?>
</div>
	</div>
	<div id="filters_popup_footer">
		<div class="row">
			<div class="col-6">
				<button class="btn btn-primary btn-lg btn-block filter_close"><?php echo t('Gerai');?>
</button>
			</div>
			<div class="col-6">
				<button class="btn btn-outline-secondary btn-lg btn-block clean_all filter_close"><?php echo t('Išvalyti viską');?>
</button>
			</div>
		</div>
	</div>
	<div id="filter_block" class="clearfix hidden-filter">
		<div class="body">
			<?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('products/filter_category_tree');?>

			<?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('products/filter_collections');?>

			<?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('products/filter_prices');?>

			<?php if (!$_smarty_tpl->tpl_vars['frontend']->value->brand) {?>
				<?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('products/filter_brands');?>

			<?php }?>
			<?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('products/filter_fmodule');?>

		</div>
	</div>
</div>

<?php echo '<script'; ?>
>
	$(function(){
		$('#filter_summary button').tooltip();

		$('#filter_block .footer a').click(function(e){
			e.preventDefault();
		});

		productFilter.init({
			replaceTemplate: 'content_types/products/listing.tpl',
			replaceContainer: '#products_listing',
			filterContainer: '#filter',
			filterToggleButton: '#filter_on, .filter_close',
			filterMenu: '#filter_block',
			filterClearAllButton: '#products_listing .clean_all',
			filterClearButton: '#filter span.clean',
			filterRemoveButton: '#products_listing .remove_filter',
			filterSetButton: '#filter .ajax li.f'
		});

		$('#products_and_filters .sort_block select').change(function(e){
			e.preventDefault();
			productFilter.updateURI({ sort_by: $(this).val() });
			productFilter.reload();
		});

		$('.pagination a:not(.dots)').click(function(e) {
			e.preventDefault();
			productFilter.updateURI(this.href.replace(/^.*\?/,'?'));
			productFilter.reload();
			if ($('#products_listing').length) {
				$('html, body').animate({
					scrollTop: $("#products_listing").offset().top
				}, 'fast');
			}
		});

		$('#products_block a.ref_link').click(function(e){
			var hash = window.location.hash.replace(/^.*#[_]?/, '');
			var params = {
				hash:hash,
				title:'<?php echo $_smarty_tpl->tpl_vars['frontend']->value->get_title();?>
',
				return_url:window.location.href,
				ref_id:$(this).data('ref-id')
			};
			var url = '<?php echo $_smarty_tpl->tpl_vars['frontend']->value->page['full_url'];?>
?set_ref=1';
			$('#ajax_loader').fadeIn();
			$.ajax({
				url: url,
				data: params,
				success: function(data){
					// console.log(data);
				},
				async: false
			});
		});
	});
<?php echo '</script'; ?>
><?php }
}
