
    <!-- Page Content --> 
    <div {insert name=sbGetSectionClassId class="row" evenid="" op="`$smarty.get.op`" page="`$page_id`" id="`$smarty.get.id`" ti="`$sb_title`"}>
		
		{if $sidebar == 'index'}
		
			<div class="span12">
				{insert name="sbGetContentCms" o1="$page_view" o2="$module_view"}
			</div> <!-- End span12 -->
		
		{elseif $sidebar == 'title'}
		
			<div class="span12">
				<h1>{$sb_pages_title}</h1>
				{insert name="sbGetContentCms" o1="$page_view" o2="$module_view"}
			</div> <!-- End span12 -->		
		
		{elseif $sidebar == 'left'}
		
			<!-- Page Sidebar --> 
			<div class="span4 sidebar page-sidebar"><!-- Begin sidebar column -->
				{insert name="sbGetContentCms" o1="$page_view_blocks" o2="$module_view_blocks"}
			</div><!-- End sidebar column -->
			<!-- Page Content --> 
			<div class="span8"><!--Begin page content column-->
				<h1>{$sb_pages_title}</h1>
				{insert name="sbGetContentCms" o1="$page_view" o2="$module_view"}
			</div> <!--End page content column-->
		
		{elseif $sidebar == 'right'}

			<!-- Page Content --> 
			<div class="span8"><!--Begin page content column-->
				<h1>{$sb_pages_title}</h1>
				{insert name="sbGetContentCms" o1="$page_view" o2="$module_view"}
			</div> <!--End page content column-->
			<!-- Page Sidebar --> 
			<div class="span4 sidebar page-sidebar"><!-- Begin sidebar column -->
				{insert name="sbGetContentCms" o1="$page_view_blocks" o2="$module_view_blocks"}
			</div><!-- End sidebar column -->
		
		{elseif $sidebar == 'contact'}
		
			<div class="span8 contact"><!--Begin page content column-->
	
				<h1>{$sb_pages_title}</h1>
				
				{insert name="sbGetContentCms" o1="$page_view" o2="$module_view"}
				
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla iaculis mattis lorem, quis gravida nunc iaculis ac. Proin tristique tellus in est vulputate luctus fermentum ipsum molestie. Vivamus tincidunt sem eu magna varius elementum. Maecenas felis tellus, fermentum vitae laoreet vitae, volutpat et urna.</p>
	
				<div class="alert alert-success">
					Well done! You successfully read this important alert message. 
				</div>
	
				<form action="#" id="contact-form">
					<div class="input-prepend">
						<span class="add-on"><i class="icon-user"></i></span>
						<input class="span4" id="prependedInput" size="16" type="text" placeholder="Name">
					</div>
					<div class="input-prepend">
						<span class="add-on"><i class="icon-envelope"></i></span>
						<input class="span4" id="prependedInput" size="16" type="text" placeholder="Email Address">
					</div>
					<div class="input-prepend">
						<span class="add-on"><i class="icon-globe"></i></span>
						<input class="span4" id="prependedInput" size="16" type="text" placeholder="Website URL">
					</div>
					<textarea class="span6"></textarea>
					<div class="row">
						<div class="span2">
							<input type="submit" class="btn btn-inverse" value="Send Message">
						</div>
					</div>
				</form>
	
			</div> <!--End page content column-->
	
			<!-- Sidebar
			================================================== --> 
			<div class="span4 sidebar page-sidebar"><!-- Begin sidebar column -->
				<h5 class="title-bg">Our Location</h5>
				<address>
				<strong>Piccolo</strong><br>
				123 Main St, Suite 600<br>
				San Francisco, CA 94107<br>
				<abbr title="Phone">P:</abbr> (123) 456-7890
				</address>
				 
				<address>
				<strong>Jimmy Doe</strong><br>
				<a href="mailto:#">first.last@gmail.com</a>
				</address>
	
				<h5 class="title-bg">Map Us</h5>
				<img src="{$smarty.const.SB_THEME_URL}img/location-map.jpg" alt="map">
				
				{insert name="sbGetContentCms" o1="$page_view_blocks" o2="$module_view_blocks"}
	
			</div><!-- End sidebar column -->
		
		{/if}
		
	</div> <!-- End row -->