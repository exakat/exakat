<?php

namespace Report\Template;

class Section extends \Report\Template {
    private $name = '';
    private $level = 0;
    private $current = false;

    private $sections = array();
    private $currentSection = null;

    private $content = array();
    
    public function addSection($name, $level = 1) {
        $this->currentSection = new Section($name);
        $this->currentSection->setLevel($level);
        $this->sections[] = $this->currentSection;
        
        return $this->currentSection;
    }
    
    public function addContent($type, $data = null, $css = null) {
        $type = 'Report\\Template\\'.$type;
        $content = new $type();
        $content->setContent($data);
        $content->setCss($css);
        $this->content[] = $content;
        
        return $content;
    }
    
    public function setContent($name) {
        $this->name = $name;
    }

    public function render($output) {
        $renderer = $output->getRenderer('Section');
        
        $this->current = true;
        $renderer->render($output, $this);
        
        foreach($this->content as $content) {
            $content->render($output);
        }
        $this->current = false;
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
        if (empty($this->name)) {
            return 'index';
        } else {
            return str_replace(array(' ', '('  , ')', ':', '/'  ), 
                               array('-', '', ''),
                               $this->name);
        }
    }
    
    public function getSections() {
        return $this->sections;
    }

    public function getContent() {
        return $this->content;
    }

    public function isCurrent() {
        return $this->current;
    }

    public function setCurrent($current) {
        $this->current = $current;
    }

}

?>