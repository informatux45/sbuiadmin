    
      <div class="row header"><!-- Begin Header -->
      
        <!-- Logo -->
        <div class="span5 logo">
        	<a href="{$smarty.const.SB_URL}" title="{$sb_site_title}">
                <img src="{$smarty.const.SB_THEME_URL}img/logo.png" title="{$sb_site_title}" alt="{$sb_site_title}" />
            </a>
            <h5>{insert name="sbGetThemeOption" option="slogan"}</h5>
        </div>
        
        <!-- Main Navigation -->
        <div class="span7 navigation">
            <div class="navbar hidden-phone">
							{insert name='sbGetMenuCms' mclass='nav' mid='nav' mtag='main_menu' mlang="`$smarty.session.lang`"}
            </div>

            <!-- Mobile Nav -->
            <form action="#" id="mobile-nav" class="visible-phone">
                <div class="mobile-nav-select">
                <select id="mobile-nav-select-opt">
										{insert name='sbGetMenuCms' mclass='nav' mid='nav' mtag='main_menu' mtype='option' mlang="`$smarty.session.lang`"}									
                </select>
                </div>
                </form>

        </div>
        
    </div><!-- End Header -->

    {if $sb_page_headpage}
    <div class="row headline"><!-- Begin Headline -->
    
     	<!-- Slider Carousel -->
        <div class="span8">
            <div class="flexslider">
              {insert name="sbDoShortcode" code="[CS id=`$sb_page_headpage` name=sbslider]"}
            </div>
        </div>
        
        <!-- Headline Text -->
        <div class="span4">
        	{insert name="sbGetConfig" id="header"}
        </div>
    </div><!-- End Headline -->
    {/if}
		
		<script>
			$('#mobile-nav-select-opt').change(function(){
					var link_menu_mobile = $(this).find('option:selected').attr('data-href');
					window.open(link_menu_mobile, '_top')
			});
		</script>
