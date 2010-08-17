<?php
/**
 * @package FacePress
 * @version $Id$
 * @copyright (c) 2010 JiffSoft
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License v3
 */


/**
 * Returns whether or not the request was made via AJAX
 * @return bool
 * @todo Make this a little more reliable...
 */
function isAjax() {
 return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
     $_SERVER ['HTTP_X_REQUESTED_WITH']  == 'XMLHttpRequest';
}

/**
 * Generates a random password of a requested length
 * @param int $len Desired length of the password (default = 8)
 * @return string
 */
function randomPassword($len = 8) {
    $chars = "abcdefghijkmnopqrstuvwxyz023456789";
    srand((double)microtime()*1000000);
    $i = 0;
    $len--;
    $pass = '' ;
    while ($i <= $len) {
        $num = rand() % 33;
        $tmp = substr($chars, $num, 1);
        $pass = $pass . $tmp;
        $i++;
    }
    return $pass;
}

function find_files($path, $pattern) {
    $path = rtrim(str_replace("\\", "/", $path), '/') . '/';
    $entries = Array();
    $matches = Array();
    $dir = dir($path);
    while (false !== ($entry = $dir->read())) {
        $entries[] = $entry;
    }
    $dir->close();
    foreach ($entries as $entry) {
        $fullname = $path . $entry;
        if ($entry != '.' && $entry != '..' && is_dir($fullname)) {
            $merge = array_merge($matches,find_files($fullname, $pattern));
            foreach ($merge as $m)
                array_push($matches,$m);
        } else if (is_file($fullname) && preg_match($pattern, $fullname)) {
            array_push($matches,$fullname);
        }
    }
    return array_unique($matches);
}

?>