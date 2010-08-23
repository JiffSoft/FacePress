/**
 * @package FacePress
 * @version $Id$
 * @copyright (c) 2010 JiffSoft
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License v3
 */


beginReplyTo = function(_id,_user) {
    $('#editor').focus();
    $('[name=reply_ID]').val(_id);
    $('#comment_label').html("Reply to "+_user+"'s Comment");
};