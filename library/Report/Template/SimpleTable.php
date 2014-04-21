<?php

namespace Report\Template;

class SimpleTable extends \Report\Template {
    public function render($output) {
        $data = $this->data->toArray();
        
        $renderer = $output->getRenderer('SimpleTable');
        $renderer->setTitles($this->data->getColumnTitles());
        $renderer->render($output, $data);
    }
    
    public function setSort($sort) {
        if (in_array($sort, range(1, 5))) {
            $this->sort = $sort; 
        }
    }

    public function setSummary($summary) {
        $this->summary = (bool) $summary;
    }

    public function setHeadersNames($name) {
        $this->headersName = $name; 
    }
}

?>