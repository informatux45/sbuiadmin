{* --- GRADUATES display a article (item) --- *}

{include file='graduates/tpls/graduates_header.tpl'}

{include file='graduates/tpls/graduates_breadcrumb.tpl'}

{if $item}
	{* Check if TPL_SINGLE custom *}
	{if $sbgraduates_item_tpl_single != ''}
	
		{* Show TPL SINGLE template *}
		{eval var=$sbgraduates_item_tpl_single}
		
	{else}
		
		{* Show Item graduates DEFAULT *}
		<h3 class="sbgraduates-single-h3">{$item.name|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}</h3>
		
		<h4 class="sbgraduates-single-h4">
			{if $item.breeder|unescape:"htmlall"}
				<span id="item-breeder">{$smarty.const._CMS_GRADUATES_BREEDER} {$item.breeder|unescape:"htmlall"}</span><br>
			{/if}
			{if $item.owner|unescape:"htmlall"}
				<span id="item-owner">{$smarty.const._CMS_GRADUATES_OWNER} {$item.owner|unescape:"htmlall"}</span>
			{/if}
		</h4>
		
		{if $item.photo}
			<img class="sbgraduates-single-img" src="{$smarty.const._AM_MEDIAS_URL}/{$item.photo}" alt="{$item.name|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}">
		{/if}

		<div class="sbgraduates-clear-both"> </div>
		
		<div class="sbgraduates-single">
			
			{if $item.perf_1}
			<div>
				<a href="{$item.video_1}" target="_blank">
					<img src="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/images/media_video.jpg" alt="VIDEO" />
					<span>{$item.perf_1}</span>
				</a>
			</div>
			{/if}

			{if $item.perf_2}
			<div>
				<a href="{$item.video_2}" target="_blank">
					<img src="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/images/media_video.jpg" alt="VIDEO" />
					<span>{$item.perf_2}</span>
				</a>
			</div>
			{/if}

			{if $item.perf_3}
			<div>
				<a href="{$item.video_3}" target="_blank">
					<img src="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/images/media_video.jpg" alt="VIDEO" />
					<span>{$item.perf_4}</span>
				</a>
			</div>
			{/if}

			{if $item.perf_4}
			<div>
				<a href="{$item.video_4}" target="_blank">
					<img src="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/images/media_video.jpg" alt="VIDEO" />
					<span>{$item.perf_4}</span>
				</a>
			</div>
			{/if}

			{if $item.perf_5}
			<div>
				<a href="{$item.video_5}" target="_blank">
					<img src="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/images/media_video.jpg" alt="VIDEO" />
					<span>{$item.perf_5}</span>
				</a>
			</div>
			{/if}

			{if $item.perf_6}
			<div>
				<a href="{$item.video_6}" target="_blank">
					<img src="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/images/media_video.jpg" alt="VIDEO" />
					<span>{$item.perf_6}</span>
				</a>
			</div>
			{/if}

			{if $item.perf_7}
			<div>
				<a href="{$item.video_7}" target="_blank">
					<img src="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/images/media_video.jpg" alt="VIDEO" />
					<span>{$item.perf_7}</span>
				</a>
			</div>
			{/if}

			{if $item.perf_8}
			<div>
				<a href="{$item.video_8}" target="_blank">
					<img src="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/images/media_video.jpg" alt="VIDEO" />
					<span>{$item.perf_8}</span>
				</a>
			</div>
			{/if}

			{if $item.perf_9}
			<div>
				<a href="{$item.video_9}" target="_blank">
					<img src="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/images/media_video.jpg" alt="VIDEO" />
					<span>{$item.perf_9}</span>
				</a>
			</div>
			{/if}

			{if $item.perf_10}
			<div>
				<a href="{$item.video_10}" target="_blank">
					<img src="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/images/media_video.jpg" alt="VIDEO" />
					<span>{$item.perf_10}</span>
				</a>
			</div>
			{/if}

		</div>
		
		<div class="sbgraduates-clear-both"> </div>
		
	{/if}

{else}

	{$smarty.const._CMS_GRADUATES_NOITEM}

{/if}