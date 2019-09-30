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

namespace Exakat\Analyzer\Type;

use Exakat\Analyzer\Analyzer;

class DuplicateLiteral extends Analyzer {
    protected $minDuplicate = 15;
    
    public function analyze() {
        // No need for boolean and null
        $this->atomIs(array('String', 'Heredoc'))
             ->hasNoIn('INDEX') // Skipping arrays $x["cbd"]
             ->hasNoParent('String', array('CONCAT'))
             ->noDelimiterIsNot(array(''))
             ->not(
                $this->side()
                     ->inIs('VALUE')
                     ->atomIs(array('Constant', 'Defineconstant'))
              )
             ->raw('groupCount("m").by("noDelimiter").cap("m").next().findAll{ it.value >= ' . $this->minDuplicate . '; }');
        $strings = $this->rawQuery();
        
        if (!empty($strings->toArray())) {
            $results = array();
            $store = array();
            foreach($strings->toArray() as $v) {
                foreach($v as $key => $value)  {
                    $results[] = $key;
                    $store[] = compact('key', 'value');
                }
            }

            $this->atomIs('String')
                 ->hasNoIn('INDEX')
                 ->noDelimiterIsNot(array(''))
                 ->not(
                    $this->side()
                         ->inIs('VALUE')
                         ->atomIs(array('Constant', 'Defineconstant'))
                  )
                 ->noDelimiterIs($results);
            $this->prepareQuery();

            $this->datastore->addRowAnalyzer($this->analyzerQuoted, $store);
        }

        $this->atomIs(array('Integer', 'Float'))
             ->hasNoIn('INDEX') // Skipping arrays $x[0]
             ->fullcodeIsNot(array(0, 1, 2, 10))  // skip some values
             ->not(
                $this->side()
                     ->inIs('VALUE')
                     ->atomIs(array('Constant', 'Defineconstant'))
              )
             ->raw('groupCount("m").by("fullcode").cap("m").next().findAll{ it.value >= ' . $this->minDuplicate . '; }');
        $integers = $this->rawQuery();
        
        if (!empty($integers->toArray())) {
            $results = array();
            $store = array();
            foreach($integers->toArray() as $v) {
                foreach($v as $key => $value)  {
                    $results[] = $key;
                    $store[] = compact('key', 'value');
                }
            }

            $this->atomIs(array('Integer', 'Float'))
                 ->hasNoIn('INDEX')
                 ->not(
                    $this->side()
                         ->inIs('VALUE')
                         ->atomIs(array('Constant', 'Defineconstant'))
                  )
                 ->fullcodeIs($results);
            $this->prepareQuery();

            $this->datastore->addRowAnalyzer($this->analyzerQuoted, $store);
        }
        
        // could we do this for array?
    }
}

?>
