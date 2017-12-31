
				<!-- START PRIMARY -->
				<div id="primary" class="sidebar-left">
				    <div {insert name=sbGetSectionClassId class="inner group" evenid="" op="`$smarty.get.op`" page="`$page_id`" id="`$smarty.get.id`" ti="`$sb_title`"}>
				        <!-- START CONTENT -->
				        <div id="content-page" class="content group">
				            <div class="hentry group">
								{insert name="sbGetContentCms" o1="$page_view" o2="$module_view"}
				                <form id="contact-form-contact-us" class="contact-form" method="post" action="sendmail.PHP" enctype="multipart/form-data">
				                    <div class="usermessagea"></div>
				                    <fieldset>
				                        <ul>
				                            <li class="text-field">
				                                <label for="name-contact-us">
				                                <span class="label">Name</span>
				                                <br /><span class="sublabel">This is the name</span><br />
				                                </label>
				                                <div class="input-prepend"><span class="add-on"><i class="fa fa-user"></i></span><input type="text" name="name" id="name-contact-us" class="required" value="" /></div>
				                                <div class="msg-error"></div>
				                            </li>
				                            <li class="text-field">
				                                <label for="email-contact-us">
				                                <span class="label">Email</span>
				                                <br /><span class="sublabel">This is a field email</span><br />
				                                </label>
				                                <div class="input-prepend"><span class="add-on"><i class="fa fa-envelope"></i></span><input type="text" name="email" id="email-contact-us" class="required email-validate" value="" /></div>
				                                <div class="msg-error"></div>
				                            </li>
				                            <li class="textarea-field">
				                                <label for="message-contact-us">
				                                <span class="label">Message</span>
				                                </label>
				                                <div class="input-prepend"><span class="add-on"><i class="fa fa-pencil"></i></span><textarea name="message" id="message-contact-us" rows="8" cols="30" class="required"></textarea></div>
				                                <div class="msg-error"></div>
				                            </li>
				                            <li class="submit-button">
				                                <input type="text" name="yit_bot" id="yit_bot" />
				                                <input type="hidden" name="yit_action" value="sendmail" id="yit_action" />
				                                <input type="hidden" name="yit_referer" value="http://yourinspirationtheme.com/demo/pinkrio/corporate/contact/" />
				                                <input type="hidden" name="id_form" value="126" />
				                                <input type="submit" name="yit_sendmail" value="Send Message" class="sendmail alignright" />			
				                            </li>
				                        </ul>
				                    </fieldset>
				                </form>
				                <script type="text/javascript">
				                    var messages_form_126 = {
				                    	name: "Please, fill in your name",
				                    	email: "Please, insert a valid email address",
				                    	message: "Please, insert your message"
				                    };
				                </script>
				            </div>
				            <!-- START COMMENTS -->
				            <div id="comments">
				            </div>
				            <!-- END COMMENTS -->
				        </div>
				        <!-- END CONTENT -->
				        <!-- START SIDEBAR -->
				        <div id="sidebar-contact" class="sidebar group">
				            <div class="widget-first widget contact-info">
								{insert name="sbGetContentCms" o1="$page_view_blocks" o2="$module_view_blocks"}
				                <h3>Contacts</h3>
				                <div class="sidebar-nav">
				                    <ul>
				                        <li>
				                            <i class="fa fa-map-marker" style="color:#979797;font-size:20pxpx"></i> Location: PinkRio, 115  Avenue street - Italy
				                        </li>
				                        <li>
				                            <i class="fa fa-phone" style="color:#979797;font-size:20pxpx"></i> Phone: 3471717174
				                        </li>
				                        <li>
				                            <i class="fa fa-print" style="color:#979797;font-size:20pxpx"></i> Fax: +39 0035 356 765
				                        </li>
				                        <li>
				                            <i class="fa fa-envelope" style="color:#979797;font-size:20pxpx"></i> Email: pinkrio@yit.com
				                        </li>
				                    </ul>
				                </div>
				            </div>
				            <div class="widget-last widget text-image">
				                <h3>Customer Support</h3>
				                <div class="text-image" style="text-align:left"><img src="theme/pinkrio/images/callus.gif" alt="Customer Support" /></div>
				                <p>Nunc sit amet pretium purus. Pellet netus et malesuada fames ac turpis egestas.entesque habitant morbi tristique senectus </p>
				            </div>
				        </div>
				        <!-- END SIDEBAR -->
				        <!-- START EXTRA CONTENT -->
				        <!-- END EXTRA CONTENT -->
				    </div>
				</div>
				<!-- END PRIMARY -->