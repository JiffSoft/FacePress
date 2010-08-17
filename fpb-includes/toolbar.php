<?php
/**
 * @package FacePress
 * @version $Id$
 * @copyright (c) 2010 JiffSoft
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License v3
 */


function fpb_toolbar_head() {
    $toolbar_authorized = ((FPBAuth::GetInstance()->IsLoggedIn()) && (FPBAuth::GetInstance()->IsUserAdmin()));
    /**
     * The 'toolbar_pre_authorize' hook runs before the toolbar code determines if the user is authorized to utilize
     * the toolbar
     * @see Hooks
     */
    Plugins::RunHook('toolbar_pre_authorize');
    if (!$toolbar_authorized)
        return;
    $toolbar_content =<<<HTML
         <link rel="stylesheet" type="text/css" href="/fpb-content/fpb-admin.css"/>
         <script type="text/javascript" language="JavaScript" src="/fpb-content/fpb-admin.js"></script> 
HTML;
    /**
     * The 'toolbar_head_content' hook runs before the toolbar code returns the HTML to be added to the <head> - hooks
     * should 'global $toolbar_content;' to access the content returned
     * @see Hooks
     */
    Plugins::RunHook('toolbar_head_content');
    echo $toolbar_content;
}

function fpb_toolbar_body() {
    $toolbar_authorized = ((FPBAuth::GetInstance()->IsLoggedIn()) && (FPBAuth::GetInstance()->IsUserAdmin()));
    /**
     * The 'toolbar_pre_authorize' hook runs before the toolbar code determines if the user is authorized to utilize
     * the toolbar
     * @see Hooks
     */
    Plugins::RunHook('toolbar_pre_authorize');
    if (!$toolbar_authorized)
        return;
    $toolbar_content = <<<HTML
    <div id="fpb-admin-tb">
        <a href="#" onclick="fpb_admin_Dashboard();">
            <div class="menu-item menu-dashboard">
            </div>
        </a><a href="#" onclick="fpb_admin_Posts();">
            <div class="menu-item menu-post">
            </div>
        </a><a href="#" onclick="alert('yo');">
            <div class="menu-item menu-media">
            </div>
        </a><a href="#" onclick="alert('yo');">
            <div class="menu-item menu-comments">
            </div>
        </a><a href="#" onclick="alert('yo');">
            <div class="menu-item menu-pages">
            </div>
        </a><hr/>
        <a href="#" onclick="alert('yo');">
            <div class="menu-item menu-appearance">
            </div>
        </a><a href="#" onclick="fpb_admin_Plugins();">
            <div class="menu-item menu-plugins">
            </div>
        </a><hr/><a href="#" onclick="alert('yo');">
            <div class="menu-item menu-users">
            </div>
        </a><a href="#" onclick="alert('yo');">
            <div class="menu-item menu-tools">
            </div>
        </a><a href="#" onclick="alert('yo');">
            <div class="menu-item menu-settings">
            </div>
        </a>
    </div>
    <div id="fpb-admin-popup"><div id="fpb-admin-popup-content"></div></div>
    <div id="fpb-admin-blackout"></div>
HTML;
    Plugins::RunHook('toolbar_body_content');
    /**
     * The 'toolbar_body_content' hook runs before the toolbar code returns the HTML to be added to the end of <body> -
     * hooks should 'global $toolbar_content;' to access the content returned
     * @see Hooks
     */
    echo $toolbar_content;
}