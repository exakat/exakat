<?php

namespace Analyzer\Constants;

use Analyzer;

class ConstantStrangeNames extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Analyzer\\Constants\\Constantnames");
    }

    public function analyze() {
        $this->atomIs("String")
             ->analyzerIs("Analyzer\\Constants\\Constantnames")
             ->regexNot('noDelimiter', '^(\\\\\\\\?)[a-zA-Z_\\\\x7f-\\\\xff][a-zA-Z0-9_\\\\x7f-\\\\xff]*\\$')
             // simple constant name
             ->regexNot('noDelimiter', '^(\\\\\\\\?)([a-zA-Z_\\\\x7f-\\\\xff][a-zA-Z0-9_\\\\x7f-\\\\xff]*\\\\\\\\)+[a-zA-Z_\\\\x7f-\\\\xff][a-zA-Z0-9_\\\\x7f-\\\\xff]*\\$');
             // \\\\\\\\ is equivalent to \\ (two slashes) in the final regex.
        $this->prepareQuery();
    }
}

?>
