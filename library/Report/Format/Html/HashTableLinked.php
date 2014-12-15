<?php

namespace Report\Format\Html;

class HashTableLinked extends \Report\Format\Html {
    public function render($output, $data) {
        $html = '<!-- '.__CLASS__.' -->';
        $output->push($html);
    }
}

?>
