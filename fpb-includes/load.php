<?php
/**
 * @package FacePress
 * @version $Id$
 * @copyright (c) 2010 JiffSoft
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License v3
 */

// load up the required classes
require(BASEDIR.'/fpb-includes/smarty/Smarty.class.php');
require(BASEDIR.'/fpb-includes/auth.php');
require(BASEDIR.'/fpb-includes/database.php');
require(BASEDIR.'/fpb-includes/functions.php');
require(BASEDIR.'/fpb-includes/fbhelper.php');
require(BASEDIR.'/fpb-includes/spyc.php');
require(BASEDIR.'/fpb-includes/plugins.php');

Plugins::Instance()->Load();
$config = FPBDatabase::Instance()->GetConfigArray();

/**
 * The 'core_loaded' hook is executed after we load up the classes and templates,
 * prior to any other code being executed
 * @see Hooks
 */
Plugins::RunHook('core_loaded');

$fb_helper = new FacebookHelper();

FPBAuth::GetInstance()->CheckFBStatus();
/**
 * The 'auth_completed' hook is executed after the authentication module has run
 * @see Hooks
 */
Plugins::RunHook('auth_completed');

$site_theme = (strlen($config["Theme"]) > 0) ? $config["Theme"] : 'coolblue';
$smarty = new Smarty();
$smarty->template_dir = BASEDIR.'/themes/'.$site_theme;
$smarty->compile_dir = BASEDIR.'/cache/';
$smarty->assign('theme_path','/themes/'.$site_theme);

if (FPBAuth::GetInstance()->IsLoggedIn())
    $smarty->assign_by_ref('user',FPBAuth::GetInstance()->GetUser());

    require(BASEDIR.'/fpb-includes/toolbar.php');
ob_start();

/**
 * The 'head_load' hook is executed when we build up the content in <head>
 * @see Hooks
 */
Plugins::RunHook('head_load');
fpb_toolbar_head();
$head_contents = ob_get_contents();
ob_clean();
$smarty->assign('head',$head_contents);
$smarty->assign('title',$page_title);
$smarty->assign('site_slogan',$config["GlobalSlogan"]);
$smarty->assign('site_name',$config["GlobalName"]);
$smarty->assign('fb_login_button',$fb_helper->FBLoginButton());
$smarty->assign('fb_root',$fb_helper->FBInitDiv());
// And now we can figure out what we're doing!
/**
 * The 'pre_action' hook runs just before we figure out what action we will be taking
 * @see Hooks
 */
Plugins::RunHook('pre_action');

$full_action_requested = $_SERVER['REQUEST_URI'];
ob_start();
/**
 * The 'body_pre_action' hook runs in an output buffer before the body action is performed
 * @see Hooks
 */
