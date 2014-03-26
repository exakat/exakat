<?php

namespace Report\Template;

class Definitions {

    public function render($output) {
        $renderer = $output->getRenderer('Definitions');
        
        $renderer->setAnalyzer($this->data->getName());
        $renderer->render($output, $this->data->getDefinitions());
    }
    
    function setContent($data) {
        if (!is_null($data)) {
            $this->data = $data; 
        } 
    }
}

?>