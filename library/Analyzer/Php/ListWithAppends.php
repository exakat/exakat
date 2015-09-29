<?php

namespace Analyzer\Php;

use Analyzer;

class ListWithAppends extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Functioncall')
             ->tokenIs('T_LIST')
//             ->outIs('ARGUMENTS')
             // more than one Arrayappend, for initial filtering
             ->filter('it.out("ARGUMENTS").out("ARGUMENT").has("atom", "Arrayappend").count() > 1')
             // more than one Arrayappend, for initial filtering
             ->filter('it.out("ARGUMENTS").out("ARGUMENT").has("atom", "Arrayappend").groupCount{it.out("VARIABLE").next().code}{it.b + 1}.cap.next().findAll{it.value > 1}.any()')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
