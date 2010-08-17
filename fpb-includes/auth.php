<?php
/**
 * @package FacePress
 * @version $Id$
 * @copyright (c) 2010 JiffSoft
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License v3
 */

/**
 * Main authentication class for FacePress - handles logins, logout, and session management
 */

require(dirname(__FILE__) . '/facebook.php');

class FPBAuth {

    /**
     * @var FPBAuth Instance of the FPBAuth class
     * @access private
     */
    private static $_instance;

    /**
     * @var Facebook The Facebook API class
     * @access private
     */
    var $_facebook;

    var $_fb_api;

    var $_user;


    /**
     * Returns the current instance of the FPBAuth object
     * @static
     * @return FPBAuth
     */
    public static function GetInstance() {
        if (!FPBAuth::$_instance)
            FPBAuth::$_instance = new FPBAuth();
        return FPBAuth::$_instance;
    }

    /**
     * Constructor for the FPBAuth class - private for singleton
     *
     * Assumes the FACEBOOK_APP_ID and FACEBOOK_SECRET definitions are already loaded
     * @return void
     * @see FACEBOOK_APP_ID
     * @see FACEBOOK_SECRET
     */
    private function __construct() {
        $this->_facebook = new Facebook(array());

        $this->_facebook->setAppId(FACEBOOK_APP_ID);
        $this->_facebook->setApiSecret(FACEBOOK_SECRET);
        $this->_facebook->setCookieSupport(true);

        Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYPEER] = false;
        Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYHOST] = 2;
        Facebook::$CURL_OPTS[CURLOPT_CONNECTTIMEOUT] = 20;
        Facebook::$CURL_OPTS[CURLOPT_TIMEOUT] = 20;
    }

    /**
     * Checks the current Facebook session for validity and logs the user in (or creates the user in our local
     * database if necessary)
     * @return void
     * @access private
     */
    public function CheckFBStatus() {
        /**
         * The 'pre_fb_auth_check' is run prior to validating the current Facebook session
         * @see Hooks
         */
        Plugins::RunHook('pre_fb_auth_check');
        if ($this->_facebook->getSession()):
            $fbid = $this->_facebook->getUser();
            // We have a Facebook session - check it
            $user_record = FPBDatabase::Instance()->GetUserFromFBId($fbid);
            if (!$user_record) {
                // we must make the user!
                $user_data = array();
                $this->_fb_api = $this->_facebook->api('/me');

                $user = $this->_facebook->getUser();

                $user_data['id'] = $user;
                /**
                 * the password table is kind of superfluous, but is here in case plugins want to override
                 * the column's use - perhaps an OpenID/Google Federated Login? Until then this is
                 * @deprecated
                 */
                $user_data['password'] = md5($user);

                $url = "https://api.facebook.com/method/fql.query";
                $url .= "?access_token=" . $this->_facebook->getAccessToken();
                $url .= "&query=SELECT email, name FROM user WHERE uid={$user}";
                $userData = simplexml_load_file($url);
                $user_data['username'] = (string)$userData->user->email;
                $user_data['displayname'] = (string)$userData->user->name;

                if (strlen($user_data['username']) == 0) {
                    $params = array();
                    $params["canvas"] = "1";
                    $params["fbconnect"] = "0";
                    $params["next"] = 'http://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                    $params["req_perms"] = "email,user_website,user_about_me";
                    $loginUrl = $this->_facebook->getLoginUrl($params);
                    header("Location: $loginUrl");
                    echo '<meta http-equiv="refresh" content="0;url='.$loginUrl.'"/>';
                    die();
                }
                /**
                 * The 'pre_fb_useradd' hook is run before inserting a new user record into the database
                 * @see Hooks
                 */
                Plugins::RunHook('pre_fb_useradd');
                FPBDatabase::Instance()->AddUser($user_data);
                
                $this->_user = $user_data;
            } else
                $this->_user = $user_record[0];
        else:
            $this->_user = null;
        endif;
        /**
         * The 'post_fb_auth_check' is run after the validation of the curent Facebook session
         * @see Hooks
         */
        Plugins::RunHook('post_fb_auth_check');
    }

    public function Facebook() {
        return $this->_facebook;
    }

    public function GetUser() {
        return $this->_user;
    }

    public function IsLoggedIn() {
        return $this->_user != null;
    }

    //
    // 0
    // 1 member
    // 2 banned
    // 3 author
    // 4 mod
    // 5 admin
    public function IsUserAdmin() {
        return $this->_user->user_status & (2^5);
    }

    public function IsUserBanned() {
        return $this->_user->user_status & (2^2);
    }
}