<?php

namespace Report\Template;

class Row extends \Report\Template {
    private $left  = null;
    private $right = null;
    private $span = 6;
    
    public function render($output) {
        $renderer = $output->getRenderer('Row');
        $renderer->setSpan($this->span);
        
        $renderer->render($output, array($this->left, $this->right));
    }
    
    public function setRight($right) {
        $this->right = $right;
    }

    public function setLeft($left) {
        $this->left = $left;
    }
    
    public function addLeftContent($type, $data = null) {
        $type = 'Report\\Template\\'.$type;
        $content = new $type();
        $content->setContent($data);
        $this->left = $content;
        
        return $content;
    }

    public function addRightContent($type, $data = null) {
        $type = 'Report\\Template\\'.$type;
        $content = new $type();
        $content->setContent($data);
        $this->right = $content;
        
        return $content;
    }
    
    public function setSpan($span) {
        $this->span = $span;
    }
}

?>