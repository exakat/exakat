<?php

namespace Report\Template;

class Horizontal extends \Report\Template {
    private $hash = array('Empty' => 'hash');
    private $summary = false;

    private $headerName = 'Item';
    private $headerCount = 'Count';
    
    const SORT_NONE = 1;
    const SORT_COUNT = 2;
    const SORT_REV_COUNT = 3;
    const SORT_KEY = 4;
    const SORT_REV_KEY = 4;
    
    public function render($output) {
        $renderer = $output->getRenderer('Horizontal');

        $renderer->setAnalyzer($this->data->getName());

        $renderer->render($output, $this->data->toFullArray());
    }

    public function setSort($sort) {
        if (in_array($sort, range(1, 5))) {
            $this->sort = $sort; 
        }
    }

    public function setSummary($summary) {
        $this->summary = (bool) $summary;
    }

    public function setHeaderName($name) {
        $this->headerName = $name; 
    }

    public function setHeaderCount($name) {
        $this->headerCount = $name; 
    }
}

?>