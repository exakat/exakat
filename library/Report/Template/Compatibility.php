<?php

namespace Report\Template;

class Compatibility extends \Report\Template\DefaultTemplate {
    public function render($output) {
        $renderer = $output->getRenderer('HashTableLinked');
        
        $info = $this->data->getInfo();
        $renderer->render($output, $info);
    }
}

?>