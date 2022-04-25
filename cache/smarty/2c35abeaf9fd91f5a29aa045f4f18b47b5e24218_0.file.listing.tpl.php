<?php
/* Smarty version 3.1.44, created on 2022-03-16 17:54:44
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/listing.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_62320844efb913_69243300',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2c35abeaf9fd91f5a29aa045f4f18b47b5e24218' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/listing.tpl',
      1 => 1647018937,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62320844efb913_69243300 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
?>
<div id="products_listing">
	<div class="container-fluid">
		<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_76421124162320844ea52a3_38390412', "description");
?>


		<div id="products_and_filters">
			<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'default', "filters_html", null);?>
				<?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('products/filters',$_smarty_tpl->tpl_vars['elements']->value);?>

			<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);?>

			<div class="row">
				<div id="filters_column" class="col-12 col-sm-auto">
					<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_27514497262320844eb6c36_85102021', "filters");
?>

				</div>
				<div id="products_column" class="col-12 col-sm-auto">
					<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_26778692362320844eb8268_70525756', "listing");
?>

				</div>
			</div>
		</div>

	</div>
	<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_88854121262320844ef75e6_21059084', "scripts");
?>

</div><?php }
/* {block "description"} */
class Block_76421124162320844ea52a3_38390412 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'description' => 
  array (
    0 => 'Block_76421124162320844ea52a3_38390412',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

			<h1 class="page_title text-center"><?php echo $_smarty_tpl->tpl_vars['frontend']->value->get_title();?>
</h1>
			<div class="html_content"><?php echo $_smarty_tpl->tpl_vars['h']->value->display_html($_smarty_tpl->tpl_vars['frontend']->value->page['content']);?>
</div>
			<?php if ($_smarty_tpl->tpl_vars['frontend']->value->collection && $_smarty_tpl->tpl_vars['frontend']->value->collection['description']) {?>
				<div class="description"><?php echo $_smarty_tpl->tpl_vars['h']->value->display_html($_smarty_tpl->tpl_vars['frontend']->value->collection['description']);?>
</div>
			<?php } elseif ($_smarty_tpl->tpl_vars['frontend']->value->subcategory && $_smarty_tpl->tpl_vars['frontend']->value->subcategory['description']) {?>
				<div class="description"><?php echo $_smarty_tpl->tpl_vars['h']->value->display_html($_smarty_tpl->tpl_vars['frontend']->value->subcategory['description']);?>
</div>
			<?php } elseif ($_smarty_tpl->tpl_vars['frontend']->value->category && $_smarty_tpl->tpl_vars['frontend']->value->category['description']) {?>
				<div class="description"><?php echo $_smarty_tpl->tpl_vars['h']->value->display_html($_smarty_tpl->tpl_vars['frontend']->value->category['description']);?>
</div>
			<?php } elseif ($_smarty_tpl->tpl_vars['frontend']->value->brand && $_smarty_tpl->tpl_vars['frontend']->value->brand['description']) {?>
				<div class="description"><?php echo $_smarty_tpl->tpl_vars['h']->value->display_html($_smarty_tpl->tpl_vars['frontend']->value->brand['description']);?>
</div>
			<?php }?>

			<?php if ($_smarty_tpl->tpl_vars['frontend']->value->category['photos_count']) {?>
				<div class="banner container-fluid">
					<img class="img-fluid center-block" src="<?php echo $_smarty_tpl->tpl_vars['frontend']->value->category['photo']['src'];?>
" />
				</div>
			<?php } elseif ($_smarty_tpl->tpl_vars['frontend']->value->subcategory['photos_count']) {?>
				<div class="banner container-fluid">
					<img class="img-fluid center-block" src="<?php echo $_smarty_tpl->tpl_vars['frontend']->value->subcategory['photo']['src'];?>
" />
				</div>
			<?php }?>
		<?php
}
}
/* {/block "description"} */
/* {block "filters"} */
class Block_27514497262320844eb6c36_85102021 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'filters' => 
  array (
    0 => 'Block_27514497262320844eb6c36_85102021',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

						<?php echo $_smarty_tpl->tpl_vars['filters_html']->value;?>

					<?php
}
}
/* {/block "filters"} */
/* {block "filter_summary"} */
class Block_22344192762320844ebee51_11060047 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

							<div id="filter_summary" class="clearfix"><?php if (!empty($_smarty_tpl->tpl_vars['frontend']->value->filter)) {?><button class="btn btn-outline-secondary btn-xs filter-btn clean_all" title="<?php echo t('Išvalyti visus pasirinktus filtrus');?>
"></button><?php }
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['frontend']->value->filter, 'v1', false, 'k1');
$_smarty_tpl->tpl_vars['v1']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['k1']->value => $_smarty_tpl->tpl_vars['v1']->value) {
$_smarty_tpl->tpl_vars['v1']->do_else = false;
if ($_smarty_tpl->tpl_vars['k1']->value == 'prices') {
if ($_smarty_tpl->tpl_vars['v1']->value[0] !== null) {?><button class="btn btn-outline-secondary btn-xs remove_filter filter-btn" title="<?php echo t('Išvalyti minimalios kainos filtrą');?>
" data-ftype="price0" data-fvalue="<?php echo $_smarty_tpl->tpl_vars['v1']->value[0];?>
"><?php echo t('Nuo');?>
 <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'fprice' ][ 0 ], array( $_smarty_tpl->tpl_vars['v1']->value[0] ));?>
</span></button><?php }
if ($_smarty_tpl->tpl_vars['v1']->value[1] !== null) {?><button class="btn btn-outline-secondary btn-xs remove_filter filter-btn" title="<?php echo t('Išvalyti maksimalios kainos filtrą');?>
" data-ftype="price1" data-fvalue="<?php echo $_smarty_tpl->tpl_vars['v1']->value[1];?>
"><?php echo t('Iki');?>
 <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'fprice' ][ 0 ], array( $_smarty_tpl->tpl_vars['v1']->value[1] ));?>
