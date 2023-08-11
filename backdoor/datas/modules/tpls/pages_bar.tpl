{* Pages Bar Buttons action *}

<div class="well well-sm">
	{*<h4>Actions</h4>*}
	
	<div class="btn-group">
		<button type="button" class="btn btn-outline btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			Pages
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li><a href="{$smarty.const._AM_SITE_URL}index.php?p=pages">Toutes les pages</a></li>
			<li class="divider"></li>
			<li><a href="{$smarty.const._AM_SITE_URL}index.php?p=pages&a=add">+1 page</a></li>
			<li><a href="{$smarty.const._AM_SITE_URL}index.php?p=pages&a=addcustom">+1 page (Custom)</a></li>
		</ul>
	</div>
	&nbsp;&nbsp;
	<div class="btn-group">
		<button type="button" class="btn btn-outline btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			Blocs
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li><a href="{$smarty.const._AM_SITE_URL}index.php?p=blocs">Tous les blocs</a></li>
			<li class="divider"></li>
			<li><a href="{$smarty.const._AM_SITE_URL}index.php?p=blocs&a=add">+1 bloc</a></li>
		</ul>
	</div>
	&nbsp;&nbsp;

</div>