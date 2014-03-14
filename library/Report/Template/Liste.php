<?php

namespace Report\Template;

class Liste {

    public function render($output) {
        $renderer = $output->getRenderer('Liste');
        
        $renderer->render($output, $this->data->toArray());
    }
    
    function setContent($data) {
        if (!is_null($data)) {
            $this->data = $data; 
        } 
    }
}

?>