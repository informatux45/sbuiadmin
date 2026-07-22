{* News Bar Buttons action *}

<div class="data-toolbar" style="margin-bottom:20px">
	<div class="data-toolbar-left">
		<div class="dd-wrap">
			<button type="button" class="btn btn--ghost" data-dropdown style="display:inline-flex;align-items:center;gap:6px">
				Articles
				<svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0"><path d="m6 9 6 6 6-6"/></svg>
			</button>
			<div class="dd-menu" role="menu" style="min-width:220px">
				<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=news">Tous les articles</a>
				<div class="dd-divider"></div>
				<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=news&a=add">+1 article</a>
			</div>
		</div>

		<div class="dd-wrap">
			<button type="button" class="btn btn--ghost" data-dropdown style="display:inline-flex;align-items:center;gap:6px">
				Catégories
				<svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0"><path d="m6 9 6 6 6-6"/></svg>
			</button>
			<div class="dd-menu" role="menu" style="min-width:220px">
				<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=news&a=category">Toutes les catégories</a>
				<div class="dd-divider"></div>
				<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=news&a=categoryadd">+1 catégorie</a>
			</div>
		</div>

		<button class="btn btn--ghost" type="button" onclick="location.href='index.php?p=news&a=settings'">
			Paramètres
		</button>
		<button class="btn btn--ghost" type="button" data-toggle="modal" data-target="#sbnews_shortcodes">
			Shortcodes
		</button>

		{if isset($smarty.get.a) && $smarty.get.a == 'category'}
			<button class="btn btn--primary" type="button" onclick="location.href='index.php?p=news&a=sort'">
				Trier les catégories
			</button>
		{/if}

		{if isset($smarty.get.a) && $smarty.get.a == 'tpl_single'}
			<button class="btn btn--primary" type="button" onclick="location.href='index.php?p=news&a=tpl_list&id={$smarty.get.id}'">
				Template LIST
			</button>
		{elseif isset($smarty.get.a) && $smarty.get.a == 'tpl_list'}
			<button class="btn btn--primary" type="button" onclick="location.href='index.php?p=news&a=tpl_single&id={$smarty.get.id}'">
				Template SINGLE
			</button>
		{/if}
	</div>
</div>

{if !isset($smarty.get.a)}
<div class="card" style="margin-bottom:20px">
	<div class="card-head">
		<div class="card-title-wrap">
			<span class="eyebrow">Aperçu</span>
			<h2 class="card-title">Infos News</h2>
		</div>
	</div>
	<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:18px">
		<div class="stat-cell">
			<div class="stat-cell-label">Articles totaux</div>
			<div class="stat-cell-value">{$total_news}</div>
		</div>
		<div class="stat-cell">
			<div class="stat-cell-label">Articles actifs</div>
			<div class="stat-cell-value">{$total_news_active}</div>
		</div>
		<div class="stat-cell">
			<div class="stat-cell-label">Catégories totales</div>
			<div class="stat-cell-value">{$total_categories}</div>
		</div>
		<div class="stat-cell">
			<div class="stat-cell-label">Catégories actives</div>
			<div class="stat-cell-value">{$total_categories_active}</div>
		</div>
	</div>
</div>
{/if}