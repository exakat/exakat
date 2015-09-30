<?php

namespace Analyzer\Variables;

use Analyzer;

class Php7IndirectExpression extends Analyzer\Analyzer {
    protected $phpVersion = '7.0+';
    
    public function analyze() {
//$$foo['bar']['baz']	${$foo['bar']['baz']}	($$foo)['bar']['baz']
        $this->atomIs('Array')
             ->outIsIE('VARIABLE')
             ->atomIs('Variable')
             ->tokenIs('T_DOLLAR')
             ->back('first')
             ->hasNoIn('VARIABLE');
        $this->prepareQuery();

//$foo->$bar['baz']	$foo->{$bar['baz']}	($foo->$bar)['baz']
        $this->atomIs('Array')
             ->outIs('VARIABLE')
             ->atomIs(array('Property', 'Staticproperty'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
