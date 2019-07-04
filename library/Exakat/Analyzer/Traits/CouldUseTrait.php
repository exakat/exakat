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

namespace Exakat\Analyzer\Traits;

use Exakat\Analyzer\Analyzer;

class CouldUseTrait extends Analyzer {
    // trait i { function i(); }
    // class x { function i() {}}
    // class x could use trait i but it was forgotten

    public function analyze() {
        // Custom traits
        $this->atomIs('Trait')
             ->_as('name')
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->_as(array('methodCount', 'static', 'ctype1'))
             ->outIs('NAME')
             ->_as('method')
             ->select(array('name'        => 'fullnspath',
                            'method'      => 'lccode',
                            'methodCount' => 'count',
                            'static'      => 'fullcode',
                            'ctype1'      => 'ctype1',
                            ));
        $res = $this->rawQuery();

        $traits = array();
        $methodNames = array();
        foreach($res->toArray() as $row) {
            $row['static'] = preg_match('/^.*static.*function /i', $row['static']) === 0 ? '' : 'static';
            array_collect_by($traits, $row['name'], "$row[method]-$row[methodCount]-$row[static]-$row[ctype1]");
            $methodNames[$row['method']] = 1;
        }

/*
        $phpTraits = $this->loadJson('php_traits_methods.json');
        foreach($phpInterfaces as $interface => $methods) {
            $translations = $this->dictCode->translate(array_column($methods, 'name'));
            if (count($methods) !== count($translations)) {
                continue;
            }
            
            // translations are in the same order than original
            foreach($methods as $id => $method) {
                $interfaces[$interface][] = $translations[$id] . "-$method->count-";
                $methodNames[$translations[$id]] = 1;
            }
        }
*/
        $methodNames = array_keys($methodNames);

        $this->atomIs(self::$CLASSES_ALL)
             ->filter(
                $this->side()
                     ->outIs(array('METHOD', 'MAGICMETHOD'))
                     ->isNot('visibility', array('private', 'protected'))
                     ->outIs('NAME')
                     ->is('lccode', $methodNames)
             )
             ->raw('sideEffect{ x = []; }')
             // Collect methods names with argument count
             // can one implement an interface, but with wrong argument counts ?
             ->raw(<<<'GREMLIN'
where( 
    __.out("METHOD", "MAGICMETHOD")
      .sideEffect{ 
        if (it.get().properties("static").any()) { 
            s = 'static';
        } else {
            s = '';
        }
        x.add(it.get().vertices(OUT, "NAME").next().value("lccode") + "-" + it.get().value("count") + "-" + s + "-" + it.get().value("ctype1")) ; 
       }
      .fold() 
)
GREMLIN
)
             ->raw('sideEffect{ php_traits = *** }', $traits)
             ->raw(<<<'GREMLIN'
filter{
    a = false;
    php_traits.each{ n, e ->
        if (x.intersect(e) == e) {
            a = true;
            fnp = n;
        }
    }
    
    a;
}

GREMLIN
)

                ->collectTraits('traits')
                ->filter('!(fnp in traits) ')
                ->back('first');
        $this->prepareQuery();
    }
}

?>
