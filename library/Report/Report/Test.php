<?php

namespace Report\Report;

class Test {
    private $client = null;
    private $db = null;

    private $summary = null;
    private $content = null;
    private $current = null;
    private $currentH1 = null;
    private $currentH2 = null;
    private $root    = null;

    public function __construct($client, $db) {
        $this->client  = $client;
        $this->db      = $db;

        $this->content = new \Report\Template\Section('');
        $this->current = $this->content;
        $this->root    = $this->content;
    }
    
    public function setProject($project) {
        $this->project = $project;
    }
    
    public function prepare() {
        // THere goes the report building
        return true;
    }
    
    public function render($format, $filename = null) {
        $class = "\\Report\\Format\\$format";
        $this->output = new $class();
        $this->output->setSummaryData($this->root);
        
        foreach($this->root->getContent() as $c) {
            $c->render($this->output);
        }
        
        if (isset($filename)) {
            return $this->output->toFile($filename.'.'.$this->output->getExtension());
        } else {
            die("No filename?");
        }
    }
    
    private function createH1($name) {
        $section = $this->root->addContent('Section', $name);
        $section->setLevel(1);

        $this->current = $section;
        $this->currentH1 = $section;
    }

    function createH2($name) {
        // @todo check that current is level 1 ? 
        $section = $this->currentH1->addContent('Section', $name);
        $section->setLevel(2);

        $this->current = $section;
        $this->currentH2 = $section;
    }

    function createH3($name) {
        $this->current = $this->content->getCurrent()->getCurrent()->addSection($name, 3);
    }

    function addContent($type, $data = null) {
        return $this->current->addContent($type, $data);
    }
}

?>
