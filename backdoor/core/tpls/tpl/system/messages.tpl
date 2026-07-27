{* -------------- *}
{* --- SYSTEM --- *}
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page page='false'}
	{* ---------------- End Headers --------------- *}

			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}

			<section class="hero mail-hero">
				<div class="hero-text">
					<span class="eyebrow">Conversations</span>
					<h1 class="hero-title">Messages</h1>
					<p class="hero-sub">
						{if $sb_messages_conversations|@count > 0}
							<strong>{$sb_messages_conversations|@count}</strong> conversation{if $sb_messages_conversations|@count > 1}s{/if}
						{else}
							Aucune conversation pour l'instant.
						{/if}
					</p>
				</div>
				<div class="hero-actions">
					<button class="btn btn--primary" type="button" data-toggle="modal" data-target="#sbNewChatModal">
						<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
						Nouvelle conversation
					</button>
				</div>
			</section>

			<section class="chat-shell" id="sbMessagesShell"
				data-url="{$smarty.const._AM_SITE_URL}index.php?p=messages"
				data-me="{$sb_messages_current_user_id}"
				data-with="{$sb_messages_with}">

				<aside class="chat-rail">
					<div class="chat-rail-head">
						<div class="chat-rail-title-row">
							<div class="chat-rail-title">Conversations</div>
						</div>
					</div>
					<div class="chat-list-scroll">
						{foreach from=$sb_messages_conversations item=conv}
							<a class="chat-conv{if $sb_messages_with == $conv.other_id} is-active{/if}{if $conv.unread > 0} is-unread{/if}"
								href="{$smarty.const._AM_SITE_URL}index.php?p=messages&with={$conv.other_id}">
								<img class="chat-conv-avatar" style="object-fit:cover" src="{$conv.other_user.email|@sbGetGravatar}" alt="">
								<div class="chat-conv-body">
									<div class="chat-conv-top">
										<div class="chat-conv-name">{$conv.other_user.username}</div>
										<div class="chat-conv-time">{$conv.last_time|date_format:"%d/%m %H:%M"}</div>
									</div>
									<div class="chat-conv-preview">{$conv.last_message|truncate:60|escape:'html'}</div>
								</div>
								{if $conv.unread > 0}<div class="chat-conv-badge">{$conv.unread}</div>{/if}
							</a>
						{foreachelse}
							<div class="chat-conv" style="cursor:default">
								<div class="chat-conv-body">
									<div class="chat-conv-preview">Démarrez une conversation via "Nouvelle conversation".</div>
								</div>
							</div>
						{/foreach}
					</div>
				</aside>

				<section class="chat-pane">
					{if $sb_messages_with_user}
						<div class="chat-pane-head">
							<img class="av" style="object-fit:cover" src="{$sb_messages_with_user.email|@sbGetGravatar}" alt="">
							<div class="meta">
								<div class="name">{$sb_messages_with_user.username}</div>
							</div>
						</div>

						<div class="chat-thread" id="sbMessagesThread">
							{foreach from=$sb_messages_thread item=msg}
								<div class="chat-msg{if $msg.mine} me{/if}" data-id="{$msg.id}" data-created-at="{$msg.created_at}">
									<div class="chat-msg-stack">
										<div class="chat-bub" style="white-space:pre-wrap">{$msg.message|escape:'html'}</div>
										<div class="chat-msg-meta">{$msg.created_at|date_format:"%d/%m %H:%M"}</div>
									</div>
								</div>
							{/foreach}
						</div>

						<div class="chat-composer">
							<div class="chat-composer-input">
								<div class="dd-wrap">
									<button class="chat-composer-send sb-emoji-toggle" type="button" data-dropdown aria-label="Émoticônes">🥳</button>
									<div class="dd-menu dd-menu-up dd-menu-left" role="menu" id="sbMessagesEmojiMenu">
										<div class="dd-head">Émoticônes</div>
										<div class="sb-emoji-grid">
											{foreach from=$sb_emoji_list item=emoji}
												<button type="button" class="sb-emoji-item">{$emoji}</button>
											{/foreach}
										</div>
									</div>
								</div>
								<textarea id="sbMessagesInput" placeholder="Votre message…" rows="1"></textarea>
								<button class="chat-composer-send" id="sbMessagesSend" aria-label="Envoyer">
									<svg viewBox="0 0 24 24"><path d="m22 2-7 20-4-9-9-4z"/><path d="M22 2 11 13"/></svg>
								</button>
							</div>
						</div>
					{else}
						<div class="chat-pane-head">
							<div class="meta"><div class="name">&nbsp;</div></div>
						</div>
						<div class="chat-thread" style="align-items:center;display:flex;justify-content:center;color:var(--t-muted)">
							Sélectionnez une conversation, ou démarrez-en une nouvelle.
						</div>
					{/if}
				</section>
			</section>

			{* --- Modal : nouvelle conversation --- *}
			<div aria-hidden="true" role="dialog" tabindex="-1" id="sbNewChatModal" class="modal">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Nouvelle conversation</h4>
							<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
						</div>
						<div class="modal-body">
							<div class="dd-list">
								{foreach from=$sb_messages_pickable_users item=u}
									<a class="dd-item" href="{$smarty.const._AM_SITE_URL}index.php?p=messages&with={$u.id}">
										<img style="width:36px;height:36px;border-radius:50%;object-fit:cover" src="{$u.email|@sbGetGravatar}" alt="">
										<div class="dd-body"><div class="dd-text">{$u.username}</div></div>
									</a>
								{foreachelse}
									<div class="dd-item"><div class="dd-body"><div class="dd-text">Aucun autre utilisateur.</div></div></div>
								{/foreach}
							</div>
						</div>
						<div class="modal-footer">
							<button data-dismiss="modal" class="btn btn--ghost" type="button">Fermer</button>
						</div>
					</div>
				</div>
			</div>

	{include file='sb_footer.tpl' page='false' pagef='false'}
