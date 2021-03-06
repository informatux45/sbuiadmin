{* ------------------------ *}
{* --- Article à voir ----- *}
{* ----> x = Page en cours  *}
{* --> y = Article par page *}
{* ------------------------ *}

<div class="sbnews-clear-both"> </div>

{math equation="x + y" x=$news_page y=$news_per_page assign=rsaquo}
{math equation="x - y" x=$news_page y=$news_per_page assign=lsaquo}
{* --- Derniere page --- *}
{math equation="z * w" z=$news_total w=$news_per_page assign=raquo}
{* --- Numéro Page en cours --- *}
{math equation="l / t" l=$rsaquo t=$news_per_page assign=pageinprogress}

<div class="sbnews-pagination">
	
	{insert name=sbGetCurrentUrlPagination assign="current_url"}
	
	{* Chevrons droits *}
	{if $pageinprogress < $news_total+1 }
		<span><a href="{$current_url}l={$raquo}">&raquo;</a></span>
		<span><a href="{$current_url}l={$rsaquo}">&rsaquo;</a></span>
		&nbsp;&nbsp;
	{/if}
	
	{* Page en cours / Pages totales *}
	<div>{$pageinprogress} / {$news_total+1}</div>

	{* Chevrons gauches *}
	{if $pageinprogress > 1}
		&nbsp;&nbsp;	
		<span><a href="{if $lsaquo > 0}{$current_url}l={$lsaquo}{else}{$current_url|rtrim:"&"}{/if}">&lsaquo;</a></span>
		<span><a href="{$current_url|rtrim:"&"}">&laquo;</a></span>
	{/if}
	
</div>