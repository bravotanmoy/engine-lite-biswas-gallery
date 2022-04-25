<div class="list-dropdown list-collapse-mobile" data-hover-delay="100">
    <h4 class="title">{$menu_group.name}</h4>
    <div class="submenu_list submenu_mega">
        <div class="container-fluid">
            <div class="banner">
                {if $menu_group.show_all_button_text}
                    <a href="{$menu_group.full_url}" class="btn btn-outline-secondary d-none">{$menu_group.show_all_button_text}</a>
                {/if}

                {if $menu_group.photo.src}
                    <img class="img-fluid d-none" src="{$menu_group.photo.src}" />
                {*{else}*}
                    {*<h4 class="title hidden-xs">{$menu_group.name}</h4>*}
                {/if}
            </div>

            <div class="submenu_groups">
            <h4 class="title mobile-head list-collapse-mobile d-md-none">{$menu_group.name}</h4>

            {$column = reset($menu_group.menu_columns)}
            <div class="eq-height col-md-{$column.columns}">
                {foreach $menu_group.menu_columns as $column}
                    {if $column.new_column && !$column@first}
                        </div>
                        <div class="eq-height col-md-{$column.columns}">
                    {/if}

                    {if $column.photo && !$column.menu_items}
                        <div class="menu_column {$column.type}">
                            <a href="{$column.full_url}"><img class="img-fluid" src="{$column.photo.src}"/></a>
                        </div>
                    {elseif $column.type == 'html'}
                        <div class="menu_column {$column.type}">
                            {if $column.name}
                                <h5 class="title">{$column.name}</h5>
                            {/if}

                            {$h->display_html($column.html)}
                        </div>
                    {elseif $column.menu_items}
                        <div class="menu_column {$column.type} list-default list-collapse-mobile">
                            <h5 class="title">{$column.name}</h5>

                            {if $column.menu_items}
                            <div class="row col-{$column.columns} level-3">
                                {$columns = array_chunk($column.menu_items, ceil(count($column.menu_items)/$column.columns))}

                                {foreach $columns as $column_items}
                                <div class="col-md-1">
                                    <ul>
                                        <li class="menu_item menu_head d-md-none">
                                            <h5 class="title">{$column.name}</h5>
                                        </li>

                                        {foreach $column_items as $item}
                                        <li class="menu_item{if $item.type == 2} menu_item_banner{/if}{if $item.on_mobile == 3} mobile_hide{/if}">
                                            {if $item.type == 2}
                                                <a class="{if $item.on_mobile == 2}mobile_hide{/if}" href="{$item.full_url}"><img class="img-fluid" title="{$item.name}" src="{$item.photo.src}"/></a>
                                            {if $item.on_mobile == 2}
                                                <a class="mobile_show" href="{$item.full_url}">{$item.name}</a>
                                            {/if}
                                            {else}
                                                <a href="{$item.full_url}">{$item.name}</a>
                                            {/if}
                                        </li>
                                        {/foreach}
                                    </ul>
                                </div>
                                {/foreach}
                            </div>
                            {/if}
                        </div>
                    {elseif $column.full_url}
                        <div class="menu_column col-md-3 {$column.type}{if $column.childs} has_child_list{/if}">
                            <a href="{$column.full_url}">
                                <h5 class="title">{$column.name}</h5>
                            </a>

                            {if $column.childs}
                                {foreach $column.childs as $child}
                                    <a href="{$child.full_url}" class="child">
                                        <span class="level-2">{$child.name}</span>
                                    </a>

                                    {if $child.count > 0 && !empty($child.childs)}
                                        {foreach $child.childs as $cchild}
                                            <a href="{$cchild.full_url}" class="subchild">
                                                <span class="level-3">{$cchild.name}</span>
                                            </a>
                                        {/foreach}
                                    {/if}
                                {/foreach}
                            {/if}
                        </div>
                    {/if}
                {/foreach}
            </div>
        </div>
        </div>
    </div>
</div>
