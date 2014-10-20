<?php

namespace Report\Template;

class Compilations extends \Report\Template\DefaultTemplate {
    public function render($output) {
        $renderer = $output->getRenderer('CompilationTable');
        
        $renderer->setTitles(array('Version', 'Total files', 'Total errors', 'Files', 'Errors'));
        $renderer->render($output, $this->data->getInfo());
    }
}

?>