{* -------------- *}
{* --- SYSTEM --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}

			{* Notes full width *}
			<div class="row">
				
				<div class="col-lg-12">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <i class="fa fa-info-circle fa-fw"></i>&nbsp;Informations
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-pills">
                                <li class="">
									<a data-toggle="tab" href="#php-pills" aria-expanded="false">PHP</a>
                                </li>
                                <li class="">
									<a data-toggle="tab" href="#smarty-pills" aria-expanded="false">SMARTY</a>
                                </li>
								&nbsp;
                                <button data-target="#adminstats" data-toggle="modal" class="btn btn-info btn-lg" style="font-size: 14px;">
									ADMIN
								</button>
								<div aria-hidden="true" aria-labelledby="adminstatsLabel" role="dialog" tabindex="-1" id="adminstats" class="modal fade" style="display: none;">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
												<h4 id="adminstatsLabel" class="modal-title"><i class="fa fa-bar-chart-o fa-fw"></i>&nbsp;ADMIN Login</h4>
											</div>
											<div class="modal-body">
												<p>
													<i class="fa fa-key fa-fw" style="font-size: 3em; margin-right: 20px; float: left;"></i>
													<i class="fa fa-user fa-fw"></i>&nbsp;User: admin<br>
													<i class="fa fa-lock fa-fw"></i>&nbsp;Pwd: admin
												</p>
											</div>
											<div class="modal-footer">
												<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
											</div>
										</div>
										<!-- /.modal-content -->
									</div>
									<!-- /.modal-dialog -->
								</div>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div id="php-pills" class="tab-pane fade">
                                    <h4>PHP Code (si vous utilisez SBADMIN en administration seule)</h4>
                                    <p>
<!-- HTML generated using hilite.me --><div style="background: #f0f3f3; overflow:auto;width:auto;border:solid gray;border-width:.1em .1em .1em .8em;padding:.2em .6em;"><table><tr><td><pre style="margin: 0; line-height: 125%">1
2
3
4
5</pre></td><td><pre style="margin: 0; line-height: 125%">$nom_page = $sb_pages_title; // nom de la page
$Racine_abs = str_replace($_SERVER[&#39;PHP_SELF&#39;], &quot;&quot;, $_SERVER[&#39;SCRIPT_FILENAME&#39;]);
$sb_require_front = &#39;go&#39;;
$sb_require_admin = SBADMIN;
require_once(SB_ADMIN_DIR . &#39;inc/plugins/stats/visiteur.php&#39;);
</pre></td></tr></table></div>
									</p>
                                </div>
                                <div id="smarty-pills" class="tab-pane fade {*active in*}">
                                    <h4>SMARTY Code (si vous utilisez SBADMIN en administration seule)</h4>
                                    <p>
<!-- HTML generated using hilite.me --><div style="background: #f0f3f3; overflow:auto;width:auto;border:solid gray;border-width:.1em .1em .1em .8em;padding:.2em .6em;"><table><tr><td><pre style="margin: 0; line-height: 125%">1
2
3
4
5
6
7</pre></td><td><pre style="margin: 0; line-height: 125%"><span style="color: #009999">&lbrace;php&rbrace;</span>
<span style="color: #003333">$nom_page</span> <span style="color: #555555">=</span> <span style="color: #003333">$sb_pages_title</span>; <span style="color: #0099FF; font-style: italic">// nom de la page</span>
<span style="color: #003333">$Racine_abs</span> <span style="color: #555555">=</span> <span style="color: #336666">str_replace</span>(<span style="color: #003333">$_SERVER</span>[<span style="color: #CC3300">&#39;PHP_SELF&#39;</span>], <span style="color: #CC3300">&quot;&quot;</span>, <span style="color: #003333">$_SERVER</span>[<span style="color: #CC3300">&#39;SCRIPT_FILENAME&#39;</span>]);
<span style="color: #003333">$sb_require_front</span> <span style="color: #555555">=</span> <span style="color: #CC3300">&#39;go&#39;</span>;
<span style="color: #003333">$sb_require_admin</span> <span style="color: #555555">=</span> SBADMIN;
<span style="color: #006699; font-weight: bold">require_once</span>(SB_ADMIN_DIR <span style="color: #555555">.</span> <span style="color: #CC3300">&#39;inc/plugins/stats/visiteur.php&#39;</span>);
<span style="color: #009999">&lbrace;/php&rbrace;</span>
</pre></td></tr></table></div>

									</p>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->

            </div>
            <!-- /.row -->

			{* Notes 12 col *}
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Statistiques
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							<iframe src="inc/plugins/stats/index.php" width="100%" height="800px" style="border: none;"></iframe>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
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

	{include file='sb_footer.tpl'}