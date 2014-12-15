<?php

namespace Analyzer\Php;

use Analyzer;

class RawPostDataUsage extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Variable")
             ->code('$HTTP_RAW_POST_DATA');
        $this->prepareQuery();
    }
}

?>
