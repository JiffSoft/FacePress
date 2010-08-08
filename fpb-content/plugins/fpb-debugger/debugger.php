<?php
/**
 * @package FacePress
 * @version $Id$
 * @copyright (c) 2010 JiffSoft
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License v3
 */

if (defined('FPB_DEBUGGER_PLUGIN'))
    exit;
define('FPB_DEBUGGER_PLUGIN',true);

define('ENABLE_DEBUGGING',true);

function FPB_Debugger_RenderDebugDiv()
{
    global $smarty;

    $var_dump = print_r($_SESSION,true);
    $var_dump .= print_r($_REQUEST,true);
    $var_dump .= print_r($_COOKIE,true);
    $plugins = print_r(Plugins::Instance()->PluginData(),true);
    $user = print_r(FPBAuth::GetInstance()->GetUser(),true);
    $debug_contents =<<<HTML
    <hr/>
    <pre>
        $var_dump
    </pre>
    <pre>
        $plugins
    </pre>
    <pre>
        $user
    </pre>
HTML;
    $smarty->assign('debug',$debug_contents);
}

/* register all hooks */
Plugins::RegisterHook('pre_render','FPB_Debugger_RenderDebugDiv');