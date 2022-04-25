{if $lines}
    <div class="linepromos" style="background: {$lines.0.background_color};">
        <div class="{if $lines|count > 1}owl-carousel{/if}">
            {foreach $lines as $line}
                <div class="item" style="padding: 3px 0; background: {$line.background_color};">
                    {$h->display_html($line.message)}
                </div>
            {/foreach}
        </div>
    </div>
    {if $lines|count > 1}
    {literal}
        <script>
            $(function(){
                var items = $('.linepromos .item');
                $('.linepromos .owl-carousel').owlCarousel({
                    loop: (items.length == 1 ? false : true),
                    items: 1,
                    dots: false,
                    nav: (items.length == 1 ? false : true),
                    navText: ["❮", "❯"],
                    lazyLoad: true,
                    autoplay: true,
                    autoplayTimeout: 5000,
                    autoplayHoverPause: true,
                    smartSpeed: 1000,
                    autoHeight:false,
                });
            });
        </script>
    {/literal}
    {/if}
{/if}