<?php

namespace Report\Template;

class Liste extends \Report\Template {

    public function render($output) {
        $renderer = $output->getRenderer('Liste');
        $renderer->render($output, $this->data->toArray());
    }
}

?>
