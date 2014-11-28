<?php

namespace Analyzer\Security;

use Analyzer;

class RemoteInjection extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Analyzer\\Security\\SensitiveArgument");
    }

    public function analyze() {
        // foreach 
        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->raw('sideEffect{ first = it;}')

             // Must change to list of incoming points
//             ->analyzerIs('Analyzer\\Security\\ContaminatedFunction')
             ->code(array('f011', 'f101', 'f110', 'f111'))

            // Loop initialisation    .filter{ it.code == '\$a' }
             ->raw("sideEffect{ x=[]; y = it.out('ARGUMENTS').out('ARGUMENT').rank.toList(); x += [y]; x += [y] ; x += 0;}")
             ->followConnexion( 10 )
             
             // here, spot vulnerable spots
             ->raw('filter{ it.out("ARGUMENTS").out("ARGUMENT").filter{ it.in("ANALYZED").has("code", "Analyzer\\\\Security\\\\SensitiveArgument").any() }.any()}')
             ->raw('transform{ first ; }');
        $this->prepareQuery();
    }
}

// handle functions : OK (Test 1,2);
// Handle methods 
// Handle staticmethods
// handle multiple arguments : OK (arguments 2,3,4)
// handle non relayed arguments : OK (arugments 2,3,4)
// handle default values, references and typehint 
// check on retro-feedback (function calling back another function already called : limiter by Iternation number, or by finding something early. Otherwise, good reaction.
// check on drive-by functions call (the one not at the end of the path)

// had a check on final methods that are sensitive to check  (OK).


?>