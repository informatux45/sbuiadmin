{* Pages Bar Buttons action *}

<div class="well well-sm">

	<div class="btn-group">
		<button type="button" class="btn btn-outline btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			Utilisateurs
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li><a href="{$sbsmarty.const._AM_SITE_URL}index.php?p=users">Tous les utilisateurs</a></li>
			<li class="divider"></li>
			<li><a href="{$sbsmarty.const._AM_SITE_URL}index.php?p=users&a=add">+1 utilisateur</a></li>
			<li><a style="color: red;" href="{$sbsmarty.const._AM_SITE_URL}index.php?p=settings">+1 administrateur</a></li>
		</ul>
	</div>
	&nbsp;&nbsp;
	<button class="btn btn-primary" type="button" onclick="window.open('https://fr.gravatar.com/')">
		Gravatar
	</button>

</div>