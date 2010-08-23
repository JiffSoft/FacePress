{include file='header.tpl'}

<!-- content-outer -->
<div id="content-wrap" class="clear" >

	<!-- content -->
   <div id="content">
        <div id="main">
            {section name=post loop=$posts}
                {strip}
                    <h3>{$posts[post]->post_title}</h3>
                    {$posts[post]->post_content}
                    <br/><hr/><br/>
                {/strip}
            {/section}
        </div> <!-- main -->
        <div id="sidebar">
            {include file='sidebar.tpl'}
        </div><!-- sidebar -->
    <!-- content -->
	</div>

<!-- /content-out -->
</div>

{include file='footer.tpl'}