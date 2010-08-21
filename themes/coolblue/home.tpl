{include file='header.tpl'}

<!-- content-outer -->
<div id="content-wrap" class="clear" >

	<!-- content -->
   <div id="content">
        <div id="main">
            {section name=post loop=$posts}{strip}
                <div class="post">
                    <div class="right">
                        <h2><a href="{$posts[post]->uri}">{$posts[post]->post_title}</a></h2>
                        <p class="post-info"></p>
                        <p>{$posts[post]->post_content|@format_post}</p>
                    </div>
                    <div class="left">
                        <p class="dateinfo">{$posts[post]->post_date|date_format:"%b"}<span>{$posts[post]->post_date|date_format:"%d"}</span></p>
                        <div class="post-meta">
                            <h4>Post Info</h4>
                            <ul>
                                <li class="user"><a href="http://www.facebook.com/jonnyfunfun">JonnyFunFun</a></li>
                                <li class="time">{$posts[post]->post_date|date_format:"%H:%M"}</li>
                                <li class="comment">{$posts[post]->comment_count} Comment{if $posts[post]->comment_count != 1}s{/if}</li>
                                <li class="permalink"><a href="{$posts[post]->uri}">Permalink</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            {/strip}{/section}
        </div><!-- main -->

        <div id="sidebar">
            {include file='sidebar.tpl'}
        </div><!-- sidebar -->
    <!-- content -->
    </div>
<!-- /content-out -->
</div>

{include file='footer.tpl'}