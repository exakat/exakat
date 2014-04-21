<?php

namespace Report\Template;

class Camembert extends \Report\Template {

    public function render($output) {
        $renderer = $output->getRenderer('Camembert');
        
        $renderer->render($output, $this->data);
    }
}

?>