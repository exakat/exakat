<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

namespace Exakat\Analyzer\Security;

use Exakat\Analyzer\Analyzer;

class UploadFilenameInjection extends Analyzer {
    public function analyze() {
        //$extension = strtolower( substr( strrchr($_FILES['upload']['name'], ".") ,1) );
        //if(@move_uploaded_file($_FILES['upload']['tmp_name'], $_FILES['upload']['name']))
        $this->atomFunctionIs('\move_uploaded_file')
             ->outWithRank('ARGUMENT', 1)
             ->atomInsideNoDefinition('Phpvariable')
             ->codeIs('$_FILES', self::TRANSLATE, self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

        //$extension = strtolower( substr( strrchr($_FILES['upload']['name'], ".") ,1) );
        //if(@move_uploaded_file($_FILES['upload']['tmp_name'], "../logos_clients/".$id.".$extension"))
        $this->atomFunctionIs('\move_uploaded_file')
             ->hasFunction()
             ->outWithRank('ARGUMENT', 1)
             ->atomInsideNoDefinition(array('Variable', 'Variableobject', 'Variablearray'))
             ->savePropertyAs('code', 'relay')
             ->goToFunction()
             ->outIs('BLOCK')
             ->atomInsideNoDefinition(self::$VARIABLES_ALL)
             ->samePropertyAs('code', 'relay')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomInsideNoDefinition('Phpvariable')
             ->codeIs('$_FILES', self::TRANSLATE, self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

        //$extension = strtolower( substr( strrchr($_FILES['upload']['name'], ".") ,1) );
        //if(@move_uploaded_file($_FILES['upload']['tmp_name'], "../logos_clients/".$id.".$extension"))
        $this->atomFunctionIs('\move_uploaded_file')
             ->hasNoFunction()
             ->outWithRank('ARGUMENT', 1)
             ->atomInsideNoDefinition(array('Variable', 'Variableobject', 'Variablearray'))
             ->savePropertyAs('code', 'relay')
             ->goToFile()
             ->outIs('FILE')
             ->atomInsideNoDefinition(self::$VARIABLES_ALL)
             ->samePropertyAs('code', 'relay')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomInsideNoDefinition('Phpvariable')
             ->codeIs('$_FILES', self::TRANSLATE, self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
