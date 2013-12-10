<?php

namespace Report;

class Section {
    private $name = 'No named section';
    private $level = 0;

    private $sections = array();
    private $currentSection = null;

    private $content = null;
    
    function __construct($name) {
        $this->name = $name;
    }
    
    function addSection($name, $level) {
        $this->currentSection = new Section($name);
        $this->currentSection->setLevel($level);
        $this->sections[] = $this->currentSection;
        
        return $this->currentSection;
    }
    
    function addContent($type, $data = null) {
        $type = 'Report\\'.$type;
        $this->content = new $type();
        $this->content->setContent($data);
    }

    function setLevel($level = 0) {
        $this->level = $level;
    }

    function getCurrent() {
        return $this->currentSection;
    }

    function getName() {
        return $this->name;
    }

    function getId() {
        return str_replace(' ', '-', $this->name);
    }
    
    function getSections() {
        return $this->sections;
    }

    function toText() {
        if ($this->level == 0) { 
            return ''; // case of the root section
        }
        $report = str_repeat('#', $this->level).$this->getName()."\n";
        
        if (!is_null($this->content)) {
            $report .= $this->content->toText();
        }
        
        if (count($this->sections) > 0) {
            foreach($this->sections as $section) {
                $report .= $section->toText();
            }
        }
        
        return $report;
    }

    function toMarkDown() {
        if ($this->level == 0) { 
            return ''; // case of the root section
        }
        $report = str_repeat('#', $this->level)." <a name=\"".$this->getId()."\"></a>".$this->getName()."\n";
        
        if (!is_null($this->content)) {
            $report .= $this->content->toMarkDown();
        }
        
        if (count($this->sections) > 0) {
            foreach($this->sections as $section) {
                $report .= $section->toMarkDown();
            }
        }
        
        return $report;
    }
}

?>