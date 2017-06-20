
	<section class="banner{if !$sb_page_headpage} no-banner{/if}" role="banner">
        <header id="header">
            <div class="header-content clearfix">
                <a class="logo" href="{$smarty.const.SB_URL}" title="{$sb_site_title}">
									<img src="{$smarty.const.SB_THEME_URL}images/logo.png" alt="">
								</a>
                <nav class="navigation" role="navigation">
										{insert name='sbGetMenuCms' mclass='primary-nav' mid='nav' mtag='main_menu' mlang="`$smarty.session.lang`"}
                    <ul class="primary-nav primary-nav2">
												{insert name="sbGetThemeOption" option="slogan"}
                    </ul>
                </nav>
                <a href="#" class="nav-toggle">Menu<span></span></a>
            </div><!-- header content -->
        </header><!-- header -->
				
		{if $sb_page_headpage}
        <div class="container container-slider">
            <div class="col-md-12">
							<div class="row">
                <div class="banner-text text-center">
										{insert name="sbDoShortcode" code="[CS id=`$sb_page_headpage` name=sbslider]"}
                    {*<h1>Your Favorite One Page Multi Purpose Template</h1>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent commodo cursus magna vel scelerisque nisl consectetur et.</p>
                    <a href="#" class="btn btn-large">Find out more</a>*}
                </div><!-- banner text -->
							</div>
            </div>
        </div>
		{/if}
				
    </section><!-- banner -->