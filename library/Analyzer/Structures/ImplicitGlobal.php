<?php

namespace Analyzer\Structures;

use Analyzer;

class ImplicitGlobal extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Global")
             ->hasFunction()
             ->outIs('GLOBAL')
             ->_as('result')
             ->savePropertyAs('code', 'theGlobal')
             ->codeIsNot(array('$argv', '$argc'))
             ->raw('filter{ g.idx("atoms")[["atom":"Global"]].out("GLOBAL").filter{ it.in.loop(1){it.object.atom != "Function"}{it.object.atom == "Function"}.any() == false}.filter{theGlobal == it.code}.any() == false }')
             ->back('result');
        $this->prepareQuery();
    }
}

?>
