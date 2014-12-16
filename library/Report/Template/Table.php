<?php

namespace Report\Template;

class Table extends \Report\Template {
    public function render($output) {
        $data = $this->data->toArray();
        
        $renderer = $output->getRenderer('Table');
        $renderer->render($output, $data);
    }
}

?>
