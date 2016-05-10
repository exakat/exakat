<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

namespace Analyzer\ZendF;

use Analyzer;

class NotInThatPath extends Analyzer\Analyzer {
    public function analyze() {
        // No Zend_Auth in .phtml
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->fullnspath('\\Zend_Auth')
             ->goToFile()
             ->regex('code', '\\\\.phtml\$')
             ->back('first');
        $this->prepareQuery();

        //Zend_Controller_Action must be in /controllers/ path
        $this->atomIs('Class')
             ->filter('"\\\\zend_controller_action" in it.classTree')
             ->goToFile()
             ->regexNot('code', '\\\\/controllers\\\\/')
             ->back('first');
        $this->prepareQuery();

        //classes in /controllers/ path must be Zend_Controller_Action ?

        //Zend_View_Helper_Abstract must be in /helpers/ folder
        $this->atomIs('Class')
             ->filter('"\\\\zend_view_helper_abstract" in it.classTree')
             ->goToFile()
             ->regexNot('code', '\\\\/helpers\\\\/')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
