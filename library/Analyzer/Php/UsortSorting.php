<?php

namespace Analyzer\Php;

use Analyzer;

class UsortSorting extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomFunctionIs(array('\\usort', '\\uksort', '\\uasort'));
        $this->prepareQuery();
    }
}

?>
