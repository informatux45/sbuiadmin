{* --- Show CONTACT Page module --- *}

			<div class="hentry group">

			     <form action="http://{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}" method="post" class="contact-form">
					<div class="usermessagea">
						{if $errMsg}
							<div class="box alert-box">
								{$errMsg}
							</div>
						{/if}
						{if $succMsg}
							<div class="box success-box">
								{$succMsg}
							</div>
						{/if}
					</div>
					<fieldset>
						<ul>
							<li class="text-field">
								<label for="name-contact-us">
								<span class="label">{$smarty.const._CMS_CONTACT_FIELD_NAME}</span>
								<br />					{*<span class="sublabel">This is the name</span><br />*}
								</label>
								<div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span><input type="text" name="name" id="name-contact-us" value="{if !$sendmailok}{$smarty.post.name}{/if}" required></div>
								<div class="msg-error"></div>
							</li>
							<li class="text-field">
								<label for="email-contact-us">
								<span class="label">{$smarty.const._CMS_CONTACT_FIELD_EMAIL}</span>
								<br />					{*<span class="sublabel">This is a field email</span><br />*}
								</label>
								<div class="input-prepend"><span class="add-on"><i class="icon-envelope"></i></span><input type="text" name="email" id="email-contact-us" value="{if !$sendmailok}{$smarty.post.email}{/if}" required></div>
								<div class="msg-error"></div>
							</li>
							<li class="text-field">
								<label for="object-contact-us">
								<span class="label">{$smarty.const._CMS_CONTACT_FIELD_OBJECT}</span>
								<br />					{*<span class="sublabel">This is the object</span><br />*}
								</label>
								<div class="input-prepend"><span class="add-on"><i class="icon-share"></i></span><input type="text" name="object" id="object-contact-us" class="{if !$sendmailok}{$smarty.post.object}{/if}" value="" required></div>
								{*<div class="msg-error"></div>*}
							</li>
							<li class="text-field">
								<label for="phone-contact-us">
								<span class="label">{$smarty.const._CMS_CONTACT_FIELD_PHONE}</span>
								<br />					{*<span class="sublabel">This is a field Phone</span><br />*}
								</label>
								<div class="input-prepend"><span class="add-on"><i class="icon-phone"></i></span><input type="text" name="phone" id="phone-contact-us" value="{if !$sendmailok}{$smarty.post.phone}{/if}" required></div>
								<div class="msg-error"></div>
							</li>
							<li class="textarea-field">
								<label for="message-contact-us">
								<span class="label">{$smarty.const._CMS_CONTACT_FIELD_MESSAGE}</span>
								</label>
								<div class="input-prepend"><span class="add-on"><i class="icon-pencil"></i></span><textarea name="message" id="message-contact-us" rows="8" cols="30" required>{if !$sendmailok}{$smarty.post.message}{/if}</textarea></div>
								<div class="msg-error"></div>
							</li>
							<li class="text-field">
								{* ================================================ *}
								{* ==== To ADD for the module CONTACT Captcha ===== *}
								{* ================================================ *}
								<div id="grecaptcha"></div>
								<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl={if $smarty.session.lang == 'en'}en{else}fr{/if}&remoteip={$smarty.server.REMOTE_ADDR}" async defer></script>
								{* ================================================ *}
							</li>
							<li class="submit-button">
								<input type="submit" name="submit" value="{$smarty.const._CMS_CONTACT_BUTTON_SEND}" class="sendmail alignleft" />			
							</li>
						</ul>
					</fieldset>
			     </form>
						
						
				{* ================================================ *}
				{* === To ADD for the module CONTACT Validation === *}
				{* ================================================ *}
				<script type="text/javascript" src="datas/modules/contact/inc/jquery.validate.min.js"></script>
				{* ================================================ *}
				{* ==== To ADD for the module CONTACT Captcha ===== *}
				{* ================================================ *}
				<script type="text/javascript">
					var onloadCallback = function() {
						grecaptcha.render('grecaptcha', {
							'sitekey' : '{$grecaptcha_publickey}',
							'theme' : 'light', // light, dark
							'type' : 'image', // image, audio
							'size' : 'normal', // normal, compact
							'tabindex' : 0
						});
					};
				</script>
				{* ================================================ *}


			</div>

