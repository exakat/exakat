<?php

namespace Report;

class Format {
    protected $name = 'Content'; 
    
    public function getRenderer($class) {
        $fullclass = "\\Report\\Format\\{$this->format}\\$class";
        
        if (!class_exists($fullclass)) {
            $fullclass = "\\Report\\Format\\{$this->format}\\Default";
        }
        
        $this->classes[$class] = new $fullclass();
        return $this->classes[$class];
    }

    public function getExtension() {
        return $this->fileExtension;
    }

}

?>