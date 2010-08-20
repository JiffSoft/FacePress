<!-- footer-outer -->
{$body_contents}
<div id="footer-outer" class="clear"><div id="footer-wrap">

	<div id="gallery" class="clear">



    </div>

	 <div class="col-a">

		<h3>Contact Info</h3>

        <p><strong>E-mail: </strong><br/>jonnyfunfun &lt;a&gt; jonnyfunfun &bull; com</p>
		<p>Want more info - go to my <a href="/pages/contact">contact page</a></p>

      <h3>Updates</h3>

      <ul class="subscribe-stuff">
      	    <li><a title="RSS" href="/rss" rel="nofollow">
				<img alt="RSS" title="RSS" src="{$theme_path}/img/social_rss.png" /></a>
			</li>
      	    <li><a title="Facebook" href="http://www.facebook.com/jonnyfunfun" rel="nofollow">
				<img alt="Facebook" title="Facebook" src="{$theme_path}/img/social_facebook.png" /></a>
			</li>
			<li><a title="Twitter" href="http://twitter.com/jonnyfunfun" rel="nofollow">
				<img alt="Twitter" title="Twitter" src="{$theme_path}/img/social_twitter.png" /></a>
			</li>
      </ul>
	</div>

	<div class="col-a">

   	<h3>Site Links</h3>

		<div class="footer-list">
			<ul>
			    <li><a href="/">Home</a></li>
                {section name=page loop=$pages}
                    {strip}
                        <li><a href="{$pages[page]->uri}">{$pages[page]->post_title}</a></li>
                    {/strip}
                {/section}
			</ul>
		</div>

	</div>

   <div class="col-a">

      <h3>Other Sites of Mine</h3>

		<div class="footer-list">
			<ul>
				<li><a href="http://www.vaultheadgames.com/">Vault Head Games</a></li>
				<li><a href="http://www.jiffsoft.com/">JiffSoft</a></li>
				<li><a href="http://www.thebrewplace.com/">The Brew Place</a></li>
				<li><a href="http://www.lan69.com/">LAN69</a></li>
			</ul>
		</div>

      <h3>Web Links</h3>

		<div class="footer-list">
			<ul>
				<li><a href="http://www.lizzyfunfun.com/">My wife, LizzyFunFun</a></li>
				<li><a href="http://donald.enzinna.com/">My father, engineer extrodinaire</a></li>
				<li><a href="http://www.massivelan.com/">MassiveLAN</a></li>
			</ul>
		</div>
   </div>

   <div class="col-b">

      <h3>Archives</h3>

	   <div class="footer-list">
            <ul>{section name=archive loop=$archives}
			    {strip}
			        <li><a href="/archives/{$archives[archive]->year}/{$archives[archive]->month}">{$archives[archive]->label}</a></li>
			    {/strip}
			{/section}</ul>
		</div>

	</div>

<!-- /footer-outer -->
</div></div>

<!-- footer-bottom -->
<div id="footer-bottom">

	<p class="bottom-left">
	    <a href="http://www.facepressblog.org/">
	        <img src="/fpb-content/images/badge.png" alt="Powered by FacePress"/>
	    </a>&nbsp;&nbsp;
		&copy; 2010 <strong>Jonathan Enzinna</strong>&nbsp; &nbsp; &nbsp;
		<a href="http://www.bluewebtemplates.com/" title="Website Templates">website templates</a> by <a href="http://www.styleshout.com/">styleshout</a>
	</p>

	<p class="bottom-right">
		<a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> |
	   <a href="http://validator.w3.org/check/referer">XHTML</a>	|
		<a href="/">Home</a> |
		<a href="/rss">RSS Feed</a> |
      <strong><a href="#top">Back to Top</a></strong>
   </p>

<!-- /footer-bottom-->
</div>

{$fb_root}

{$debug}
</body>
</html>