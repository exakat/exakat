<?php

namespace Report\Template;

class TextLead extends \Report\Template {
    public function render($output) {
        $renderer = $output->getRenderer('TextLead');
        
        $renderer->render($output, $this->data);
    }
}

?>