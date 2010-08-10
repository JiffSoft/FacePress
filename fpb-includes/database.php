<?php
/**
 * @package FacePress
 * @version $Id$
 * @copyright (c) 2010 JiffSoft
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License v3
 */

/**
 * Main database class used for all database operations [Singleton]
 */
class FPBDatabase
{
    /**
     * @var FPBDatabase The static instance of the database - used for singleton pattern
     * @access private
     */
    private static $_instance;

    /**
     * @var resource The current SQL link identifier
     * @access private
     */
    var $_sql_link_id;

    /**
     * @var array The cached config array from the database
     * @access private
     */
    var $_config;

    /**
     * Returns the current instance of the FPBDatabase object
     * @static
     * @return FPBDatabase
     */
    public static function Instance()
    {
        if (!self::$_instance)
            self::$_instance = new FPBDatabase();
        return self::$_instance;
    }

    /**
     * Constructor for FPBDatabase object - sets up database connections
     *
     * Assumes database credentials have been defined
     *
     * Private for singleton pattern
     * @return void
     * @access private
     * @see FPB_DB_HOST
     * @see FPB_DB_USER
     * @see FPB_DB_PASS
     * @see FPB_DB_NAME
     * @see FPB_DB_PREFIX
     */
    private function __construct()
    {
        if (!$this->_sql_link_id = mysql_connect(FPB_DB_HOST, FPB_DB_USER, FPB_DB_PASS))
            trigger_error('Could not connect to MySQL database: '.mysql_error());
        if (!mysql_select_db(FPB_DB_NAME))
            trigger_error('Coult not select MySQL database: '.myql_error());
    }

    /**
     * Deconstructor - simply closes the SQL link
     * @return void
     */
    function __destruct()
    {
        mysql_close($this->_sql_link_id);
    }

    /**
     * Sanitizes a query and binds variables
     * @param string $_query The query to sanitize with inputs bound to question marks
     * @param array $_params The parameters to bind to the query
     * @return string
     * @uses _Q_escape($_value, $_position) Helper function for sanitation
     * @access private
     */
    private function Q($_query, $_params = null)
    {
            if ($_params)
                $argc = count($_params);
            else
                $argc = 0;
            $n = 0;                 // first vararg $argv[1]
            
            $out = '';
            $quote = FALSE;         // quoted string state
            $slash = FALSE;         // backslash state

            // b - pointer to start of uncopied text
            // e - pointer to current input character
            // end - end of string pointer
            $end = strlen($_query);
            for ($b = $e = 0; $e < $end; ++$e)
            {
                    $ch = $_query{$e};

                    if ($quote !== FALSE)
                    {
                            if ($slash)
                            {
                                    $slash = FALSE;
                            }
                            elseif ($ch === '\\')
                            {
                                    $slash = TRUE;
                            }
                            elseif ($ch === $quote)
                            {
                                    $quote = FALSE;
                            }
                    }
                    elseif ($ch === "'" || $ch === '"')
                    {
                            $quote = $ch;
                    }
                    elseif ($ch === '?')
                    {
                            $out .= substr($_query, $b, $e - $b) .
                                    $this->_Q_escape($_params[$n], $n);
                            $b = $e + 1;
                            $n++;
                    }
            }
            $out .= substr($_query, $b, $e - $b);

            // warn on arg count mismatch
            if ($argc != $n)
            {
                    $adj = ($argc > $n) ? 'many' : 'few';
                    trigger_error('Too ' . $adj . ' arguments ' .
                                    '(expected ' . $n . '; got ' . $argc . ')',
                            E_USER_WARNING);
            }

            return $out;
    }

    /**
     * Helper function for "Q($_query)" - do not use directly
     * @param  $_value
     * @param bool $_position
     * @return string
     * @see Q($_query)
     * @access private
     */
    private function _Q_escape($_value, $_position = FALSE)
    {
            static $r_position;
            // Save $_position to simplify recursive calls.
            if ($_position !== FALSE)
            {
                    $r_position = $_position;
            }

            if (is_null($_value))
            {
                    // The NULL value
                    return 'NULL';
            }
            elseif (is_int($_value) || is_float($_value))
            {
                    // All integer and float representations should be
                    // safe for mysql (including 5e-12 notation)
                    $result = "$_value";
            }
            elseif (is_array($_value))
            {
                    // Arrays are written as a comma-separated list of
                    // values.  Useful for IN, find_in_set(), etc.

                    // KM, AS: PHP stoneage is crashing here, when the
                    // _values array is missing a 0 index.. hence the array_values()
                    $result = implode(', ', array_map('_Q_escape', array_values($_value)));
            }
            else
            {
                    // Warn if given an unexpected value type
                    if (!is_string($_value))
                    {
                            trigger_error('Unexpected value of type "' .
                                    gettype($_value) . '" in arg '.$r_position,
                                    E_USER_WARNING);
                    }

                    // Everything else gets escaped as a string
                    $result = "'" . addslashes($_value) . "'";
            }

            return $result;
    }

