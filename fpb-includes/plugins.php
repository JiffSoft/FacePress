<?php
/**
 * @package FacePress
 * @version $Id$
 * @copyright (c) 2010 JiffSoft
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License v3
 */

 class Plugins {
     /**
     * @var Plugins The static instance of the database - used for singleton pattern
     * @access private
     */
    private static $_instance;

    /**
     * @var array $_hooks An array of associated function hooks
     */
    var $_hooks = array();

    /**
     * @var array All the loaded plugin information
     */
    var $_plugin_data;

    public static function Instance()
    {
        if (!self::$_instance)
            self::$_instance = new Plugins();
        return self::$_instance;
    }

    public static function RegisterHook($_hook_name, $_function, $_run_order = 10)
    {
        $_hook_name = strtolower($_hook_name);
        $this_hook = array('function' => $_function, 'order' => $_run_order);
        if (!array_key_exists($_hook_name, self::Instance()->_hooks))
            self::$_instance->_hooks[$_hook_name] = array();
        array_push(self::$_instance->_hooks[$_hook_name], $this_hook);
    }

    public static function RunHook($_hook_name)
    {
        $_hook_name = strtolower($_hook_name);
        if (!array_key_exists($_hook_name,self::Instance()->_hooks))
            return;
        $hooks = self::Instance()->_hooks[$_hook_name];

        $functions = array();
        $run_weights = array();
        foreach ($hooks as $key=>$row) {
            $functions[$key] = $row['function'];
            $run_weights[$key] = $row['order'];
        }

        array_multisort($run_weights, SORT_ASC, $hooks);
        
        foreach ($hooks as $hook) {
            $hook_fxn = $hook['function'];
            eval("$hook_fxn();");
        }
    }

    public static function InstallPlugin($_plugin_yaml_file)
    {
        $this_plugin = Spyc::YAMLLoad(BASEDIR.'/fpb-content/plugins/'.$_plugin_yaml_file);

        foreach (Plugins::Instance()->_plugin_data as $plugin)
            if (strtoupper($plugin['PluginName']) == strtoupper($this_plugin['PluginName']))
                return;
        array_push(Plugins::Instance()->_plugin_data,$this_plugin);
                
        FPBDatabase::Instance()->SetConfigElement('Plugins',serialize(Plugins::Instance()->_plugin_data));
    }

    public static function UninstallPlugin($_plugin_name)
    {
        
    }

    /**
     * Base constructor
     * @return void
     */
    private function __construct()
    {
    }

    public function Load()
    {
        $config = FPBDatabase::Instance()->GetConfigArray();
        if (!array_key_exists('Plugins',$config))
            return;
        $this->_plugin_data = unserialize($config['Plugins']);
        if (count($this->_plugin_data) == 0)
            return;
        
        foreach ($this->_plugin_data as $index=>$plugin) {
            if (file_exists(BASEDIR.'/fpb-content/plugins/'.$plugin['IncludeFile']))
                include_once(BASEDIR.'/fpb-content/plugins/'.$plugin['IncludeFile']);
            else {
                trigger_error("The plugin ".$plugin['PluginName']." could not be found and was disabled!", E_USER_WARNING);
                self::UninstallPlugin($plugin['PluginName']);
            }
        }
        FPBDatabase::Instance()->SetConfigElement('Plugins',serialize($this->_plugin_data));
    }

    public function PluginData()
    {
        return $this->_plugin_data;
    }
 }