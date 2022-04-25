<picture class="{$picture_class}">
    {if $webp}
        <source srcset="{$webp}{if $webp2x} 1x, {$webp2x} 2x{/if}" type="image/webp"
                media="(min-width: 769px)">
        {if !$only_desktop}
            <source srcset="{$webpmobile}" type="image/webp" media="(max-width: 768px)">
        {/if}
    {/if}
    <source srcset="{$src}{if $src2x} 1x, {$src2x} 2x{/if}" media="(min-width: 769px)"/>
    {if !$only_desktop}
        <source srcset="{$srcmobile}" type="image/webp" media="(max-width: 768px)">
    {/if}
    <img loading="lazy" class="img-responsive {$class} {if $only_desktop}hidden-xs hidden-sm{/if}" src="{$src}" alt="{$alt}" {$additional_attr}>
</picture>