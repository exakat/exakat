<?php

namespace Analyzer\Php;

use Analyzer;

class DirectiveName extends Analyzer\Analyzer {
    public function analyze() {
        $directives = $this->loadIni('directives.ini', 'directives');

        $this->atomFunctionIs(array('\\ini_set', '\\ini_get'))
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->hasRank(0)
             ->atomIs('String')
             ->hasNoOut('CONTAINS')
             ->noDelimiterIsNot($directives)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
