<?php

namespace Report\Template;

class DefaultTemplate extends \Report\Template {

    public function render($output) {
        $renderer = $output->getRenderer('Text');
        
        $renderer->render($output, "This is the default template for ".get_class($this));
    }
}

?>