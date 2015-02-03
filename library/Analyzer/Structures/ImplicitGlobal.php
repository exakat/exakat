<?php

namespace Analyzer\Structures;

use Analyzer;

class ImplicitGlobal extends Analyzer\Analyzer {
    public function analyze() {
        $superglobals = $this->loadIni('php_superglobals.ini', 'superglobal');

        $this->atomIs("Global")
             ->hasFunction()
             ->outIs('GLOBAL')
             ->tokenIs('T_VARIABLE')
             ->codeIsNot($superglobals)
             ->_as('result')
             ->savePropertyAs('code', 'theGlobal')
             ->codeIsNot(array('$argv', '$argc'))
             ->raw('filter{ g.idx("atoms")[["atom":"Global"]].out("GLOBAL").filter{ it.in.loop(1){it.object.atom != "Function"}{it.object.atom == "Function"}.any() == false}.filter{theGlobal == it.code}.any() == false }')
             ->back('result');
        $this->prepareQuery();
    }
}

?>
