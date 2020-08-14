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

namespace Exakat\Analyzer\Dump;

class ParameterArgumentsLinks extends AnalyzerArrayHashResults {
    protected $analyzerName = 'Parameter Arguments';

    public function analyze(): void {
        $this->analyzerValues = array();

        // Total parameter usage
        $this->atomIs('Parameter')
             ->goToParameterUsage()
             ->count();
        $total = $this->rawQuery()->toInt();
        $this->analyzerValues[] = array('total', $total);

        // identical parameter usage
        $this->atomIs('Parameter')
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->goToParameterUsage()
             ->atomIs('Variable')
             ->samePropertyAs('code', 'name')
             ->count();
        $identical = $this->rawQuery()->toInt();
        $this->analyzerValues[] = array('identical', $identical);

        // different variable parameter usage
        $this->atomIs('Parameter')
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->goToParameterUsage()

             ->atomIs('Variable')
             ->notSamePropertyAs('code', 'name')
             ->count();
        $different = $this->rawQuery()->toInt();
        $this->analyzerValues[] = array('different', $different);

        // expression parameter usage
        $this->atomIs('Parameter')
             ->goToParameterUsage()
             ->atomIsNot(array('Variable', 'Array', 'Member', 'Staticproperty'))
             ->count();
        $build = $this->rawQuery()->toInt();
        $this->analyzerValues[] = array('expression', $build);

        // constant parameter usage
        $this->atomIs('Parameter')
             ->goToParameterUsage()
             ->is('constant', true)
             ->count();
        $constant = $this->rawQuery()->toInt();
        $this->analyzerValues[] = array('constant', $constant);

        $this->prepareQuery();
    }
}

?>
