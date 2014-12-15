<?php

namespace Report\Format\Ace;

class Text extends \Report\Format\Ace { 
    public function render($output, $data) {
        $data = nl2br($data);
        
        if (!empty($this->css->style)) {
            $class = ' class="'.$this->css->style.'"';
        } else {
            $class = '';
        }
        $output->push("<p$class>".$data."</p>\n");
    }

}

?>
