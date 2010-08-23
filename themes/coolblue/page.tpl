{include file='header.tpl'}

<!-- content-outer -->
<div id="content-wrap" class="clear" >

	<!-- content -->
   <div id="content">
        <div id="main">
            <h1>{$page->post_title}</h1><br/>
            {$page->post_content}
        </div><!-- main -->
        <div id="sidebar">
            {include file='sidebar.tpl'}
        </div><!-- sidebar -->
    <!-- content -->
	</div>

<!-- /content-out -->
</div>

{include file='footer.tpl'}