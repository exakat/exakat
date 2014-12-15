<?php

namespace Analyzer\Arrays;

use Analyzer;

class AmbiguousKeys extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Functioncall")
             ->code('array')
             ->raw("aggregate().findAll{ it.out('ARGUMENTS').out('ARGUMENT').has('atom', 'Keyvalue').out('KEY').groupBy{if (it.hasNot('delimiter', null).count() > 0) { it.noDelimiter; } else { it.code ;}}{it.atom}{it.unique().toList().size()}.cap.next().findAll{it.value > 1}.size() > 0}");
        $this->prepareQuery();
    }
}

?>
