{* ------------------------ *}
{* --- Article à voir ----- *}
{* ----> x = Page en cours  *}
{* --> y = Article par page *}
{* ------------------------ *}

<div class="sbeffectives-clear-both"> </div>

{math equation="x + y" x=$effectives_page y=$effectives_per_page assign=rsaquo}
{math equation="x - y" x=$effectives_page y=$effectives_per_page assign=lsaquo}
{* --- Derniere page --- *}
{math equation="z * w" z=$effectives_total w=$effectives_per_page assign=raquo}
{* --- Numéro Page en cours --- *}
{math equation="l / t" l=$rsaquo t=$effectives_per_page assign=pageinprogress}

<div class="sbeffectives-pagination">
	
	{insert name=sbGetCurrentUrlPagination assign="current_url"}
	
	{* Chevrons droits *}
	{if $pageinprogress < $effectives_total+1 }
		<span><a href="{$current_url}l={$raquo}">&raquo;</a></span>
		<span><a href="{$current_url}l={$rsaquo}">&rsaquo;</a></span>
		&nbsp;&nbsp;
	{/if}
	
	{* Page en cours / Pages totales *}
	<div>{$pageinprogress} / {$effectives_total+1}</div>

	{* Chevrons gauches *}
	{if $pageinprogress > 1}
		&nbsp;&nbsp;	
		<span><a href="{if $lsaquo > 0}{$current_url}l={$lsaquo}{else}{$current_url|rtrim:"&"}{/if}">&lsaquo;</a></span>
		<span><a href="{$current_url|rtrim:"&"}">&laquo;</a></span>
	{/if}
	
</div>