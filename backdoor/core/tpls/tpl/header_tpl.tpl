{* Header TPL *}

			{* --- Page Title --- *}
			<div class="row header-title-row">
				<h3>{if $page_title}{$page_title|upper}{else}{$pageindex|upper}{/if}</h3>
				<div class="breadcrumbx">
					{if $page_title}
						<a href="{$smarty.const._AM_SITE_URL}"><i class="fa fa-home fa-fw"></i> Dashboard</a>
						&nbsp;&raquo;&nbsp;
						<a href="{if $smarty.get.p}{$smarty.const._AM_SITE_URL}index.php?p={$smarty.get.p}{else}#{/if}">{$page_title|lower|@ucfirst}</a>
						{if $smarty.get.a}
							&nbsp;&raquo;&nbsp;
							{$smarty.get.a|lower|@ucfirst}
						{/if}
					{else}
						<i class="fa fa-home fa-fw"></i> Dashboard
					{/if}
				</div>
			</div>
			
			{if $smarty.const._AM_SITE_DEBUG}
				{* --------------------------------------- *}
				{* Smarty Function                         *}
				{* Libs / Plugins -> function.sbdebug.php  *}
				{* Created by BooBoo                       *}
				{* --------------------------------------- *}
				{sbdebug debugsql=$sbdebugsql odump=$sbodump file_content=$file_content}
				<p></p>
			{/if}
			
			{* Message status if action *}
			{if $sb_msg_error || $sb_msg_valid}

				{if $sb_msg_valid}
					{* --- Message status VALID --- *}
					<div class="alert alert-success alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						{$sb_msg_valid}
					</div>
				{elseif $sb_msg_error}
					{* --- Message status VALID --- *}
					<div class="alert alert-danger alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 {$sb_msg_error}
					</div>
				{/if}
				
			{/if}