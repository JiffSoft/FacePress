<?php
/** 
 * @package FacePress
 * @version $Id$
 * @copyright (c) 2010 JiffSoft
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License v3
 */


define('BASEDIR',dirname(__FILE__));

// check for a valid installation
if (!file_exists(BASEDIR.'/config.inc.php')) {
    // include the installer
    include_once(BASEDIR.'/fpb-admin/install.php');
} else {
    // otherwise include the config and the bootstrapper
    require(BASEDIR.'/config.inc.php');
    include_once(BASEDIR.'/fpb-includes/load.php');
}

?>