Plugins::RunHook('body_pre_action');
if ((preg_match("|/(?<year>[0-9]{4})/(?<month>[0-9]{2})/(?<day>[0-9]{2})/(?<title>(.*))/comments/?$|",$full_action_requested,$action_parts) != 0)
    && (count($_POST) > 0)) {
    // post of a comment
    if ((!FPBAuth::GetInstance()->IsLoggedIn()) || (FPBAuth::GetInstance()->IsUserBanned())) {
        $action = '403';
        header('HTTP/1.0 403 Not authorized',true,403);
        $smarty->assign('page_title','Not authorized');
    } else {
        // and afterwards we go back to the post
        $action = 'post';
        $post = FPBDatabase::Instance()->GatherPostFromURIData($action_parts);
        /**
         * The 'pre_post_assign' hook runs just before assigning the post to the Smarty engine
         * @see Hooks
         */
        Plugins::RunHook('pre_post_assign');
        $smarty->assign('post',$post);
        $smarty->assign('page_title',$post->post_title);
    }
} elseif (preg_match("|/(?<year>[0-9]{4})/(?<month>[0-9]{2})/(?<day>[0-9]{2})/(?<title>(.*))/?$|",$full_action_requested,$action_parts) != 0) {
    // Find a post
    $action = 'post';
    $post = FPBDatabase::Instance()->GatherPostFromURIData($action_parts);
    if ($post == null) {
        // 404!
        $action = '404';
        header('HTTP/1.0 404 Not found',true,404);
        $smarty->assign('page_title','Page not found');
    } else {
        /**
         * The 'pre_post_assign' hook runs just before assigning the post to the Smarty engine
         * @see Hooks
         */
        Plugins::RunHook('pre_post_assign');
        $smarty->assign('post',$post);
        $smarty->assign('page_title',$post->post_title);
    }
} elseif (preg_match("|/archives/(?<year>[0-9]{4})/(?<month>[0-9]{2})/?$|",$full_action_requested,$action_parts) != 0) {
    // Archives!
    $action = 'archives';
    $posts = FPBDatabase::Instance()->GatherPostsFromArchive($action_parts);
    /**
     * The 'pre_posts_archive_assign' hook runs just before assigning the posts list to the
     * Smarty engine when browsing archives
     * @see Hooks
     */
    Plugins::RunHook('pre_posts_archive_assign');
    $smarty->assign('posts',$posts);
    $smarty->assign('page_title','Archives for '.$action_parts['month'].'/'.$action_parts['year']);
} elseif (preg_match("|/search/?$|",$full_action_requested,$action_parts) != 0) {
    // Search!
    $action = 'search';
    $results = null;
    /**
     * The 'pre_search' hook runs before running a search, if a plugin is handling the search, populate
     * the $results array to override the built-in search
     * @see Hooks
     */
    Plugins::RunHook('pre_search');
    if ($results == null) {
        $results = FPBDatabase::Instance()->Search($_GET['q']);
        /**
         * The 'post_search' hook runs after a built-in search is completed
         * @see Hooks
         */
        Plugins::RunHook('post_search');
    }
    if ($results == null) {
        // 404!
        $action = '404';
        header('HTTP/1.0 404 Not found',true,404);
        $smarty->assign('page_title','Page not found');
    } else {
        $smarty->assign('results',$results);
        $smarty->assign('page_title','Search the site');
    }
} elseif (preg_match("|/(?<page>(.+))/?$|",$full_action_requested,$action_parts) != 0) {
    // Display a page
    $action = 'page';
    $page = FPBDatabase::Instance()->GetPageBySlug($action_parts['page']);
    if ($page == null) {
        // 404!
        $action = '404';
        header('HTTP/1.0 404 Not found',true,404);
        $smarty->assign('page_title','Page not found');
    } else {
        /**
         * The 'pre_page_assign' hook runs just before assigning the page to the Smarty engine
         * @see Hooks
         */
        Plugins::RunHook('pre_page_assign');
        $smarty->assign('page',$page);
        $smarty->assign('page_title',$page->post_title);
    }
} else {
    // the default action
    $action = 'home'; // default to home
    $posts = FPBDatabase::Instance()->GatherPosts(20);
    /**
     * The 'pre_posts_home_assign' hook runs just before assigning the posts for the homepage
     * to the Smarty engine
     * @see Hooks
     */
    Plugins::RunHook('pre_posts_home_assign');
    $smarty->assign('posts',$posts);
    $smarty->assign('page_title',$config["GlobalSlogan"]);
}
fpb_toolbar_body();
/**
 * The 'body_post_action' hook runs in an output buffer just after the body action is performed
 * @see Hooks
 */
Plugins::RunHook('body_post_action');

$body_contents = ob_get_contents();
ob_clean();
$tpl_file = $action;
/**
 * The 'post_action' hook runs just after we have run the requested action, just before
 * it is finished and then rendered
 * @see Hooks
 */
Plugins::RunHook('post_action');
$smarty->assign('body_contents',$body_contents);

// Grab all archives for the $archives variable to be populated
$archives = FPBDatabase::Instance()->GetArchiveList();
$smarty->assign('archives',$archives);

/**
 * The 'pre_render' hook runs just before we render the smarty template to the client
 * @see Hooks
 */
Plugins::RunHook('pre_render');

// make it happen!
$smarty->display($tpl_file.'.tpl');
/**
 * The 'page_ended' hook is executed at the very end of execution, this is a good
 * place to put plugin cleanup
 * @see Hooks
 */
Plugins::RunHook('page_ended');
?>