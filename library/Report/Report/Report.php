<?php

namespace Report\Report;

class Report {
    protected $client    = null;
    protected $db        = null;
    protected $project   = null;

    private $content   = null;
    private $current   = null;
    private $currentLevel1 = null;
    private $currentLevel2 = null;

    public function __construct($project, $client, $db) {
        $this->project = $project;
        $this->client  = $client;
        $this->db      = $db;
        
        $this->content = new \Report\Template\Section('');
        $this->current = $this->content;
        $this->root    = $this->content;
    }

    protected function getContent($name) {
        $nsname = "Report\\Content\\$name";
        $content = new $nsname();

        $content->setProject($this->project);
        $content->setNeo4j($this->client);
        $content->setDb($this->db);
        
        return $content;
    }

    protected function addContent($type, $data = null, $css = null) {
        if (is_string($data) && strpos($data, ' ') === false) { // rough check if this may be a class name
            $content = $this->getContent($data);
            $content->collect();
        } else {
            $content = $data;
        }
        
        return $this->current->addContent($type, $content, $css);
    }

    protected function createLevel1($name) {
        $section = $this->root->addContent('Section', $name);
        $section->setLevel(1);

        $this->current = $section;
        $this->currentLevel1 = $section;
    }

    protected function createLevel2($name) {
        // @todo check that current is level 1 ? 
        $section = $this->currentLevel1->addContent('Section', $name);
        $section->setLevel(2);

        $this->current = $section;
        $this->currentLevel2 = $section;
    }

    public function render($format, $filename = null) {
        $format = "\\Report\\Format\\$format";

        $this->output = new $format();
        $this->output->setProjectName($this->project);
        $this->output->setProjectUrl('');
        $this->output->setSummaryData($this->root);
        
        foreach($this->root->getContent() as $c) {
            $c->render($this->output);
        }
        
        if (isset($filename)) {
            return $this->output->toFile($filename.'.'.$this->output->getExtension());
        } else {
            die("No filename? ".__METHOD__);
        }
    }

}

?>
