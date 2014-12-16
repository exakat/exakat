<?php

namespace Report\Template;

class HashTable extends \Report\Template {
    public function render($output) {
        if ($this->countedValues) {
            $data = $this->data->toCountedArray();
        } else {
            $data = $this->data->toArray();
        }
        
        $renderer = $output->getRenderer('HashTable');
        $renderer->render($output, $data);
    }
}

?>
