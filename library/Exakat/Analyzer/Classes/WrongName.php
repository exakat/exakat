<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class WrongName extends Analyzer {
    public function analyze() {
        // class x { function __constructor() {}; fucntion __something__(); }
        $this->atomIs('Method')
             ->outIs('NAME')
             ->codeIsNot(array('__construct', '__destruct', '__call',  '__callStatic',  '__get',  '__set',  '__isset',  '__unset',  '__sleep',  '__wakeup',  '__tostring',  '__invoke',  '__set_state', '__clone', '__debuginfo'))
             ->regexIs('code', '/^__.*/')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
