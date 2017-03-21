{* Header TPL *}

			{* --- Page Title --- *}
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">{if $page_title}{$page_title}{else}{$pageindex}{/if}</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
			
			{if $smarty.const._AM_SITE_DEBUG}
				{* --------------------------------------- *}
				{* Smarty Function                         *}
				{* Libs / Plugins -> function.sbdebug.php  *}
				{* Created by BooBoo                       *}
				{* --------------------------------------- *}
				{sbdebug debugsql=$sbdebugsql odump=$sbodump file_content=$file_content}
				<p></p>
			{/if}
			
			{* Message status if action *}
			{if $sb_msg_error || $sb_msg_valid}

				{if $sb_msg_valid}
					{* --- Message status VALID --- *}
					<div class="alert alert-success alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						{$sb_msg_valid}
					</div>
				{elseif $sb_msg_error}
					{* --- Message status VALID --- *}
					<div class="alert alert-danger alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 {$sb_msg_error}
					</div>
				{/if}
				
			{/if}