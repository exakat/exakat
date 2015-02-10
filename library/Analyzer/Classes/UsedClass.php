<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

    public function analyze() {
        // class used in a New
        $this->atomIs('Class')
             ->savePropertyAs('fullnspath', 'classdns')
             ->raw('filter{ g.idx("atoms")[["atom":"New"]].out("NEW").hasNot("fullnspath", null).has("fullnspath", classdns).any()}');
        $this->prepareQuery();
        
        // classed used in a extends
        $this->atomIs('Class')
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'classdns')
             ->raw('filter{ g.idx("atoms")[["atom":"Class"]].out("EXTENDS").has("fullnspath", classdns).any()}');
        $this->prepareQuery();

        // class used in an implements
        $this->atomIs('Class')
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'classdns')
             ->raw('filter{ g.idx("atoms")[["atom":"Class"]].out("IMPLEMENTS").has("fullnspath", classdns).any()}');
        $this->prepareQuery();

        // class used in a staticmethodcall
        $this->atomIs('Class')
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'classdns')
             ->raw('filter{ g.idx("atoms")[["atom":"Staticmethodcall"]].out("CLASS").has("fullnspath", classdns).any()}');
        $this->prepareQuery();

        // class used in static property
        $this->atomIs('Class')
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'classdns')
             ->raw('filter{ g.idx("atoms")[["atom":"Staticproperty"]].out("CLASS").has("fullnspath", classdns).any()}');
        $this->prepareQuery();

        // class used in static constant
        $this->atomIs('Class')
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'classdns')
             ->raw('filter{ g.idx("atoms")[["atom":"Staticconstant"]].out("CLASS").has("fullnspath", classdns).any()}');
        $this->prepareQuery();

        // class use in a instanceof
        $this->atomIs('Class')
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'classdns')
             ->raw('filter{ g.idx("atoms")[["atom":"Instanceof"]].out("CLASS").has("fullnspath", classdns).any()}');
        $this->prepareQuery();

        // class used in a typehint
        $this->atomIs('Class')
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'classdns')
             ->raw('filter{ g.idx("atoms")[["atom":"Typehint"]].out("CLASS").has("fullnspath", classdns).any()}');
        $this->prepareQuery();

        // class used in a Use
        $this->atomIs('Class')
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'classdns')
             ->raw('filter{ g.idx("atoms")[["atom":"Use"]].out("USE").has("originpath", classdns).any()}');
        $this->prepareQuery();

        // class used in a String (full string only)
        $this->atomIs('Class')
             ->outIs('CLASS')
             ->analyzerIsNot('self')
             ->savePropertyAs('code', 'name')
             ->raw('filter{ g.idx("atoms")[["atom":"String"]].has("code", name).any()}');
        $this->prepareQuery();
    }
}

?>
