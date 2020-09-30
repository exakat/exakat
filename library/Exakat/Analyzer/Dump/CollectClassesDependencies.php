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

namespace Exakat\Analyzer\Dump;

#use Exakat\Analyzer\Analyzer;

class CollectClassesDependencies extends AnalyzerTable {
    protected $analyzerName = 'classesDependencies';

    protected $analyzerTable = 'classesDependencies';

    protected $analyzerSQLTable = <<<'SQL'
CREATE TABLE classesDependencies ( id INTEGER PRIMARY KEY AUTOINCREMENT,
                                   including STRING,
                                   including_name STRING,
                                   including_type STRING,
                                   included STRING,
                                   included_name STRING,
                                   included_type STRING,
                                   type STRING
                                  )
SQL;


    public function analyze(): void {

        // Finding extends and implements
        $this->atomIs(array('Class', 'Interface'), self::WITHOUT_CONSTANTS)
             ->raw('sideEffect{ calling_type = it.get().label().toLowerCase(); }')
             ->outIs('NAME')
             ->savePropertyAs('fullcode', 'calling_name')
             ->back('first')
             ->savePropertyAs('fullnspath', 'calling')

             ->raw('outE().hasLabel("EXTENDS", "IMPLEMENTS").sideEffect{ type = it.get().label().toLowerCase(); }.inV()')
             ->inIs('DEFINITION')
             ->atomIs(array('Class', 'Interface'), self::WITHOUT_CONSTANTS)
             ->raw('sideEffect{ called_type = it.get().label().toLowerCase(); }')
             ->outIs('NAME')
             ->savePropertyAs('fullcode', 'called_name')

             ->savePropertyAs('fullnspath', 'called')

             ->raw(<<<'GREMLIN'
map{ ["calling":calling, 
      "calling_name":calling_name, 
      "calling_type":calling_type, 
      "type":type, 
      "called":called, 
      "called_name":called_name, 
      "called_type":called_type, 
           ]; }
GREMLIN
);
        $this->prepareQuery();

        // Finding extends for interfaces
        $this->atomIs('Interface', self::WITHOUT_CONSTANTS)
              ->savePropertyAs('fullnspath', 'calling')
              ->outIs('NAME')
              ->savePropertyAs('fullcode', 'calling_name')
              ->back('first')

              ->outIs('EXTENDS')
              ->inIs('DEFINITION')
              ->atomIs('Interface', self::WITHOUT_CONSTANTS)

              ->savePropertyAs('fullnspath', 'called')
              ->outIs('NAME')
              ->savePropertyAs('fullcode', 'called_name')

              ->raw(<<<'GREMLIN'
map{ ["calling":calling, 
      "calling_name":calling_name, 
      "calling_type":"interface", 
      "type":"extends", 
      "called":called, 
      "called_name":called_name, 
      "called_type":"interface", 
           ]; }
GREMLIN
);
        $this->prepareQuery();

        // Finding typehint
        $this->atomIs('Parameter', self::WITHOUT_CONSTANTS)
             ->outIs('TYPEHINT')
             ->fullnspathIsNot(array('\\int', '\\\float', '\\object', '\\boolean', '\\string', '\\array', '\\callable', '\\iterable', '\\void'))
             ->inIs('DEFINITION')
             ->raw('sideEffect{ called_type = it.get().label().toLowerCase(); }')
             ->savePropertyAs('fullnspath', 'called')
             ->outIs('NAME')
             ->savePropertyAs('fullcode', 'called_name')

             ->back('first')
             ->goToInstruction(self::CIT)

             ->savePropertyAs('fullnspath', 'calling')
             ->raw('sideEffect{ calling_type = it.get().label().toLowerCase(); }')
             ->outIs('NAME')
             ->savePropertyAs('fullcode', 'calling_name')

             ->raw(<<<'GREMLIN'
map{ ["calling":calling, 
      "calling_name":calling_name, 
      "calling_type":calling_type, 
      "type":"typehint", 
      "called":called, 
      "called_name":called_name, 
      "called_type":called_type, 
           ]; }
GREMLIN
);
        $this->prepareQuery();

        $this->atomIs(array('Method', 'Magicmethod'), self::WITHOUT_CONSTANTS)
             ->outIs('RETURNTYPE')
             ->fullnspathIsNot(array('\\int', '\\\float', '\\object', '\\boolean', '\\string', '\\array', '\\callable', '\\iterable', '\\void'))
             ->inIs('DEFINITION')
             ->atomIs(array('Class', 'Interface'), self::WITHOUT_CONSTANTS)
             ->raw('sideEffect{ called_type = it.get().label().toLowerCase(); }')
             ->savePropertyAs('fullnspath', 'called')
             ->outIs('NAME')
             ->savePropertyAs('fullcode', 'called_name')
             ->back('first')

             ->goToInstruction(self::CIT)

             ->savePropertyAs('fullnspath', 'calling')
             ->raw('sideEffect{ calling_type = it.get().label().toLowerCase(); }')
             ->outIs('NAME')
             ->savePropertyAs('fullcode', 'calling_name')

             ->raw(<<<'GREMLIN'
map{ ["calling":calling, 
      "calling_name":calling_name, 
      "calling_type":calling_type, 
      "type":"typehint", 
      "called":called, 
      "called_name":called_name, 
      "called_type":called_type, 
           ]; }
GREMLIN
);
        $this->prepareQuery();

        // Finding trait use
        $this->atomIs(array('Class', 'Trait'), self::WITHOUT_CONSTANTS)
             ->savePropertyAs('fullnspath', 'calling')
             ->raw('sideEffect{ calling_type = it.get().label().toLowerCase(); }')
             ->outIs('NAME')
             ->savePropertyAs('fullcode', 'calling_name')
             ->back('first')

             ->outIs('USE')
             ->outIs('USE')

             ->savePropertyAs('fullnspath', 'called')
             ->outIs('NAME')
             ->savePropertyAs('fullcode', 'called_name')
             ->raw(<<<'GREMLIN'
map{ ["calling":calling, 
      "calling_name":calling_name, 
      "calling_type":calling_type, 
      "type":"use", 
      "called":called, 
      "called_name":called_name, 
      "called_type":"trait", 
           ]; }
GREMLIN
);
        $this->prepareQuery();

        // New
        $this->atomIs('New', self::WITHOUT_CONSTANTS)
             ->outIs('NEW')
             ->inIs('DEFINITION')
             ->atomIs(array('Class'), self::WITHOUT_CONSTANTS)
             ->savePropertyAs('fullnspath', 'called')
             ->outIs('NAME')
             ->savePropertyAs('fullcode', 'called_name')

             ->back('first')
             ->goToInstruction('Class') // no trait?

             ->savePropertyAs('fullnspath', 'calling')
             ->raw('sideEffect{ calling_type = it.get().label().toLowerCase(); }')
             ->outIs('NAME')
             ->savePropertyAs('fullcode', 'calling_name')

             ->raw(<<<'GREMLIN'
map{ ["calling":calling, 
      "calling_name":calling_name, 
      "calling_type":calling_type, 
      "type":"new", 
      "called":called, 
      "called_name":called_name, 
      "called_type":"class", 
           ]; }
GREMLIN
);
        $this->prepareQuery();

        // Clone
        $this->atomIs('Clone', self::WITHOUT_CONSTANTS)
             ->goToInstruction(self::CIT)
             ->savePropertyAs('fullnspath', 'calling')
             ->raw('sideEffect{ calling_type = it.get().label().toLowerCase(); }')
             ->outIs('NAME')
             ->savePropertyAs('fullcode', 'calling_name')
             ->back('first')

             ->outIs('CLONE')
             ->inIs('DEFINITION')
             ->atomIs(array('Class'), self::WITHOUT_CONSTANTS)
             ->savePropertyAs('fullnspath', 'called')
             ->raw('sideEffect{ called_type = it.get().label().toLowerCase(); }')
             ->outIs('NAME')
             ->savePropertyAs('fullcode', 'called_name')

             ->raw(<<<'GREMLIN'
map{ ["calling":calling, 
      "calling_name":calling_name, 
      "calling_type":calling_type, 
      "type":"clone", 
      "called":called, 
      "called_name":called_name, 
      "called_type":called_type, 
           ]; }
GREMLIN
);
        $this->prepareQuery();

        // static calls (property, constant, method)
        $this->atomIs(array('Staticconstant', 'Staticmethodcall', 'Staticproperty'), self::WITHOUT_CONSTANTS)
             ->raw('sideEffect{ type = it.get().label().toLowerCase(); }')

             ->goToInstruction(self::CIT)
             ->savePropertyAs('fullnspath', 'calling')
             ->raw('sideEffect{ calling_type = it.get().label().toLowerCase(); }')
             ->outIs('NAME')
             ->savePropertyAs('fullcode', 'calling_name')
             ->back('first')

             ->outIs('CLASS')
             ->inIs('DEFINITION')
             ->atomIs(array('Class', 'Trait'), self::WITHOUT_CONSTANTS)
             ->savePropertyAs('fullnspath', 'called')
             ->raw('sideEffect{ called_type = it.get().label().toLowerCase(); }')
             ->outIs('NAME')
             ->savePropertyAs('fullcode', 'called_name')

             ->raw(<<<'GREMLIN'
map{ ["calling":calling, 
      "calling_name":calling_name, 
      "calling_type":calling_type, 
      "type":type, 
      "called":called, 
      "called_name":called_name, 
      "called_type":called_type, 
           ]; }
GREMLIN
);
        $this->prepareQuery();

        // Skipping normal method/property call : They actually depends on new
        // Magic methods : todo!
        // instanceof ?
    }
}

?>
