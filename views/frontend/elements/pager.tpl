{if count($numeriai) > 1}
    <ul class="pagination justify-content-center mt-3>
        <li class="{if $aktyvus_psl <= 1}disabled{/if} page-item"><a class="pagination_link page-link border-0 rounded-0" href="{if $aktyvus_psl <= 1}#{else}?{$url}&{$prefix}page={$aktyvus_psl-1}{$bookmark}{/if}">{if $frontend}<i class="icon-left"></i>{else}&laquo;{/if}</a></li>
        {foreach $numeriai as  $i}
            {if $i-$last>1}<li class="dot page-item"><a nohref class="dots page-link border-0">...</a></li>{/if}
            <li class="{if $aktyvus_psl == $i}active{/if} page-item"><a href="?{$url}&{$prefix}page={$i}{$bookmark}" class="page-link border-0">{$i}</a></li>
            {$last = $i}
        {/foreach}
        <li class="{if $aktyvus_psl >= $puslapiu_sk}disabled{/if} page-item"><a class="pagination_link page-link border-0 rounded-0" href="{if $aktyvus_psl >= $puslapiu_sk}#{else}?{$url}&{$prefix}page={$aktyvus_psl+1}{$bookmark}{/if}">{if $frontend}<i class="icon-right"></i>{else}&raquo;{/if}</a></li>
    </ul>
{/if}

