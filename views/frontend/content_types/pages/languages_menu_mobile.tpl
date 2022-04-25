{if $languages}
    <li id="languages_menu_mobile" class="d-md-none">
        <div class="list-dropdown list-collapse-mobile" data-hover-delay="100">
            <h4 class="title">
                {t('Kalba')}

                <i class="icon-kalba icon"></i>
                <img src="{$smarty.const.PROJECT_URL}images/languages/{$frontend->lang_key|lower}.svg"/>
            </h4>

            <div class="submenu_list">
                <ul>
                    <li class="d-md-none mobile-head list-collapse-mobile level-2">
                        <h4 class="title">
                            {t('Pasirinkite kalbą')}

                            <i class="icon-kalba icon"></i>
                        </h4>
                    </li>

                    {foreach $languages as $lang}
                        <li>
                            <a href="{$smarty.const.PROJECT_URL}{$lang.language|lower}">
                                <h4 class="level-2">{$lang.name} <img src="{$smarty.const.PROJECT_URL}images/languages/{$lang.language|lower}.svg"/></h4>
                            </a>
                        </li>
                    {/foreach}
                </ul>
            </div>
        </div>
    </li>
{/if}
