<?php

namespace Report\Template;

class SimpleTable extends \Report\Template {
    public function render($output) {
        $data = $this->data->getArray();
        
        $renderer = $output->getRenderer('SimpleTable');
        $renderer->setCss($this->css);
        $renderer->render($output, $data);
    }
}

?>
