<?php
/* Smarty version 3.1.29, created on 2016-12-20 14:40:55
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/navigation.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_585934e7af48b3_04106966',
  'file_dependency' => 
  array (
    'c85e58bf4b8fb846598032fb005d3ccbbcef6b10' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/navigation.tpl',
      1 => 1477409586,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:main_menu.tpl' => 1,
  ),
),false)) {
function content_585934e7af48b3_04106966 ($_smarty_tpl) {
if (!is_callable('smarty_modifier_date_format')) require_once '/Applications/MAMP/htdocs/sbmagic_new/administration/core/plugins/modifier.date_format.php';
?>
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <?php if ($_smarty_tpl->tpl_vars['sb_url_customer']->value != '') {?>
                    <a class="navbar-brand" target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['sb_url_customer']->value;?>
"><?php echo @constant('_AM_SITE_CUSTOMER_NAME');?>
</a>
                <?php } else { ?>
                    <a class="navbar-brand" href="index.php"><?php echo @constant('_AM_SITE_CUSTOMER_NAME');?>
</a>
                <?php }?>
                
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li class="text-primary"><?php echo @constant('SBMAGIC_GLOBAL_HI');?>
 <?php echo strtolower($_smarty_tpl->tpl_vars['sbmagic_user_name']->value);?>
</li>
                
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
                                    <i class="fa fa-thumbs-up fa-fw"></i> <span style="font-size: 0.8em;">SBMAGIC Version <?php echo @constant('_AM_START_VERSION');?>
</span>
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
                                    <i class="fa fa-power-off fa-fw"></i> <span style="font-size: 0.8em;">Powered by Smarty <?php echo Smarty::SMARTY_VERSION;?>
</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-soundcloud fa-fw"></i> <span style="font-size: 0.8em;">&copy; <?php echo smarty_modifier_date_format(time(),'%Y');?>
 Agence DOLLAR</span>
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
                        <?php if ($_smarty_tpl->tpl_vars['sbmagic_user_type']->value == 'admin') {?>
                            <li>
                                <a href="index.php?p=users"><i class="fa fa-gear fa-fw"></i> <?php echo @constant('SBMAGIC_GLOBAL_SETTINGS');?>
</a>
                            </li>
                        <?php }?>
                        <li class="divider"></li>
                        <li>
                            <a href="<?php echo @constant('_AM_SITE_URL');?>
?ac=logout"><i class="fa fa-sign-out fa-fw"></i> <?php echo @constant('SBMAGIC_GLOBAL_LOGOUT');?>
</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
                
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"<?php if ($_smarty_tpl->tpl_vars['sbmagic_upgrade_core']->value || $_smarty_tpl->tpl_vars['sbmagic_upgrade_modules']->value) {?> style="color: red;"<?php }?>>
                        <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li<?php if ($_smarty_tpl->tpl_vars['sbmagic_upgrade_core']->value) {?> class="dropdown-upgrade"<?php }?>>
                            <?php if ($_smarty_tpl->tpl_vars['sbmagic_upgrade_core']->value) {?>
                                <a href="#" data-target="#sbupgradecore" data-toggle="modal">
                                    <div>
                                        <i class="fa fa-hand-o-right fa-fw"></i> Nouvelle version <span style="color: red;"><?php echo $_smarty_tpl->tpl_vars['sbmagic_upgrade_core']->value;?>
</span><br>
                                        <span class="pull-right text-muted small">Version actuelle <?php echo @constant('_AM_START_VERSION');?>
</span><br>
                                    </div>
                                </a>                            
                            <?php } else { ?>
                                <a href="#">
                                    <div>
                                        <i class="fa fa-thumbs-o-up fa-fw"></i> Système à jour
                                        <span class="pull-right text-muted small">Version actuelle <?php echo @constant('_AM_START_VERSION');?>
</span><br>
                                    </div>
                                </a>
                            <?php }?>
                        </li>
                        <li class="divider"></li>
                        <li<?php if ($_smarty_tpl->tpl_vars['sbmagic_upgrade_modules']->value) {?> class="dropdown-upgrade"<?php }?>>
                            <?php if ($_smarty_tpl->tpl_vars['sbmagic_upgrade_modules']->value) {?>
                                <a href="#" data-target="#sbupgrademodules" data-toggle="modal">
                                    <div>
                                        <i class="fa fa-hand-o-right fa-fw"></i> <?php echo $_smarty_tpl->tpl_vars['sbmagic_upgrade_modules']->value;?>
 New modules<br><?php echo $_smarty_tpl->tpl_vars['sbmagic_upgrade_modules']->value;?>
<br>
                                    </div>
                                </a>                            
                            <?php } else { ?>
                                <a href="#">
                                    <div>
                                        <i class="fa fa-thumbs-o-up fa-fw"></i> Modules à jour<br>
                                    </div>
                                </a>
                            <?php }?>
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
                                <img src="<?php echo sbGetGravatar($_smarty_tpl->tpl_vars['sbmagic_user_email']->value);?>
" class="img-thumbnail" />
                                <div class="inner-text">
                                    <?php echo $_smarty_tpl->tpl_vars['sbmagic_user_name']->value;?>

                                    <p class="user-last-login">
                                        <small><?php echo @constant('SBMAGIC_GLOBAL_LAST_LOGIN');?>
<br><?php echo $_smarty_tpl->tpl_vars['sbmagic_user_last_login']->value;?>
</small>
                                    </p>
                                </div>
                            </div>
                       </li>
                        <?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:main_menu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
                
        
        <div aria-hidden="true" aria-labelledby="sbupgradecoreLabel" role="dialog" tabindex="-1" id="sbupgradecore" class="modal fade" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                        <h4 id="sbupgradecoreLabel" class="modal-title">Mise à niveau vers la version <span style="color: red;"><?php echo $_smarty_tpl->tpl_vars['sbmagic_upgrade_core']->value;?>
</span></h4><a onclick="javascript:sbUgrade('core','<?php echo @constant('_AM_SITE_URL');?>
');" role="button" class="upgrade-now btn btn-danger" id="upgrade-core">upgrade now!</a>
                    </div>
                    <div id="upgrade-ajax-content" class="modal-body">
                        <div class="sbupgrade-filelist">
                            <span>Liste des fichiers à mettre à niveau :</span><br>
                            <?php echo $_smarty_tpl->tpl_vars['sbmagic_upgrade_core_filelist']->value;?>

                        </div>
                        <div id="sbupgrade-inprogress" class="center" style="display: none;">
                            <br>Mise à niveau en cours<br>
                            <img src="<?php echo @constant('_AM_SITE_URL');?>
img/ajax-loader-upgrade.gif" alt="Upgrade in progress" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button" onclick="javascript:location.href='<?php echo @constant('_AM_SITE_URL');?>
'">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        
        
        <div aria-hidden="true" aria-labelledby="sbupgrademodulesLabel" role="dialog" tabindex="-1" id="sbupgrademodules" class="modal fade" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                        <h4 id="sbupgrademodulesLabel" class="modal-title">Mise à niveau des modules <span style="color: red;"><?php echo $_smarty_tpl->tpl_vars['sbmagic_upgrade_modules']->value;?>
</span></h4>
                    </div>
                    <div class="modal-body">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                        
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div><?php }
}
