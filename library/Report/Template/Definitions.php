<?php

namespace Report\Template;

class Definitions  extends \Report\Template {

    public function render($output) {
        $renderer = $output->getRenderer('Definitions');
        $renderer->setCss($this->css);
        
        $renderer->render($output, $this->data->getDefinitions());
    }
}

?>
