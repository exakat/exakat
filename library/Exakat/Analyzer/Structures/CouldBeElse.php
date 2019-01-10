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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class CouldBeElse extends Analyzer {
    public function analyze() {
        // if ($a) {}; if (!$a) {}
        $this->atomIs('Ifthen')
             ->outIs('CONDITION')
             ->atomIsNot('Not')
             ->savePropertyAs('fullcode', 'condition')
             ->back('first')
             ->nextSibling()
             ->atomIs('Ifthen')
             ->outIs('CONDITION')
             ->atomIs('Not')
             ->outIs('NOT')
             ->samePropertyAs('fullcode', 'condition')
             ->back('first');
        $this->prepareQuery();

        // if (!$a) {}; if ($a) {}
        $this->atomIs('Ifthen')
             ->outIs('CONDITION')
             ->atomIs('Not')
             ->outIs('NOT')
             ->savePropertyAs('fullcode', 'condition')
             ->back('first')
             ->nextSibling()
             ->atomIs('Ifthen')
             ->outIs('CONDITION')
             ->atomIsNot('Not')
             ->samePropertyAs('fullcode', 'condition')
             ->back('first');
        $this->prepareQuery();

        // if ($a == 1) {}; if ($a != 1) {}
            $normalize = '.replaceAll("!=", "==")
                          .replaceAll("<", ">") ';
        $this->atomIs('Ifthen')
             ->outIs('CONDITION')
             ->atomIs('Comparison')
             ->savePropertyAs('fullcode', 'condition')
             ->back('first')
             ->nextSibling()
             ->atomIs('Ifthen')
             ->outIs('CONDITION')
             ->atomIs('Comparison')
             ->filter('it.get().value("fullcode").toString()'.$normalize.' == condition'.$normalize)
             ->back('first')
             ;
        $this->prepareQuery();
    }
}

?>
