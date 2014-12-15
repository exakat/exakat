<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extgettext extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'gettext.ini';
        
        parent::analyze();
    }
}

?>
