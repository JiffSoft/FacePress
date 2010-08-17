<?php
/**
 * @package FacePress
 * @version $Id$
 * @copyright (c) 2010 JiffSoft
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License v3
 */

if (defined('FPB_LIGHTBOX_PLUGIN'))
    exit;
define('FPB_LIGHTBOX_PLUGIN',true);

function FPB_Lightbox_AddScripts()
{
    // Find the path
    $this_dir = dirname(__FILE__);
    $this_dir = str_replace(BASEDIR,'',$this_dir);
    echo '<script type="text/javascript" src="'.$this_dir.'/javascript/lightbox.js"></script>';
    echo '<link rel="stylesheet" type="text/css" href="'.$this_dir.'/css/lightbox.css"/>';
}

function FPB_Lightbox_Magic()
{
    $dir = dirname(__FILE__);
    $dir = str_replace(BASEDIR,'',$dir);

    echo <<<HTML
    <script type="text/javascript" language="JavaScript">
        /* FacePress LightBox Automagic Creation */
        $('a > img').parent('a').lightBox({
            imageLoading: '$dir/images/lightbox-ico-loading.gif',
            imageBtnClose: '$dir/images/lightbox-btn-close.gif'
        });
    </script>
HTML;

}

/* register all hooks */
Plugins::RegisterHook('head_load','FPB_Lightbox_AddScripts',15);
Plugins::RegisterHook('body_post_action','FPB_Lightbox_Magic');
?>