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


namespace Analyzer\Functions;

use Analyzer;

class UselessReturn extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Function')
             ->hasClass()
             ->outIs('NAME')
             ->code(array('__constructor', '__destructor', '__set', '__clone', '__unset', '__wakeup'))
             ->inIs('NAME')
             ->raw("filter{ it.out('BLOCK').out.loop(1){it.object.atom != 'Function'}{it.object.atom == 'Return'}.filter{it.out('RETURN').filter{it.atom in ['Void', 'Null']}.any() == false}.count() > 0}")
             ->back('first');
        $this->prepareQuery();

// @todo : spot such functions
//Also `__autoload`, methods used for autoloading and methods registered for shutdown, have no need to return anything. 

    }
}

?>
