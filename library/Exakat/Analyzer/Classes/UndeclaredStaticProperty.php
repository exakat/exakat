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

class UndeclaredStaticProperty extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/OverwrittenProperties',
                    );
    }

    public function analyze(): void {
        // class a { public $a = 1;}
        // a::$a
        $this->atomIs('Staticproperty')
             ->inIs('DEFINITION')
             ->atomIs('Virtualproperty')
             ->hasOut('OVERWRITE')
             ->not(
                 $this->side()
                      ->outIs('OVERWRITE')
                      ->atomIs('Propertydefinition')
                      ->inIs('PPP')
                      ->is('static', true)
             )
             ->back('first');
        $this->prepareQuery();

        // class a { public $a = 1;}
        // a::$a
        $this->atomIs('Staticproperty')
             ->inIs('DEFINITION')
             ->atomIs('Propertydefinition')
             ->inIs('PPP')
             ->isNot('static', true)
             ->back('first');
        $this->prepareQuery();

        // class a { static public $a = 1;}
        // $a->$a
        $this->atomIs('Member')
             ->inIs('DEFINITION')
             ->atomIs('Propertydefinition')
             ->inIs('PPP')
             ->is('static', true)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Member')
             ->inIs('DEFINITION')
             ->atomIs('Virtualproperty')
             ->hasOut('OVERWRITE')
             ->not(
                 $this->side()
                      ->outIs('OVERWRITE')
                      ->atomIs('Propertydefinition')
                      ->inIs('PPP')
                      ->isNot('static', true)
             )
             ->back('first');
        $this->prepareQuery();
    }
}

?>
