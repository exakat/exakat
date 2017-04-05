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

namespace Exakat\Tasks\Helpers;

use Exakat\Tasks\Load;

class Atom {
    static $atomCount = 0;
    
    public $atom         = 'No Atom Set';
    public $id           = 0;
    public $code         = '';
    public $fullcode     = '';
    public $variadic     = Load::NOT_VARIADIC;
    public $line         = Load::NO_LINE;
    public $constant     = Load::CONSTANT_EXPRESSION;
    public $token        = '';
    public $intval       = null;
    public $boolean      = null;
    public $bracket      = Load::NOT_BRACKET;
    public $rank         = 0;
    public $count        = 0;
    public $root         = false;  // false is on purpose. 
    public $close_tag    = Load::NO_CLOSING_TAG;
    public $absolute     = Load::NOT_ABSOLUTE;
    public $encoding     = '';
    public $delimiter    = '';
    public $noDelimiter  = '';
    public $args_max     = 0;
    public $args_min     = 0;
    public $reference    = Load::NOT_REFERENCE;
    public $fullnspath   = '';
    public $aliased      = Load::NOT_ALIASED;
    public $alias        = '';
    public $origin       = '';
    public $enclosing    = Load::NO_ENCLOSING;
    public $globalvar    = false;
    public $alternative  = Load::NOT_ALTERNATIVE;
    public $heredoc      = false;
    public $propertyname = '';

    public function __construct($atom) {
        $this->id = ++self::$atomCount;
        $this->atom = $atom;
    }
    
    public function __set($name, $value) {
        print "Undefined $name property in Atom\n";
    }
}

?>
