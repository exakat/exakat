<?php declare(strict_types = 1);
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

class EvalUsage extends Analyzer {
    public function dependsOn(): array {
        return array('Constants/ConstantUsage',
                    );
    }

    public function analyze(): void {
        // eval($a . ' -s la');
        // OK for constants
        $this->atomFunctionIs('\\create_function')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIsNot(array('Identifier', 'Nsname'))
             ->analyzerIsNot('Constants/ConstantUsage')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Eval')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIsNot(array('Identifier', 'Nsname'))
             ->analyzerIsNot('Constants/ConstantUsage')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
