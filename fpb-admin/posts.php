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

echo '<h3>Posts</h3>';

switch ($sub_action) {
    case 'edit':
        break;
    case 'save':
        break;
    case 'del':
        break;
    case 'pub':
        break;
    case 'list':
    default:
        $page = (int)((strlen($_POST['p']) > 0) ? $_POTS['p'] : 1);
        $posts = FPBDatabase::Instance()->GatherPosts(20,$page);
        break;
}