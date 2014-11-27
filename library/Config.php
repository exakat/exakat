<?php

class Config {
    static private $singleton = null;
           private $config_file = array();
           private $commandline = array();
           private $project_config = array();
        
           private $options = array();
     
    private function __construct() {
        $this->config_file = parse_ini_file('./config/config.ini');

        // then read the config from the commandline (if any)
        $this->read_commandline();
        
        // then read the config for the project in its folder
        if (isset($this->commandline['project'])) {
            $this->read_project_config($this->commandline['project']);
        } 
        
        // build the actual config. Project overwrite commandline overwrites config, if any.
        $this->options = array_merge($this->config_file, $this->commandline, $this->project_config);
    }
    
    static function factory() {
        if (Config::$singleton === null) {
            Config::$singleton = new Config();
        }
        
        return Config::$singleton;
    }

    public function __isset($name) {
        return isset($this->options[$name]);
    }
    
    public function __get($name) {
        if (isset($this->options[$name])) {
            $return = $this->options[$name];
        } else if ($name == 'mysql_exakat_pdo') {
            $return = 'mysql:host='.$this->options['mysql_host'].';dbname='.$this->options['mysql_exakat_db'];
        } else {
            $return = null;
        }
        
        return $return;
    }
    
    public function __set($name, $value) {
        print "It is not possible to modify configuration\n";
    }

    private function read_project_config($project) {
        if (!file_exists('./projects/'.$project.'/config.ini')) {
            return null;
        }
        
        $this->project_config = parse_ini_file('./projects/'.$project.'/config.ini');
        
        // check and default values
        $defaults = array( 'ignore_dirs' => array('tests', 'test', 'Tests'));
        
        foreach($defaults as $name => $value) {
            if (!isset($this->project_config[$name])) {
                $this->project_config[$name] = $value;
            }
        }
        
        return null;
    }

    private function read_commandline() {
        global $argv;
        $args = $argv;

        if (empty($args)) {
            return null;
        }
        
        $options_boolean = array('-v'     => array('verbose',   false),
                                 '-h'     => array('help',      false),
                                 '-r'     => array('recursive', false),
                                 '-l'     => array('lint',      false),
                                 '-json'  => array('json',      false),
                                 '-ss'    => array('ss',        false),
                                 '-sm'    => array('sm',        false),
                                 '-sl'    => array('sl',        false),
                                 '-today' => array('today',     false),
                                 '-none'  => array('none',      false),
                                 '-table' => array('table',     false),
                                 '-json'  => array('json',      false),
                                 '-text'  => array('text',      false),
                                 '-o'     => array('output',    false),

                                 );

        foreach($options_boolean as $key => $config) {
            if (($id = array_search($key, $args)) !== false) {
                $this->commandline[$config[0]] = true;

                unset($args[$id]);
            } else {
                $this->commandline[$config[0]] = $config[1];
            }
        }
                                 
        $options_value   = array('-f' => array('filename', null),
                                 '-d' => array('dirname',  null),
                                 '-p' => array('project',  'default'),
                                 '-P' => array('program',  null),
                                 '-T' => array('thema',    null),
//                                 '-q' => array('loader',   'Load\Csv'),
                                 );

        foreach($options_value    as $key => $config) {
            if ( ($id = array_search($key, $args)) !== false) {
                $this->commandline[$config[0]] = $args[$id + 1];

                unset($args[$id]);
                unset($args[$id + 1]);
            } else {
                $this->commandline[$config[0]] = $config[1];
            }
        }
    }
}

?>