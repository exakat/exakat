<?php

namespace Report\Template;

class Tree extends \Report\Template {
    public function render($output) {
        $renderer = $output->getRenderer('Tree');
        $renderer->render($output, $this->data );
    }
}

?>