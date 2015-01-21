<?php

namespace Analyzer\Structures;

use Analyzer;

class MultipleDefaultInSwitch extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Switch')
             ->raw("aggregate().findAll{ m = [:]; it.out('CASES').out('ELEMENT').has('atom', 'Default').groupCount(m){it.fullcode}.cap.next().findAll{it.value > 1}.size() > 0}");
        $this->prepareQuery();
    }
}

?>
