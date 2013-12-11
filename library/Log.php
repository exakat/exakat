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
        
        if (!is_null($this->log)) {
            fclose($this->log);
            unset($this->log);
        } else {
            print "Log already destroyed.";
        }
    }
    
    public function log($message) {
        if (is_null($this->log)) { return true; }
        
        fwrite($this->log, $message."\n");
    }
}

?>