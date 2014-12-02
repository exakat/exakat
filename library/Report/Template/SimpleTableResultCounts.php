<?php

namespace Report\Template;

class SimpleTableResultCounts extends \Report\Template {
    public function render($output) {
        $data = $this->data->getArray();
        
        $renderer = $output->getRenderer('SimpleTableResultCounts');
        $renderer->setCss($this->css);
        $renderer->render($output, $data);
    }
}

?>