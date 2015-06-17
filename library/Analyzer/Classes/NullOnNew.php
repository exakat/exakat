<?php

namespace Analyzer\Classes;

use Analyzer;

class NullOnNew extends Analyzer\Analyzer {
    public function analyze() {
        $names = array('finfo',
                       'PDO',
                       'Collator',
                       'IntlDateFormatter',
                       'MessageFormatter',
                       'NumberFormatter',
                       'ResourceBundle',
                       'IntlRuleBasedBreakIterator');
        $names = $this->makeFullNsPath($names);
        
        $this->atomIs('New')
             ->outIs('NEW')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath($names)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
