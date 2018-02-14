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
    static public $atomCount = 0;
    
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
    public $close_tag    = Load::NO_CLOSING_TAG;
    public $aliased      = Load::NOT_ALIASED;
    public $propertyname = '';
    public $constant     = Load::NOT_CONSTANT_EXPRESSION;
    public $root         = false;  // false is on purpose.
    public $globalvar    = false;
    public $binaryString = Load::NOT_BINARY;

    public function __construct($atom) {
        $this->id = ++self::$atomCount;
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
        
        $this->code          = addcslashes($this->code       , '\\"');
        $this->lccode        = addcslashes($this->lccode     , '\\"');
        $this->fullcode      = addcslashes($this->fullcode   , '\\"');
        $this->fullnspath    = addcslashes($this->fullnspath , '\\"');
        $this->strval        = addcslashes($this->strval     , '\\"');
        $this->noDelimiter   = addcslashes($this->noDelimiter, '\\"');

        $this->alternative   = $this->alternative ? 1 : null;
        $this->reference     = $this->reference   ? 1 : null;
        $this->heredoc       = $this->heredoc     ? 1 : null;
        $this->variadic      = $this->variadic    ? 1 : null;
        $this->absolute      = $this->absolute    ? 1 : null;
        $this->constant      = $this->constant    ? 1 : null;
        $this->boolean       = $this->boolean     ? 1 : null;
        $this->enclosing     = $this->enclosing   ? 1 : null;
        $this->bracket       = $this->bracket     ? 1 : null;
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

    public function toNonEmptyArray() {
        $return = array();

        if (strlen($this->code) > self::STRING_MAX_SIZE) {
            $this->code = substr($this->code, 0, self::STRING_MAX_SIZE).'...[ total '.strlen($this->code).' chars]';
        }
        if (strlen($this->lccode) > self::STRING_MAX_SIZE) {
            $this->lccode = substr($this->lccode, 0, self::STRING_MAX_SIZE).'...[ total '.strlen($this->lccode).' chars]';
        }
        if (strlen($this->fullcode) > self::STRING_MAX_SIZE) {
            $this->fullcode = substr($this->fullcode, 0, self::STRING_MAX_SIZE).'...[ total '.strlen($this->fullcode).' chars]';
        }

        if ($this->intval > 2147483647) {
            $this->intval = 2147483647;
        }
        if ($this->intval < -2147483648) {
            $this->intval = -2147483648;
        }
        $this->globalvar     = !$this->globalvar  ? null : $this->globalvar;

        
        // Those are always present
        $return = array(
            'id'            => $this->id,
            'atom'          => $this->atom,
            'label'         => $this->atom,
            'line'          => $this->line,
            'token'         => $this->token,
            'code'          => addcslashes($this->code       , '\\"'),
            'lccode'        => addcslashes($this->lccode     , '\\"'),
            'fullcode'      => addcslashes($this->fullcode   , '\\"'),
            'fullnspath'    => addcslashes($this->fullnspath , '\\"'),
            );

        if ($this->count !== null) { $return['count'] = $this->count; }
        if ($this->rank !== '') { $return['rank'] = $this->rank; }
        if ($this->alternative) { $return['alternative'] = 1; }
        if ($this->reference) { $return['reference'] = 1; }
        if ($this->heredoc) { $return['heredoc'] = 1; }
        if ($this->variadic) { $return['variadic'] = 1; }
        if ($this->absolute) { $return['absolute'] = 1; }
        if ($this->constant) { $return['constant'] = 1; }
        if ($this->boolean) { $return['boolean'] = 1; }
        if ($this->enclosing) { $return['enclosing'] = 1; }
        if ($this->bracket) { $return['bracket'] = 1; }
        if ($this->close_tag) { $return['close_tag'] = 1; }
        if ($this->aliased) { $return['aliased'] = 1; }
        if ($this->alias !== '') { $return['alias'] = $this->alias; }
        if ($this->origin !== '') { $return['origin'] = $this->origin; }
        if ($this->strval) { $return['close_tag'] = addcslashes($this->strval     , '\\"'); }
        if ($this->noDelimiter !== null) { $return['noDelimiter'] = addcslashes($this->noDelimiter, '\\"'); }
        if ($this->propertyname) { $return['propertyname'] = $this->propertyname; }
        if ($this->globalvar) { $return['globalvar'] = 1; }
        if ($this->intval !== null) { $return['intval'] = 1; }
        if ($this->delimiter !== '') { $return['delimiter'] = $this->delimiter; }
        if ($this->args_max !== '') { $return['args_max'] = $this->args_max; }
        if ($this->args_min !== '') { $return['args_min'] = $this->args_min; }

        return $return;
    }
    public function toLimitedArray($headers) {
        $return = array();

        if (strlen($this->code) > self::STRING_MAX_SIZE) {
            $this->code = substr($this->code, 0, self::STRING_MAX_SIZE).'...[ total '.strlen($this->code).' chars]';
        }
        if (strlen($this->lccode) > self::STRING_MAX_SIZE) {
            $this->lccode = substr($this->lccode, 0, self::STRING_MAX_SIZE).'...[ total '.strlen($this->lccode).' chars]';
        }
        if (strlen($this->fullcode) > self::STRING_MAX_SIZE) {
            $this->fullcode = substr($this->fullcode, 0, self::STRING_MAX_SIZE).'...[ total '.strlen($this->fullcode).' chars]';
        }

        $this->code          = addcslashes($this->code       , '\\"');
        $this->lccode        = addcslashes($this->lccode     , '\\"');
        $this->fullcode      = addcslashes($this->fullcode   , '\\"');
        $this->fullnspath    = addcslashes($this->fullnspath , '\\"');
        $this->strval        = addcslashes($this->strval     , '\\"');
        $this->noDelimiter   = addcslashes($this->noDelimiter, '\\"');

//'alternative', 'reference', 'heredoc', 'variadic', 'absolute','enclosing', 'bracket', 'close_tag', 'aliased', 'boolean'
        $this->alternative   = (int) $this->alternative;
        $this->reference     = (int) $this->reference  ;
        $this->heredoc       = (int) $this->heredoc    ;
        $this->variadic      = (int) $this->variadic   ;
        $this->absolute      = (int) $this->absolute   ;
        $this->constant      = (int) $this->constant   ;
        $this->boolean       = (int) $this->boolean    ;
        $this->bracket       = (int) $this->bracket    ;
        $this->close_tag     = (int) $this->close_tag  ;
        $this->aliased       = (int) $this->aliased    ;

        $this->enclosing     = !$this->enclosing  ? null : 1;
        $this->globalvar     = !$this->globalvar  ? null : $this->globalvar;

        $return = array( $this->id,
                         $this->atom,
                         $this->code,
                         $this->lccode,
                         $this->fullcode,
                         $this->line,
                         $this->token,
                         $this->rank);
        
        foreach($headers as $head) {
            $return[] = $this->$head;
        }
        
        return $return;
    }
    
    public function toGraphsonLine(&$id) {
        $booleanValues = array('alternative', 'heredoc', 'reference', 'variadic', 'absolute', 'enclosing', 'bracket', 'close_tag', 'aliased', 'boolean', 'constant');
        $integerValues = array('count', 'intval', 'args_max', 'args_min');

        $falseValues = array('globalvar', 'variadic', 'enclosing', 'heredoc', 'aliased', 'alternative', 'reference');
        
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
            };

            if ($l === 'lccode') {
                $this->lccode = mb_strtolower($this->code);
            };

            if (!in_array($l, array('noDelimiter', 'lccode')) &&
                $value === '') {
                continue;
            };

            if ($value === false) {
                continue;
            };
        
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
}

?>
