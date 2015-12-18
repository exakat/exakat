<?php

namespace Analyzer\Functions;

use Analyzer;

class UnusedArguments extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Variables/Arguments',
                     'Variables/IsRead',
                     'Variables/IsModified',
                     );
    }
    
    public function analyze() {
        // Arguments, not reference
        $this->analyzerIs('Variables/Arguments')
             ->savePropertyAs('code', 'varname')
             ->isNot('reference', true)
             ->inIsIE('LEFT')     // for default value
             ->inIsIE('VARIABLE') // for typehint
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->hasNoOut('ABSTRACT')
             ->notInTrait()
             ->notInInterface()
             
             // this argument must be read at least once
             ->filter('it.out("BLOCK").out.loop(1){true}{ it.object.atom == "Variable" }.has("code", varname).filter{ it.in("ANALYZED").has("code", "Variables/IsRead").any()}.any() == false')
             ->back('first');
        $this->prepareQuery();

        // Arguments, reference
        $this->analyzerIs('Variables/Arguments')
             ->savePropertyAs('code', 'varname')
             ->is('reference', true)
             ->inIsIE('LEFT')     // for default value
             ->inIsIE('VARIABLE') // for typehint
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->hasNoOut('ABSTRACT')
             ->notInTrait()
             ->notInInterface()
             
             // this argument must be read or written at least once
             ->filter('it.out("BLOCK").out.loop(1){true}{ it.object.atom == "Variable" }.has("code", varname)
                                          .filter{ it.in("ANALYZED").filter{ it.code in ["Variables/IsModified", "Variables/IsRead"]}.any()}.any() == false')
             ->back('first');
        $this->prepareQuery();

        // Arguments in a USE, not a reference
        $this->atomIs('Function')
             ->is('lambda', true)
             ->outIs('USE')
             ->outIs('ARGUMENT')
             ->isNot('reference', true)
             ->savePropertyAs('code', 'varname')
             ->_as('first')
             ->inIs('ARGUMENT')
             ->inIs('USE')
             
             // this argument must be read or written at least once
             ->filter('it.out("BLOCK").out.loop(1){true}{ it.object.atom == "Variable" }.has("code", varname).filter{ it.in("ANALYZED").has("code", "Variables/IsRead").any()}.any() == false')
             ->back('first');
        $this->prepareQuery();

        // Arguments in a USE, reference
        $this->atomIs('Function')
             ->is('lambda', true)
             ->outIs('USE')
             ->outIs('ARGUMENT')
             ->is('reference', true)
             ->savePropertyAs('code', 'varname')
             ->_as('result')
             ->inIs('ARGUMENT')
             ->inIs('USE')
             
             // this argument must be read or written at least once
             ->filter('it.out("BLOCK").out.loop(1){true}{ it.object.atom == "Variable" }.has("code", varname)
                                          .filter{ it.in("ANALYZED").filter{ it.code in ["Variables/IsModified", "Variables/IsRead"]}.any()}.any() == false')
             ->back('result');
        $this->prepareQuery();
    }
}

?>
