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

function str_truncate($string, $limit, $break=".", $pad="...")
{
  // return with no change if string is shorter than $limit
  if(strlen($string) <= $limit) return $string;

  // is $break present between $limit and the end of the string?
  if(false !== ($breakpoint = strpos($string, $break, $limit))) {
    if($breakpoint < strlen($string) - 1) {
      $string = substr($string, 0, $breakpoint) . $pad;
    }
  }

  return $string;
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

function format_post($post) {
    $post = nl2br($post);
    $bbcode_search = array(
        '/\[br\]/is',
        '/\[b\](.*?)\[\/b\]/is',
        '/\[i\](.*?)\[\/i\]/is',
        '/\[u\](.*?)\[\/u\]/is',
        '/\[url\=(.*?)\](.*?)\[\/url\]/is',
        '/\[url\](.*?)\[\/url\]/is',
        '/\[align\=(left|center|right)\](.*?)\[\/align\]/is',
        '/\[img\](.*?)\[\/img\]/is',
        '/\[mail\=(.*?)\](.*?)\[\/mail\]/is',
        '/\[mail\](.*?)\[\/mail\]/is',
        '/\[font\=(.*?)\](.*?)\[\/font\]/is',
        '/\[size\=(.*?)\](.*?)\[\/size\]/is',
        '/\[color\=(.*?)\](.*?)\[\/color\]/is',
        '/\[codearea\](.*?)\[\/codearea\]/is',
        '/\[code\](.*?)\[\/code\]/is',
        '/\[p\](.*?)\[\/p\]/is',
    );
    $bbcode_replace = array(
        '<br />',
        '<strong>$1</strong>',
        '<em>$1</em>',
        '<u>$1</u>',
        '<a href="$1" rel="nofollow" title="$2 - $1">$2</a>',
        '<a href="$1" rel="nofollow" title="$1">$1</a>',
        '<div style="text-align: $1;">$2</div>',
        '<img src="$1" alt="" />',
        '<a href="mailto:$1">$2</a>',
        '<a href="mailto:$1">$1</a>',
        '<span style="font-family: $1;">$2</span>',
        '<span style="font-size: $1;">$2</span>',
        '<span style="color: $1;">$2</span>',
        '<textarea class="code_container" rows="30" cols="70">$1</textarea>',
        '<pre class="code">$1</pre>',
        '<p>$1</p>',
    );
    $post = preg_replace ($bbcode_search, $bbcode_replace, $post);
    preg_match_all ('/\[quote\]/i', $post, $matches);
    $opentags = count($matches['0']);
    preg_match_all ('/\[\/quote\]/i', $post, $matches);  
    $closetags = count($matches['0']);
    if ($opentags > 0) {
        $unclosed = $opentags - $closetags;
        for ($i = 0; $i < $unclosed; $i++) {
            $post .= '</div></blockquote>';
        }
        $post = str_replace ('[' . 'quote]', '<blockquote><div class="quote">', $post);
        $post = str_replace ('[/' . 'quote]', '</div></blockquote>', $post);
    }
    echo $post;
}

?>