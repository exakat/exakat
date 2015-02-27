<?php

namespace Analyzer\Classes;

use Analyzer;

class NoPublicAccess extends Analyzer\Analyzer {
    /* Remove this if useless
    public function dependsOn() {
        return array('MethodDefinition');
    }
    */
    
    public function analyze() {
        $this->atomIs('Ppp')
             ->hasOut('PUBLIC')
             ->hasNoOut('STATIC')
             ->savePropertyAs('propertyname', 'property')
             ->raw('filter{ g.idx("atoms")[["atom":"Property"]].out("OBJECT").has("atom", "Variable").hasNot("code", "$this").in("OBJECT").out("PROPERTY").has("code", property).any() == false}')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Ppp')
             ->hasOut('PUBLIC')
             ->hasOut('STATIC')
             ->savePropertyAs('code', 'property')
             ->goToClass()
             ->savePropertyAs('fullnspath', 'fns')
             ->raw('filter{ g.idx("atoms")[["atom":"Staticproperty"]].out("CLASS").filter{it.token in ["T_STRING", "T_NS_SEPARATOR"]}.has("fullnspath", fns).in("CLASS").out("PROPERTY").has("code", property).any() == false}')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
