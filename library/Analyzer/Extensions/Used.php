<?php

namespace Analyzer\Extensions;

use Analyzer;

class Used extends Analyzer\Analyzer {

    function dependsOn() {
        return array('Analyzer\\Extensions\\Extmcrypt', 
                     'Analyzer\\Extensions\\Extpcre',
                     'Analyzer\\Extensions\\Extmysqli',
                     'Analyzer\\Extensions\\Extkdm5',
                     );
    }

    function analyze() {
        $depends = $this->dependsOn();
        foreach($depends as $k => $v) {
            $depends[$k] = addslashes($v);
        }
        
        $this->atomIs('Index')
             ->code($depends)
             ->_as('result')
             ->out('ANALYZED')
             ->back('result');

        $this->prepareQuery();
    }
}

?>