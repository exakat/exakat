<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/


namespace Analyzer\Classes;

use Analyzer;

class UsedClass extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Functions/MarkCallable');
    }
    
    public function analyze() {
        $new = $this->query(<<<GREMLIN
g.idx("atoms")[["atom":"New"]].out("NEW").hasNot("fullnspath", null).fullnspath.unique()
GREMLIN
);
        // class used in a New
        if (!empty($new)) {
            $this->atomIs('Class')
                 ->savePropertyAs('fullnspath', 'classdns')
                 ->fullnspath($new);
            $this->prepareQuery();
        }
        
        // classed used in a extends
        $extends = $this->query(<<<GREMLIN
g.idx("atoms")[["atom":"Class"]].out("EXTENDS").fullnspath.unique()
GREMLIN
);
        $this->atomIs('Class')
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'classdns')
             ->fullnspath($extends);
        $this->prepareQuery();

        // class used in an implements
        $implements = $this->query(<<<GREMLIN
g.idx("atoms")[["atom":"Class"]].out("IMPLEMENTS").fullnspath.unique()
GREMLIN
);
        $this->atomIs('Class')
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'classdns')
             ->fullnspath($implements);
        $this->prepareQuery();

        // class used in static property
        $staticproperties = $this->query(<<<GREMLIN
g.idx("atoms")[["atom":"Staticproperty"]].out("CLASS").filter{it.token in ['T_NS_SEPARATOR', 'T_STRING']}.fullnspath.unique()
GREMLIN
);
        $this->atomIs('Class')
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'classdns')
             ->fullnspath($staticproperties);
        $this->prepareQuery();

        // class used in static constant
        $staticconstants = $this->query(<<<GREMLIN
g.idx("atoms")[["atom":"Staticconstant"]].out("CLASS").filter{it.token in ['T_NS_SEPARATOR', 'T_STRING']}.fullnspath.unique()
GREMLIN
);
        $this->atomIs('Class')
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'classdns')
             ->fullnspath($staticconstants);
        $this->prepareQuery();

        // class used in a staticmethodcall
        $staticmethodcalls = $this->query(<<<GREMLIN
g.idx("atoms")[["atom":"Staticmethodcall"]].out("CLASS").filter{it.token in ['T_NS_SEPARATOR', 'T_STRING']}.fullnspath.unique()
GREMLIN
);
        $this->atomIs('Class')
             ->analyzerIsNot('self')
             ->fullnspath($staticmethodcalls);
        $this->prepareQuery();

        // class use in a instanceof
        $instanceofs = $this->query(<<<GREMLIN
g.idx("atoms")[["atom":"Instanceof"]].out("CLASS").filter{it.token in ['T_NS_SEPARATOR', 'T_STRING']}.fullnspath.unique()
GREMLIN
);
        $this->atomIs('Class')
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'classdns')
             ->fullnspath($instanceofs);
        $this->prepareQuery();

        // class used in a typehint
        $typehints = $this->query(<<<GREMLIN
g.idx("atoms")[["atom":"Typehint"]].out("CLASS").fullnspath.unique()
GREMLIN
);
        $this->atomIs('Class')
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'classdns')
             ->fullnspath($typehints);
        $this->prepareQuery();

        // class used in a Use
        $uses = $this->query(<<<GREMLIN
g.idx("atoms")[["atom":"Use"]].out("Use").originpath.unique()
GREMLIN
);
        $this->atomIs('Class')
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'classdns')
             ->fullnspath($uses);
        $this->prepareQuery();

        // class used in a String (full string only)
        $strings = $this->query(<<<GREMLIN
g.idx("atoms")[["atom":"String"]].filter{ it.in("ANALYZED").has("code", "Analyzer\\\\Functions\\\\MarkCallable").any()}
                                 .noDelimiter.unique()
GREMLIN
);
        $this->atomIs('Class')
             ->analyzerIsNot('self')
             ->outIs('NAME')
             ->code($strings)
             ->back('first');
        $this->prepareQuery();

        // class used in an array
        $arrays = $this->query(<<<GREMLIN
g.idx("atoms")[["atom":"Functioncall"]].filter{ it.token in ["T_OPEN_BRACKET", "T_ARRAY"]}
                                       .out("ARGUMENTS")
                                       .filter{ it.in("ANALYZED").has("code", "Analyzer\\\\Functions\\\\MarkCallable").any()}
                                       .out("ARGUMENT")
                                       .has("rank", 0)
                                       .noDelimiter.unique()
GREMLIN
);
        $this->atomIs('Class')
             ->analyzerIsNot('self')
             ->outIs('NAME')
             ->code($arrays)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
