<?php

namespace Analyzer\Type;

use Analyzer;

class String extends Analyzer\Type {

    function analyze() {
        $this->type = array('String', 'HereDoc', 'NowDoc');

        parent::analyze();
    }
}

?>