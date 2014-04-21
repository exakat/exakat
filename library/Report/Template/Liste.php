<?php

namespace Report\Template;

class Liste {

    public function render($output) {
        $renderer = $output->getRenderer('Liste');
        $renderer->render($output, $this->data->toArray());
    }
}

?>