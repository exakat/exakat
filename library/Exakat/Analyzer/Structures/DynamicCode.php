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

class DynamicCode extends Analyzer {
    public function analyze(): void {

        // $$v
        $this->atomIs('Variable')
             ->outIs('NAME')
             ->tokenIsNot('T_STRING')
             ->back('first');
        $this->prepareQuery();

        // $o->$p
        // $o->$p();
        //$classname::methodcall();
        $this->atomIs(array('Member', 'Methodcall', 'Staticmethodcall'))
             ->outIs(array('MEMBER', 'METHOD', 'CLASS'))
             ->tokenIsNot(self::STATICCALL_TOKEN)
             ->atomIsNot(array('Self', 'Parent', 'Static'))
             ->back('first');
        $this->prepareQuery();

        //$functioncall(2,3,3);
        //new $classname(); (also done here)
        $this->atomIs(array('Functioncall', 'Newcall'))
             ->outIs('NAME')
             ->tokenIsNot(self::FUNCTIONS_TOKENS)
             ->back('first');
        $this->prepareQuery();

        // class_alias, extract and parse_url
        $this->atomFunctionIs('\\extract');
        $this->prepareQuery();

        $this->atomFunctionIs(array('\parse_str', '\mb_parse_str'))
             ->noChildWithRank('ARGUMENT', 1);
        $this->prepareQuery();

        $this->atomIs('Classalias')
             ->isNot('constant', true);
        $this->prepareQuery();
    }
}

?>
