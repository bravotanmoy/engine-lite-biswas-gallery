{if $element.cookie_bar && $element.active && !$smarty.cookies.NewCookiePolicyShown}
    <div class="cookie_bar clearfix">
        <div class="container-fluid">
            <div class="close"></div>
            <p>{t('Mūsų svetainėje naudojami slapukai, kad užtikrintume jums teikiamų paslaugų kokybę. Išjungdami šį pranešimą arba toliau naršydami šioje svetainėje sutinkate su')} <a href="{$element.full_url}">{t('slapukų naudojimo politika')}</a>.&nbsp;&nbsp;&nbsp;<span class="btn btn-outline-secondary">{t('Sutinku')}</span></p>
        </div>

        {literal}
            <script>
                $(document).ready(function(){
                    $('.cookie_bar .btn').click(function(){
                        setCookie('NewCookiePolicyShown','1','1000');
                        $(".cookie_bar").remove();
                    });
                    $('.cookie_bar .close').click(function(){
                        $(".cookie_bar").remove();
                    });
                });

                function setCookie(cname,cvalue,exdays)
                {
                    let d = new Date();
                    d.setTime(d.getTime()+(exdays*24*60*60*1000));

                    let expires = "expires="+d.toGMTString();
                    document.cookie = cname + "=" + cvalue + ";domain={/literal}{$smarty.const.PROJECT_DOMAIN}{literal};path=/;" + expires;
                }
            </script>
        {/literal}
    </div>
{/if}