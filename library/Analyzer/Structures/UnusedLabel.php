<?php

namespace Analyzer\Structures;

use Analyzer;

class UnusedLabel extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Label')
             ->outIs('LABEL')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->raw('filter{ g.idx("atoms")[["atom":"Goto"]].out("LABEL").has("code", name).any() == false}');
        $this->prepareQuery();
    }
}

?>
