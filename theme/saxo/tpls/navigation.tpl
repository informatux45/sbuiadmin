    
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
                <select onchange="window.open(this.options[this.selectedIndex].value,'_top')">
                    <option value="">Navigate...</option>
                    <option value="index.htm">Home</option>
                        <option value="index.htm">- Full Page</option>
                        <option value="index-gallery.htm">- Gallery Only</option>
                        <option value="index-slider.htm">- Slider Only</option>
                    <option value="features.htm">Features</option>
                    <option value="page-full-width.htm">Pages</option>
                        <option value="page-full-width.htm">- Full Width</option>
                        <option value="page-right-sidebar.htm">- Right Sidebar</option>
                        <option value="page-left-sidebar.htm">- Left Sidebar</option>
                        <option value="page-double-sidebar.htm">- Double Sidebar</option>
                    <option value="gallery-4col.htm">Gallery</option>
                        <option value="gallery-3col.htm">- 3 Column</option>
                        <option value="gallery-4col.htm">- 4 Column</option>
                        <option value="gallery-6col.htm">- 6 Column</option>
                        <option value="gallery-4col-circle.htm">- Gallery 4 Col Round</option>
                        <option value="gallery-single.htm">- Gallery Single</option>
                    <option value="blog-style1.htm">Blog</option>
                        <option value="blog-style1.htm">- Blog Style 1</option>
                        <option value="blog-style2.htm">- Blog Style 2</option>
                        <option value="blog-style3.htm">- Blog Style 3</option>
                        <option value="blog-style4.htm">- Blog Style 4</option>
                        <option value="blog-single.htm">- Blog Single</option>
                    <option value="page-contact.htm">Contact</option>
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
