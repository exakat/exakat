<?php

namespace Analyzer\Exceptions;

use Analyzer;

class AlreadyCaught extends Analyzer\Analyzer {
    public function analyze() {
        // Check that the class of on catch is not a parent of of the next catch
        // class A, class B extends A
        // catch(A $a) {} catch (B $b) <= then Catch A is wrong 
        $this->atomIs('Try')
             ->outIs('CATCH')
             ->savePropertyAs('rank', 'rank')
             ->outIs('CLASS')
             ->savePropertyAs('fullnspath', 'fnp')
             ->inIs('CLASS')
             ->inIs('CATCH')
             ->outIs('CATCH')
             ->isMore('rank', 'rank')
             ->outIs('CLASS')
             ->classDefinition()
             ->isInProperty('classTree', 'fnp')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
