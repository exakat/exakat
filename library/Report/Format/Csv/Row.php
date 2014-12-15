<?php

namespace Report\Format\Csv;

class Row extends \Report\Format\Csv {
    private $span = 6;
    
    public function render($output, $data) {
        $left = $data['left'];
        $right = $data['right'];
        
        if (is_object($left)) {
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
