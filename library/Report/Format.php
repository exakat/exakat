<?php

namespace Report;

class Format {
    protected $name = 'Content'; 
    
    protected $projectName = '';
    protected $projectUrl = '';

    protected $format = 'DefaultFormat';    
    
    public function __construct() {
        
    }
    
    public function getRenderer($class) {
        $fullclass = "\\Report\\Format\\{$this->format}\\$class";
        
        if (!class_exists($fullclass)) {
            $fullclass = "\\Report\\Format\\{$this->format}\\Missing";
        }
        
        $this->classes[$class] = new $fullclass();
        return $this->classes[$class];
    }

    public function getExtension() {
        return $this->fileExtension;
    }

    public function setProjectName($projectName) {
        $this->projectName = $projectName;
    }

    public function setProjectUrl($projectUrl) {
        $this->projectUrl = $projectUrl;
    }

}

?>
