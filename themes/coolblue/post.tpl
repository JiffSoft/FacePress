{include file='header.tpl'}

<!-- content-outer -->
<div id="content-wrap" class="clear" >

	<!-- content -->
   <div id="content">
        <div id="main">
            <div class="post">
                <div class="right">
                    <h2>{$post->post_title}</h2>
                    <p class="post-info"></p>
                    <p>{$post->post_content|@format_post}</p>
                    <h3>Comments:</h3>
                    {if count($comments) > 0}
                        <div class="comment-list">
                            <pre>{$comments|@print_r}</pre>
                            {foreach from=$comments item=comment}
                                <div class="comment">
                                    <div class="left">
                                        <h6>{$comment->displayname}</h6>
                                    </div>
                                    <div class="right">
                                        {$comment->comment_content|@format_post}
                                    </div>
                                    {if count($comment->subcomments) > 0}
                                        {foreach from=$comment->subcomments item=subcomment}
                                            <div class="comment">
                                                <div class="left">
                                                    <h6>{$comment->displayname}</h6>
                                                </div>
                                                <div class="right">
                                                    {$comment->comment_content|@format_post}
                                                </div>
                                            </div>
                                        {/foreach}
                                    {/if}
                                </div>
                            {/foreach}
                        </div>
                    {/if}
                    {if $user}
                        <p>Post a new comment:</p>
                        <form action="." method="POST" id="new_comment">
                            <textarea id="editor" name="content" style="width: 462px; height: 150px;"></textarea>
                            <a class="more" href="#" onclick="$('#new_comment').submit();">Post Comment</a><br/><br/>
                        </form>
                        <script type="text/javascript">
                            $('#editor').bbcode();
                        </script>
                    {/if}
                </div>
                <div class="left">
                    <p class="dateinfo">{$post->post_date|date_format:"%b"}<span>{$post->post_date|date_format:"%d"}</span></p>
                    <div class="post-meta">
                        <h4>Post Info</h4>
                        <ul>
                            <li class="user"><a href="http://www.facebook.com/jonnyfunfun">JonnyFunFun</a></li>
                            <li class="time">{$post->post_date|date_format:"%H:%M"}</li>
                            <li class="comment">{$post->comment_count} Comment{if $post->comment_count != 1}s{/if}</li>
                            <li class="permalink"><a href="{$post->uri}">Permalink</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div><!-- main -->

        <div id="sidebar">
            {include file='sidebar.tpl'}
        </div><!-- sidebar -->
        <!-- content -->
	</div>
<!-- /content-out -->
</div>

{include file='footer.tpl'}