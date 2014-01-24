<?php

namespace Analyzer\Structures;

use Analyzer;

class ShortTags extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("Phpcode")
             ->code(array('<?', '<script language="php">', '<%=', '<%'), true);
        $this->prepareQuery();
    }
}

?>