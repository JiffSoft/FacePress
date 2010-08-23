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
            <div class="post-bottom-section">
                <h4>{$post->comment_count} Comment{if $post->comment_count != 1}s{/if}</h4>
                <div class="right">
                {if count($comments) > 0}
                    <ol class="commentlist">
                    {foreach from=$comments item=comment}
                        <li class="depth-1 {cycle values=" thread-alt,"}">
                            <div class="comment-info">
                                <img src="{$theme_path}/img/gravatar.jpg" alt="{$comment->displayname}" width="40" height="40" class="avatar"/>
                                <cite>
                                    <a href="http://www.facebook.com/profile.php?id={$comment->user_ID}">{$comment->displayname}</a> Says:<br/>
                                    <span class="comment-data">{$comment->comment_date}</span>
                                </cite>
                            </div>
                            <div class="comment-text">
                                {$comment->comment_content|@format_post}<br/><br/>
                                {if $user}<div class="reply"><a href="#comment_form" onclick="beginReplyTo({$comment->comment_ID},'{$comment->displayname}')" class="comment-reply-link" rel="nofollow">Reply</a></div>{/if}
                            </div>
                            {if count($comment->subcomments) > 0}
                                <ul class="children">
                                    {foreach from=$comment->subcomments item=subcomment}
                                    <li class="depth-2 {cycle values=" thread-alt,"}">
                                        <div class="comment-info">
                                            <img src="{$theme_path}/img/gravatar.jpg" alt="{$subcomment->displayname}" width="40" height="40" class="avatar"/>
                                            <cite>
                                                <a href="http://www.facebook.com/profile.php?id={$comment->user_ID}">{$subcomment->displayname}</a> Says:<br/>
                                                <span class="comment-data">{$subcomment->comment_date}</span>
                                            </cite>
                                        </div>
                                        <div class="comment-text">
                                            {$subcomment->comment_content|@format_post}<br/><br/>
                                            {if $user}<div class="reply"><a href="#comment_form" onclick="beginReplyTo({$subcomment->comment_ID},'{$comment->displayname}')" class="comment-reply-link" rel="nofollow">Reply</a></div>{/if}
                                        </div>
                                        {if count($subcomment->subcomments) > 0}
                                            <ul class="children">
                                                {foreach from=$subcomment->subcomments item=subcomment2}
                                                    <li class="depth-3 {cycle values=" thread-alt,"}">
                                                        <div class="comment-info">
                                                            <img src="{$theme_path}/img/gravatar.jpg" alt="{$subcomment2->displayname}" width="40" height="40" class="avatar"/>
                                                            <cite>
                                                                <a href="http://www.facebook.com/profile.php?id={$comment->user_ID}">{$subcomment2->displayname}</a> Says:<br/>
                                                                <span class="comment-data">{$subcomment2->comment_date}</span>
                                                            </cite>
                                                        </div>
                                                        <div class="comment-text">
                                                            {$subcomment2->comment_content|@format_post}<br/><br/>
                                                        </div>
                                                    </li>
                                                {/foreach}
                                            </ul>
                                        {/if}
                                    </li>
                                    {/foreach}
                                </ul>
                            {/if}
                        </li>
                    {/foreach}
                    </ol>
                {/if}
                </div><!-- right -->
            </div><!-- post-bottom-section -->
            <div class="post-bottom-section">
                <h4 id="comment_label">Leave a Comment</h4><br/>
                <a name="comment_form"></a>
                <div class="right">
                    {if $user}
                        <form action="{$smarty.server.REQUEST_URI}" method="POST" id="new_comment">
                            <input type="hidden" name="reply_ID" value="0"/>
                            <input type="hidden" name="post_ID" value="{$post->ID}"/>
                            <textarea id="editor" name="content" style="width: 462px; height: 150px;"></textarea>
                            <input class="button" type="submit" value="Post Comment">
                        </form>
                        <script type="text/javascript">
                            $('#editor').bbcode();
                        </script>
                    {else}
                        <div class="center">Please {$fb_login_button} to comment.</div>
                    {/if}
                </div>
            </div><!-- post-bottom-section -->
        </div><!-- main -->

        <div id="sidebar">
            {include file='sidebar.tpl'}
        </div><!-- sidebar -->
        <!-- content -->
	</div>
<!-- /content-out -->
</div>

{include file='footer.tpl'}