</span></button><?php }
} else {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['v1']->value, 'v2', false, 'k2');
$_smarty_tpl->tpl_vars['v2']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['k2']->value => $_smarty_tpl->tpl_vars['v2']->value) {
$_smarty_tpl->tpl_vars['v2']->do_else = false;
if ($_smarty_tpl->tpl_vars['k1']->value == 'fmod') {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['v2']->value, 'v3', false, 'k3');
$_smarty_tpl->tpl_vars['v3']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['k3']->value => $_smarty_tpl->tpl_vars['v3']->value) {
$_smarty_tpl->tpl_vars['v3']->do_else = false;
?><button class="btn btn-outline-secondary btn-xs remove_filter filter-btn" title="<?php echo sprintf(t('Išvalyti filtrą: %s'),$_smarty_tpl->tpl_vars['frontend']->value->filter_info["fmod_".((string)$_smarty_tpl->tpl_vars['k2']->value)]['options'][$_smarty_tpl->tpl_vars['k3']->value]['title']);?>
" data-ftype="<?php echo $_smarty_tpl->tpl_vars['k1']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['k2']->value;?>
" data-fvalue="<?php echo $_smarty_tpl->tpl_vars['k3']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['frontend']->value->filter_info["fmod_".((string)$_smarty_tpl->tpl_vars['k2']->value)]['options'][$_smarty_tpl->tpl_vars['k3']->value]['title'];?>
</span></button><?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
} else { ?><button class="btn btn-outline-secondary btn-xs remove_filter filter-btn" title="<?php echo sprintf(t('Išvalyti filtrą: %s'),$_smarty_tpl->tpl_vars['frontend']->value->filter_info[$_smarty_tpl->tpl_vars['k1']->value]['options'][$_smarty_tpl->tpl_vars['k2']->value]['title']);?>
" data-ftype="<?php echo $_smarty_tpl->tpl_vars['k1']->value;?>
" data-fvalue="<?php echo $_smarty_tpl->tpl_vars['k2']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['frontend']->value->filter_info[$_smarty_tpl->tpl_vars['k1']->value]['options'][$_smarty_tpl->tpl_vars['k2']->value]['title'];?>
</span></button><?php }
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></div>
						<?php
}
}
/* {/block "filter_summary"} */
/* {block "listing"} */
class Block_26778692362320844eb8268_70525756 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'listing' => 
  array (
    0 => 'Block_26778692362320844eb8268_70525756',
  ),
  'filter_summary' => 
  array (
    0 => 'Block_22344192762320844ebee51_11060047',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

						<button id="filter_on" class="pf-toggle-menu btn btn-primary rounded-0"><?php echo t('Filtrai');?>
 <span class="icon icon-filter"></span></button>
						<div class="sort_block">
							<select class="selectpicker" title="<?php echo t('Rikiuoti pagal');?>
" data-style="rounded-0 btn-outline-custom">
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['entity_config']->value['sort_options'], 'v', false, 'k');
$_smarty_tpl->tpl_vars['v']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->do_else = false;
?>
									<option title="<?php echo t('Rikiuoti pagal');?>
