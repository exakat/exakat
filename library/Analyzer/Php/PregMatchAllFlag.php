<?php

namespace Analyzer\Php;

use Analyzer;

class PregMatchAllFlag extends Analyzer\Analyzer {
    public function analyze() {
        // Using default configuration
        $this->atomFunctionIs('\preg_match_all')
             ->outIs('ARGUMENTS')
             ->noChildWithRank('ARGUMENT', 3)
             ->outIs('ARGUMENT')
             ->hasRank(2)
             ->savePropertyAs('code', 'r')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->nextSiblings() // Do we really need all of them? May be limit to 3/5
             ->atomIs('Foreach')
             ->outIs('SOURCE')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'r')
             ->inIs('VARIABLE')
             ->inIs('SOURCE')
             ->outIs('VALUE')
             ->atomIs('Keyvalue')
             ->outIs('KEY')
             ->savePropertyAs('code', 'key')
             ->inIs('KEY')
             ->inIs('VALUE')
             ->outIs('BLOCK')
             ->atomInside('Array')
             ->outIs('VARIABLE')// $r[1][$id]
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'r')
             ->inIs('VARIABLE')
             ->inIs('VARIABLE')
             ->outIs('INDEX')
             ->samePropertyAs('code', 'key')
             ->back('first');
        $this->prepareQuery();

        // Using explicit configuration
        $this->atomFunctionIs('\preg_match_all')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->hasRank(3)
             ->fullnspath('\PREG_PATTERN_ORDER')
             ->inIs('ARGUMENT')
             ->outIs('ARGUMENT')
             ->hasRank(2)
             ->savePropertyAs('code', 'r')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->nextSiblings() // Do we really need all of them? May be limit to 3/5
             ->atomIs('Foreach')
             ->outIs('SOURCE')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'r')
             ->inIs('VARIABLE')
             ->inIs('SOURCE')
             ->outIs('VALUE')
             ->atomIs('Keyvalue')
             ->outIs('KEY')
             ->savePropertyAs('code', 'key')
             ->inIs('KEY')
             ->inIs('VALUE')
             ->outIs('BLOCK')
             ->atomInside('Array')
             ->outIs('VARIABLE')// $r[1][$id]
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'r')
             ->inIs('VARIABLE')
             ->inIs('VARIABLE')
             ->outIs('INDEX')
             ->samePropertyAs('code', 'key')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
