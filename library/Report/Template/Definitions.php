<?php

namespace Report\Template;

class Definitions  extends \Report\Template {

    public function render($output) {
        $renderer = $output->getRenderer('Definitions');
        
        $renderer->setAnalyzer($this->data->getName());
        $renderer->render($output, $this->data->getDefinitions());
    }
}

?>