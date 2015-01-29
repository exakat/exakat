<?php

namespace Analyzer\Functions;

use Analyzer;

class UselessReturn extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Function')
             ->hasClass()
             ->outIs('NAME')
             ->code(array('__constructor', '__destructor', '__set', '__clone', '__unset', '__wakeup'))
             ->inIs('NAME')
             ->raw("filter{ it.out('BLOCK').out.loop(1){it.object.atom != 'Function'}{it.object.atom == 'Return'}.filter{it.out('RETURN').filter{it.atom in ['Void', 'Null']}.any() == false}.count() > 0}")
             ->back('first');
        $this->prepareQuery();

// @todo : spot such functions
//Also `__autoload`, methods used for autoloading and methods registered for shutdown, have no need to return anything. 

    }
}

?>
