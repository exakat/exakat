<?php

namespace Report\Template;

class Horizontal extends \Report\Template {
    private $hash = array('Empty' => 'hash');
    private $summary = false;

    private $headerName = 'Item';
    private $headerCount = 'Count';
    
    const SORT_NONE = 1;
    const SORT_COUNT = 2;
    const SORT_REV_COUNT = 3;
    const SORT_KEY = 4;
    const SORT_REV_KEY = 4;
    
    public function render($output) {
        $renderer = $output->getRenderer('Horizontal');

        $renderer->setAnalyzer($this->data->getName());
        $renderer->setCss($this->css);
        
        $renderer->render($output, $this->data->getArray());
    }
}

?>