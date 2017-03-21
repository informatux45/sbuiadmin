        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                {if $sb_url_customer != ''}
                    <a class="navbar-brand" target="_blank" href="{$sb_url_customer}">{$smarty.const._AM_SITE_CUSTOMER_NAME}</a>
                {else}
                    <a class="navbar-brand" href="index.php">{$smarty.const._AM_SITE_CUSTOMER_NAME}</a>
                {/if}
                
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li class="text-primary">{$smarty.const.SBMAGIC_GLOBAL_HI} {$sbmagic_user_name|@strtolower}</li>
                
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-coffee fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-tasks">
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-codepen fa-fw"></i> <span style="font-size: 0.8em;">Software SBMAGIC par l'agence DOLLAR</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-thumbs-up fa-fw"></i> <span style="font-size: 0.8em;">SBMAGIC Version {$smarty.const._AM_START_VERSION}</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-edit fa-fw"></i> <span style="font-size: 0.8em;">Design by Bootstrap</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-power-off fa-fw"></i> <span style="font-size: 0.8em;">Powered by Smarty {$smarty.version}</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-soundcloud fa-fw"></i> <span style="font-size: 0.8em;">&copy; {$smarty.now|date_format:'%Y'} Agence DOLLAR</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->
                
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        {if $sbmagic_user_type == 'admin'}
                            <li>
                                <a href="index.php?p=users"><i class="fa fa-gear fa-fw"></i> {$smarty.const.SBMAGIC_GLOBAL_SETTINGS}</a>
                            </li>
                        {/if}
                        <li class="divider"></li>
                        <li>
                            <a href="{$smarty.const._AM_SITE_URL}?ac=logout"><i class="fa fa-sign-out fa-fw"></i> {$smarty.const.SBMAGIC_GLOBAL_LOGOUT}</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
                
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"{if $sbmagic_upgrade_core || $sbmagic_upgrade_modules} style="color: red;"{/if}>
                        <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li{if $sbmagic_upgrade_core} class="dropdown-upgrade"{/if}>
                            {if $sbmagic_upgrade_core}
                                <a href="#" data-target="#sbupgradecore" data-toggle="modal">
                                    <div>
                                        <i class="fa fa-hand-o-right fa-fw"></i> Nouvelle version <span style="color: red;">{$sbmagic_upgrade_core}</span><br>
                                        <span class="pull-right text-muted small">Version actuelle {$smarty.const._AM_START_VERSION}</span><br>
                                    </div>
                                </a>                            
                            {else}
                                <a href="#">
                                    <div>
                                        <i class="fa fa-thumbs-o-up fa-fw"></i> Système à jour
                                        <span class="pull-right text-muted small">Version actuelle {$smarty.const._AM_START_VERSION}</span><br>
                                    </div>
                                </a>
                            {/if}
                        </li>
                        <li class="divider"></li>
                        <li{if $sbmagic_upgrade_modules} class="dropdown-upgrade"{/if}>
                            {if $sbmagic_upgrade_modules}
                                <a href="#" data-target="#sbupgrademodules" data-toggle="modal">
                                    <div>
                                        <i class="fa fa-hand-o-right fa-fw"></i> {$sbmagic_upgrade_modules} New modules<br>{$sbmagic_upgrade_modules}<br>
                                    </div>
                                </a>                            
                            {else}
                                <a href="#">
                                    <div>
                                        <i class="fa fa-thumbs-o-up fa-fw"></i> Modules à jour<br>
                                    </div>
                                </a>
                            {/if}
                        </li>
                        <li class="divider"></li>
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->
                
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="user-profile">
                            <div class="user-img-div">
                                <img src="{$sbmagic_user_email|@sbGetGravatar}" class="img-thumbnail" />
                                <div class="inner-text">
                                    {$sbmagic_user_name}
                                    <p class="user-last-login">
                                        <small>{$smarty.const.SBMAGIC_GLOBAL_LAST_LOGIN}<br>{$sbmagic_user_last_login}</small>
                                    </p>
                                </div>
                            </div>
                       </li>
                        {include file='main_menu.tpl'}
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
                
        {* --- Dialog box UPGRADE CORE --- *}
        <div aria-hidden="true" aria-labelledby="sbupgradecoreLabel" role="dialog" tabindex="-1" id="sbupgradecore" class="modal fade" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                        <h4 id="sbupgradecoreLabel" class="modal-title">Mise à niveau vers la version <span style="color: red;">{$sbmagic_upgrade_core}</span></h4><a onclick="javascript:sbUgrade('core','{$smarty.const._AM_SITE_URL}');" role="button" class="upgrade-now btn btn-danger" id="upgrade-core">upgrade now!</a>
                    </div>
                    <div id="upgrade-ajax-content" class="modal-body">
                        <div class="sbupgrade-filelist">
                            <span>Liste des fichiers à mettre à niveau :</span><br>
                            {$sbmagic_upgrade_core_filelist}
                        </div>
                        <div id="sbupgrade-inprogress" class="center" style="display: none;">
                            <br>Mise à niveau en cours<br>
                            <img src="{$smarty.const._AM_SITE_URL}img/ajax-loader-upgrade.gif" alt="Upgrade in progress" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button" onclick="javascript:location.href='{$smarty.const._AM_SITE_URL}'">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        
        {* --- Dialog box UPGRADE MODULES --- *}
        <div aria-hidden="true" aria-labelledby="sbupgrademodulesLabel" role="dialog" tabindex="-1" id="sbupgrademodules" class="modal fade" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                        <h4 id="sbupgrademodulesLabel" class="modal-title">Mise à niveau des modules <span style="color: red;">{$sbmagic_upgrade_modules}</span></h4>
                    </div>
                    <div class="modal-body">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                        {*<button class="btn btn-primary" type="button">Save changes</button>*}
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>