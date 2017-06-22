
				<!-- Main -->
					<div id="main">
						<div class="inner">

							<!-- Header -->
								<header id="header">
									<a href="{$smarty.const.SB_URL}" class="logo">
										<strong>{$sb_site_title}</strong>
									</a>
									<ul class="icons">
										{if $sb_theme_infos.theme_infos_twitter}<li><a href="{$sb_theme_infos.theme_infos_twitter}" class="icon fa-twitter"><span class="label">Twitter</span></a></li>{/if}
										{if $sb_theme_infos.theme_infos_facebook}<li><a href="{$sb_theme_infos.theme_infos_facebook}" class="icon fa-facebook"><span class="label">Facebook</span></a></li>{/if}
										{if $sb_theme_infos.theme_infos_google_plus}<li><a href="{$sb_theme_infos.theme_infos_google_plus}" class="icon fa-google-plus"><span class="label">Google +</span></a></li>{/if}
										{if $sb_theme_infos.theme_infos_pinterest}<li><a href="{$sb_theme_infos.theme_infos_pinterest}" class="icon fa-pinterest"><span class="label">Pinterest</span></a></li>{/if}
										{if $sb_theme_infos.theme_infos_instagram}<li><a href="{$sb_theme_infos.theme_infos_instagram}" class="icon fa-instagram"><span class="label">Instagram</span></a></li>{/if}
										{if $sb_theme_infos.theme_infos_vimeo}<li><a href="{$sb_theme_infos.theme_infos_vimeo}" class="icon fa-vimeo"><span class="label">Vimeo</span></a></li>{/if}
										{if $sb_theme_infos.theme_infos_viadeo}<li><a href="{$sb_theme_infos.theme_infos_viadeo}" class="icon fa-viadeo"><span class="label">Viadeo</span></a></li>{/if}
										{if $sb_theme_infos.theme_infos_youtube}<li><a href="{$sb_theme_infos.theme_infos_youtube}" class="icon fa-youtube"><span class="label">Youtube</span></a></li>{/if}
										{if $sb_theme_infos.theme_infos_skype}<li><a href="{$sb_theme_infos.theme_infos_skype}" class="icon fa-skype"><span class="label">Skype</span></a></li>{/if}
										{if $sb_theme_infos.theme_infos_linkedin}<li><a href="{$sb_theme_infos.theme_infos_linkedin}" class="icon fa-linkedin"><span class="label">Linkedin</span></a></li>{/if}
									</ul>
								</header>

							<!-- Banner -->
							{if $sidebar == 'index'}
								<section id="banner">
									<div class="content">
										<header>
											{insert name="sbGetConfig" id="header"}
										</header>
										{*<p>Aenean ornare velit lacus, ac varius enim ullamcorper eu. Proin aliquam facilisis ante interdum congue. Integer mollis, nisl amet convallis, porttitor magna ullamcorper, amet egestas mauris. Ut magna finibus nisi nec lacinia. Nam maximus erat id euismod egestas. Pellentesque sapien ac quam. Lorem ipsum dolor sit nullam.</p>*}
										<ul class="actions">
											<li><a href="{$smarty.const.SB_URL}" class="button big">{$smarty.const._CMS_GLOBAL_LEARN_MORE}</a></li>
										</ul>
									</div>
									{if $sb_page_headpage}
										<span class="image object">
											{insert name="sbDoShortcode" code="[CS id=`$sb_page_headpage` name=sbslider]"}
										</span>
									{/if}
								</section>
							{/if}

							<!-- Section -->
								<section{if $sidebar == 'page-404'} id="search"{/if}>
									
									{if $sidebar == 'index-title' ||  $sidebar == 'index-contact'}
										<header class="main">
											<h1>
												{$sb_title}
											</h1>
										</header>
									{/if}
									
									{if $sb_page_headpage && $sidebar != 'index'}
									<div class="row 100%">
										<div class="8u 12u$(medium) sidebar-slider">
											<span class="image fit">
												{insert name="sbDoShortcode" code="[CS id=`$sb_page_headpage` name=sbslider]"}
											</span>
										</div>
									</div>
									{/if}

									{insert name="sbGetContentCms" o1="$page_view" o2="$module_view"}
									
									{if $sidebar == 'index-contact'}
										<!-- Form -->
											<header class="main">
												<h1>Contact</h1>
											</header>
											
											<h4 class="widget-title">PLACE TO TALK WITH ME</h4>
											<p>Vestibulum ac iaculis erat, in semper dolor. Maecenas et lorem molestie, maximus justo dignissim, cursus nisl. Nullam at ante quis ex pharetra pulvinar quis id dolor. Integer lorem odio, euismod ut sem sit amet, imperdiet condimentum diam.</p>

											<form method="post" action="#">
												<div class="row uniform">
													<div class="6u 12u$(xsmall)">
														<input type="text" name="demo-name" id="demo-name" value="" placeholder="Name" />
													</div>
													<div class="6u$ 12u$(xsmall)">
														<input type="email" name="demo-email" id="demo-email" value="" placeholder="Email" />
													</div>
													<!-- Break -->
													<div class="12u$">
														<div class="select-wrapper">
															<select name="demo-category" id="demo-category">
																<option value="">- Category -</option>
																<option value="1">Manufacturing</option>
																<option value="1">Shipping</option>
																<option value="1">Administration</option>
																<option value="1">Human Resources</option>
															</select>
														</div>
													</div>
													<!-- Break -->
													<div class="4u 12u$(small)">
														<input type="radio" id="demo-priority-low" name="demo-priority" checked>
														<label for="demo-priority-low">Low</label>
													</div>
													<div class="4u 12u$(small)">
														<input type="radio" id="demo-priority-normal" name="demo-priority">
														<label for="demo-priority-normal">Normal</label>
													</div>
													<div class="4u$ 12u$(small)">
														<input type="radio" id="demo-priority-high" name="demo-priority">
														<label for="demo-priority-high">High</label>
													</div>
													<!-- Break -->
													<div class="6u 12u$(small)">
														<input type="checkbox" id="demo-copy" name="demo-copy">
														<label for="demo-copy">Email me a copy</label>
													</div>
													<div class="6u$ 12u$(small)">
														<input type="checkbox" id="demo-human" name="demo-human" checked>
														<label for="demo-human">I am a human</label>
													</div>
													<!-- Break -->
													<div class="12u$">
														<textarea name="demo-message" id="demo-message" placeholder="Enter your message" rows="6"></textarea>
													</div>
													<!-- Break -->
													<div class="12u$">
														<ul class="actions">
															<li><input type="submit" value="Send Message" class="special" /></li>
															<li><input type="reset" value="Reset" /></li>
														</ul>
													</div>
												</div>
											</form>
									{/if}
									
									{if $sidebar == 'page-404'}
										<header class="main">
											<h1>{$smarty.const._CMS_GLOBAL_INFO}</h1>
										</header>
										
										<p class="acenter">
											<img class="error-404-image group" src="{$smarty.const.SB_THEME_URL}images/features/404.png" title="Error 404" alt="404" />
											<br><br>
											{$smarty.const._CMS_GLOBAL_404|@sprintf:"{$smarty.const.SB_URL}"}
										</p>
										
										<form method="post" action="#" style="width: 50%; margin: 0 auto">
											<input name="query" id="query" placeholder="{$smarty.const._CMS_GLOBAL_SEARCH}" type="text" style="50%">
										</form>
										
									{/if}

								</section>

						</div> <!-- .inner -->
					</div> <!-- #main -->
