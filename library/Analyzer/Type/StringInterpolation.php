<?php

namespace Analyzer\Type;

use Analyzer;

class StringInterpolation extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs(array('String', 'HereDoc', 'NowDoc'))
             ->outIs('CONTAIN')
             ->outIs('CONCAT')
             ->atomIs(array('Variable', 'Array', 'Property'))
             ->is('enclosing', null)
             ->nextSibling('CONCAT')
             ->regex('code', '^(->|\\\\[|\\\\{|::)')
             ->back('first')
             ;
        $this->prepareQuery();
    }

}

?>