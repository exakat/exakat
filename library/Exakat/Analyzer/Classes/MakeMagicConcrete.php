<?php
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class MakeMagicConcrete extends Analyzer {
    protected $magicMemberUsage = 1;

    public function analyze() {
        // class x { __get($x) {}}
        // $a->bbb; $a->bbb; $a->bbb;
        $this->atomIs('Magicmethod')
             ->outIs('NAME')
             ->codeIs('__get')
             ->back('first')
             ->raw(<<<GREMLIN
sideEffect{ members = [:]; }
.where(
      __.out("DEFINITION").hasLabel("Member").out("MEMBER").has("token", "T_STRING")
      .sideEffect{ 
        m = it.get().value("fullcode");
        if (members[m] != null) {
          ++members[m]; 
        } else {
          members[m] = 1; 
        }
      }
      .fold()
)
GREMLIN
)
             ->outIs('DEFINITION')
             ->atomIs('Member')
             ->outIs('MEMBER')
             ->tokenIs('T_STRING')
             ->filter('members[it.get().value("fullcode")] > ' . $this->magicMemberUsage . ';');
        $this->prepareQuery();
    }
}

?>
