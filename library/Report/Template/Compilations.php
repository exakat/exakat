<?php

namespace Report\Template;

class Compilations extends \Report\Template\DefaultTemplate {
    public function render($output) {
        $renderer = $output->getRenderer('CompilationTable');
        
        $renderer->setCss($this->css);
        $renderer->render($output, $this->data->getArray());
    }
}

?>
