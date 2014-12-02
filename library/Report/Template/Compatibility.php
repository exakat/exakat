<?php

namespace Report\Template;

class Compatibility extends \Report\Template\DefaultTemplate {
    public function render($output) {
        $renderer = $output->getRenderer('HashTableLinked');

        $renderer->setCss($this->css);
        $renderer->render($output, $this->data->getArray());
    }
}

?>