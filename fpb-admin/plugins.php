<?php
/**
 * @package FacePress
 * @version $Id$
 * @copyright (c) 2010 JiffSoft
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License v3
 */


$sub_action = strtolower((strlen($_POST['sub']) > 0) ? $_POST['sub'] : 'list');

if (strlen($_POST['tid']) > 0) {
    // Gather the post ID we are talking about
    $post = FPBDatabase::Instance()->GatherPostById($_POST['tid']);
}

echo '<h3>Plugin Management</h3>';

// Load plugin list
$yaml_files = find_files(BASEDIR.'/fpb-content/plugins','/ya?ml$/i');
$plugins = array();
foreach ($yaml_files as $yaml)
    array_push($plugins, Spyc::YAMLLoad($yaml));

switch ($sub_action) {
    case 'activate':
        break;
    case 'deactivate':
        /**
         * @todo Deactivation should look for if the plugin is core (MissionCritical: true) in the YML, or
         * if it is required (RequiresPlugins:) from another plugin
         */
        break;
    case 'list':
    default:
        // List our plugins
        echo '<pre>';
        print_r($plugins);
        echo '</pre>';
        break;
}