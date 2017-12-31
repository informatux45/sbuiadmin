        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div id="brand-sbuiadmin">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" x="0px" y="0px" viewBox="0 0 48 48" class="icon sbuiadmin-logo" ><circle fill="#FFF59D" cx="24" cy="22" r="20"></circle><path fill="#FBC02D" d="M37,22c0-7.7-6.6-13.8-14.5-12.9c-6,0.7-10.8,5.5-11.4,11.5c-0.5,4.6,1.4,8.7,4.6,11.3	c1.4,1.2,2.3,2.9,2.3,4.8V37h12v-0.1c0-1.8,0.8-3.6,2.2-4.8C35.1,29.7,37,26.1,37,22z"></path><path fill="#FFF59D" d="M30.6,20.2l-3-2c-0.3-0.2-0.8-0.2-1.1,0L24,19.8l-2.4-1.6c-0.3-0.2-0.8-0.2-1.1,0l-3,2	c-0.2,0.2-0.4,0.4-0.4,0.7s0,0.6,0.2,0.8l3.8,4.7V37h2V26c0-0.2-0.1-0.4-0.2-0.6l-3.3-4.1l1.5-1l2.4,1.6c0.3,0.2,0.8,0.2,1.1,0	l2.4-1.6l1.5,1l-3.3,4.1C25.1,25.6,25,25.8,25,26v11h2V26.4l3.8-4.7c0.2-0.2,0.3-0.5,0.2-0.8S30.8,20.3,30.6,20.2z"></path><circle fill="#5C6BC0" cx="24" cy="44" r="3"></circle><path fill="#9FA8DA" d="M26,45h-4c-2.2,0-4-1.8-4-4v-5h12v5C30,43.2,28.2,45,26,45z"></path><g>	<path fill="#5C6BC0" d="M30,41l-11.6,1.6c0.3,0.7,0.9,1.4,1.6,1.8l9.4-1.3C29.8,42.5,30,41.8,30,41z"></path>	<polygon fill="#5C6BC0" points="18,38.7 18,40.7 30,39 30,37"></polygon></g></svg>
                    <div id="brand-sbuiadmin-name">
                        <span style="color: #5c6bc0;">SB</span><span style="color: #fbc02d;">UI</span><span style="color: #9fa8da;">ADMIN</span>                        
                    </div>
                </div>
            </div>
            <!-- /.navbar-header -->
            
            <ul class="nav navbar-left">
                <li>
                    {*$smarty.const._AM_SITE_CUSTOMER_NAME*}
                    <a class="navbar-brandx" target="_blank" href="{if $sb_url_customer != ''}{$sb_url_customer}{else}index.php{/if}" title="Aller sur le site {$smarty.const._AM_SITE_CUSTOMER_NAME}">
                        <i class="fa fa-globe fa-fw"></i>
                    </a>
                </li>

                <li class="dropdown">
                    <a title="Mise à jour" class="dropdown-toggle" data-toggle="dropdown" href="#"{if $sbuiadmin_upgrade_core || $sbuiadmin_upgrade_modules} style="color: #fbc02d !important;"{/if}>
                        <i class="fa fa-refresh fa-fw"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts" style="font-size: 0.8em;">
                        <li{if $sbuiadmin_upgrade_core} class="dropdown-upgrade"{/if}>
                            {if $sbuiadmin_upgrade_core}
                                <a href="#" data-target="#sbupgradecore" data-toggle="modal">
                                    <div>
                                        <i class="fa fa-hand-o-right fa-fw"></i> Nouvelle version <span style="color: #fbc02d !important;">{$sbuiadmin_upgrade_core}</span><br>
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
                        <li{if $sbuiadmin_upgrade_modules} class="dropdown-upgrade"{/if}>
                            {if $sbuiadmin_upgrade_modules}
                                <a href="#" data-target="#sbupgrademodules" data-toggle="modal">
                                    <div>
                                        <i class="fa fa-hand-o-right fa-fw"></i> {$sbuiadmin_upgrade_modules} New modules<br>{$sbuiadmin_upgrade_modules}<br>
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

            <ul class="nav navbar-top-links navbar-right">
                {*<li class="text-primary">{$smarty.const.SBUIADMIN_GLOBAL_HI} {$sbuiadmin_user_name|@strtolower}</li>*}
                
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" title="Credits SBUIADMIN">
                        <i class="fa fa-credit-card fa-fw"></i>{*  <i class="fa fa-caret-down"></i>*}
                    </a>
                    <ul class="dropdown-menu dropdown-tasks">
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-codepen fa-fw"></i> <span style="font-size: 0.8em;">Software SBUIADMIN par INFORMATUX</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-thumbs-up fa-fw"></i> <span style="font-size: 0.8em;">SBUIADMIN Version {$smarty.const._AM_START_VERSION}</span>
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
                                    <i class="fa fa-soundcloud fa-fw"></i> <span style="font-size: 0.8em;">&copy; 2007 - {$smarty.now|date_format:'%Y'} INFORMATUX</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->
                
                <li>
                    <a class="" href="{$smarty.const._AM_SITE_URL}?ac=logout" title="{$smarty.const.SBUIADMIN_GLOBAL_LOGOUT}">
                        <i class="fa fa-sign-out fa-fw"></i>
                    </a>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
                
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="user-profile">
                            <div class="user-img-div">
                                <img src="{$sbuiadmin_user_email|@sbGetGravatar}" class="img-thumbnail" />
                                <div class="inner-text">
                                    {$sbuiadmin_user_name}
                                    <p class="user-last-login">
                                        <small>{$smarty.const.SBUIADMIN_GLOBAL_LAST_LOGIN}<br>{$sbuiadmin_user_last_login}</small>
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
                        <h4 id="sbupgradecoreLabel" class="modal-title">Mise à niveau vers la version <span style="color: red;">{$sbuiadmin_upgrade_core}</span></h4><a onclick="javascript:sbUgrade('core','{$smarty.const._AM_SITE_URL}');" role="button" class="upgrade-now btn btn-danger" id="upgrade-core">upgrade now!</a>
                    </div>
                    <div id="upgrade-ajax-content" class="modal-body">
                        <div class="sbupgrade-filelist">
                            <span>Liste des fichiers à mettre à niveau :</span><br>
                            {$sbuiadmin_upgrade_core_filelist}
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
                        <h4 id="sbupgrademodulesLabel" class="modal-title">Mise à niveau des modules <span style="color: red;">{$sbuiadmin_upgrade_modules}</span></h4>
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
