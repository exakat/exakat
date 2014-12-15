<?php

namespace Report\Template;

class TableForVersions extends \Report\Template {
    public function render($output) {
        $data = $this->data->toArray();
        
        $renderer = $output->getRenderer('TableForVersions');
        $renderer->setCss($this->css);
        $renderer->render($output, $data);
    }
}

?>
