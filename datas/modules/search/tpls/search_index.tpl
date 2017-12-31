{* --- Show Search Result Infos --- *}

{if $sb_no_search || $sb_search_empty}

	<div class="acenter">
		<p>&nbsp;</p>
		<img src="{$smarty.const.SB_MODULES_URL}real_estate/images/search_result_empty.jpg" alt="" />
		<h2 style="line-height: 1em;">
			{if $sb_no_search}
				{$smarty.const._CMS_REAL_ESTATE_SEARCH_NO_RESULT}
			{else}
				{$smarty.const._CMS_REAL_ESTATE_SEARCH_EMPTY_CRITERIA}
			{/if}
		</h2>
	</div>

{else}

	<h3>
		{if $bien_total > 1}
			{$smarty.const._CMS_REAL_ESTATE_SEARCH_RESULT_BEFORE} {$smarty.const._CMS_GLOBAL_RESULTS|@sprintf:"`$bien_total`"}
		{else}
			{$smarty.const._CMS_REAL_ESTATE_SEARCH_RESULT_BEFORE} {$smarty.const._CMS_GLOBAL_RESULT}
		{/if}
	</h3>

	{include file='real_estate/tpls/real_estate_index.tpl'}
	
{/if}