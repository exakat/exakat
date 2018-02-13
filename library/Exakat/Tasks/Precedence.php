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

namespace Exakat\Tasks;

use Exakat\Exceptions\NoPrecedence;
use Exakat\Phpexec;

class Precedence {

    private $precedence = array();
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
                        'T_TILDE'                       => 4,
                        'T_ARRAY_CAST'                  => 4,
                        'T_BOOL_CAST'                   => 4,
                        'T_DOUBLE_CAST'                 => 4,
                        'T_INT_CAST'                    => 4,
                        'T_OBJECT_CAST'                 => 4,
                        'T_STRING_CAST'                 => 4,
                        'T_UNSET_CAST'                  => 4,
                        'T_AT'                          => 4,

                        'T_INSTANCEOF'                  => 5,

                        'T_BANG'                        => 6,

                        'T_REFERENCE'                   => 6, // Special for reference's usage of &

                        'T_SLASH'                       => 7,
                        'T_STAR'                        => 7,
                        'T_PERCENTAGE'                  => 7,

                        'T_PLUS'                        => 8,
                        'T_MINUS'                       => 8,
                        'T_DOT'                         => 8,

                        'T_SR'                          => 9,
                        'T_SL'                          => 9,

                        'T_IS_SMALLER_OR_EQUAL'         => 10,
                        'T_IS_GREATER_OR_EQUAL'         => 10,
                        'T_GREATER'                     => 10,
                        'T_SMALLER'                     => 10,

                        'T_IS_EQUAL'                    => 11,
                        'T_IS_NOT_EQUAL'                => 11, // Double operator
                        'T_IS_IDENTICAL'                => 11,
                        'T_IS_NOT_IDENTICAL'            => 11,
                        'T_SPACESHIP'                   => 11,

                        'T_AND'                         => 12,    // &

                        'T_CARET'                       => 13,    // ^

                        'T_PIPE'                        => 14,     // |

                        'T_BOOLEAN_AND'                 => 15, // &&

                        'T_BOOLEAN_OR'                  => 16, // ||

                        'T_COALESCE'                    => 17,

                        'T_QUESTION'                    => 18,

                        'T_EQUAL'                       => 19,
                        'T_PLUS_EQUAL'                  => 19,
                        'T_AND_EQUAL'                   => 19,
                        'T_CONCAT_EQUAL'                => 19,
                        'T_DIV_EQUAL'                   => 19,
                        'T_MINUS_EQUAL'                 => 19,
                        'T_MOD_EQUAL'                   => 19,
                        'T_MUL_EQUAL'                   => 19,
                        'T_OR_EQUAL'                    => 19,
                        'T_POW_EQUAL'                   => 19,
                        'T_SL_EQUAL'                    => 19,
                        'T_SR_EQUAL'                    => 19,
                        'T_XOR_EQUAL'                   => 19,

                        'T_LOGICAL_AND'                 => 20, // and

                        'T_LOGICAL_XOR'                 => 21, // xor

                        'T_LOGICAL_OR'                  => 22, // or

                        'T_ECHO'                        => 30,
                        'T_HALT_COMPILER'               => 30,
                        'T_PRINT'                       => 30,
                        'T_INCLUDE'                     => 30,
                        'T_INCLUDE_ONCE'                => 30,
                        'T_REQUIRE'                     => 30,
                        'T_REQUIRE_ONCE'                => 30,
                        'T_DOUBLE_ARROW'                => 30,

                        'T_RETURN'                      => 31,
                        'T_THROW'                       => 31,
                        'T_YIELD'                       => 31,
                        'T_YIELD_FROM'                  => 31,
                        'T_COLON'                       => 31,
                        'T_COMMA'                       => 31,
                        'T_CLOSE_TAG'                   => 31,
                        'T_CLOSE_PARENTHESIS'           => 31,
                        'T_CLOSE_BRACKET'               => 31,
                        'T_CLOSE_CURLY'                 => 31,
                        'T_AS'                          => 31,
                        'T_CONTINUE'                    => 31,
                        'T_BREAK'                       => 31,
                        'T_ELLIPSIS'                    => 31,
                        'T_GOTO'                        => 31,
                        'T_INSTEADOF'                   => 31,

