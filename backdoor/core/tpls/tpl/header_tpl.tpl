{* Header TPL — page title/breadcrumb now live in navigation.tpl's topbar (.crumbs) *}

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
					<div class="alert success">
						<span class="ico"><svg viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg></span>
						<div class="body">{$sb_msg_valid}</div>
						<button type="button" class="close" aria-label="Fermer" onclick="this.closest('.alert').style.display='none'">&times;</button>
					</div>
				{elseif $sb_msg_error}
					{* --- Message status ERROR --- *}
					<div class="alert danger">
						<span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg></span>
						<div class="body">{$sb_msg_error}</div>
						<button type="button" class="close" aria-label="Fermer" onclick="this.closest('.alert').style.display='none'">&times;</button>
					</div>
				{/if}

			{/if}
