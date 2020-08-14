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

namespace Exakat\Tasks\LoadFinal;

use Exakat\Analyzer\Analyzer;

class FinishIsModified extends LoadFinal {
    protected $methods = null;

    public function __construct() {
        parent::__construct();

        $this->methods = exakat('methods');
    }

    public function run(): void {
        $variables = array('Variable',
                           'Variableobject',
                           'Variablearray',
                           'Array',
                           'Member',
                           'Staticproperty',
                           'Phpvariable',
                          );

        // No support for old style constructors
        $query = $this->newQuery('isModified with New');
        $query->atomIs('New', Analyzer::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->outIs('ARGUMENT')
              ->is('reference', true)
              ->savePropertyAs('rank', 'r')
              ->back('first')
              ->outIs('NEW')
              ->outIs('ARGUMENT')
              ->samePropertyAs('rank', 'r', Analyzer::CASE_SENSITIVE)
              ->atomIs($variables, Analyzer::WITHOUT_CONSTANTS)
              ->setProperty('isModified', true)
              ->returnCount();
        $query->prepareRawQuery();
        if ($query->canSkip()) {
            $countNew = 0;
        } else {
            $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
            $countNew = $result->toInt();
        }

        $query = $this->newQuery('isModified with function calls');
        $query->atomIs(array('Functioncall', 'Methodcall', 'Staticmethodcall'), Analyzer::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->outIs('ARGUMENT')
              ->is('reference', true)
              ->savePropertyAs('rank', 'r')
              ->back('first')
              ->outIsIE('METHOD')
              ->outIs('ARGUMENT')
              ->samePropertyAs('rank', 'r', Analyzer::CASE_INSENSITIVE)
              ->atomIs($variables, Analyzer::WITHOUT_CONSTANTS)
              ->setProperty('isModified', true)
              ->returnCount();
        $query->prepareRawQuery();
        if ($query->canSkip()) {
            $countFunction = 0;
        } else {
            $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
            $countFunction = $result->toInt();
        }

        $count = $countNew + $countFunction;
        display("Created $count isModified values");

        // Managing Appends and its descendants
        // TODO : this should be a loop
        $query = $this->newQuery('isModified with append $a[]');
        $query->atomIs('Arrayappend', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('APPEND')
              ->atomIs($variables, Analyzer::WITHOUT_CONSTANTS)
              ->setProperty('isModified', true)
              ->returnCount();
        $query->prepareRawQuery();
        if ($query->canSkip()) {
            $countAppend0 = 0;
        } else {
            $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
            $countAppend0 = $result->toInt();
        }

        $query = $this->newQuery('isModified with append $a[1][]');
        $query->atomIs('Arrayappend', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('APPEND')
              ->outIs('VARIABLE')
              ->atomIs($variables, Analyzer::WITHOUT_CONSTANTS)
              ->setProperty('isModified', true)
              ->returnCount();
        $query->prepareRawQuery();
        if ($query->canSkip()) {
            $countAppend1 = 0;
        } else {
            $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
            $countAppend1 = $result->toInt();
        }

        $query = $this->newQuery('isModified with append $a[1][2][]');
        $query->atomIs('Arrayappend', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('APPEND')
              ->outIs('VARIABLE')
              ->outIs('VARIABLE')
              ->atomIs($variables, Analyzer::WITHOUT_CONSTANTS)
              ->setProperty('isModified', true)
              ->returnCount();
        $query->prepareRawQuery();
        if ($query->canSkip()) {
            $countAppend2 = 0;
        } else {
            $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
            $countAppend2 = $result->toInt();
        }

        $count = $countAppend0 + $countAppend1 + $countAppend2;
        display("Created $count isModified values with array append");

        // Managing Unset()
        $query = $this->newQuery('isModified with unset function');
        $query->atomIs('Unset', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('ARGUMENT')
              ->atomIs('Array', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('VARIABLE')
              ->atomIs($variables, Analyzer::WITHOUT_CONSTANTS)
              ->setProperty('isModified', true)
              ->returnCount();
        $query->prepareRawQuery();
        if ($query->canSkip()) {
            $countFunction = 0;
        } else {
            $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
            $countFunction = $result->toInt();
        }

        $query = $this->newQuery('isModified with unset operator');
        $query->atomIs('Cast', Analyzer::WITHOUT_CONSTANTS)
              ->tokenIs('T_UNSET_CAST')
              ->outIs('CAST')
              ->atomIs('Array', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('VARIABLE')
              ->atomIs($variables, Analyzer::WITHOUT_CONSTANTS)
              ->setProperty('isModified', true)
              ->returnCount();
        $query->prepareRawQuery();
        if ($query->canSkip()) {
            $countOperator = 0;
        } else {
            $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
            $countOperator = $result->toInt();
        }

        $count = $countFunction + $countOperator;
        display("Created $count isModified values with unset");

        $query = $this->newQuery('isModified with list() or foreach()');
        $query->atomIs('Keyvalue', Analyzer::WITHOUT_CONSTANTS)
              ->inIs(array('VALUE', 'ARGUMENT'))
              ->atomIs(array('Foreach', 'List'), Analyzer::WITHOUT_CONSTANTS)
              ->back('first')
              ->outIs(array('INDEX', 'VALUE'))
              ->atomIs($variables, Analyzer::WITHOUT_CONSTANTS)
              ->setProperty('isModified', true)
              ->returnCount();
        $query->prepareRawQuery();
        if ($query->canSkip()) {
            $countOperator = 0;
        } else {
            $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
            $countOperator = $result->toInt();
        }

        $count = $countFunction + $countOperator;
        display("Created $count isModified values with => ");
    }
}

?>
