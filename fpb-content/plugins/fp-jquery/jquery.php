<?php
/**
 * @package FacePress
 * @version $Id$
 * @copyright (c) 2010 JiffSoft
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License v3
 */

if (defined('FPB_JQUERY'))
    exit;
define('FPB_JQUERY',true);

function FP_jQuery_Head()
{
    // Find the path
    $this_dir = dirname(__FILE__);
    $this_dir = str_replace(BASEDIR,'',$this_dir);
    echo '<script type="text/javascript" src="'.$this_dir.'/javascript/jquery-1.4.2.min.js"></script>';
    echo '<script type="text/javascript" src="'.$this_dir.'/javascript/jquery-ui-1.8.2.custom.min.js"></script>';
}

/* register all hooks */
Plugins::RegisterHook('head_load','FP_jQuery_Head',8);
?>