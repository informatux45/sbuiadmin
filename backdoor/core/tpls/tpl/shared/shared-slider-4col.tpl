{* --- SHARED SLIDER INSERT HEADPAGE ----
     N'enveloppe plus dans .col-4 : le seul appelant (pages.tpl) inclut ce
     partial DANS sa propre colonne .col-4, pour empiler cette carte sous
     "Choix du template" au lieu d'ouvrir une rangée à part. *}

<div class="card" style="margin-top:20px">
	<div class="card-head">
		<div class="card-title-wrap">
			<h2 class="card-title">Entête de la page</h2>
		</div>
	</div>
	<div class="entete_view">
		<img src="{$smarty.const._AM_SITE_IMG_URL}slider-headpage.jpg" alt="Choisissez un slider" title="Choisissez un slider" style="max-width: 100%;border-radius:8px" />
		<select id="" onchange="javascript:$('#headpage').val(this.value)" class="select" style="margin-top:12px">
			<option value="">Sélectionnez un Slider</option>
			{foreach from=$theme_headpage item=slide}
				<option value="{$slide.id}"{if $headpage == $slide.id} selected=""{/if}>{$slide.title}</option>
			{/foreach}
		</select>
	</div>
</div>
<!-- /.card -->