<?php

namespace Report\Template;

class Top5 extends \Report\Template {
    
    public function render($output) {
        $renderer = $output->getRenderer('Top5');
        
        $renderer->setTitle($this->title);
        $renderer->render($output, $this->data);
    }
    
}

?>