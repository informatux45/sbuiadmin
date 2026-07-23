{* ----------------- *}
{* --- Main Menu --- *}
{* ----------------- *}

<div class="nav-label">Workspace</div>

{* ----------------------------------- *}
{* Dashboard - Don't Remove this entry *}
{* ----------------------------------- *}
<a class="nav-link{if !isset($smarty.get.p) || $smarty.get.p == 'index'} is-active{/if}" href="index.php">
	<i class="fa fa-dashboard fa-fw"></i>
	<span>Dashboard</span>
</a>

{* --------------------------------------------- *}
{* Sandbox - Can be desactivated from options    *}
{* --------------------------------------------- *}
{if $sb_sandbox == 1}
<a class="nav-link{if isset($smarty.get.p) && $smarty.get.p == 'sandbox'} is-active{/if}" href="index.php?p=sandbox">
	<i class="fa fa-ambulance fa-fw"></i>
	<span>Sandbox</span>
</a>
{/if}

<div class="nav-label">Configuration</div>
{$sb_main_menu_admin}

<div class="nav-label">Modules</div>
{$sb_main_menu}
