<?php

namespace Report\Template;

class Section {
    private $name = 'No named section';
    private $level = 0;

    private $sections = array();
    private $currentSection = null;

    private $content = array();
    
    public function addSection($name, $level = 1) {
        $this->currentSection = new Section($name);
        $this->currentSection->setLevel($level);
        $this->sections[] = $this->currentSection;
        
        return $this->currentSection;
    }
    
    public function addContent($type, $data = null) {
        $type = 'Report\\Template\\'.$type;
        $content = new $type();
        $content->setContent($data);
        $this->content[] = $content;
        
        return $content;
    }
    
    public function setContent($name) {
        $this->name = $name;
    }

    public function render($output) {
        $renderer = $output->getRenderer('Section');
        
        $renderer->render($output, $this);
        
        foreach($this->content as $content) {
            $content->render($output);
        }
    }

    public function setLevel($level = 0) {
        $this->level = $level;
    }

    public function getLevel() {
        return $this->level;
    }

    public function getCurrent() {
        return $this->currentSection;
    }

    public function getName() {
        return $this->name;
    }

    public function getId() {
        return str_replace(array(' ', '('  , ')'  ), 
                           array('-', '', ''),
                           $this->name);
    }
    
    public function getSections() {
        return $this->sections;
    }

    public function getContent() {
        return $this->content;
    }

    public function toText() {
        if ($this->level == 0) { 
            return ''; // case of the root section
        }
        $report = str_repeat('#', $this->level).$this->getName()."\n";
        
        if (!is_null($this->content)) {
            foreach($this->content as $content) {
                $report .= $content->toText();
                $report .= "\n";
            }
        }
        
        if (count($this->sections) > 0) {
            foreach($this->sections as $section) {
                $report .= $section->toText();
            }
        }
        
        return $report;
    }

    public function toMarkDown() {
        if ($this->level == 0) { 
            return ''; // case of the root section
        }
        $report = str_repeat('#', $this->level)." <a name=\"".$this->getId()."\"></a>".$this->getName()."\n";
        
        if (!is_null($this->content)) {
            foreach($this->content as $content) {
                $report .= $content->toMarkDown();
                $report .= "\n";
            }
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