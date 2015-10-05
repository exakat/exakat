<?php

namespace Analyzer\Variables;

use Analyzer;

class Php5IndirectExpression extends Analyzer\Analyzer {
    protected $phpVersion = '7.0-';
    
    public function analyze() {
//$$foo['bar']['baz']	${$foo['bar']['baz']}	($$foo)['bar']['baz']
        $this->atomIs('Variable')
             ->tokenIs('T_DOLLAR')
             ->outIs('NAME')
             ->atomIs('Array')
             ->back('first');
        $this->prepareQuery();

//$foo->$bar['baz']	$foo->{$bar['baz']}	($foo->$bar)['baz']
        $this->atomIs('Property')
             ->outIs('PROPERTY')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->atomIs('T_VARIABLE')
             ->back('first');
        $this->prepareQuery();


//$foo->$bar['baz']()	$foo->{$bar['baz']}()	($foo->$bar)['baz']()
        $this->atomIs('Methodcall')
             ->outIs('METHOD')
             ->outIs('NAME')
             ->atomIs('Array')
             ->back('first');
        $this->prepareQuery();

//Foo::$bar['baz']()
        $this->atomIs('Staticmethodcall')
             ->outIs('METHOD')
             ->outIs('NAME')
             ->atomIs('Array')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