                        'T_SEMICOLON'                   => 32,
    );

    private $allTokens = array('T_REQUIRE_ONCE', 'T_REQUIRE', 'T_EVAL', 'T_INCLUDE_ONCE', 'T_INCLUDE', 'T_LOGICAL_OR', 'T_LOGICAL_XOR', 'T_LOGICAL_AND', 'T_PRINT', 'T_YIELD', 'T_DOUBLE_ARROW', 'T_YIELD_FROM', 'T_POW_EQUAL', 'T_SR_EQUAL', 'T_SL_EQUAL', 'T_XOR_EQUAL', 'T_OR_EQUAL', 'T_AND_EQUAL', 'T_MOD_EQUAL', 'T_CONCAT_EQUAL', 'T_DIV_EQUAL', 'T_MUL_EQUAL', 'T_MINUS_EQUAL', 'T_PLUS_EQUAL', 'T_COALESCE', 'T_BOOLEAN_OR', 'T_BOOLEAN_AND', 'T_SPACESHIP', 'T_IS_NOT_IDENTICAL', 'T_IS_IDENTICAL', 'T_IS_NOT_EQUAL', 'T_IS_EQUAL', 'T_IS_GREATER_OR_EQUAL', 'T_IS_SMALLER_OR_EQUAL', 'T_SR', 'T_SL', 'T_INSTANCEOF', 'T_UNSET_CAST', 'T_BOOL_CAST', 'T_OBJECT_CAST', 'T_ARRAY_CAST', 'T_STRING_CAST', 'T_DOUBLE_CAST', 'T_INT_CAST', 'T_DEC', 'T_INC', 'T_POW', 'T_CLONE', 'T_NEW', 'T_ELSEIF', 'T_ELSE', 'T_ENDIF', 'T_PUBLIC', 'T_PROTECTED', 'T_PRIVATE', 'T_FINAL', 'T_ABSTRACT', 'T_STATIC', 'T_LNUMBER', 'T_DNUMBER', 'T_STRING', 'T_VARIABLE', 'T_INLINE_HTML', 'T_ENCAPSED_AND_WHITESPACE', 'T_CONSTANT_ENCAPSED_STRING', 'T_STRING_VARNAME', 'T_NUM_STRING', 'T_EXIT', 'T_IF', 'T_ECHO', 'T_DO', 'T_WHILE', 'T_ENDWHILE', 'T_FOR', 'T_ENDFOR', 'T_FOREACH', 'T_ENDFOREACH', 'T_DECLARE', 'T_ENDDECLARE', 'T_AS', 'T_SWITCH', 'T_ENDSWITCH', 'T_CASE', 'T_DEFAULT', 'T_BREAK', 'T_CONTINUE', 'T_GOTO', 'T_FUNCTION', 'T_RETURN', 'T_TRY', 'T_CATCH', 'T_FINALLY', 'T_THROW', 'T_USE', 'T_INSTEADOF', 'T_GLOBAL', 'T_VAR', 'T_UNSET', 'T_ISSET', 'T_EMPTY', 'T_HALT_COMPILER', 'T_CLASS', 'T_TRAIT', 'T_INTERFACE', 'T_EXTENDS', 'T_IMPLEMENTS', 'T_OBJECT_OPERATOR', 'T_LIST', 'T_ARRAY', 'T_CALLABLE', 'T_LINE', 'T_FILE', 'T_DIR', 'T_CLASS_C', 'T_TRAIT_C', 'T_METHOD_C', 'T_FUNC_C', 'T_COMMENT', 'T_DOC_COMMENT', 'T_OPEN_TAG', 'T_OPEN_TAG_WITH_ECHO', 'T_CLOSE_TAG', 'T_WHITESPACE', 'T_START_HEREDOC', 'T_END_HEREDOC', 'T_DOLLAR_OPEN_CURLY_BRACES', 'T_CURLY_OPEN', 'T_PAAMAYIM_NEKUDOTAYIM', 'T_NAMESPACE', 'T_NS_C', 'T_NS_SEPARATOR', 'T_ELLIPSIS', 'T_DOUBLE_COLON', 'T_CONST');

    public function __construct($version, $config) {

        $php = new Phpexec($version, $config->{'php'.str_replace('.', '', $config->phpversion)});
        $tokens = array_flip($php->getTokens());

        foreach($this->allTokens as $name) {
            if (!isset($tokens[$name])) {
                $tokens[$name] = -1;
            }
            define('Exakat\\Tasks\\'.$name, $tokens[$name]);
        }

        foreach($this->definitions as $name => $priority) {
            $this->precedence[constant('Exakat\\Tasks\\'.$name)] = $priority;
        }
    }

    public function get($token, $itself = false) {
        static $cache;

        if ($cache === null) {
            $cache = array();
            foreach($this->precedence as $k1 => $p1) {
                $cache[$k1] = array();
                foreach($this->precedence as $k2 => $p2) {
                    if ($p1 <= $p2 && ($itself === true || $k1 !== $k2) ) {
                        $cache[$k1][] = $k2;
                    }
                }
            }
        }

        if (!isset($cache[$token])) {
            throw new NoPrecedence($token);
        }

        return $cache[$token];
    }
}

?>