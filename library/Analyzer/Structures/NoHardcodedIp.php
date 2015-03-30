<?php

namespace Analyzer\Structures;

use Analyzer;

class NoHardcodedIp extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('String')
             ->noDelimiterIsNot('127.0.0.1')
             ->regex('noDelimiter', '^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\\\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\\\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\\\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(:\\\\d+)?\\$')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
