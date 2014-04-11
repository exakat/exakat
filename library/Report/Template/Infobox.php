<?php

namespace Report\Template;

class Infobox extends \Report\Template {

    public function render($output) {
        $renderer = $output->getRenderer('Infobox');
        
        $renderer->render($output, $this->data->toInfoBox());
    }
}

?>