<?php
/**
 * @package FacePress
 * @version $Id$
 * @copyright (c) 2010 JiffSoft
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License v3
 */

define('BASEDIR',str_replace("/fpb-admin","",dirname(__FILE__)));

// check for a valid installation
if (!file_exists(BASEDIR.'/config.inc.php')) {
    // include the installer
    include_once(BASEDIR.'/fpb-admin/install.php');
    exit;
} else {
    // otherwise include the config
    require(BASEDIR.'/config.inc.php');
}

// load up the required classes
require(BASEDIR.'/fpb-includes/smarty/Smarty.class.php');
require(BASEDIR.'/fpb-includes/auth.php');
require(BASEDIR.'/fpb-includes/database.php');
require(BASEDIR.'/fpb-includes/functions.php');
require(BASEDIR.'/fpb-includes/fbhelper.php');
require(BASEDIR.'/fpb-includes/spyc.php');
require(BASEDIR.'/fpb-includes/plugins.php');

Plugins::Instance()->Load();
FPBAuth::GetInstance()->CheckFBStatus();

// Check for a valid admin session
$admin_authorized = ((FPBAuth::GetInstance()->IsLoggedIn()) && (FPBAuth::GetInstance()->IsUserAdmin()));
/**
 * The 'admin_pre_authorize' hook runs before the admin code determines if the user is authorized to utilize
 * its functions
 * @see Hooks
 */
Plugins::RunHook('admin_pre_authorize');
if (!$admin_authorized) {
    header("HTTP/1.0 403 Access denied",true,403);
    die('HTTP/1.0 403 Access denied');
}

/**
 * The 'admin_bootstrap' hook runs after all files have been included and before any processing occurs in the
 * admin pages
 * @see Hooks
 */
Plugins::RunHook('admin_bootstrap');

if (empty($_POST))
    die('Invalid request');

if (file_exists(BASEDIR.'/fpb-admin/'.$_POST['action'].'.php'))
    include_once(BASEDIR.'/fpb-admin/'.$_POST['action'].'.php');
else
    die('Invalid request');