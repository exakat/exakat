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

namespace Exakat\Tasks\Helpers;

use Exakat\Tasks\Load;
use Exakat\Tasks\Helpers\Property;

class Atom {
    const STRING_MAX_SIZE = 500;
    
    public $id           = 0;
    public $atom         = 'No Atom Set';
    public $code         = '';
    public $lccode       = '';
    public $fullcode     = '';
    public $line         = Load::NO_LINE;
    public $token        = '';
    public $rank         = ''; // Not 0
    public $alternative  = Load::NOT_ALTERNATIVE;
    public $reference    = Load::NOT_REFERENCE;
    public $heredoc      = false;
    public $delimiter    = '';
    public $noDelimiter  = null;
    public $variadic     = Load::NOT_VARIADIC;
    public $count        = null;
    public $fullnspath   = '';
    public $absolute     = Load::NOT_ABSOLUTE;
    public $alias        = '';
    public $origin       = '';
    public $encoding     = '';
    public $block        = '';
    public $intval       = '';
    public $strval       = '';
    public $boolean      = ''; // null, so boolean may NOT be available
    public $enclosing    = Load::NO_ENCLOSING;
    public $args_max     = '';
    public $args_min     = '';
    public $bracket      = Load::NOT_BRACKET;
    public $flexible     = Load::NOT_FLEXIBLE;
    public $close_tag    = Load::NO_CLOSING_TAG;
    public $aliased      = Load::NOT_ALIASED;
    public $propertyname = '';
    public $constant     = Load::NOT_CONSTANT_EXPRESSION;
    public $root         = false;  // false is on purpose.
    public $globalvar    = false;
    public $binaryString = Load::NOT_BINARY;
    public $isNull       = false;
    public $visibility   = '';
    public $final        = '';
    public $abstract     = '';
    public $static       = '';
    public $ctype1       = '';
    public $ctype1_size  = 0;
    public $noscream     = 0;
    public $relaxed      = null;

    public function __construct($id, $atom) {
        $this->id   = $id;
        $this->atom = $atom;
    }
    
    public function toArray() {
        if (strlen($this->code) > self::STRING_MAX_SIZE) {
            $this->code = substr($this->code, 0, self::STRING_MAX_SIZE).'...[ total '.strlen($this->code).' chars]';
        }
        if (strlen($this->lccode) > self::STRING_MAX_SIZE) {
            $this->lccode = substr($this->lccode, 0, self::STRING_MAX_SIZE).'...[ total '.strlen($this->lccode).' chars]';
        }
        if (strlen($this->fullcode) > self::STRING_MAX_SIZE) {
            $this->fullcode = substr($this->fullcode, 0, self::STRING_MAX_SIZE).'...[ total '.strlen($this->fullcode).' chars]';
        }
        
        $this->code          = $this->protectString($this->code       );
        $this->lccode        = $this->protectString($this->lccode     );
        $this->fullcode      = $this->protectString($this->fullcode   );
        $this->fullnspath    = $this->protectString($this->fullnspath );
        $this->strval        = $this->protectString($this->strval     );
        $this->noDelimiter   = $this->protectString($this->noDelimiter);
        $this->visibility    = $this->protectString($this->visibility );
        $this->ctype1        = $this->protectString($this->ctype1     );
        $this->relaxed       = $this->protectString($this->relaxed    );

        $this->alternative   = $this->alternative ? 1 : null;
        $this->reference     = $this->reference   ? 1 : null;
        $this->heredoc       = $this->heredoc     ? 1 : null;
        $this->variadic      = $this->variadic    ? 1 : null;
        $this->final         = $this->final       ? 1 : null;
        $this->abstract      = $this->abstract    ? 1 : null;
        $this->static        = $this->static      ? 1 : null;
        $this->absolute      = $this->absolute    ? 1 : null;
        $this->constant      = $this->constant    ? 1 : null;
        $this->boolean       = $this->boolean     ? 1 : null;
        $this->enclosing     = $this->enclosing   ? 1 : null;
        $this->bracket       = $this->bracket     ? 1 : null;
        $this->flexible      = $this->flexible    ? 1 : null;
        $this->close_tag     = $this->close_tag   ? 1 : null;
        $this->aliased       = $this->aliased     ? 1 : null;
        
        if ($this->intval > 2147483647) {
            $this->intval = 2147483647;
        }
        if ($this->intval < -2147483648) {
            $this->intval = -2147483648;
        }

        $this->globalvar     = !$this->globalvar  ? null : $this->globalvar;

        return (array) $this;
    }

    public function toGraphsonLine(&$id) {
        $booleanValues = array('alternative',
                               'absolute',
                               'abstract',
                               'aliased',
                               'boolean',
                               'bracket',
                               'close_tag',
                               'constant',
                               'enclosing',
                               'final',
                               'flexible',
                               'heredoc',
                               'noscream',
                               'reference',
                               'static',
                               'variadic',
                               );
        $integerValues = array('args_max',
                               'args_min',
                               'count',
                               'intval',
                               );

        $falseValues = array('aliased',
                             'alternative',
                             'enclosing',
                             'globalvar',
                             'heredoc',
                             'noscream',
                             'reference',
                             'variadic',
                             );
        
        $properties = array();
        foreach($this as $l => $value) {
            if ($l === 'id') { continue; }
            if ($l === 'atom') { continue; }
            if ($value === null) { continue; }

/*            
            if (!in_array($l, array('atom', 'rank', 'token', 'fullcode', 'code', 'lccode', 'line')) &&
                !in_array($this->atom, Load::$PROP_OPTIONS[$l])) {
                continue;
            };
*/
    
            if (in_array($l, $falseValues) &&
                !$value) {
                continue;
            }

            if ($l === 'lccode') {
                $this->lccode = mb_strtolower($this->code);
            }

            if (!in_array($l, array('noDelimiter', 'lccode', 'code', 'fullcode', 'relaxed' )) &&
                $value === '') {
                continue;
            }

            if ($value === false) {
                continue;
            }
        
            if (in_array($l, $booleanValues)) {
                $value = (boolean) $value;
            } elseif (in_array($l, $integerValues)) {
                $value = (integer) $value;
            }
            $properties[$l] = [ new Property($id++, $value) ];
        }
        
        $object = array('id'         => $this->id,
                        'label'      => $this->atom,
                        'inE'        => new \stdClass(),
                        'outE'       => new \stdClass(),
                        'properties' => $properties,
                        );

        return (object) $object;
    }
    
    private function protectString($code) {
        return addcslashes($code , '\\"');
    }
}

?>
