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

namespace Exakat\Tasks\Helpers;

use Exakat\Exceptions\NoRecognizedTokens;

abstract class Php {

// Exakat home-made tokens
    const T_BANG                         = '!';
    const T_CLOSE_BRACKET                = ']';
    const T_CLOSE_PARENTHESIS            = ')';
    const T_CLOSE_CURLY                  = '}';
    const T_COMMA                        = ',';
    const T_DOT                          = '.';
    const T_EQUAL                        = '=';
    const T_MINUS                        = '-';
    const T_AT                           = '@';
    const T_OPEN_BRACKET                 = '[';
    const T_OPEN_CURLY                   = '{';
    const T_OPEN_PARENTHESIS             = '(';
    const T_PERCENTAGE                   = '%';
    const T_PLUS                         = '+';
    const T_QUESTION                     = '?';
    const T_COLON                        = ':';
    const T_SEMICOLON                    = ';';
    const T_SLASH                        = '/';
    const T_STAR                         = '*';
    const T_SMALLER                      = '<';
    const T_GREATER                      = '>';
    const T_TILDE                        = '~';
    const T_QUOTE                        = '"';
    const T_DOLLAR                       = '$';
    const T_AND                          = '&';
    const T_BACKTICK                     = '`';
    const T_OR                           = '|';
    const T_XOR                          = '^';
    const T_ANDAND                       = '&&';
    const T_OROR                         = '||';
    const T_QUOTE_CLOSE                  = '"_CLOSE';
    const T_SHELL_QUOTE                  = '`';
    const T_SHELL_QUOTE_CLOSE            = '`_CLOSE';

    const T_END                          = 'The End';
    const T_REFERENCE                    = 'r';
    const T_VOID                         = 'v';

    const TOKENS = array(
                     ';'  => self::T_SEMICOLON,
                     '+'  => self::T_PLUS,
                     '-'  => self::T_MINUS,
                     '/'  => self::T_SLASH,
                     '*'  => self::T_STAR,
                     '.'  => self::T_DOT,
                     '['  => self::T_OPEN_BRACKET,
                     ']'  => self::T_CLOSE_BRACKET,
                     '('  => self::T_OPEN_PARENTHESIS,
                     ')'  => self::T_CLOSE_PARENTHESIS,
                     '{'  => self::T_OPEN_CURLY,
                     '}'  => self::T_CLOSE_CURLY,
                     '='  => self::T_EQUAL,
                     ','  => self::T_COMMA,
                     '!'  => self::T_BANG,
                     '~'  => self::T_TILDE,
                     '@'  => self::T_AT,
                     '?'  => self::T_QUESTION,
                     ':'  => self::T_COLON,
                     '<'  => self::T_SMALLER,
                     '>'  => self::T_GREATER,
                     '%'  => self::T_PERCENTAGE,
                     '"'  => self::T_QUOTE,
                     'b"' => self::T_QUOTE,
                     '$'  => self::T_DOLLAR,
                     '&'  => self::T_AND,
                     '|'  => self::T_OR,
                     '^'  => self::T_XOR,
                     '`'  => self::T_BACKTICK,
                   );

    public static function getInstance($tokens): self {
        $errors = array();

        if (empty($tokens)) {
            throw new NoRecognizedTokens($tokens);
        }

        //'Php80',
        $versions = array('Php80', 'Php74', 'Php73', 'Php72', 'Php71', 'Php70', 'Php56', 'Php55', );

        foreach($versions as $version) {
            $errors = array();
            foreach($tokens as $k => $v) {
                if (constant(__NAMESPACE__ . "\\$version::$v") !== $k) {
                    $errors[$k] = $v;
                }
            }

            if (empty($errors)) {
                $className = __NAMESPACE__ . "\\$version";
                return new $className();
            }
        }

        throw new NoRecognizedTokens();
    }
}
?>
