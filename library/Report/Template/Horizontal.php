<?php

namespace Report\Template;

class Horizontal extends \Report\Template {
    public function render($output) {
        $renderer = $output->getRenderer('Horizontal');

        $renderer->setAnalyzer($this->data->getDescription()->getName());
        $renderer->setCss($this->css);
        
        $renderer->render($output, $this->data->getArray());
    }
}

?>
