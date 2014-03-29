<?php

namespace Report\Template;

class Camembert {

    public function render($output) {
        $renderer = $output->getRenderer('Camembert');
        
//        $renderer->setAnalyzer($this->data->getName());
        $renderer->render($output, null);
    }
    
    function setContent($data) {
        if (!is_null($data)) {
            $this->data = $data; 
        } 
    }
}

?>