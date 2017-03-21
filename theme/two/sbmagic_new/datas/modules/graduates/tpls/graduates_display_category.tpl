{* --- GRADUATES display a specific category --- *}

{include file='graduates/tpls/graduates_header.tpl'}

{include file='graduates/tpls/graduates_breadcrumb.tpl'}

{if $sbgraduates_module_show == 'masonry'}
	<link rel="stylesheet" href="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/style_category_masonry.css">
	<style>
		.sbgraduates {
			max-width: {$sbgraduates_module_show_masonry|intval|default:"500"}px;
			width: 100%;
		}
	</style>
	<script src="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/masonry.pkgd.min.js"></script>
{elseif $sbgraduates_module_show == 'float'}
	<link rel="stylesheet" href="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/style_category_float.css">
{elseif $sbgraduates_module_show == 'masonrycss'}
	<link rel="stylesheet" href="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/style_category_masonrycss.css">
{else}
	<link rel="stylesheet" href="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/style_category_normal.css">
{/if}

{if $sbgraduates_module_show == 'masonry'}
	<div id="sbgraduates" data-masonry='{ "itemSelector": ".sbgraduates" }'>
{else}
	<div id="sbgraduates">	
{/if}

{foreach from=$all item=graduates}
	{* Check if TPL_LIST custom *}
	{if $sbgraduates_cat_tpl_list != ''}
	
		{* Show TPL LIST template *}
		{eval var=$sbgraduates_cat_tpl_list}
		
	{else}
		
		{* Show List graduates DEFAULT *}
		<div class="sbgraduates {cycle values="even,odd"}">

			<div class="sbgraduates-div-l">
				<a class="" href="{seo url="index.php?p=graduates&op=article&id={$graduates.id}" rewrite="graduates/article/{$graduates.id}/{$graduates.name|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"|strip_tags|@sbRewriteString|@strtolower}"}">
					<img src="{$smarty.const._AM_MEDIAS_URL}/{$graduates.photo}" alt="{$graduates.name|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}" style="width: 100%;">
				</a>
			</div>
		
			<div class="sbgraduates-div-r">
				<h3>
					{$graduates.name|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}
				</h3>
				<p class="sbgraduates-info">
					{$graduates.sire_dam_info|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}
				</p>
				<p class="sbgraduates-p-info">
					{$smarty.const._CMS_GRADUATES_BREEDER}: {$graduates.breeder|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}
					<br>
					{$smarty.const._CMS_GRADUATES_OWNER}: {$graduates.owner|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}
				</p>
				<div class="sbgraduates-p-perf">
					{if $graduates.perf_1}
						<div>
						<span class="sbgraduates-perf">{$graduates.perf_1}&nbsp;</span>
						{if $graduates.video_1}
							<span class="sbgraduates-perf-v">
								<a href="{$graduates.video_1}" target="_blank">
									<img src="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/images/media_video_icon.png" alt="VIDEO" />
								</a>
							</span>&nbsp;
						{/if}
						</div>
					{/if}
					{if $graduates.perf_2}
						<div>
						<span class="sbgraduates-perf">{$graduates.perf_2}&nbsp;</span>
						{if $graduates.video_2}
							<span class="sbgraduates-perf-v">
								<a href="{$graduates.video_2}" target="_blank">
									<img src="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/images/media_video_icon.png" alt="VIDEO" />
								</a>
							</span>&nbsp;
						{/if}
						</div>
					{/if}
					{if $graduates.perf_3}
						<div>
						<span class="sbgraduates-perf">{$graduates.perf_3}&nbsp;</span>
						{if $graduates.video_3}
							<span class="sbgraduates-perf-v">
								<a href="{$graduates.video_3}" target="_blank">
									<img src="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/images/media_video_icon.png" alt="VIDEO" />
								</a>
							</span>&nbsp;
						{/if}
						</div>
					{/if}
					{if $graduates.perf_4}
						<div>
						<span class="sbgraduates-perf">{$graduates.perf_4}&nbsp;</span>
						{if $graduates.video_4}
							<span class="sbgraduates-perf-v">
								<a href="{$graduates.video_4}" target="_blank">
									<img src="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/images/media_video_icon.png" alt="VIDEO" />
								</a>
							</span>&nbsp;
						{/if}
						</div>
					{/if}
					{if $graduates.perf_5}
						<div>
						<span class="sbgraduates-perf">{$graduates.perf_5}&nbsp;</span>
						{if $graduates.video_5}
							<span class="sbgraduates-perf-v">
								<a href="{$graduates.video_5}" target="_blank">
									<img src="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/images/media_video_icon.png" alt="VIDEO" />
								</a>
							</span>&nbsp;
						{/if}
						</div>
					{/if}
					{if $graduates.perf_6}
						<div>
						<span class="sbgraduates-perf">{$graduates.perf_6}&nbsp;</span>
						{if $graduates.video_6}
							<span class="sbgraduates-perf-v">
								<a href="{$graduates.video_6}" target="_blank">
									<img src="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/images/media_video_icon.png" alt="VIDEO" />
								</a>
							</span>&nbsp;
						{/if}
						</div>
					{/if}
					{if $graduates.perf_7}
						<div>
						<span class="sbgraduates-perf">{$graduates.perf_7}&nbsp;</span>
						{if $graduates.video_7}
							<span class="sbgraduates-perf-v">
								<a href="{$graduates.video_7}" target="_blank">
									<img src="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/images/media_video_icon.png" alt="VIDEO" />
								</a>
							</span>&nbsp;
						{/if}
						</div>
					{/if}
					{if $graduates.perf_8}
						<div>
						<span class="sbgraduates-perf">{$graduates.perf_8}&nbsp;</span>
						{if $graduates.video_8}
							<span class="sbgraduates-perf-v">
								<a href="{$graduates.video_8}" target="_blank">
									<img src="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/images/media_video_icon.png" alt="VIDEO" />
								</a>
							</span>&nbsp;
						{/if}
						</div>
					{/if}
					{if $graduates.perf_9}
						<div>
						<span class="sbgraduates-perf">{$graduates.perf_9}&nbsp;</span>
						{if $graduates.video_9}
							<span class="sbgraduates-perf-v">
								<a href="{$graduates.video_9}" target="_blank">
									<img src="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/images/media_video_icon.png" alt="VIDEO" />
								</a>
							</span>&nbsp;
						{/if}
						</div>
					{/if}
					{if $graduates.perf_10}
						<div>
						<span class="sbgraduates-perf">{$graduates.perf_10}&nbsp;</span>
						{if $graduates.video_10}
							<span class="sbgraduates-perf-v">
								<a href="{$graduates.video_10}" target="_blank">
									<img src="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/images/media_video_icon.png" alt="VIDEO" />
								</a>
							</span>&nbsp;
						{/if}
						</div>
					{/if}
				</div>
				<p class="sbgraduates-link-item">
					<a href="{seo url="index.php?p=graduates&op=article&id={$graduates.id}" rewrite="graduates/article/{$graduates.id}/{$graduates.name|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"|strip_tags|@sbRewriteString|@strtolower}"}">
						{$smarty.const._CMS_GRADUATES_READ_ITEM}
					</a>
				</p>
			</div>

			<div class="sbgraduates-clear-both"> </div>

		</div>
		
	{/if}

{foreachelse}

	{$smarty.const._CMS_GRADUATES_NOGRADUATES}

{/foreach}

</div> {* --- End div sbgraduates --- *}

	{if $all}
		{include file='graduates/tpls/graduates_pagination.tpl'}
	{/if}
