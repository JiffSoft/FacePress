<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">

<head>
    <title>{$site_name} | {$page_title|default:"I got no title!"}</title>
    <link rel="stylesheet" href="{$theme_path}/css/screen.css" type="text/css" media="screen print projection"/>
    {$head}
    <script type="text/javascript" src="{$theme_path}/js/coolblue.js"></script>
</head>

<body>

<!--header -->
<div id="header-wrap"><div id="header">

	<a name="top"></a>

	<h1 id="logo-text"><a href="/" title="">{$site_name}</a></h1>
	<p id="slogan">{$site_slogan}</p>

	<div id="nav">
        <ul id="main-nav">
            <li><a href="/">Home</a></li>
            {section name=page loop=$pages}
                {strip}
                    <li><a href="{$pages[page]->uri}">{$pages[page]->post_title}</a>
                    {if isset($pages[page]->subpages)}
                        <ul>
                        {foreach item=subpage from=$pages[page]->subpages}
                            {strip}
                                <li><a href="{$subpage->uri}">{$subpage->post_title}</a></li>
                            {/strip}
                        {/foreach}
                        </ul>
                    {/if}
                    </li>
                {/strip}
            {/section}
        </ul>
        <div id="sub-link-bar"></div>
	</div>

   <p id="rss">
      <a href="/rss">Grab the RSS feed</a>
   </p>

   <form id="quick-search" method="get" action="/search">
      <fieldset class="search">
         <label for="qsearch">Search:</label>
         <input class="tbox" id="qsearch" type="text" name="q" value="Search..." title="Start typing and hit ENTER" />
         <button class="btn" title="Submit Search">Search</button>
      </fieldset>
   </form>

    <div id="login-box">
        {if $user == null}
            {$fb_login_button}
        {else}
            Welcome back <b>{$user->displayname}</b>!<br />
        {/if}
    </div>
<!--/header-->
</div></div>