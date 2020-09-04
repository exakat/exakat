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


class CollectClassChanges extends AnalyzerTable {
    protected $analyzerName = 'classChanges';

    protected $analyzerTable = 'classChanges';

    protected $analyzerSQLTable = <<<'SQL'
CREATE TABLE classChanges (  
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    changeType   STRING,
    name         STRING,
    parentClass  STRING,
    parentValue  STRING,
    childClass   STRING,
    childValue   STRING
                    )
SQL;

    public function dependsOn(): array {
        return array('Complete/OverwrittenProperties',
                     'Complete/OverwrittenMethods',
                    );
    }


    public function analyze(): void {

        $total = 0;

        // Comparing Class constant : values, visibility

        // Class constants with different values
         $this->atomIs('Constant', self::WITHOUT_CONSTANTS)
              ->outIs('NAME')
              ->savePropertyAs('fullcode', 'name')
              ->inIs('NAME')
              ->outIs('VALUE')
              ->savePropertyAs('fullcode', 'default1')
              ->inIs('VALUE')

              ->inIs('CONST')
              ->inIs('CONST')
              ->atomIs(self::CLASSES_ALL, self::WITHOUT_CONSTANTS)

              ->savePropertyAs('fullcode', 'class1')
              ->goToAllParents(self::EXCLUDE_SELF)
              ->savePropertyAs('fullcode', 'class2') // another class

              ->outIs('CONST')
              ->outIs('CONST')

              ->outIs('NAME')
              ->samePropertyAs('fullcode', 'name', self::CASE_SENSITIVE)
              ->inIs('NAME')

              ->outIs('VALUE')
              ->notSamePropertyAs('fullcode', 'default1', self::CASE_SENSITIVE) // test
              ->savePropertyAs('fullcode', 'default2') // collect

              ->raw(<<<'GREMLIN'
map{["type": 'Constant Value',
      "name":name,
      "parent":class2,
      "parentValue":name + " = " + default2,
      "class":class1,
      "classValue":name + " = " + default1];
}
GREMLIN
);
        $this->prepareQuery();

        // Class constants with different visibility
         $this->atomIs('Constant', self::WITHOUT_CONSTANTS)
              ->outIs('NAME')
              ->savePropertyAs('fullcode', 'name')
              ->inIs('NAME')

              ->inIs('CONST')
              ->savePropertyAs('visibility', 'visibility1')
              ->inIs('CONST')
              ->atomIs(self::CLASSES_ALL, self::WITHOUT_CONSTANTS)

              ->savePropertyAs('fullcode', 'class1')
              ->goToAllParents(self::EXCLUDE_SELF)
              ->savePropertyAs('fullcode', 'class2') // another class

              ->outIs('CONST')
              ->notSamePropertyAs('visibility', 'visibility1', self::CASE_SENSITIVE) // test
              ->savePropertyAs('visibility', 'visibility2') // collect
              ->outIs('CONST')

              ->outIs('NAME')
              ->samePropertyAs('fullcode', 'name', self::CASE_SENSITIVE)
              ->inIs('NAME')

              ->raw(<<<'GREMLIN'
map{["type": "Constant Visibility",
      "name":name,
      "parent":class2,
      "parentValue":visibility2 + ' ' + name,
      "class":class1,
      "classValue":visibility1 + ' ' + name];
}
GREMLIN
);
        $this->prepareQuery();

        // Comparing Methods : return type, visibility, argument's type, default, name

         // Methods with different signatures : argument's type, default, name
         // Upgrade this with separate queries for each element.
         $this->atomIs(array('Method', 'Magicmethod'), self::WITHOUT_CONSTANTS)
              ->outIs('NAME')
              ->savePropertyAs('fullcode', 'name')
              ->inIs('NAME')
              ->raw('sideEffect{ signature1 = []; it.get().vertices(OUT, "ARGUMENT").sort{it.value("rank")}.each{ signature1.add(it.value("fullcode"));} }')

              ->inIs('METHOD')
              ->atomIs(self::CLASSES_ALL, self::WITHOUT_CONSTANTS)
              ->savePropertyAs('fullcode', 'class1')

              ->back('first')
              ->outIs('OVERWRITE')

              ->outIs('NAME')
              ->samePropertyAs('fullcode', 'name', self::CASE_SENSITIVE)
              ->inIs('NAME')
              ->raw('sideEffect{ signature2 = []; it.get().vertices(OUT, "ARGUMENT").sort{it.value("rank")}.each{ signature1.add(it.value("fullcode"));} }.filter{ signature2 != signature1; }')

              ->inIs('METHOD')
              ->atomIs(self::CLASSES_ALL, self::WITHOUT_CONSTANTS)
              ->savePropertyAs('fullcode', 'class2') // another class

              ->raw(<<<'GREMLIN'
map{["type": "Method Signature",
      "name":name,
      "parent":class2,
      "parentValue":"function " + name + "(" + signature2.join(", ") + ")",
      "class":class1,
      "classValue":"function " + name + "(" + signature1.join(", ") + ")"];
}
GREMLIN
);
        $this->prepareQuery();

         // Methods with different visibility
         $this->atomIs(array('Method', 'Magicmethod'), self::WITHOUT_CONSTANTS)
              ->savePropertyAs('fullnspath', 'fnp')
              ->savePropertyAs('visibility', 'visibility1')
              ->inIs(array('METHOD', 'MAGICMETHOD'))
              ->savePropertyAs('fullcode', 'name1')
              ->back('first')
              ->inIs('OVERWRITE')
              ->savePropertyAs('visibility', 'visibility2')
              ->raw('filter{visibility1  != visibility2;}')
              ->inIs('METHOD')
              ->savePropertyAs('fullcode', 'name2')
              ->raw(<<<'GREMLIN'
map{ ["type": "Method Visibility",
      "name":fnp.tokenize('::')[1],
      "parent":name1,
      "parentValue":visibility2 + ' ' + fnp.tokenize('::')[1],
      "class":name2,
      "classValue":visibility1 + ' ' + fnp.tokenize('::')[1]];
}
GREMLIN
);
        $this->prepareQuery();

         // Methods with different visibility
         $this->atomIs(array('Method', 'Magicmethod'), self::WITHOUT_CONSTANTS)
              ->savePropertyAs('fullnspath', 'fnp')
              ->outIs('RETURNTYPE')
              ->savePropertyAs('fullnspath', 'fnp1')
              ->inIs('RETURNTYPE')
              ->inIs(array('METHOD', 'MAGICMETHOD'))
              ->savePropertyAs('fullcode', 'name1')
              ->back('first')
              ->inIs('OVERWRITE')
              ->outIs('RETURNTYPE')
              ->savePropertyAs('fullnspath', 'fnp2')
              ->inIs('RETURNTYPE')
              ->raw('filter{fnp1  != fnp2;}')
              ->inIs('METHOD')
              ->savePropertyAs('fullcode', 'name2')
              ->raw(<<<'GREMLIN'
map{ ["type": "Method Returntype",
      "name":fnp.tokenize('::')[1],
      "parent":name1,
      "parentValue":fnp1 + ' ' + fnp.tokenize('::')[1],
      "class":name2,
      "classValue":fnp2 + ' ' + fnp.tokenize('::')[1]];
}
GREMLIN
);
        $this->prepareQuery();

        // Comparing Properties
        // default value, visibility, typehint

         // Property with different default value
         $this->atomIs('Propertydefinition', self::WITHOUT_CONSTANTS)
              ->savePropertyAs('fullcode', 'name')
              ->outIs('DEFAULT')
              ->hasNoIn('RIGHT') // find an explicit default
              ->savePropertyAs('fullcode', 'default1')
              ->inIs('DEFAULT')
              ->inIs('PPP')
              ->inIs('PPP')
              ->atomIs(self::CLASSES_ALL, self::WITHOUT_CONSTANTS)
              ->savePropertyAs('fullcode', 'class1')

              ->back('first')
              ->outIs('OVERWRITE')

              ->outIs('DEFAULT')
              ->notSamePropertyAs('fullcode', 'default1', self::CASE_SENSITIVE)
              ->savePropertyAs('fullcode', 'default2')
              ->inIs('DEFAULT')

              ->inIs('PPP')
              ->inIs('PPP')
              ->savePropertyAs('fullcode', 'class2')

              ->raw(<<<'GREMLIN'
map{ ["type": "Member Default",
      "name":name,
      "parent":class2,
      "parentValue":name + ' = ' + default2,
      "class":class1,
      "classValue":name + ' = ' + default1];
}
GREMLIN
);
        $this->prepareQuery();

         // Property with different visibility
         $this->atomIs('Propertydefinition', self::WITHOUT_CONSTANTS)
              ->savePropertyAs('fullcode', 'name')
              ->inIs('PPP')
              ->savePropertyAs('visibility', 'visibility1')
              ->inIs('PPP')
              ->atomIs(self::CLASSES_ALL, self::WITHOUT_CONSTANTS)
              ->savePropertyAs('fullcode', 'class1')
              ->goToAllParents(self::EXCLUDE_SELF)
              ->savePropertyAs('fullcode', 'class2')

              ->outIs('PPP')
              ->notSamePropertyAs('visibility', 'visibility1', self::CASE_SENSITIVE)
              ->savePropertyAs('visibility', 'visibility2')
              ->outIs('PPP')
              ->samePropertyAs('fullcode', 'name', self::CASE_SENSITIVE)

              ->raw(<<<'GREMLIN'
map{ ["type": "Member Visibility",
      "name":name,
      "parent":class2,
      "parentValue":visibility2 + ' ' + name,
      "class":class1,
      "classValue":visibility1 + ' ' + name];
}
GREMLIN
);
        $this->prepareQuery();

         // Property with different typehint
         $this->atomIs('Propertydefinition', self::WITHOUT_CONSTANTS)
              ->savePropertyAs('fullcode', 'name')
              ->inIs('PPP')
              ->outIs('TYPEHINT')
              ->savePropertyAs('fullnspath', 'fnp1')
              ->inIs('TYPEHINT')
              ->inIs('PPP')
              ->atomIs(self::CLASSES_ALL, self::WITHOUT_CONSTANTS)
              ->savePropertyAs('fullcode', 'class1')
              ->goToAllParents(self::EXCLUDE_SELF)
              ->savePropertyAs('fullcode', 'class2')

              ->outIs('PPP')
              ->outIs('TYPEHINT')
              ->savePropertyAs('fullnspath', 'fnp2')
              ->inIs('TYPEHINT')
              ->raw('filter{fnp1  != fnp2;}')
              ->outIs('PPP')
              ->samePropertyAs('fullcode', 'name', self::CASE_SENSITIVE)

              ->raw(<<<'GREMLIN'
map{ ["type": "Member Typehint",
      "name":name,
      "parent":class2,
      "parentValue":fnp2 + ' ' + name,
      "class":class1,
      "classValue":fnp1 + ' ' + name];
}
GREMLIN
);
        $this->prepareQuery();
    }
}

?>
