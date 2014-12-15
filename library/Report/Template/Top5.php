<?php

namespace Report\Template;

class Top5 extends \Report\Template {
    
    public function render($output) {
        $renderer = $output->getRenderer('Top5');
        
        $renderer->setCss($this->css);
        $renderer->render($output, $this->data->getArray());
    }
}

?>
