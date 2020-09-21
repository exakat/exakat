<?php declare (strict_types = 1);
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

namespace Exakat\Tasks\Helpers;

use Exakat\Exceptions\NoPrecedence;

class Precedence {
    public const WITH_SELF = true;
    public const WITHOUT_SELF = false;

    private $precedence  = array();
    private $definitions = array(
                        'T_OBJECT_OPERATOR'             => 0,
                        'T_DOUBLE_COLON'                => 0,
                        'T_DOLLAR'                      => 0,
                        'T_STATIC'                      => 0,
                        'T_EXIT'                        => 0,

                        'T_CLONE'                       => 1,
                        'T_NEW'                         => 1,

                        'T_OPEN_BRACKET'                => 2,

                        'T_POW'                         => 3,

                        'T_INC'                         => 4,
                        'T_DEC'                         => 4,
                        'T_ARRAY_CAST'                  => 4,
                        'T_BOOL_CAST'                   => 4,
                        'T_DOUBLE_CAST'                 => 4,
                        'T_INT_CAST'                    => 4,
                        'T_OBJECT_CAST'                 => 4,
                        'T_STRING_CAST'                 => 4,
                        'T_UNSET_CAST'                  => 4,
                        'T_AT'                          => 4,

                        'T_INSTANCEOF'                  => 5,

                        'T_TILDE'                       => 6,
                        'T_BANG'                        => 6,
                        'T_REFERENCE'                   => 6, // Special for reference's usage of &

                        'T_YIELD'                       => 9,
                        'T_YIELD_FROM'                  => 9,

                        'T_SLASH'                       => 10,
                        'T_STAR'                        => 10,
                        'T_PERCENTAGE'                  => 10,

                        'T_PLUS'                        => 18,
                        'T_MINUS'                       => 18,
                        'T_DOT'                         => 18, // Changed at constructor time for 21

                        'T_SR'                          => 20,
                        'T_SL'                          => 20,

                        'T_IS_SMALLER_OR_EQUAL'         => 30,
                        'T_IS_GREATER_OR_EQUAL'         => 30,
                        'T_GREATER'                     => 30,
                        'T_SMALLER'                     => 30,

                        'T_IS_EQUAL'                    => 37,
                        'T_IS_NOT_EQUAL'                => 37, // Double operator
                        'T_IS_IDENTICAL'                => 37,
                        'T_IS_NOT_IDENTICAL'            => 37,
                        'T_SPACESHIP'                   => 37,

                        'T_AND'                         => 42,    // &

                        'T_XOR'                         => 43,    // ^

                        'T_OR'                          => 54,     // |

                        'T_BOOLEAN_AND'                 => 65, // &&

                        'T_BOOLEAN_OR'                  => 76, // ||

                        'T_COALESCE'                    => 87,

                        'T_QUESTION'                    => 98,

                        'T_EQUAL'                       => 99,
                        'T_PLUS_EQUAL'                  => 99,
                        'T_AND_EQUAL'                   => 99,
                        'T_CONCAT_EQUAL'                => 99,
                        'T_DIV_EQUAL'                   => 99,
                        'T_MINUS_EQUAL'                 => 99,
                        'T_MOD_EQUAL'                   => 99,
                        'T_MUL_EQUAL'                   => 99,
                        'T_OR_EQUAL'                    => 99,
                        'T_POW_EQUAL'                   => 99,
                        'T_SL_EQUAL'                    => 99,
                        'T_SR_EQUAL'                    => 99,
                        'T_XOR_EQUAL'                   => 99,
                        'T_COALESCE_EQUAL'              => 99,

                        'T_LOGICAL_AND'                 => 100, // and

                        'T_LOGICAL_XOR'                 => 101, // xor

                        'T_LOGICAL_OR'                  => 102, // or

                        'T_ECHO'                        => 110,
                        'T_HALT_COMPILER'               => 110,
                        'T_PRINT'                       => 110,
                        'T_INCLUDE'                     => 110,
                        'T_INCLUDE_ONCE'                => 110,
                        'T_REQUIRE'                     => 110,
                        'T_REQUIRE_ONCE'                => 110,
                        'T_DOUBLE_ARROW'                => 110,

                        'T_RETURN'                      => 121,
                        'T_THROW'                       => 121,
                        'T_COLON'                       => 121,
                        'T_COMMA'                       => 121,
                        'T_CLOSE_TAG'                   => 121,
                        'T_CLOSE_PARENTHESIS'           => 121,
                        'T_CLOSE_BRACKET'               => 121,
                        'T_CLOSE_CURLY'                 => 121,
                        'T_AS'                          => 121,
                        'T_CONTINUE'                    => 121,
                        'T_BREAK'                       => 121,
                        'T_ELLIPSIS'                    => 121,
                        'T_GOTO'                        => 121,
                        'T_INSTEADOF'                   => 121,

                        'T_SEMICOLON'                   => 132,
    );

    private static $cache = array();

    public function __construct(string $phpVersion) {
        foreach($this->definitions as $name => $priority) {
            // Skip unknown tokens from other versions
            if (defined("$phpVersion::$name")) {
                $this->precedence[constant("$phpVersion::$name")] = $priority;
            }
        }

        if (substr($phpVersion, -2) === '80') {
            $this->precedence[constant("$phpVersion::T_DOT")] = 21;
        }

        foreach($this->precedence as $k1 => $p1) {
            self::$cache[$k1] = array();
            foreach($this->precedence as $k2 => $p2) {
                if ($p1 <= $p2 && $k1 !== $k2) {
                    self::$cache[$k1][] = $k2;
                }
            }
        }
    }

    public function get($token, bool $itself = self::WITHOUT_SELF): array {
        if (!isset(self::$cache[$token])) {
            throw new NoPrecedence($token);
        }

        if ($itself === self::WITH_SELF) {
            return array_merge(self::$cache[$token], array($token));
        } else {
            return self::$cache[$token];
        }
    }
}

?>