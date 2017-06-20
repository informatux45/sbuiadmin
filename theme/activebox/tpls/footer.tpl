
    <footer class="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="footer-col col-md-4">
                        <h5>Adresse</h5>
                        <p>35 rue de la bourse<br>75016 Paris</p>
                    </div>
                    <div class="footer-col col-md-4">
                        <h5>Partage</h5>
                        <ul class="footer-share">
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                            <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                        </ul>
                    </div>
                    <div class="footer-col col-md-4">
                        <h5>A propos de nous</h5>
                        {insert name="sbGetConfig" id="header"}
                    </div>
                </div>
            </div>
        </div><!-- footer top -->
        <div class="footer-bottom">
            <div class="container">
                <div class="col-md-12">
                    <p>{insert name="sbGetConfig" id="footer"}</p>
                </div>
            </div>
        </div>
    </footer><!-- footer -->

    <script src="{$smarty.const.SB_THEME_URL}js/bootstrap.min.js"></script>
    <script src="{$smarty.const.SB_THEME_URL}js/jquery.flexslider-min.js"></script>
    <script src="{$smarty.const.SB_THEME_URL}js/jquery.fancybox.pack.js"></script>
    <script src="{$smarty.const.SB_THEME_URL}js/jquery.waypoints.min.js"></script>
    <script src="{$smarty.const.SB_THEME_URL}js/retina.min.js"></script>
    <script src="{$smarty.const.SB_THEME_URL}js/modernizr.js"></script>
    <script src="{$smarty.const.SB_THEME_URL}js/main.js"></script>

	{insert name="sbGetPlugins"}

</body>
</html>