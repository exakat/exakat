<?php

namespace Analyzer\Arrays;

use Analyzer;

class MultipleIdenticalKeys extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Functioncall")
             ->code('array')
             ->raw("aggregate().findAll{ m = [:]; it.out('ARGUMENTS').out('ARGUMENT').has('atom', 'Keyvalue').out('KEY').groupCount(m){it.fullcode}.cap.next().findAll{it.value > 1}.size() > 0}");
        $this->prepareQuery();
    }
}

?>