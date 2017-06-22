

                <!-- START PRIMARY -->
				<div id="primary" class="sidebar-no">
					
					 <!-- START INNER GROUP -->
				    <div class="inner group">
						
				        <!-- START CONTENT -->
				        <div id="content-index" class="content group">
				            <img class="error-404-image group" src="{$smarty.const.SB_THEME_URL}images/features/404.png" title="Error 404" alt="404" />
				            <div class="error-404-text group">
				                <p>
									{$smarty.const._CMS_GLOBAL_404|@sprintf:"{$smarty.const.SB_URL}"}
								</p>
								
				                <form method="post" action="#" id="contact-form-contact-us" class="contact-form" style="margin: 0 auto; width: 50%;">
									<fieldset>
				                        <ul>
				                            <li class="text-field" style="width: 100%">
				                                <div class="input-prepend">
													<span class="add-on"><i class="fa fa-search"></i></span>
													<input name="name" id="search-submit" class="required" value="" type="text" placeholder="{$smarty.const._CMS_GLOBAL_SEARCH}">
												</div>
				                            </li>

				                        </ul>
				                    </fieldset>
				                </form>
				            </div>
				        </div>
				        <!-- END CONTENT -->

				    </div> <!-- END INNER GROUP -->
				</div>
				<!-- END PRIMARY -->