<?php

namespace Report\Format;

class Css { 
    public $css         = null;
    public $css_default = null;
    
    public function __construct($cssName, $className) {
        // $cssName already holds the format name as a directory : Ace/Css/<Css>.json
        if ($cssName === null) {
            // actually, this is OK
        } elseif (file_exists(__DIR__.'/Ace/Css/'.$cssName.'.json')) {
            $json = file_get_contents(__DIR__.'/Ace/Css/'.$cssName.'.json');
            $this->css = json_decode($json);
            if ($this->css === null) {
                print "JSON error in '$cssName.json' : ".json_last_error()."\n";
            }
        } else {
            print "Couldn't find '$cssName.json' file.\n";
        }

        $this->css_default = json_decode(file_get_contents(__DIR__.'/Ace/Css/default/'.$className.'.json'));
    }
    
    public function __get($name) {
        if (isset($this->css->$name)) {
            return $this->css->$name;
        } else {
            if (!isset($this->css_default->$name)) { print "Warning : No such default as '$name'\n";
            
            print_r($this); }
            return $this->css_default->$name;
        }
    }
    
    public function __isset($name) {
        return isset($this->css->$name) && isset($this->css_default->$name);
    }
}

?>