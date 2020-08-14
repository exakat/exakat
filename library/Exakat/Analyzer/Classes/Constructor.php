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


namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;
use Exakat\Data\Dictionary;

class Constructor extends Analyzer {
    public function analyze(): void {
        $construct = $this->dictCode->translate(array('__construct'), Dictionary::CASE_INSENSITIVE);

        if (empty($construct)) {
            return;
        }

        // __construct is the main constructor of the class
        $this->atomIs('Magicmethod')
             ->hasClass()
             ->outIs('NAME')
             ->codeIs($construct, self::NO_TRANSLATE)
             ->back('first');
        $this->prepareQuery();

        // if no __construct(), then default back on the method with the class name
        $this->atomIs('Class')
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->not(
                $this->side()
                     ->outIs('MAGICMETHOD')
                     ->atomIs('Magicmethod')
                     ->outIs('NAME')
                     ->codeIs('__construct', self::TRANSLATE, self::CASE_INSENSITIVE)
             )
             ->outIs('METHOD')
             ->atomIs('Method')
             ->as('constructor')
             ->outIs('NAME')
             ->samePropertyAs('code', 'name')
             ->back('constructor');
        $this->prepareQuery();
    }
}

?>
