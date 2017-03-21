{* ----------------- *}
{* --- Main Menu --- *}
{* ----------------- *}

{* ----------------------------------- *}
{* Dashboard - Don't Remove this entry *}
{* ----------------------------------- *}
<li class="menu-header menu-header-top">
	<a href="">Main menu</a>
</li>
<li id="index">
	<a {if $active == 'index'}class="active"{/if} href="index.php"><i class="fa fa-dashboard fa-fw fa-menu-i"></i> Dashboard</a>
</li>

{* --------------------------------------------- *}
{* Sandbox - Can be desactivated from options    *}
{* --------------------------------------------- *}
{if $sb_sandbox == 1}
<li id="sandbox">
	<a {if $active == 'sandbox'}class="active"{/if} href="index.php?p=sandbox"><i class="fa fa-ambulance fa-fw fa-menu-i"></i> Sandbox</a>
</li>
{/if}

<li class="menu-header">
	<a href="">Configuration</a>
</li>
{$sb_main_menu_admin}

<li class="menu-header">
	<a href="">Modules</a>
</li>
{$sb_main_menu}