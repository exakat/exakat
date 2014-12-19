<?php

class Log {
    private $name = null;
    private $log = null;
    private $begin = 0;
    
    public function __construct($name = null) {
        $this->name = $name;

        $this->log = fopen('log/'.$this->name.'.log', 'w+');
        $this->log($this->name." created on ".date('r'));

        $this->begin = microtime(true);
    }

    public function __destruct() {
        $this->log("Duration : ".number_format(1000 * (microtime(true) - $this->begin), 2, '.', ''));
        $this->log($this->name." closed on ".date('r'));
        
        if ($this->log !== null) {
            fclose($this->log);
            unset($this->log);
        } else {
            print "Log already destroyed.";
        }
    }
    
    public function log($message) {
        if ($this->log === null) { return true; }
        
        fwrite($this->log, $message."\n");
    }

    public function report($script, $info) {
        $config = \Config::factory();
        
        $mysql = new \PDO($config->mysql_exakat_pdo, $config->mysql_exakat_user, $config->mysql_exakat_pass);
        if (!$mysql) { return false; }
        
        $values = array('project' => $info['project'],
                        'time' => (microtime(true) - $this->begin) * 1000,
                        );
                        
        $query = "DESCRIBE `$script`";
        $res = $mysql->query($query);
        while($row = $res->fetch()) {
            if (isset($info[$row['Field']])) {
                $values[$row['Field']] = $info[$row['Field']];
            }
        }

        $query = "INSERT INTO `$script` (".implode(", ", array_keys($values)).") VALUES ('".implode("', '", array_values($values))."')";
        $mysql->query($query);
        
        return true;
    }
}

?>
