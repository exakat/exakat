<?php

class Config {
    static private $singleton = null;
           private $options = array();
    
    private function __construct() {
        $this->options = parse_ini_file('./config/config.ini');
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
            return 'mysql:host='.$this->options['mysql_host'].';dbname='.$this->options['mysql_exakat_db'];
        } else {
            print "No such configuration as $name\n";
            $return = null;
        }
        
        return $return;
    }
    
    public function __set($name, $value) {
        print "It is not possible to modify configuration\n";
    }
}

?>