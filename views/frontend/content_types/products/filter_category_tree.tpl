{if $categories}
<div id="products_filter_category_tree" class="filter-group list-collapse-mobile list-tree list-tree-checkboxes ajax">
	<h5 class="title">{t('Kategorija')}</h5>
	{include $h->get_view_path("frontend/content_types/products/_filter_category_tree_ul.tpl") items=$categories}
</div>

<script>
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
</script>
{/if}