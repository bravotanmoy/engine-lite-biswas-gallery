{if !empty($elements)}
    {$desktop_count = 0}
    {$mobile_count = 0}
    {foreach from=$elements item="element"}
        {if $element.visible_desktop}{$desktop_count = $desktop_count+1}{/if}
        {if $element.visible_mobile}{$mobile_count = $mobile_count+1}{/if}
    {/foreach}
    <div id="banners_hero" class="banners{if !$mobile_count} d-none d-sm-block{/if}{if !$desktop_count} d-sm-none{/if}">
        <div class="container-fluid">
            <div id="hero_slider">
                <div class="carousel-inner-x">
                    {foreach $elements as $element}
                        <div class="item{if $element@first} active{/if}">
                            <div class="banner_img">
                                {$h->picture($element.photo, "width=1120&height=525&fill=1&bg_color=FFFFFF&mode=crop&quality=90", "{$element.photo.name}", "", "", "banner_picture")}
                            </div>

                            <div class="title_wrp">
                                {if !$element.hide_text}
                                    {capture assign="banner_content"}
                                        <div class="banner_content{if $element.color_theme} color-theme-{$element.color_theme}{/if}" style="{if $element.text_color}color:{$element.text_color}; {/if}{if $element.vertical_align} vertical-align: {$element.vertical_align};{/if}">
                                            {if $element.name}<h1 class="title">{$h->display_html($element.name)}</h1>{/if}
                                            {if $element.description}<p class="desc">{$element.description|nl2br}</p>{/if}
                                            {if $element.link}<button {*if $element.text_color}style="color:{$element.text_color}; border-color:{$element.text_color};"{/if}*} class="btn btn-primary btn-lg rounded-0">{t('Plačiau')}</button>{/if}
                                        </div>
                                    {/capture}
                                {/if}

                                {if $element.link}
                                    <a href="{$element.link}" {if $element.blank}target="_blank"{/if} class="container-fluid vcenter" style="text-align: {$element.text_align};">
                                        {$banner_content}
                                    </a>
                                {else}
                                    <div class="container-fluid vcenter" style="text-align: {$element.text_align};">
                                        {$banner_content}
                                    </div>
                                {/if}
                            </div>
                        </div>
                    {/foreach}
                </div>
            </div>
            <div class="owl-nav owl-out-nav"></div>
            {*<div class="owl-dots owl-out-dots"></div>*}
        </div>

        <script>
            $(function(){
                $('#hero_slider').addClass('owl-container');
                $('#hero_slider .carousel-inner-x').each(function () {
                    // Add main classes
                    $(this).addClass('owl-carousel');
                    // Options
                    $(this).owlCarousel({
                        autoHeight: true,
                        loop: true,
                        autoplay: true,
                        autoplayTimeout: 5000,
                        autoplayHoverPause: true,
                        nav: true,
                        navText: [
                            "<span class='icon icon-left-big'></span>",
                            "<span class='icon icon-right-big'></span>",
                        ],
                        responsiveClass: true,
                        navContainer: '#banners_hero .owl-out-nav',
    //                    dotsContainer: '.owl-out-dots',
                        slideBy: 'page',
                        dots: false,
                        responsive: {
                            0: {
                                items: 1
                            },
                            750: {
                                items: 1
                            },
                            970: {
                                items: 1
                            },
                            1170: {
                                items: 1
                            }
                        }
                    });
                });
            });
        </script>

    </div>
{/if}