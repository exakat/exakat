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


namespace Analyzer\Structures;

use Analyzer;

class NoDirectAccess extends Analyzer\Analyzer {
    public function analyze() {
        //defined('AJXP_EXEC') or die('Access not allowed'); : Constant used!
        $this->atomIs('Logical')
             ->tokenIs(array('T_BOOLEAN_AND', 'T_BOOLEAN_OR','T_LOGICAL_AND', 'T_LOGICAL_OR'))
             // find !defined and defined
             ->atomInside('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs('T_STRING')
             ->fullnspath('\\defined')
             ->back('first')
             ->atomInside('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_EXIT', 'T_DIE'))
             ->fullnspath(array('\\die', '\\exit'))
             ->back('first');
        $this->prepareQuery();

        //if(!defined('CMS'))die/exit
        $this->atomIs('Ifthen')
             ->outIs('CONDITION')
             // find !defined and defined
             ->atomInside('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs('T_STRING')
             ->fullnspath('\\defined')
             ->back('first')
             ->outIs('THEN')
             ->atomInside('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_EXIT', 'T_DIE'))
             ->fullnspath(array('\\die', '\\exit'))
             ->back('first');
        $this->prepareQuery();

        //if (defined('_ECRIRE_INC_VERSION')) return;
        $this->atomIs('Ifthen')
             ->outIs('CONDITION')
             // find !defined and defined
             ->atomInside('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs('T_STRING')
             ->fullnspath('\\defined')
             ->back('first')
             ->outIs('THEN')
             ->atomInside('Return')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
