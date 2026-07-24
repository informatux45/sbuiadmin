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
					{* toast.js is loaded with "defer" further down the page, not
					   executed yet at this point in the HTML parse — wait for
					   DOMContentLoaded so window.sbToast is guaranteed to exist. *}
					<script>document.addEventListener('DOMContentLoaded', function() { sbToast("{$sb_msg_valid|replace:'<br>':'\n'|escape:'javascript'}", 'success'); });</script>
				{elseif $sb_msg_error}
					{* --- Message status ERROR --- *}
					<script>document.addEventListener('DOMContentLoaded', function() { sbToast("{$sb_msg_error|replace:'<br>':'\n'|escape:'javascript'}", 'error'); });</script>
				{/if}

			{/if}
