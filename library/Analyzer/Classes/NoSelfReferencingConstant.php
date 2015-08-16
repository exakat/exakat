<?php

namespace Analyzer\Classes;

use Analyzer;

class NoSelfReferencingConstant extends Analyzer\Analyzer {
    public function analyze() {
        // const c = self::b
        $this->atomIs('Const')
             ->inClass()
             ->outIs('VALUE')
             ->outIs('CLASS')
             ->code('self')
             ->back('first');
        $this->prepareQuery();

        // const c = self::$b + 1
        $this->atomIs('Const')
             ->inClass()
             ->outIs('VALUE')
             ->atomInside('Staticconstant')
             ->outIs('CLASS')
             ->code('self')
             ->back('first');
        $this->prepareQuery();    

        // const c = a::b
        $this->atomIs('Const')

             ->goToClass()
             ->savePropertyAs('fullnspath', 'classe')
             ->back('first')

             ->outIs('NAME')
             ->savePropertyAs('code', 'constante')
             ->inIs('NAME')

             ->outIs('VALUE')
             ->atomIs('Staticconstant')
             ->outIs('CLASS')
             ->samePropertyAs('fullnspath', 'classe')
             ->inIs('CLASS')

             ->outIs('CONSTANT')
             ->samePropertyAs('code', 'constante')

             ->back('first');
        $this->prepareQuery();

        // const c = a::b + 1
        $this->atomIs('Const')

             ->goToClass()
             ->savePropertyAs('fullnspath', 'classe')
             ->back('first')

             ->outIs('NAME')
             ->savePropertyAs('code', 'constante')
             ->inIs('NAME')

             ->outIs('VALUE')
             ->atomInside('Staticconstant')
             ->outIs('CLASS')
             ->samePropertyAs('fullnspath', 'classe')
             ->inIs('CLASS')

             ->outIs('CONSTANT')
             ->samePropertyAs('code', 'constante')

             ->back('first');
        $this->prepareQuery();
    }
}

?>
