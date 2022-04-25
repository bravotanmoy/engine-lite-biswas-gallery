<div id="pages_mega_menu">
	<div id="mega_menu_overlay"></div>
	<ul id="mega_menu">
		{foreach $elements as $menu_group}
			{if $menu_group.show_type == 'mega_menu'}
				<li class="mega_element">
					{$frontend->view('pages/mega_menu_panel', $menu_group)}
				</li>
			{else}
				<li>
					{$frontend->view('pages/mega_menu_list', $menu_group)}
				</li>
			{/if}
		{/foreach}

		{$frontend->view('pages/profile_menu_mobile')}
		{$frontend->view('pages/languages_menu_mobile')}
	</ul>
</div>

<script>
    $(function () {
        $('.list-dropdown').each(function () {
            let listDropdown = $(this);

            listDropdown.find('.mobile-head').on('click', function () {
                listDropdown.removeClass('open').removeClass('hover');
                listDropdown.find('.submenu_list').removeAttr('style');
            });
        });

        $('.mega_element').each(function () {
            let megaElement = $(this);

            megaElement.find('.menu_head').on('click', function () {
                megaElement.find('.menu_column').removeClass('open');
            });
        })
    })
</script>