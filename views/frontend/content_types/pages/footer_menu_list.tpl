{if $menu_group.menu_columns}
    <div class="list-default">
        <h4 class="title">{$menu_group.name}</h4>
        {if true || $menu_group.level > 1}
            <div class="submenu_list">
                <ul>
                    {foreach from=$menu_group.menu_columns item="menu_column"}
                        {if in_array($menu_group.type, ['category_list', 'page_list', 'brand_list'])}
                            <li {if $menu_group.level > 2 && isset($menu_column.childs) && !empty($menu_column.childs)}class="has_child"{/if}>
                                <a href="{$menu_column.full_url}" title="{$menu_column.name}">{$menu_column.name}</a>

                                {if $menu_group.level > 2 && isset($menu_column.childs) && !empty($menu_column.childs)}
                                    <ul class="submenu_list">
                                        {foreach from=$menu_column.childs item="sub_element"}
                                            <li><a href="{$sub_element.full_url}" title="{$sub_element.name}" class="">{$sub_element.name}</a></li>
                                        {/foreach}
                                    </ul>
                                {/if}
                            </li>
                        {else}
                            <li {if isset($menu_column.menu_items) && !empty($menu_column.menu_items)}class="has_child"{/if}>
                                <a href="{$menu_column.full_url}" title="{$menu_column.name}" class="" {if $menu_column.new_window}target="_blank"{/if}>{$menu_column.name}</a>

                                {if isset($menu_column.menu_items) && !empty($menu_column.menu_items)}
                                    <ul class="submenu_list">
                                        {foreach from=$menu_column.menu_items item="sub_element"}
                                            <li><a href="{$sub_element.full_url}" title="{$sub_element.name}" class="" {if $sub_element.new_window}target="_blank"{/if}>{$sub_element.name}</a></li>
                                        {/foreach}
                                    </ul>
                                {/if}
                            </li>
                        {/if}
                    {/foreach}
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