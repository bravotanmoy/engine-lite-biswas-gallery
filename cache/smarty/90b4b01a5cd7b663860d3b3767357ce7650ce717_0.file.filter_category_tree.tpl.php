<?php
/* Smarty version 3.1.44, created on 2022-03-16 17:54:44
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/filter_category_tree.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_62320844f1f171_84823937',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '90b4b01a5cd7b663860d3b3767357ce7650ce717' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/filter_category_tree.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62320844f1f171_84823937 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['categories']->value) {?>
<div id="products_filter_category_tree" class="filter-group list-collapse-mobile list-tree list-tree-checkboxes ajax">
	<h5 class="title"><?php echo t('Kategorija');?>
</h5>
	<?php $_smarty_tpl->_subTemplateRender($_smarty_tpl->tpl_vars['h']->value->get_view_path("frontend/content_types/products/_filter_category_tree_ul.tpl"), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('items'=>$_smarty_tpl->tpl_vars['categories']->value), 0, true);
?>
</div>

<?php echo '<script'; ?>
>
	$(function() {
		$('#products_filter_category_tree li.active').each(function() {
			$(this).parents('li').addClass('childs-active');
		});
		$('#products_filter_category_tree').find('li.parent.active, li.parent.childs-active').each(function() {
			$(this).addClass('expanded');
		});
	});
	$('#products_filter_category_tree ul li').click(function(e){
		e.preventDefault();
		e.stopPropagation();
	});
	$('#products_filter_category_tree ul li > span').click(function(e){
		e.preventDefault();
		e.stopPropagation();
		var $li = $(this).parent();
		if ($(this).is('.arr')) {
			$li.toggleClass('expanded');
			return;
		}
		$li.find('li').removeClass('active').removeClass('childs-active');
		if ($li.is('.active') || $li.is('.childs-active')) {
			$li.removeClass('active').removeClass('childs-active').removeClass('expanded');
		} else {
			$li.addClass('active').addClass('expanded');
		}
		$li.parents('li').each(function(){
			$parent = $(this);
			var count_active = $parent.find('> ul > li.active, > ul > li.childs-active').length;
			var count_empty = $parent.find('> ul > li:not(.active):not(.childs-active)').length;
			if (count_active) {
				$parent.addClass('childs-active').removeClass('active');
			} else {
				$parent.removeClass('childs-active');
			}
		});
		var active_categories = [];
		$('#products_filter_category_tree li.active').each(function() {
			active_categories.push($(this).data('fvalue'));
		});
		if (active_categories.length) {
			window.productFilter.set('categories', active_categories.join());
		} else {
			window.productFilter.clear('categories');
		}

		/*
		var $childs = $li.find('ul');
		if ($childs.length) {
			$li.toggleClass('expanded');
		}*/
	});
	/*
	$('#products_filter_category_tree ul li > span').click(function(e){
		e.preventDefault();
		var $childs = $(this).parent().find('ul');
		if ($childs.length) {
			e.stopPropagation();
			$(this).parent().toggleClass('expanded');
		}
	});*/
<?php echo '</script'; ?>
>
<?php }
}
}
