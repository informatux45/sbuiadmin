{* --- NEWS display a article (item) --- *}

{include file='news/tpls/news_header.tpl'}

{include file='news/tpls/news_breadcrumb.tpl'}

{if $item}

	{* Stat VIEWED *}
	{insert name="sbGetNewsViewed" id="{$item.id}"}

	{* Check if TPL_SINGLE custom *}
	{if $sbnews_item_tpl_single != ''}
	
		{* Show TPL SINGLE template *}
		{eval var=$sbnews_item_tpl_single}
		
	{else}
		
		{* Show Item news DEFAULT *}
		<h3>[{$item.date|sbConvertDate:"FR"}] {$item.title|unescape:"htmlall"|sbDisplayLang:"`$smarty.session.lang`"}</h3>
		{if $item.subtitle|unescape:"htmlall"|sbDisplayLang:"`$smarty.session.lang`"}
			<h4>{$item.subtitle|unescape:"htmlall"|sbDisplayLang:"`$smarty.session.lang`"}</h4>
		{/if}
		
		<p class="sbnews-single">
			{if $item.image}
			<img class="sbnews-single-img" src="{$smarty.const._AM_MEDIAS_URL}/{$item.image}" alt="{$item.title|unescape:"htmlall"|sbDisplayLang:"`$smarty.session.lang`"|sbGetShortcode}">
			{/if}
			<div class="sbnews-single-text">
				{$item.desc_full|unescape:"htmlall"|sbDisplayLang:"`$smarty.session.lang`"|sbGetShortcode}
			</div>
		</p>

		<div class="sbnews-clear-both"> </div>
				
		<div class="sbnews-single-next-prev">
			{if $sbnews_options.news_next_prev == "title"}
				{if $next_prev.next}
					<a rel="next" class="next_a" href="{seo url="index.php?p=news&op=article&id={$next_prev.next}" rewrite="news/article/{$next_prev.next}/{$next_prev.next_title|unescape:"htmlall"|sbDisplayLang:"`$smarty.session.lang`"|strip_tags|sbRewriteString|strtolower}"}">
						&Leftarrow;&nbsp;{$next_prev.next_title|unescape:"htmlall"|sbDisplayLang:"`$smarty.session.lang`"|strip_tags|truncate:"40":"..."}
					</a>
				{/if}
				{if $next_prev.prev}
					<a rel="prev" class="prev_a" href="{seo url="index.php?p=news&op=article&id={$next_prev.prev}" rewrite="news/article/{$next_prev.prev}/{$next_prev.prev_title|unescape:"htmlall"|sbDisplayLang:"`$smarty.session.lang`"|strip_tags|sbRewriteString|strtolower}"}">
						{$next_prev.prev_title|unescape:"htmlall"|sbDisplayLang:"`$smarty.session.lang`"|strip_tags|truncate:"40":"..."}&nbsp;&Rightarrow;
					</a>
				{/if}
			{else}
				{if $next_prev.next}
				<span class="next">
					<a rel="next" href="{seo url="index.php?p=news&op=article&id={$next_prev.next}" rewrite="news/article/{$next_prev.next}/{$next_prev.next_title|unescape:"htmlall"|sbDisplayLang:"`$smarty.session.lang`"|strip_tags|sbRewriteString|strtolower}"}">
						&rsaquo;
					</a>
				</span>
				{/if}
				{if $next_prev.prev}
				<span class="prev">
					<a rel="prev" href="{seo url="index.php?p=news&op=article&id={$next_prev.prev}" rewrite="news/article/{$next_prev.prev}/{$next_prev.prev_title|unescape:"htmlall"|sbDisplayLang:"`$smarty.session.lang`"|strip_tags|sbRewriteString|strtolower}"}">
						&lsaquo;
					</a>
				</span>
				{/if}
			{/if}
		</div>

		<div class="sbnews-clear-both"> </div>

		{if $sbnews_options.other_news}
			{math equation="x - 2" x=$sbnews_options.other_news_per_page assign=media_768}
			{math equation="x - 1" x=$media_768 assign=media_544}
			<style>
				.sbnews-single-others {
					-webkit-columns: {$sbnews_options.other_news_per_page|default:4};
					-moz-columns: {$sbnews_options.other_news_per_page|default:4};
					columns: {$sbnews_options.other_news_per_page|default:4};
				}
				.sbnews-othernews {
					height: 300px;
				}
				@media screen and (max-width: 768px) {
					.sbnews-single-others {
						-webkit-columns: {$media_768};
						-moz-columns: {$media_768};
						columns: {$media_768};
					}
					.sbnews-othernews {
					  margin-bottom: 10px;
					}
				}
				@media screen and (max-width: 544px) {
					.sbnews-single-others {
						-webkit-columns: {$media_544};
						-moz-columns: {$media_544};
						columns: {$media_544};
					}
					.sbnews-othernews {
					  margin-bottom: 10px;
					}
				}
			</style>
			{if $sbnews_options.other_news_title}
				<h2 class="sbnews-single-others-h2">{$sbnews_options.other_news_title}</h2>
			{/if}
			<div class="sbnews-single-others">
				{foreach from=$sbnews_other_news item=other}
					<div class="col-lg-3 m-b-20">
						<a href="{seo url="index.php?p=news&op=article&id={$other.id}" rewrite="news/article/{$other.id}/{$other.title|unescape:"htmlall"|sbDisplayLang:"`$smarty.session.lang`"|strip_tags|sbRewriteString|strtolower}"}">
							<div class="sbnews-othernews sbnews_overlay sbnews_color" style="{if $other.image}background: url({$smarty.const.SB_URL}thumb.php?src={$smarty.const._AM_MEDIAS_URL}{$other.image}&size=15x15) no-repeat; background-size: cover;{/if}">
								<span class="title">{$other.title|unescape:"htmlall"|strip_tags|sbDisplayLang:"`$smarty.session.lang`"|upper}</span>
								<span class="content">{$other.desc_short|unescape:"htmlall"|strip_tags|sbDisplayLang:"`$smarty.session.lang`"|lower|truncate:150:"..."}</span>
							</div>
						</a>
					</div>
				{/foreach}
			</div>
		{/if}
		
		<div class="sbnews-clear-both"> </div>
		
		{if $sbnews_options.comments == 'disqus' && $sbnews_options.comments_user != ''}
			<div id="disqus_thread"></div>
			<script>
			/**
			*  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
			*  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables
			*/
			var disqus_config = function () {
			this.page.url = '{seo url="index.php?p=news&op=article&id={$item.id}" rewrite="news/article/{$item.id}/{$item.title|unescape:"htmlall"|sbDisplayLang:"`$smarty.session.lang`"|strip_tags|sbRewriteString|strtolower}"}';  // Replace PAGE_URL with your page's canonical URL variable
			this.page.identifier = '{$item.id}-{$item.title|strtolower|sbRewriteString|trim:"fr"|rtrim:"-"}'; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
			};
			(function() { // DON'T EDIT BELOW THIS LINE
			var d = document, s = d.createElement('script');
			s.src = 'https://{$sbnews_options.comments_user}.disqus.com/embed.js';
			s.setAttribute('data-timestamp', +new Date());
			(d.head || d.body).appendChild(s);
			})();
			</script>
			<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
		{/if}
		
	{/if}
	
	<div class="related-posts-viewed">
		<i class="fa fa-archive"></i>&nbsp;&nbsp;Posté le {$item.date|sbConvertDate:"FR"}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-eye" title="Article vu {$item.viewed} fois"></i>&nbsp;&nbsp;{$item.viewed}
	</div>

{else}

	{$smarty.const._CMS_NEWS_NOITEM}

{/if}
