<?php

namespace Report\Template;

class Text {
    public function render($output) {
        $renderer = $output->getRenderer('Text');
        
        $renderer->render($output, $this->data);
    }
    
    public function setContent($data) {
        if (!is_null($data)) {
            $this->data = $data; 
        } 
    }
}

?>