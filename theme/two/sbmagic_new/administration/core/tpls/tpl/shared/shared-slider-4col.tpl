{* --- SHARED SLIDER INSERT HEADPAGE ---- *}

	<div class="col-lg-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<span class="fa fa-square fa-fw"></span> <strong>Entête de la page</strong>
			</div>
			<!-- /.panel-heading -->
			<div class="panel-body">
				<div class="entete_view">
					<img src="{$smarty.const._AM_SITE_IMG_URL}slider-headpage.jpg" alt="Choisissez un slider" title="Choisissez un slider" style="max-width: 100%;" />
					<select id="" onchange="javascript:$('#headpage').val(this.value)" class="form-control">
						<option value="">Sélectionnez un Slider</option>
						{foreach from=$theme_headpage item=slide}
							<option value="{$slide.id}"{if $headpage == $slide.id} selected=""{/if}>{$slide.title}</option>
						{/foreach}
					</select>
				</div>
			</div>
			<!-- /.panel-body -->
		</div>
		<!-- /.panel -->
	</div>
	<!-- /.col-lg-4 -->