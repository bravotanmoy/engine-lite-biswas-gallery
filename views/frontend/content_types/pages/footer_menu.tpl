<div id="pages_footer_menu">
    {foreach from=$elements item="menu_group"}
        {$frontend->view('pages/footer_menu_list', $menu_group)}
    {/foreach}
</div>