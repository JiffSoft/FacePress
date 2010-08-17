<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">

<head>
    <title>{$site_name} | {$page_title|default:"I got no title!"}</title>
    <link rel="stylesheet" href="{$theme_path}/css/screen.css" type="text/css" media="screen print projection"/>
    {$head}
</head>

<body>

<!--header -->
<div id="header-wrap"><div id="header">

	<a name="top"></a>

	<h1 id="logo-text"><a href="/" title="">{$site_name}</a></h1>
	<p id="slogan">{$site_slogan}</p>

	<div  id="nav">
		<ul>
		    {section name=navigation loop=$nav_links}
		        {strip}
		            <li><a href="{$nav_links[navigation].href}">{$nav_links[navigation].text}</a></li>
		        {/strip}
		    {/section}
		    <li><a href="/">Home</a></li>
		    <li><a href="/archives">Archives</a></li>
		    <li><a href="/pages/projects">Projects</a></li>
            <li><a href="/pages/gaming">Gaming</a></li>
            <li><a href="/pages/brewing">Homebrewing</a></li>
            <li><a href="http://www.jiffsoft.com/services">Services</a></li>
            <li><a href="/forum">Forum</a></li>
            <li><a href="/pages/about">About</a></li>
            <li><a href="/pages/contact">Contact</a></li>
		</ul>
	</div>

   <p id="rss">
      <a href="/blog/index.rss">Grab the RSS feed</a>
   </p>

   <form id="quick-search" method="get" action="/search">
         <label for="qsearch">Search:</label>
         <input id="searchbox" class="tbox" id="qsearch" type="text" name="q" value="Search..." title="Start typing and hit ENTER" />
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