{if $menu_group.menu_columns}
    <div class="list-dropdown list-collapse-mobile" data-hover-delay="100">
        <h4 class="title"><a href="{$menu_group.full_url}">{$menu_group.name}</a></h4>
        {if true || $menu_group.level > 1}
            <div class="submenu_list">
                <ul>
                    <li class="d-md-none mobile-head list-collapse-mobile level-2">
                        <h4 class="title">{$menu_group.name}</h4>
                    </li>

                    {foreach from=$menu_group.menu_columns item="menu_column"}
                        {if in_array($menu_group.type, ['category_list', 'page_list', 'brand_list'])}
                            <li {if $menu_group.level > 2 && isset($menu_column.childs) && !empty($menu_column.childs)}class="has_child"{/if}>
                                <a href="{$menu_column.full_url}" title="{$menu_column.name}">
                                    <h4 class="level-2">{$menu_column.name}</h4>
                                </a>

                                {if $menu_group.level > 2 && isset($menu_column.childs) && !empty($menu_column.childs)}
                                    <ul class="submenu_list">
                                        {foreach from=$menu_column.childs item="sub_element"}
                                            <li><a href="{$sub_element.full_url}" title="{$sub_element.name}" class=""><span class="level-2">{$sub_element.name}</span></a></li>
                                        {/foreach}
                                    </ul>
                                {/if}
                            </li>
                        {else}
                            <li {if isset($menu_column.menu_items) && !empty($menu_column.menu_items)}class="has_child"{/if}>
                                <a href="{$menu_column.full_url}" title="{$menu_column.name}" class="" {if $menu_column.new_window}target="_blank"{/if}>
                                    <span class="level-2">{$menu_column.name}</span>
                                </a>

                                {if isset($menu_column.menu_items) && !empty($menu_column.menu_items)}
                                    <ul class="submenu_list">
                                        {foreach from=$menu_column.menu_items item="sub_element"}
                                            <li><a href="{$sub_element.full_url}" title="{$sub_element.name}" class="" {if $sub_element.new_window}target="_blank"{/if}><span class="level-3">{$sub_element.name}</span></a></li>
                                        {/foreach}
                                    </ul>
                                {/if}
                            </li>
                        {/if}
                    {/foreach}

                    {if isset($menu_group.element)}
                        <li class="d-md-none">
                            <a href="{$menu_group.element.full_url}" title="{t('Peržiūrėti viską')}"><h4 class="level-2">{t('Peržiūrėti viską')}</h4></a>
                        </li>
                    {/if}
                </ul>
            </div>
        {/if}
    </div>
{elseif isset($menu_group.link) && strlen($menu_group.link) > 0}
    {if in_array($menu_group.type, ['category_list', 'page_list'])}
        {assign var='element_link' value=$menu_group.element.full_url}
    {else}
        {assign var='element_link' value=$menu_group.full_url}
    {/if}

    <a href="{$element_link}" {if $menu_group.new_window}target="_blank"{/if}>
        <h4 class="title">{$menu_group.name}</h4>
    </a>
{/if}
