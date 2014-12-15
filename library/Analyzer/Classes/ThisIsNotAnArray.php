<?php

namespace Analyzer\Classes;

use Analyzer;

class ThisIsNotAnArray extends Analyzer\Analyzer {

    public function analyze() {
        // direct class
        $this->atomIs("Variable")
             ->code('$this')
             ->inIs('VARIABLE')
             ->atomIs(array('Array', 'Arrayappend'))
             // class may be \ArrayAccess 
             ->raw('filter{ (it.in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}.out("EXTENDS", "IMPLEMENTS").has("fullnspath", "\\\\arrayaccess").any() == false) &&
                            (it.in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}.out("EXTENDS", "IMPLEMENTS")
             .as("parent").transform{ g.idx("classes")[["path":it.fullnspath]].next()}.out("EXTENDS", "IMPLEMENTS").loop("parent"){it.loops < 5}{true}
             .has("fullnspath", "\\\\arrayaccess").any() == false)
             }');
        $this->prepareQuery();
    }
}

?>
