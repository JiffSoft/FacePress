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

?>
 
