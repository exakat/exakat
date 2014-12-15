<?php

namespace Report\Format\Text;

class Section extends \Report\Format\Text { 
    public function render($output, $data) {
        // todo link to the actual section  ?
        $output->push(str_repeat('#', $data->getLevel())." ".$data->getName()." \n");
    }

}

?>
