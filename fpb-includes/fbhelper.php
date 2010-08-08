<?php
/**
 * @package FacePress
 * @version $Id$
 * @copyright (c) 2010 JiffSoft
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License v3
 */


class FacebookHelper {

    public function FBInitDiv() {
        $fbappid = FACEBOOK_APP_ID;
        return <<<HTML
            <div id="fb-root"></div>
            <script src="http://connect.facebook.net/en_US/all.js"></script>
            <script>
              FB.init({
                appId  : '$fbappid',
                status : true, // check login status
                cookie : true, // enable cookies to allow the server to access the session
                xfbml  : true  // parse XFBML
              });
            </script>
HTML;
    }

    public function FBLoginButton() {
        return <<<HTML
            <fb:login-button perms="email,user_website,user_about_me" onlogin="document.location.reload();" v="2">
                <fb:intl>Login with Facebook</fb:intl>
            </fb:login-button>
HTML;

    }
}