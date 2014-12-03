<?php

namespace Report\Format;

class Css { 
    private $css         = null;
    private $css_default = null;
    private $cssName     = null; 
    
    public function __construct($cssName, $className) {
        // $cssName already holds the format name as a directory : Ace/Css/<Css>.json
        if ($cssName === null) {
            $cssName = 'Null';
            // actually, this is OK
        } elseif (file_exists(__DIR__.'/Ace/Css/'.$cssName.'.json')) {
            $cssName = $cssName;
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
            if (!property_exists($this->css_default, $name)) { 
                print "Warning : No such default as '$name' in '$this->cssName'\n";
                print_r($this); 
            }
            return $this->css_default->$name;
        }
    }
    
    public function __isset($name) {
        return isset($this->css->$name) && isset($this->css_default->$name);
    }
}

?>