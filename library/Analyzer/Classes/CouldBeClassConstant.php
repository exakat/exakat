<?php

namespace Analyzer\Classes;

use Analyzer;

class CouldBeClassConstant extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\IsModified');
    }
    
    public function analyze() {
        $this->atomIs('Ppp')
             ->hasOut('DEFINE')
             ->filter('it.out("VALUE").has("atom", "Void").any() == false')
             ->filter(' it.out("VALUE").filter{ it.atom == "Void"}.any() == false')
             ->savePropertyAs('propertyname', 'name')
             ->outIs('DEFINE')
             ->savePropertyAs('code', 'staticName')
             ->goToClass()
             ->outIs('BLOCK')
             ->raw('filter{it.out.loop(1){true}{it.object.atom == "Property"}.filter{ it.out("OBJECT").has("code", "\$this").any()}
                                                                             .filter{ it.out("PROPERTY").has("code", name).any()}
                                                                             .filter{ it.in("ANALYZED").has("code", "Analyzer\\\\Classes\\\\IsModified").any()}
                                                                             .any() == false}')

                // array usage as static property with self or static
             ->raw('filter{it.out.loop(1){true}{it.object.atom == "Staticproperty"}.filter{ it.out("CLASS").filter{ it.code.toLowerCase() in ["static", "self"]}.any()}
                                                                                   .filter{ it.out("PROPERTY").has("code", staticName).any()}
                                                                                   .filter{ it.in("ANALYZED").has("code", "Analyzer\\\\Classes\\\\IsModified").any()}
                                                                                   .any() == false}')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