    /**
     * @param string $query_string The query to execute with bind parameter positions specified as ?
     * @param array $parameters An array of parameters to bind to the query
     * @param bool $return_as_object Return the result as an object rather than an associative array
     * @return void
     * @uses Q($_query)
     */
    public function DirectQuery($query_string, $parameters = null, $return_as_object = true)
    {
        $clean_query = $this->Q($query_string, $parameters);

        if (!$recordset = mysql_query($clean_query,$this->_sql_link_id))
            $this->LogSQLError(mysql_error(),$clean_query);

        if (preg_match("/^SELECT/i",$clean_query) == 0)
            return;

        if (mysql_num_rows($recordset) == 0)
            return null;

        $ret = array();
        if ($return_as_object)
            while ($data = mysql_fetch_object($recordset))
                array_push($ret, $data);
        else
            while ($data = mysql_fetch_assoc($recordset))
                array_push($ret, $data);

        return $ret;
    }

    /**
     * Handles a SQL error or exception
     * @param string $_error The error or exception text
     * @param string $_query The query that caused the error 
     * @return void
     * @access private
     */
    private function LogSQLError($_error, $_query)
    {
        trigger_error("Database error: $_error",E_USER_ERROR);
    }

    /**
     * Gets the current SQL link identifier
     * @return resource The SQL link identifier
     */
    public function Link()
    {
        return $this->_sql_link_id;
    }

    /**
     * Returns the correct table name
     * @param string $table Desired table
     * @return string
     */
    public function TableName($table)
    {
        return FPB_DB_PREFIX.$table;
    }

    /**
     * Retrieves the current configuration from the database
     * @return array The current configuration in an associative array
     */
    public function GetConfigArray()
    {
        // Cache this object simply to improve performance
        if (!$this->_config) {
            $config_lines = $this->DirectQuery("SELECT * FROM ".$this->TableName('config'),null,false);
            foreach ($config_lines as $line) {
                $this->_config[$line['key']] = $line['value'];
            }
        }
        return $this->_config;
    }

    public function SetConfigElement($_name, $_data)
    {
        // for consistency's sake - we do this
        $_name = substr(strtoupper($_name),0,1).substr(strtolower($_name),1);
        $this->DirectQuery("INSERT INTO ".$this->TableName('config')." (`key`,`value`) VALUES (?,?) ON DUPLICATE KEY UPDATE value = ?",
            array($_name,$_data,$_data));
    }

    public function GatherPostFromURIData($_data)
    {
        $posts = $this->DirectQuery("SELECT * FROM ".$this->TableName('posts')." WHERE post_type='post' AND post_status='publish'
            AND post_name=?",array(0=>$_data['title']));
        if (count($posts) == 1)
            return $posts[0];
        trigger_error("Unable to find the requested post!",E_USER_ERROR);
    }

    public function GatherPosts($limit, $page = 1)
    {
        $page--;  // decrement the page since our logic starts at 0 not 1
        $min = ($page * $limit);
        $max = ($page * $limit) + $limit;
        return $this->DirectQuery("SELECT * FROM ".$this->TableName('posts')." WHERE post_type='post' AND post_status='publish'
            ORDER BY post_date DESC LIMIT ?, ?",array($min,$max));
    }

    public function GatherPostById($_id)
    {
        return $this->DirectQuery("SELECT * FROM ".$this->TableName('posts')." WHERE post_type='post' AND post_status='publish'
            AND ID=?",array(0=>$_id));
    }

    public function GetUserFromFBId($_fbid)
    {
        return $this->DirectQuery("SELECT * FROM ".$this->TableName('users')." WHERE ID=?",array(0=>$_fbid));
    }

    public function AddUser($_user_data)
    {
        
    }

    public function GetPageBySlug($_slug)
    {
        $slug_parts = explode('/',$_slug);
        $main_slug = $slug_parts[count($slug_parts) - 1];
        $page = $this->DirectQuery("SELECT * FROM ".$this->TableName('posts')." WHERE post_status='publish'
            AND post_type='page' AND post_name=?",array(0=>$main_slug));
        if (count($page) == 0)
            return null;
        elseif (count($page) == 1)
            return $page[0];
        foreach ($page as $p) {
            $parent = $this->DirectQuery("SELECT * FROM ".$this->TableName('posts')." WHERE post_status='publish'
                AND post_type='page' AND ID=?",array(0=>$p['post_parent']));
            if (($parent) && ($parent['post_name'] == $slug_parts[count($slug_parts) - 2]))
                return $p;
        }
    }
}