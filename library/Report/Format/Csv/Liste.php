<?php

namespace Report\Format\Html;

class Liste extends Report\Format\Html { 

    public function render($output, $data) {
        foreach($data as $k => $v) {
            $output->push($v);
        }
    }

}

?>