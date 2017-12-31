
				<!-- Sidebar -->
					<div id="sidebar">
						<div class="inner">

							<!-- Search -->
								<section id="search" class="alt">
									<form method="post" action="#">
										<input type="text" name="query" id="query" placeholder="{$smarty.const._CMS_GLOBAL_SEARCH}" />
									</form>
								</section>

							<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>{$smarty.const._CMS_GLOBAL_MENU}</h2>
									</header>
                                    {insert name='sbGetMenuCms' mclass='' mid='' mtag='main_menu' mlang="`$smarty.session.lang`"}
								</nav>
                                
                            <!-- Section Blocks -->
								<section>
                                    {insert name="sbGetContentCms" o1="$page_view_blocks" o2="$module_view_blocks"}
								</section>

							<!-- Section -->
								{if $sb_theme_infos.theme_infos_email || $sb_theme_infos.theme_infos_tel || $sb_theme_infos.theme_infos_address}
								<section>
									<header class="major">
										<h2>{$smarty.const._CMS_GLOBAL_ABOUT}</h2>
									</header>
									{*<p>Sed varius enim lorem ullamcorper dolore aliquam aenean ornare velit lacus, ac varius enim lorem ullamcorper dolore. Proin sed aliquam facilisis ante interdum. Sed nulla amet lorem feugiat tempus aliquam.</p>*}
									<ul class="contact">
                                        <li class="fa-globe">
                                            {$sb_site_title|default:""}
                                        </li>
										{if $sb_theme_infos.theme_infos_email}
                                        <li class="fa-envelope-o">
                                            <a href="mailto:{$sb_theme_infos.theme_infos_email|escape:'mail'}">{$sb_theme_infos.theme_infos_email|escape:'mail'}</a>
                                        </li>
                                        {/if}
                                        {if $sb_theme_infos.theme_infos_tel}
										<li class="fa-phone">
                                            {$sb_theme_infos.theme_infos_tel}
                                        </li>
                                        {/if}
                                        {if $sb_theme_infos.theme_infos_address}
										<li class="fa-home">
                                            {$sb_theme_infos.theme_infos_address|@nl2br}
                                        </li>
                                        {/if}
									</ul>
								</section>
								{/if}

							<!-- Footer -->
								<footer id="footer">
									<p class="copyright">
                                        {insert name="sbGetConfig" id="footer"}
                                    </p>
								</footer>

						</div> <!-- .inner -->
					</div> <!-- #sidebar -->
