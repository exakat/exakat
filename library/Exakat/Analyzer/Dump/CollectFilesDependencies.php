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

class CollectFilesDependencies extends AnalyzerTable {
    protected $analyzerName = 'filesDependencies';

    protected $analyzerTable = 'filesDependencies';

    protected $analyzerSQLTable = <<<'SQL'
CREATE TABLE filesDependencies ( id INTEGER PRIMARY KEY AUTOINCREMENT,
                                 including STRING,
                                 included STRING,
                                 type STRING
                                )
SQL;


    public function analyze(): void {
        // Direct inclusion
        $this->atomIs('Include', self::WITHOUT_CONSTANTS)
             ->outIs('ARGUMENT')
             ->outIsIE('CODE')
             ->_as('include')
             ->goToInstruction('File')
             ->_as('file')
             ->_as('type')
             ->select(array('file'    => 'fullcode',
                            'include' => 'fullcode',
                            'type'    => 'include'
                            ));
        $this->prepareQuery();

        // Finding extends and implements
        $this->atomIs(array('Class', 'Interface'), self::WITHOUT_CONSTANTS)
             ->goToInstruction('File')
             ->savePropertyAs('fullcode', 'calling')
             ->back('first')

             ->raw('outE().hasLabel("EXTENDS", "IMPLEMENTS").sideEffect{ type = it.get().label().toLowerCase(); }.inV()')
             ->inIs('DEFINITION')
             ->atomIs(array('Class', 'Interface'), self::WITHOUT_CONSTANTS)

             ->goToInstruction('File')
             ->savePropertyAs('fullcode', 'called')

             ->raw('map{ ["file":calling, "include":called, "type":type]; }');
        $this->prepareQuery();

        // Finding extends for interfaces
        $this->atomIs('Interface', self::WITHOUT_CONSTANTS)
             ->_as('classe')
             ->_as('type')
             ->raw(<<<'GREMLIN'
repeat( __.inE().not(hasLabel("DEFINITION")).outV() ).until(hasLabel("File"))
GREMLIN
)
             ->_as('file')
             ->raw(<<<'GREMLIN'
select("classe").out("EXTENDS")
.repeat( __.inE().not(hasLabel("DEFINITION")).outV() ).until(hasLabel("File"))
GREMLIN
)
             ->_as('include')
             ->select(array('file'    => 'fullcode',
                            'include' => 'fullcode',
                            'type'    => 'extends'
                            ));
        $this->prepareQuery();

        // Finding typehint
        $this->atomIs(self::FUNCTIONS_ALL, self::WITHOUT_CONSTANTS)
             ->outIs('ARGUMENT')
             ->outIs('TYPEHINT')
             ->fullnspathIsNot(array('\\int', '\\\float', '\\object', '\\boolean', '\\string', '\\array', '\\callable', '\\iterable', '\\void'))
             ->inIs('DEFINITION')
             ->goToInstruction('File')
             ->_as('include')

             ->back('first')
             ->_as('type')
             ->goToInstruction('File')
             ->_as('file')
             ->select(array('file'    => 'fullcode',
                            'include' => 'fullcode',
                            'type'    => 'use'
                            ));
        $this->prepareQuery();

        // Return Typehints
        $this->atomIs(self::FUNCTIONS_ALL, self::WITHOUT_CONSTANTS)
             ->outIs('RETURNTYPE')
             ->fullnspathIsNot(array('\\int', '\\\float', '\\object', '\\boolean', '\\string', '\\array', '\\callable', '\\iterable', '\\void'))
             ->inIs('DEFINITION')
             ->goToInstruction('File')
             ->_as('include')

             ->back('first')
             ->_as('type')
             ->goToInstruction('File')
             ->_as('file')
             ->select(array('file'    => 'fullcode',
                            'include' => 'fullcode',
                            'type'    => 'use'
                            ));
        $this->prepareQuery();

        // Finding trait use
        $this->atomIs('Usetrait', self::WITHOUT_CONSTANTS)
             ->outIs('USE')
             ->_as('use')
             ->goToFile()
             ->_as('file')
             ->_as('type')
             ->back('use')
             ->inIs('DEFINITION')
             ->goToFile()
             ->_as('include')
             ->select(array('file'    => 'fullcode',
                            'include' => 'fullcode',
                            'type'    => 'use',
                            ));
        $this->prepareQuery();

        // Functioncall()
        $this->atomIs('Functioncall', self::WITHOUT_CONSTANTS)
             ->_as('functioncall')
             ->goToFile()
             ->_as('file')
             ->_as('type')
             ->back('functioncall')
             ->inIs('DEFINITION')
             ->goToFile()
             ->_as('include')
             ->select(array('file'    => 'fullcode',
                            'include' => 'fullcode',
                            'type'    => 'use'
                            ));
        $this->prepareQuery();

        // constants
        $this->atomIs('Identifier', self::WITHOUT_CONSTANTS)
             ->hasNoIn(array('NAME', 'CLASS', 'MEMBER', 'AS', 'CONSTANT', 'TYPEHINT', 'EXTENDS', 'USE', 'IMPLEMENTS', 'INDEX'))
             ->_as('constant')
             ->goToFile()
             ->_as('file')
             ->_as('type')
             ->back('constant')
             ->inIs('DEFINITION')
             ->goToFile()
             ->_as('include')
             ->select(array('file'    => 'fullcode',
                            'include' => 'fullcode',
                            'type'    => 'use'
                            ));
        $this->prepareQuery();

        // New
        $this->atomIs(array('New', 'Clone'), self::WITHOUT_CONSTANTS)
             ->outIs(array('NEW', 'CLONE'))
             ->_as('new')
             ->goToFile()
             ->_as('file')
             ->_as('type')
             ->back('new')
             ->inIs('DEFINITION')
             ->goToFile()
             ->_as('include')
             ->select(array('file'    => 'fullcode',
                            'include' => 'fullcode',
                            'type'    => 'use'
                            ));
        $this->prepareQuery();

        // static calls (property, constant, method)
        $this->atomIs(array('Staticconstant', 'Staticmethodcall', 'Staticproperty'), self::WITHOUT_CONSTANTS)
             ->outIs('CLASS')
             ->_as('call')
             ->goToFile()
             ->_as('file')
             ->_as('type')
             ->back('call')
             ->inIs('DEFINITION')
             ->goToFile()
             ->_as('include')
             ->select(array('file'    => 'fullcode',
                            'include' => 'fullcode',
                            'type'    => 'use'
                            ));
        $this->prepareQuery();

        // Skipping normal method/property call : They actually depends on new
        // Magic methods : todo!
        // instanceof ?
        }
}

?>
