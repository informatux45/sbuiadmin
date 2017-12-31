
	<!-- Page Content --> 
    <section {insert name=sbGetSectionClassId class="features section" evenid="features" op="`$smarty.get.op`" page="`$page_id`" id="`$smarty.get.id`" ti="`$sb_title`"}>
        <div class="container">
            <div class="row">

				{if $sidebar == 'index'}
				
					{insert name="sbGetContentCms" o1="$page_view" o2="$module_view"}
					
				{elseif $sidebar == 'title'}
				
					<h1>{$sb_pages_title}</h1>
					{insert name="sbGetContentCms" o1="$page_view" o2="$module_view"}
				
				{elseif $sidebar == 'contact'}
				
						<h1>{$sb_pages_title}</h1>
						
						{insert name="sbGetContentCms" o1="$page_view" o2="$module_view"}
						
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla iaculis mattis lorem, quis gravida nunc iaculis ac. Proin tristique tellus in est vulputate luctus fermentum ipsum molestie. Vivamus tincidunt sem eu magna varius elementum. Maecenas felis tellus, fermentum vitae laoreet vitae, volutpat et urna.</p>
			
						<div class="alert alert-success">
							Well done! You successfully read this important alert message. 
						</div>
			
						<form action="#" id="contact-form">
							<div class="row">
								<div class="col-md-6 col-sm-12 text-center">
									<div class="form-group">
										<label for="exampleInputEmail1">Nom</label>
										<input id="exampleInputEmail1" class="form-control" aria-describedby="nameHelp" placeholder="Votre nom" type="text">
										<small id="nameHelp" class="form-text text-muted">Nous n'utilisons pas vos coordonnées à des fins commerciales</small>
									</div>
								</div>
								<div class="col-md-6 col-sm-12 text-center">
									<div class="form-group">
										<label for="exampleInputEmail">Email</label>
										<input id="exampleInputEmail" class="form-control" aria-describedby="emailHelp" placeholder="Votre email" type="email">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 col-sm-12 text-center">
									<div class="form-group">
										<label for="exampleInputObject">Objet</label>
										<input id="exampleInputObject" class="form-control" aria-describedby="objectHelp" placeholder="Votre demande" type="text">
									</div>
								</div>
								<div class="col-md-6 col-sm-12 text-center">
									<div class="form-group">
										<label for="exampleInputWebsite">Site web</label>
										<input id="exampleInputWebsite" class="form-control" aria-describedby="websiteHelp" placeholder="Votre site web" type="text">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12 text-center">
									<div class="form-group">
										<label for="exampleInputMessage">Votre message</label>
										<textarea id="exampleInputMessage" class="form-control" aria-describedby="messageHelp" placeholder="Votre site web"></textarea>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12 text-left">
									<div class="form-group">
										<input type="submit" class="btn btn-inverse" value="Send Message">
									</div>
								</div>
							</div>
						</form>
				
				{/if}

            </div>
        </div>
    </section><!-- Page Content -->