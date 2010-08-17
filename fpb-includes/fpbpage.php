<?php
/**
 * @package FacePress
 * @version $Id$
 * @copyright (c) 2010 JiffSoft
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License v3
 */

interface FPBPage {
    

    public function userHasAccess($user);
    public function render($parameters);
}
