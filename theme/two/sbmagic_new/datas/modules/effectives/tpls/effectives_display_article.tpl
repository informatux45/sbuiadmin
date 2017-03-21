{* --- EFFECTIVES display a article (item) --- *}

{include file='effectives/tpls/effectives_header.tpl'}

{include file='effectives/tpls/effectives_breadcrumb.tpl'}

{if $item}
	{* Check if TPL_SINGLE custom *}
	{if $sbeffectives_item_tpl_single != ''}
	
		{* Show TPL SINGLE template *}
		{eval var=$sbeffectives_item_tpl_single}
		
	{else}
		
		{* Show Item effectives DEFAULT *}
		<h3 class="sbeffectives-single-h3">{$item.name|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}</h3>
		
		<h4 class="sbeffectives-single-h4">
			{if $item.colour|unescape:"htmlall"}
				<span id="item-colour">{$item.colour|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}</span>
			{/if}
			{if $item.size|unescape:"htmlall"}
				<span id="item-size">- {$item.size|unescape:"htmlall"}</span>
			{/if}
			{if $item.origine|unescape:"htmlall" && $item.date}
				<span id="item-origine">- {$smarty.const._CMS_EFFECTIVES_BORN} {$item.origine|unescape:"htmlall"} {$smarty.const._CMS_EFFECTIVES_BORNDATE} {$item.date|@sbConvertDate:"YEAR"}</span>
			{/if}
			{if $item.breeder|unescape:"htmlall"}
				<span id="item-breeder">- {$smarty.const._CMS_EFFECTIVES_BREEDER} {$item.breeder|unescape:"htmlall"}</span>
			{/if}
			{if $item.owner|unescape:"htmlall"}
				<span id="item-owner">- {$smarty.const._CMS_EFFECTIVES_OWNER} {$item.owner|unescape:"htmlall"}</span>
			{/if}
			{if $item.chrono|unescape:"htmlall"}
				<span id="item-chrono">- {$item.chrono|unescape:"htmlall"}</span>			
			{/if}
			{if $item.status|unescape:"htmlall"}
				<span id="item-status">- {$item.status|unescape:"htmlall"}</span>
			{/if}
			{if $item.winnings|unescape:"htmlall"}
				<span id="item-winnings">- {$item.winnings|unescape:"htmlall"}</span>
			{/if}
		</h4>
		
		<div class="sbeffectives-single">

			{if $item.description|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}
			<div class="sbeffectives-single-desc even">
				{if $sbeffectives_options.title_description|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}
					<h5>{$sbeffectives_options.title_description|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}</h5>
				{/if}
				{$item.description|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"|@sbGetShortcode}
				{if $item.pedigree OR $item.pedigree_extend}
					<div class="sbeffectives-single-pdf">
						{if $item.pedigree}
							<span class="pdf">
								<a href="{$smarty.const._AM_MEDIAS_URL}/{$item.pedigree}" target="_blank">
									<img src="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/images/pdf-pedigree.png" title="Pedigree" />
								</a>
							</span>
						{/if}
						{if $item.pedigree_extend}
							<span class="pdf">
								<a href="{$smarty.const._AM_MEDIAS_URL}/{$item.pedigree_extend}" target="_blank">
									<img src="{$smarty.const.SB_MODULES_URL}{$smarty.const.MODULEFILE}/inc/images/pdf-pedigree-extend.png" title="Pedigree Extend" />
								</a>
							</span>
						{/if}
					</div>
				{/if}
			</div>
			{/if}

			{if $item.pedigree_desc|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}
			<div class="sbeffectives-single-pedigree odd">
				{if $sbeffectives_options.title_pedigree|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}
					<h5>{$sbeffectives_options.title_pedigree|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}</h5>
				{/if}
				{$item.pedigree_desc|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"|@sbGetShortcode}
			</div>
			{/if}

			{if $production}
			<div class="sbeffectives-single-production even">
				{if $sbeffectives_options.title_production|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}
					<h5>{$sbeffectives_options.title_production|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}</h5>
				{/if}
				{foreach from=$production item=prod}
					<p>
						{if $prod.date}
							{$prod.date|@sbConvertDate:"YEAR"} -
						{/if}
						{if $prod.group_bold}
							<b>{$prod.name|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}</b>
						{else}
							{$prod.name|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}
						{/if}

						{if $prod.sex}
							({$prod.sex|lower}.{if $prod.colour|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"} {$prod.colour|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}{/if})
						{/if}

						{if $prod.photo}
							{insert name=sbeffectives_medias type="photo" url="`$prod.photo`" title="`$prod.name`" media="icon"}
						{/if}
						{if $prod.video}
							{insert name=sbeffectives_medias type="youtube" url="`$prod.video`" title="`$prod.name`" media="icon"}
						{/if}
						{if $prod.pedigree}
							{insert name=sbeffectives_medias type="pdf" url="`$prod.pedigree`" title="`$prod.name`" media="icon"}
						{/if}
						
						{if $prod.dam|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}
							<br>
							<i class="sbeffectives-single-dam-sire">
							{$prod.dam|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}
							{if $prod.sire_dam|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}
								{$smarty.const._CMS_EFFECTIVES_BY}
								{$prod.sire_dam|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}
							{/if}
							</i>
						{/if}

						{if $prod.performance|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}
							<br>
							{$prod.performance|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}
						{/if}
					</p>
				{foreachelse}
					{$smarty.const._CMS_EFFECTIVES_NOPROD}
				{/foreach}
			</div>
			{/if}

			{if $medias}
			<div class="sbeffectives-single-medias odd">
				{if $sbeffectives_options.title_medias|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}
					<h5>{$sbeffectives_options.title_medias|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}</h5>
				{/if}
				
				{foreach from=$medias item=media}
					{insert name=sbeffectives_medias type="`$media.type`" url="`$media.url`" title="`$media.title`"}
				{foreachelse}
					{$smarty.const._CMS_EFFECTIVES_NOMEDIAS}
				{/foreach}
			</div>
			{/if}
			
			{if $item.description_extend|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}
			<div class="sbeffectives-single-desc-extend even">
				{if $sbeffectives_options.title_description_extend|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}
					<h5>{$sbeffectives_options.title_description_extend|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}</h5>
				{/if}
				{$item.description_extend|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"|@sbGetShortcode}
			</div>
			{/if}

		</div>
		
		<div class="sbeffectives-clear-both"> </div>
		
	{/if}

{else}

	{$smarty.const._CMS_EFFECTIVES_NOITEM}

{/if}