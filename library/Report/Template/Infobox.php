<?php

namespace Report\Template;

class Infobox extends \Report\Template {

    public function render($output) {
        $renderer = $output->getRenderer('Infobox');
        
        $renderer->setCss($this->css);
        $renderer->render($output, $this->data->getArray());
    }
}

?>
