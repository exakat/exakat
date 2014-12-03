<?php

namespace Report\Template;

class Directives extends \Report\Template {

    public function render($output) {
        $renderer = $output->getRenderer('SectionedTable');
        
        $renderer->setCss($this->css ?: 'directives');
        $renderer->render($output, $this->data->getArray());
    }
}

?>