{* ----------------- *}
{* --- Main Menu --- *}
{* ----------------- *}

{* ----------------------------------- *}
{* Dashboard - Don't Remove this entry *}
{* ----------------------------------- *}
<li id="index">
	<a {if $active == 'index'}class="active"{/if} href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
</li>

{* --------------------------------------------- *}
{* Sandbox - Can be desactivated from options    *}
{* --------------------------------------------- *}
{if $sb_sandbox == 1}
<li id="sandbox">
	<a {if $active == 'sandbox'}class="active"{/if} href="index.php?p=sandbox"><i class="fa fa-ambulance fa-fw"></i> Sandbox</a>
</li>
{/if}

{$sb_main_menu_admin}

{$sb_main_menu}