{* ============================================== *}
{* Module SEARCH - resultats de recherche         *}
{* ---------------------------------------------- *}
{* Aucune donnee n'est mise en forme ici : le     *}
{* controleur (search.php) fournit deja des       *}
{* titres echappes et des extraits surlignes.     *}
{* ============================================== *}

<link href="{$smarty.const.SB_MODULES_URL}search/inc/search.css" rel="stylesheet" />

<div class="sbsearch">

	<div class="sbsearch-head">
		{$sb_search_form}
	</div>

	{* -------- Aucune recherche demandee -------- *}
	{if $sb_search_state == 'empty'}

		<p class="sbsearch-msg">{$smarty.const._CMS_SEARCH_EMPTY}</p>

	{* -------- Saisie trop courte -------- *}
	{elseif $sb_search_state == 'tooshort'}

		<p class="sbsearch-msg">{$smarty.const._CMS_SEARCH_TOOSHORT|@sprintf:$sb_search_min_length}</p>

	{* -------- Recherche effectuee -------- *}
	{else}

		{if $sb_search_total == 0}

			<p class="sbsearch-msg">{$smarty.const._CMS_SEARCH_NORESULT|@sprintf:$sb_search_query}</p>

		{else}

			<p class="sbsearch-count">
				{if $sb_search_total == 1}
					{$smarty.const._CMS_SEARCH_RESULT|@sprintf:$sb_search_query}
				{else}
					{$smarty.const._CMS_SEARCH_RESULTS|@sprintf:$sb_search_total:$sb_search_query}
				{/if}
			</p>

			<ul class="sbsearch-list">
				{foreach from=$sb_search_results item=result}
					<li class="sbsearch-item sbsearch-item-{$result.module}">

						<span class="sbsearch-type">{$result.type}</span>

						<h3 class="sbsearch-title">
							{if $result.url}
								<a href="{$result.url}">{$result.title}</a>
							{else}
								{$result.title}
								<span class="sbsearch-nolink">{$smarty.const._CMS_SEARCH_NOLINK}</span>
							{/if}
						</h3>

						{if $result.excerpt}
							<p class="sbsearch-excerpt">{$result.excerpt}</p>
						{/if}

						{if $result.host}
							<p class="sbsearch-host">{$smarty.const._CMS_SEARCH_SEEIN} {$result.host}</p>
						{/if}

					</li>
				{/foreach}
			</ul>

			{if $sb_search_prev || $sb_search_next}
				<p class="sbsearch-pagination">
					{if $sb_search_prev}<a class="sbsearch-prev" href="{$sb_search_prev}">&laquo; {$smarty.const._CMS_SEARCH_PREVIOUS}</a>{/if}
					{if $sb_search_next}<a class="sbsearch-next" href="{$sb_search_next}">{$smarty.const._CMS_SEARCH_NEXT} &raquo;</a>{/if}
				</p>
			{/if}

		{/if}

	{/if}

</div> <!-- End sbsearch -->
