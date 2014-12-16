<?php

namespace Report\Template;

class Summary extends \Report\Template {
    public function render($output) {
        $renderer = $output->getRenderer('Summary');
        $renderer->render($output, $this->data->getContent());
    }
}

?>
