<?php

namespace Report\Template;

class Row extends \Report\Template {
    
    public function render($output, $data) {
        $renderer = $output->getRenderer('Row');

        $renderer->setCss($this->css);
        $renderer->render($output, $data);
    }
}

?>
