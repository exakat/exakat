<?php

class Description {
    private $language = 'en';
    private $ini = array('human' => '',
                         'name' => '',
                         'clearphp' => '');
    
    public function __construct($analyzer) {
        $filename = './human/'.$this->language.'/'.str_replace('\\', '/', str_replace("Analyzer\\", "", $analyzer)).'.ini';
        
        if (!file_exists($filename)) {
            $this->ini = array();
        } else {
            $this->ini = parse_ini_file($filename) + $this->ini;
        }
    }

    private function setLanguage($language) {
        $this->language = $language;
    }

    public function getDescription() {
        return $this->ini['description'];
    }

    public function getName() {
        return $this->ini['name'];
    }    

    public function getClearPHP() {
        return $this->ini['clearphp'];
    }    
}

?>
