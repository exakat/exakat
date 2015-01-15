<?php

namespace Analyzer\Arrays;

use Analyzer;

class ShouldPreprocess extends Analyzer\Analyzer {
    public function analyze() {
        // in case this is the first one in the sequence
        $this->atomIs('Array')
             ->outIs('VARIABLE')
             ->savePropertyAs('fullcode', 'tableau')
             ->inIs('VARIABLE')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->code('=')
             ->is('rank', 0)
             ->nextSibling()
             ->atomIs('Assignation')
             ->code('=')
             ->outIs('LEFT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->samePropertyAs('fullcode', 'tableau')
             ->inIs('VARIABLE')
             ->inIs('LEFT')
             ->raw('filter{ it.out("RIGHT").out.loop(1){true}{it.object.atom == "Variable" && it.object.fullcode == tableau}.any() == false}')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Array')
             ->outIs('VARIABLE')
             ->savePropertyAs('fullcode', 'tableau')
             ->inIs('VARIABLE')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->code('=')
             ->isNot('rank', 0)
             ->_as('main')
             ->nextSibling()
             ->atomIs('Assignation')
             ->code('=')
             ->outIs('LEFT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->samePropertyAs('fullcode', 'tableau')
             ->inIs('VARIABLE')
             ->inIs('LEFT')
             ->raw('filter{ it.out("RIGHT").out.loop(1){true}{it.object.atom == "Variable" && it.object.fullcode == tableau}.any() == false}')
             ->back('main')
             ->previousSibling()
             ->raw('filter{ it.atom != "Assignation" || it.code != "=" || it.out("LEFT").has("atom", "Array").any() == false || it.out("LEFT").out("VARIABLE").has("fullcode", tableau).any() == false }')
             ->back('first');
        $this->prepareQuery();

        // same as above with $array[] 
        // in case this is the first one in the sequence
        $this->atomIs('Arrayappend')
             ->outIs('VARIABLE')
             ->savePropertyAs('fullcode', 'tableau')
             ->inIs('VARIABLE')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->is('rank', 0)
             ->nextSibling()
             ->atomIs('Assignation')
             ->outIs('LEFT')
             ->atomIs('Arrayappend')
             ->outIs('VARIABLE')
             ->samePropertyAs('fullcode', 'tableau')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Arrayappend')
             ->outIs('VARIABLE')
             ->savePropertyAs('fullcode', 'tableau')
             ->inIs('VARIABLE')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->isNot('rank', 0)
             ->_as('main')
             ->nextSibling()
             ->atomIs('Assignation')
             ->outIs('LEFT')
             ->atomIs('Arrayappend')
             ->outIs('VARIABLE')
             ->samePropertyAs('fullcode', 'tableau')
             ->back('main')
             ->previousSibling()
             ->raw('filter{ it.atom != "Assignation" || it.out("LEFT").has("atom", "Arrayappend").any() == false || it.out("LEFT").out("VARIABLE").has("fullcode", tableau).any() == false }')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
