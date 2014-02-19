<?php

namespace Analyzer\Arrays;

use Analyzer;

class MultipleIdenticalKeys extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("Functioncall")
             ->code('array')
             ->raw("aggregate().findAll{ m = [:]; it.out('ARGUMENTS').out('ARGUMENT').has('atom', 'Keyvalue').out('KEY').groupCount(m){it.code}.cap.next().findAll{it.value > 1}.size() > 0}");
//        $this->printQuery();

//g.V.filter{ it.in("ANALYZED").has("code", 'Analyzer\\Arrays\\MultipleIdenticalKeys').count() == 0}.has("atom", 'Functioncall').as("first").
//filter{ it.code.toLowerCase() == 'array'}.
//aggregate(x).findAll{ m = [:]; it.out('ARGUMENTS').out('ARGUMENT').has("atom", 'Keyvalue').out('KEY').groupCount(m){it.code}.cap.next().findAll{it.value > 1}.size() > 0}

    }
}

?>