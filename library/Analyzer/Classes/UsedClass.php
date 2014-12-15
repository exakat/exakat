<?php

namespace Analyzer\Classes;

use Analyzer;

class UsedClass extends Analyzer\Analyzer {

    public function analyze() {
        // class used in a New
        $this->atomIs("Class")
             ->savePropertyAs('fullnspath', 'classdns')
             ->raw('filter{ g.idx("atoms")[["atom":"New"]].out("NEW").hasNot("fullnspath", null).has("fullnspath", classdns).any()}');
        $this->prepareQuery();
        
        // classed used in a extends
        $this->atomIs("Class")
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'classdns')
             ->raw('filter{ g.idx("atoms")[["atom":"Class"]].out("EXTENDS").has("fullnspath", classdns).any()}');
        $this->prepareQuery();

        // class used in an implements
        $this->atomIs("Class")
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'classdns')
             ->raw('filter{ g.idx("atoms")[["atom":"Class"]].out("IMPLEMENTS").has("fullnspath", classdns).any()}');
        $this->prepareQuery();

        // class used in a staticmethodcall
        $this->atomIs("Class")
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'classdns')
             ->raw('filter{ g.idx("atoms")[["atom":"Staticmethodcall"]].out("CLASS").has("fullnspath", classdns).any()}');
        $this->prepareQuery();

        // class used in static property
        $this->atomIs("Class")
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'classdns')
             ->raw('filter{ g.idx("atoms")[["atom":"Staticproperty"]].out("CLASS").has("fullnspath", classdns).any()}');
        $this->prepareQuery();

        // class used in static constant
        $this->atomIs("Class")
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'classdns')
             ->raw('filter{ g.idx("atoms")[["atom":"Staticconstant"]].out("CLASS").has("fullnspath", classdns).any()}');
        $this->prepareQuery();

        // class use in a instanceof
        $this->atomIs("Class")
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'classdns')
             ->raw('filter{ g.idx("atoms")[["atom":"Instanceof"]].out("CLASS").has("fullnspath", classdns).any()}');
        $this->prepareQuery();

        // class used in a typehint
        $this->atomIs("Class")
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'classdns')
             ->raw('filter{ g.idx("atoms")[["atom":"Typehint"]].out("CLASS").has("fullnspath", classdns).any()}');
        $this->prepareQuery();

        // class used in a Use
        $this->atomIs("Class")
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'classdns')
             ->raw('filter{ g.idx("atoms")[["atom":"Use"]].out("USE").has("originpath", classdns).any()}');
        $this->prepareQuery();

        // class used in a String (full string only)
        $this->atomIs("Class")
             ->outIs('CLASS')
             ->analyzerIsNot('self')
             ->savePropertyAs('code', 'name')
             ->raw('filter{ g.idx("atoms")[["atom":"String"]].has("code", name).any()}');
        $this->prepareQuery();
        
    }
}

?>
