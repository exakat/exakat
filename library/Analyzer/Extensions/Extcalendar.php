<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extcalendar extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'calendar.ini';
        
        parent::analyze();
    }
}

?>
