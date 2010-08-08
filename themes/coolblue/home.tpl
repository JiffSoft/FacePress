{include file='header.tpl'}

<!-- content-outer -->
<div id="content-wrap" class="clear" >

	<!-- content -->
   <div id="content">
        {section name=post loop=$posts}
            {strip}
                <h3>{$posts[post]->post_title}</h3>
                {$posts[post]->post_content}
                <br/><hr/><br/>
            {/strip}
        {/section}
    <!-- content -->
	</div>

<!-- /content-out -->
</div>

{include file='footer.tpl'}