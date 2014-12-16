<?php

namespace Analyzer\Structures;

use Analyzer;

class ShortTags extends Analyzer\Analyzer {
    protected $phpConfiguration = array("short_open_tags" => true);

    public function analyze() {
        $this->atomIs("Phpcode")
             ->code(array('<?', '<script language="php">', '<%=', '<%'), true);
        $this->prepareQuery();
    }
}

?>
