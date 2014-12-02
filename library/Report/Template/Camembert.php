<?php

namespace Report\Template;

class Camembert extends \Report\Template {

    public function render($output) {
        $renderer = $output->getRenderer('Camembert');
        
        $renderer->setCss($this->css);
        $renderer->render($output, $this->data->getArray());
    }
}

?>