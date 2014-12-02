<?php

namespace Report\Template;

class SectionedHashTable extends \Report\Template {
    public function render($output) {
        $data = $this->data->getHash();
        
        $renderer = $output->getRenderer('SectionedHashTable');
        $renderer->setCss($this->css);
        $renderer->render($output, $data);
    }
}

?>