" value="<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['entity_config']->value['sort_by'] == $_smarty_tpl->tpl_vars['k']->value) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['v']->value;?>
</option>
								<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							</select>
						</div>
						<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_22344192762320844ebee51_11060047', "filter_summary", $this->tplIndex);
?>

						<?php if ($_smarty_tpl->tpl_vars['elements']->value) {?>
							<div class="product_listing">
								<div class="clearfix">
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['elements']->value, 'element');
$_smarty_tpl->tpl_vars['element']->iteration = 0;
$_smarty_tpl->tpl_vars['element']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['element']->value) {
$_smarty_tpl->tpl_vars['element']->do_else = false;
$_smarty_tpl->tpl_vars['element']->iteration++;
$__foreach_element_13_saved = $_smarty_tpl->tpl_vars['element'];
?>
										<?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('products/element',$_smarty_tpl->tpl_vars['element']->value);?>

										<?php if ($_smarty_tpl->tpl_vars['element']->iteration%2 == 0) {?>
											<div class="clearfix-xs"></div>
										<?php }?>
										<?php if ($_smarty_tpl->tpl_vars['element']->iteration%3 == 0) {?>
											<div class="clearfix-sm"></div>
										<?php }?>
										<?php if ($_smarty_tpl->tpl_vars['element']->iteration%4 == 0) {?>
											<div class="clearfix-md clearfix-lg"></div>
										<?php }?>
									<?php
$_smarty_tpl->tpl_vars['element'] = $__foreach_element_13_saved;
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								</div>
							</div>
							<div class="pagination-wrp">
								<?php if (count($_smarty_tpl->tpl_vars['entity_config']->value['available_page_sizes']) > 1) {?>
									<div class="page_sizes">
										<?php echo t('Rodyti po');?>
:
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['entity_config']->value['available_page_sizes'], 'size');
$_smarty_tpl->tpl_vars['size']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['size']->value) {
$_smarty_tpl->tpl_vars['size']->do_else = false;
?>
											<a <?php if ($_smarty_tpl->tpl_vars['size']->value == $_smarty_tpl->tpl_vars['page_size']->value) {?>class="active"<?php }?> data-ajaxnav="true" data-ajaxnav-template="content_types/products/listing" data-ajaxnav-container="#products_listing" href="?filter=<?php echo urlencode($_GET['filter']);?>
&page_size=<?php echo $_smarty_tpl->tpl_vars['size']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['size']->value;?>
</a>
										<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
									</div>
								<?php }?>
								<?php if ($_smarty_tpl->tpl_vars['lazy_load']->value || ($_smarty_tpl->tpl_vars['elements_info']->value['page_info']['pages_count'] > 1)) {?>
									<div class="lazy">
										<?php if (!$_smarty_tpl->tpl_vars['lazy_load']->value) {?>
											<a data-ajaxnav="true" data-ajaxnav-template="content_types/products/listing" data-ajaxnav-container="#products_listing" href="?filter=<?php echo urlencode($_GET['filter']);?>
&page_size=0"><?php echo t('Rodyti viską');?>
</a>
										<?php } else { ?>
											<a id="lazy_load_off" data-ajaxnav="true" data-ajaxnav-template="content_types/products/listing" data-ajaxnav-container="#products_listing" href="?filter=<?php echo urlencode($_GET['filter']);?>
&page_size=<?php echo $_smarty_tpl->tpl_vars['default_page_size']->value;?>
"><?php echo t('Rodyti puslapius');?>
</a>
										<?php }?>
									</div>
								<?php }?>
								<?php if (!$_smarty_tpl->tpl_vars['lazy_load']->value) {?>
									<div class="pages"><?php echo $_smarty_tpl->tpl_vars['elements_info']->value['pages'];?>
</div>
								<?php }?>
							</div>
						<?php } else { ?>
							<div class="product_listing">
								<?php if ($_smarty_tpl->tpl_vars['frontend']->value->filter) {?>
									<div class="alert alert-warning"><?php echo t('Apgailestaujame, tačiau šioje kategorijeje nėra prekių, atitinkančių pasirinktus filtro kriterijus.');?>
</div>
								<?php } else { ?>
									<div class="alert alert-warning"><?php echo t('Apgailestaujame, tačiau prekių šioje kategorijoje nėra.');?>
</div>
								<?php }?>
							</div>
						<?php }?>
						<div id="lazy_loader"><span class="icon-spin fa-spin"></span></div>
					<?php
}
}
/* {/block "listing"} */
/* {block "scripts"} */
class Block_88854121262320844ef75e6_21059084 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'scripts' => 
  array (
    0 => 'Block_88854121262320844ef75e6_21059084',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

		<?php echo '<script'; ?>
>
			<?php if ($_smarty_tpl->tpl_vars['elements']->value) {?>
			var products_listing_filter = '<?php echo $_GET['filter'];?>
';
			var page = <?php echo $_smarty_tpl->tpl_vars['elements_info']->value['page_info']['page'];?>
 + 1;
			var max_page = <?php echo $_smarty_tpl->tpl_vars['elements_info']->value['page_info']['pages_count'];?>
;
			<?php }?>

			var dolazy = <?php if ($_smarty_tpl->tpl_vars['lazy_load']->value) {?>true<?php } else { ?>false<?php }?>;
			(function(){
				var xhr;

				$(function(){
					lazyload();
				});

				$(window).resize(function(){
					lazyload();
				});

				$(window).scroll(function(){
					lazyload()
				});

				$(document).keyup(function(e) {
					if (e.keyCode == 27) { // escape key maps to keycode `27`
						console.log('escape');
						if(xhr && xhr.readyState != 4){
							$('#lazy_loader').hide();
							xhr.abort();
						}
					}
				});

				function lazyload() {
					var full_height = $(document).height();
					var window_height = $(window).height();
					var scrollTop = $(window).scrollTop();
					if ( max_page >= page && dolazy && scrollTop + window_height >= full_height - 500 ) {
						dolazy = false;
						$('#lazy_loader').show();
						xhr = $.ajax({
							url : '?&page='+page+'&display=content_types/products/listing.tpl&filter='+encodeURIComponent(products_listing_filter),
							type : 'post',
							success : function(data) {
								page++;
								$('#lazy_loader').hide();
								var a = $(data).find('.product_listing');
								$('.product_listing').append( a.html() );
								dolazy = true;
							}
						});
					}
				}
			})();
		<?php echo '</script'; ?>
>
	<?php
}
}
/* {/block "scripts"} */
}
