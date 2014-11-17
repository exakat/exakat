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
            $this->project_config = parse_ini_file('./projects/'.$this->commandline['project'].'/config.ini');
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

    private function read_commandline() {
        global $argv;
        $args = $argv;

        if (empty($args)) {
            return null;
        }
        
        $options_boolean = array('-v'     => 'verbose',
                                 '-h'     => 'help',
                                 '-r'     => 'recursive',
                                 '-l'     => 'lint',
                                 '-json'  => 'json',
                                 '-ss'    => 'ss',
                                 '-sm'    => 'sm',
                                 '-sl'    => 'sl',
                                 '-today' => 'today',
                                 '-none'  => 'none',
                                 );

        foreach($options_boolean as $key => $config) {
            if (($id = array_search($key, $args)) !== false) {
                $this->commandline[$config] = true;

                unset($args[$id]);
            }
        }
//'-q' => 'loader',
                                 
        $options_value   = array('-f' => 'filename',
                                 '-d' => 'dirname',
                                 '-p' => 'project',
//                                 '-q' => 'loader'
                                 );

        foreach($options_value    as $key => $config) {
            if ( ($id = array_search($key, $args)) !== false) {
                $this->commandline[$config] = $args[$id + 1];

                unset($args[$id]);
                unset($args[$id + 1]);
            }
        }
    }
}

?>