<?php

namespace Report\Template;

class Definitions {

    public function render($output) {
        $renderer = $output->getRenderer('Definitions');
        
        $renderer->render($output, $this->data);
    }
    
    function setContent($data) {
        if (!is_null($data)) {
            $this->data = $data; 
        } 
    }
}

?>