{* -------------- *}
{* --- SYSTEM --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page pageindex='Dashboard' page='false'}
	{* ---------------- End Headers --------------- *}

			<section class="hero">
				<div class="hero-text">
					<span class="eyebrow">{$sb_dashboard_date_fr}</span>
					<h1 class="hero-title">Bon retour, <span class="accent">{$sbuiadmin_user_name}</span></h1>
					<p class="hero-sub">Voici un aperçu de votre back-office {$smarty.const._AM_SITE_CUSTOMER_NAME}.</p>
				</div>
			</section>

			{if $sbuiadmin_user_type == 'admin'}
            <!-- .row -->
            <div class="row">
				
				{if isset($sb_warning_installer_lock) || isset($sb_warning_install_file) || isset($sb_warning_admin_user)}
					<div class="col-lg-12" style="display:flex;flex-direction:column;gap:12px;margin-bottom:20px">
					{if isset($sb_warning_installer_lock)}
						<div class="alert danger">
							<span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg></span>
							<div class="body">
								<div class="title">Le répertoire INSTALL existe toujours</div>
								Supprimez-le au plus vite ! <a href="#">Vite</a> !!!
							</div>
							<button type="button" class="close" aria-label="Fermer" onclick="this.closest('.alert').style.display='none'"><svg viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
						</div>
					{/if}

					{if isset($sb_warning_install_file) && $sb_warning_install_file == true}
						<div class="alert danger">
							<span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg></span>
							<div class="body">
								<div class="title">Le fichier INSTALL.PHP existe toujours</div>
								Supprimez-le au plus vite ! <a href="#">Vite</a> !!!
							</div>
							<button type="button" class="close" aria-label="Fermer" onclick="this.closest('.alert').style.display='none'"><svg viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
						</div>
					{/if}

					{if isset($sb_warning_admin_user) && $sb_warning_admin_user == true}
						<div class="alert danger">
							<span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg></span>
							<div class="body">
								<div class="title">L'utilisateur ADMIN existe toujours</div>
								Créez d'autres utilisateurs et supprimez-le ! <a href="index.php?p=users">Vite</a> !!!
							</div>
							<button type="button" class="close" aria-label="Fermer" onclick="this.closest('.alert').style.display='none'"><svg viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
						</div>
					{/if}
					</div>
				{/if}

                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-users fa-4x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">{$sb_users_cpt|default:0}</div>
                                    <div>Utilisateurs</div>
                                </div>
                            </div>
                        </div>
                        <a href="index.php?p=users">
                            <div class="panel-footer">
                                <span class="pull-left">Détails utilisateurs</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-gears fa-4x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">{$smarty.const._AM_SERVER_PHP_VERSION_ID}</div>
                                    <div>Version PHP</div>
                                </div>
                            </div>
                        </div>
                        <a href="index.php?p=database">
                            <div class="panel-footer">
                                <span class="pull-left">Détails configuration serveur</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-database fa-4x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">{$smarty.const._AM_DB_HOST}</div>
                                    <div>DB Host</div>
                                </div>
                            </div>
                        </div>
                        <a href="index.php?p=settings">
                            <div class="panel-footer">
                                <span class="pull-left">Détails configuration</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
				
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-upload fa-4x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">{$smarty.const._AM_MEDIAS_SIZE_LIMIT}</div>
                                    <div>Upload limit</div>
                                </div>
                            </div>
                        </div>
                        <a href="index.php?p=settings">
                            <div class="panel-footer">
                                <span class="pull-left">Détails paramètres</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

            <!-- /.row -->
            </div>
			{/if}


            <!-- .row -->
            <div class="row">
				
				
				{if $sb_dashboard_status1_table != ''}
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-{$sb_dashboard_status1_icon} fa-4x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">{$sb_dashboard_status1_cpt|default:0}</div>
                                    <div>{$sb_dashboard_status1_title|@sbDisplayLang}</div>
                                </div>
                            </div>
                        </div>
                        <a href="{$sb_dashboard_status1_link}">
                            <div class="panel-footer">
                                <span class="pull-left">Détails</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
				{/if}
				
				{if $sb_dashboard_status2_table != ''}
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-{$sb_dashboard_status2_icon} fa-4x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">{$sb_dashboard_status2_cpt|default:0}</div>
                                    <div>{$sb_dashboard_status2_title|@sbDisplayLang}</div>
                                </div>
                            </div>
                        </div>
                        <a href="{$sb_dashboard_status2_link}">
                            <div class="panel-footer">
                                <span class="pull-left">Détails</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
				{/if}
				
				{if $sb_dashboard_status3_table != ''}
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-{$sb_dashboard_status3_icon} fa-4x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">{$sb_dashboard_status3_cpt|default:0}</div>
                                    <div>{$sb_dashboard_status3_title|@sbDisplayLang}</div>
                                </div>
                            </div>
                        </div>
                        <a href="{$sb_dashboard_status3_link}">
                            <div class="panel-footer">
                                <span class="pull-left">Détails</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
				{/if}
				
				{if $sb_dashboard_status4_table != ''}
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-{$sb_dashboard_status4_icon} fa-4x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">{$sb_dashboard_status4_cpt|default:0}</div>
                                    <div>{$sb_dashboard_status4_title|@sbDisplayLang}</div>
                                </div>
                            </div>
                        </div>
                        <a href="{$sb_dashboard_status4_link}">
                            <div class="panel-footer">
                                <span class="pull-left">Détails</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
				{/if}
				
            </div>
            <!-- /.row -->
			
			
			<!-- .row -->
            <div class="row">
				
				{if $sb_dashboard_status1_table != ''}
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-{$sb_dashboard_status1_icon} fa-fw"></i> {$sb_dashboard_status1_title}
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="list-group">
								{foreach from=$sb_dashboard_status1_all item=status1all}
									{if $status1all@iteration == 11}{break}{/if}
									<a href="#" class="list-group-item">
										<i class="fa fa-th-list fa-fw"></i> {$status1all.$sb_dashboard_status1_tbcol|@sbDisplayLang|unescape:"htmlall"}
										{*<span class="pull-right text-muted small"><em>4 minutes ago</em></span>*}
									</a>
								{/foreach}
                            </div>
                            <!-- /.list-group -->
                            <a href="{$sb_dashboard_status1_link}" class="btn btn-default btn-block">Voir {$sb_dashboard_status1_title|@strtolower}</a>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <!-- /.col-lg-4 -->
				{/if}

				{if $sb_dashboard_status2_table != ''}
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-{$sb_dashboard_status2_icon} fa-fw"></i> {$sb_dashboard_status2_title}
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="list-group">
								{foreach from=$sb_dashboard_status2_all item=status2all}
									{if $status2all@iteration == 11}{break}{/if}
									<a href="#" class="list-group-item">
										<i class="fa fa-th-list fa-fw"></i> {$status2all.$sb_dashboard_status2_tbcol|@sbDisplayLang|unescape:"htmlall"}
										{*<span class="pull-right text-muted small"><em>4 minutes ago</em></span>*}
									</a>
								{/foreach}
                            </div>
                            <!-- /.list-group -->
                            <a href="{$sb_dashboard_status2_link}" class="btn btn-default btn-block">Voir {$sb_dashboard_status2_title|@strtolower}</a>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <!-- /.col-lg-4 -->
				{/if}
				
				{if $sb_dashboard_status3_table != ''}
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-{$sb_dashboard_status3_icon} fa-fw"></i> {$sb_dashboard_status3_title}
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="list-group">
								{foreach from=$sb_dashboard_status3_all item=status3all}
									{if $status3all@iteration == 11}{break}{/if}
									<a href="#" class="list-group-item">
										<i class="fa fa-th-list fa-fw"></i> {$status3all.$sb_dashboard_status3_tbcol|@sbDisplayLang|unescape:"htmlall"}
										{*<span class="pull-right text-muted small"><em>4 minutes ago</em></span>*}
									</a>
								{/foreach}
                            </div>
                            <!-- /.list-group -->
                            <a href="{$sb_dashboard_status3_link}" class="btn btn-default btn-block">Voir {$sb_dashboard_status3_title|@strtolower}</a>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <!-- /.col-lg-4 -->
				{/if}

			</div>
			<!-- /.row -->
				
			<!-- .row -->
            <div class="row">
				
				{if $sb_dashboard_status4_table != ''}
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-{$sb_dashboard_status4_icon} fa-fw"></i> {$sb_dashboard_status4_title}
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="list-group">
								{foreach from=$sb_dashboard_status4_all item=status4all}
									{if $status4all@iteration == 11}{break}{/if}
									<a href="#" class="list-group-item">
										<i class="fa fa-th-list fa-fw"></i> {$status4all.$sb_dashboard_status4_tbcol|@sbDisplayLang|unescape:"htmlall"}
										{*<span class="pull-right text-muted small"><em>4 minutes ago</em></span>*}
									</a>
								{/foreach}
                            </div>
                            <!-- /.list-group -->
                            <a href="{$sb_dashboard_status4_link}" class="btn btn-default btn-block">Voir {$sb_dashboard_status4_title|@strtolower}</a>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <!-- /.col-lg-4 -->
				{/if}

			</div>
			<!-- /.row -->
			
		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script>
		$(document).ready(function() {
			// Your own code

		});
		</script>

	{include file='sb_footer.tpl' page='false' pagef='false'}
