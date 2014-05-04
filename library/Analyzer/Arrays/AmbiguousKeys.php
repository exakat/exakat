<?php

namespace Analyzer\Arrays;

use Analyzer;

class AmbiguousKeys extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Functioncall")
             ->code('array')
             ->raw("aggregate().findAll{ it.out('ARGUMENTS').out('ARGUMENT').has('atom', 'Keyvalue').out('KEY').groupBy{if (it.hasNot('delimiter', null).count() > 0) { it.noDelimiter; } else { it.code ;}}{it.atom}{it.unique().toList().size()}.cap.next().findAll{it.value > 1}.size() > 0}");
//        $this->printQuery();

//g.V.filter{ it.in("ANALYZED").has("code", 'Analyzer\\Arrays\\MultipleIdenticalKeys').count() == 0}.has("atom", 'Functioncall').as("first").
//filter{ it.code.toLowerCase() == 'array'}.
//aggregate(x).findAll{ m = [:]; it.out('ARGUMENTS').out('ARGUMENT').has("atom", 'Keyvalue').out('KEY').groupCount(m){it.code}.cap.next().findAll{it.value > 1}.size() > 0}

    }
}

?>