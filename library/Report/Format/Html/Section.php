<?php

namespace Report\Format\Html;

class Section extends \Report\Format\Html { 
    public function render($output, $data) {
        // todo link to the actual section  ?
        $h = $data->getLevel();
        $output->push("<h$h>".$data->getName()."</h$h>\n");
    }

}

?>