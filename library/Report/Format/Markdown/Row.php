<?php

namespace Report\Format\Markdown;

class Row extends \Report\Format\Markdown {
    private $span = 6;
    
    public function render($output, $data) {
        // two columns are meaningless. We do one after each other
        list($left, $right) = $data;
        
        if (is_object($left)) {
            print "rendering\n";
            $left->render($output);
        }

        if (is_object($right)) {
            $right->render($output);
        }
    }
    
    public function setSpan($span = 6) {
        $this->span = $span;
    }
}

?>