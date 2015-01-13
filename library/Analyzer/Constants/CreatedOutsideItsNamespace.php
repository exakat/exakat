<?php

namespace Analyzer\Constants;

use Analyzer;

class CreatedOutsideItsNamespace extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Functioncall')
             ->hasNoIn('Method')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath('\\define')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('String')
             ->regex('noDelimiter', '\\\\\\\\')
             ->fetchContext()
             ->regexNot('noDelimiter', '^" + context["Namespace"].replaceAll( "\\\\\\\\", "\\\\\\\\\\\\\\\\" ) + "')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
