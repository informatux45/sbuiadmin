

        <script src="{$smarty.const.SB_THEME_URL}js/min/plugins.min.js"></script>
		<script src="{$smarty.const.SB_THEME_URL}dist/js/lightbox.min.js"></script>
		<script type="text/javascript">
			{* --- Menu responsive --- *}
			$('.toggle-menu').click(function(){
				$('.responsive-menu').slideToggle();
				return false;
			});
		</script>
		
		{insert name="sbGetPlugins"}

    </body>
</html>