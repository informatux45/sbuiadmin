{* Pages Bar Buttons action *}

<div class="well well-sm">
	
	<div class="btn-group">
		<button type="button" class="btn btn-outline btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			Formulaires
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li><a href="{$sbsmarty.const._AM_SITE_URL}index.php?p=contact">Tous les formulaires</a></li>
			<li class="divider"></li>
			<li><a href="{$sbsmarty.const._AM_SITE_URL}index.php?p=contact&a=add">+1 formulaire</a></li>
		</ul>
	</div>
	&nbsp;&nbsp;
	<button class="btn btn-outline btn-success" type="button" onclick="location.href='index.php?p=contact&a=settings'">
		Paramètres
	</button>

</div>