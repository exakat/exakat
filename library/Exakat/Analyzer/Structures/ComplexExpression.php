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

class ComplexExpression extends Analyzer {
    protected $complexExpressionThreshold = 30;

    public function analyze(): void {
        // if (Condition);
        $this->atomIs(array('Ifthen', 'Dowhile', 'While'))
             ->outIs('CONDITION')
             ->IsComplexExpression($this->complexExpressionThreshold)
             ->back('first');
        $this->prepareQuery();

        // foreach($source...)
        $this->atomIs('Foreach')
             ->outIs('SOURCE')
             ->IsComplexExpression($this->complexExpressionThreshold)
             ->back('first');
        $this->prepareQuery();

        // for($i = 3; ; )
        $this->atomIs('For')
             ->outIs(array('INCREMENT', 'INIT', 'FINAL'))
             ->IsComplexExpression($this->complexExpressionThreshold)
             ->back('first');
        $this->prepareQuery();

        // $a = expression;
        $this->atomIs('Assignation')
             ->outIs('RIGHT')
             ->IsComplexExpression($this->complexExpressionThreshold)
             ->back('first');
        $this->prepareQuery();

        // foo($a)
        $this->atomIs('Functioncall')
             ->outIs('ARGUMENT')
             ->IsComplexExpression($this->complexExpressionThreshold)
             ->back('first');
        $this->prepareQuery();
    }
}

?>