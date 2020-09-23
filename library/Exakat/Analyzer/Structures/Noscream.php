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

class Noscream extends Analyzer {
    protected $authorizedFunctions = 'noscream_functions.json';

    public function analyze(): void {
        $list = array('Addition',
                      'Array',
                      'Arrayappend',
                      'Arrayliteral',
                      //'Assignation',  Not possible
                      'Bitshift',
                      'Boolean',
                      'Break',
                      'Cast',
                      'Clone',
                      'Closure',
                      'Coalesce',
                      'Comparison',
                      'Concatenation',
                      'Constant',
                      'Continue',
                      'Declare',
                      'Declaredefinition',
                      'Defineconstant',
                      'Echo',
                      'Empty',
                      'Eval',
                      'Exit',
                      'Function',
                      'Global',
                      'Heredoc',
                      'Identifier',
                      'Include',
                      'Instanceof',
                      'Insteadof',
                      'Integer',
                      'Isset',
                      'List',
                      //'Logical', Not possible
                      'Magicconstant',
                      'Member',
                      'Methodcall',
                      'Methodcallname',
                      'Multiplication',
                      'Name',
                      'New',
                      'Newcall',
                      'Not',
                      'Nsname',
                      'Null',
                      'Parent',
                      'Parenthesis',
                      'Phpvariable',
                      'Postplusplus',
                      'Power',
                      'Preplusplus',
                      'Print',
                      'Propertydefinition',
                      'Float',
                      'Return',
                      'Self',
                      'Shell',
                      'Sign',
                      'Static',
                      'Staticclass',
                      'Staticconstant',
                      'Staticdefinition',
                      'Staticmethodcall',
                      'Staticproperty',
                      'String',
                      'This',
                      'Throw',
                      'Unset',
                      'Variable',
                      'Yield',
                      'Yieldfrom',
                      );

        // @$s
        $this->atomIs($list)
             ->is('noscream', true);
        $this->prepareQuery();

        // @fopen($s, 'r')
        $this->atomIs('Functioncall')
             ->fullnspathIsNot(makefullnspath($this->authorizedFunctions))
             ->is('noscream', true);
        $this->prepareQuery();
    }
}

?>
