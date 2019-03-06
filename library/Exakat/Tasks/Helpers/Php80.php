<?php
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


class Php80 extends Php {

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
    const T_PIPE                         = '|';
    const T_CARET                        = '^';
    const T_BACKTICK                     = '`';
    
    const T_END                          = 'The End';
    const T_REFERENCE                    = 'r';
    const T_VOID                         = 'v';

    // PHP tokens
    const T_INCLUDE                       = 258;
    const T_INCLUDE_ONCE                  = 259;
    const T_EVAL                          = 260;
    const T_REQUIRE                       = 261;
    const T_REQUIRE_ONCE                  = 262;
    const T_LOGICAL_OR                    = 263;
    const T_LOGICAL_XOR                   = 264;
    const T_LOGICAL_AND                   = 265;
    const T_PRINT                         = 266;
    const T_YIELD                         = 267;
    const T_DOUBLE_ARROW                  = 268;
    const T_YIELD_FROM                    = 269;
    const T_PLUS_EQUAL                    = 270;
    const T_MINUS_EQUAL                   = 271;
    const T_MUL_EQUAL                     = 272;
    const T_DIV_EQUAL                     = 273;
    const T_CONCAT_EQUAL                  = 274;
    const T_MOD_EQUAL                     = 275;
    const T_AND_EQUAL                     = 276;
    const T_OR_EQUAL                      = 277;
    const T_XOR_EQUAL                     = 278;
    const T_SL_EQUAL                      = 279;
    const T_SR_EQUAL                      = 280;
    const T_POW_EQUAL                     = 281;
    const T_COALESCE_EQUAL                = 282;
    const T_COALESCE                      = 283;
    const T_BOOLEAN_OR                    = 284;
    const T_BOOLEAN_AND                   = 285;
    const T_IS_EQUAL                      = 286;
    const T_IS_NOT_EQUAL                  = 287;
    const T_IS_IDENTICAL                  = 288;
    const T_IS_NOT_IDENTICAL              = 289;
    const T_SPACESHIP                     = 290;
    const T_IS_SMALLER_OR_EQUAL           = 291;
    const T_IS_GREATER_OR_EQUAL           = 292;
    const T_SL                            = 293;
    const T_SR                            = 294;
    const T_INSTANCEOF                    = 295;
    const T_INC                           = 296;
    const T_DEC                           = 297;
    const T_INT_CAST                      = 298;
    const T_DOUBLE_CAST                   = 299;
    const T_STRING_CAST                   = 300;
    const T_ARRAY_CAST                    = 301;
    const T_OBJECT_CAST                   = 302;
    const T_BOOL_CAST                     = 303;
    const T_UNSET_CAST                    = 304;
    const T_POW                           = 305;
    const T_NEW                           = 306;
    const T_CLONE                         = 307;
    const T_ELSEIF                        = 309;
    const T_ELSE                          = 310;
    const T_ENDIF                         = 311;
    const T_STATIC                        = 312;
    const T_ABSTRACT                      = 313;
    const T_FINAL                         = 314;
    const T_PRIVATE                       = 315;
    const T_PROTECTED                     = 316;
    const T_PUBLIC                        = 317;
    const T_LNUMBER                       = 318;
    const T_DNUMBER                       = 319;
    const T_STRING                        = 320;
    const T_VARIABLE                      = 321;
    const T_INLINE_HTML                   = 322;
    const T_ENCAPSED_AND_WHITESPACE       = 323;
    const T_CONSTANT_ENCAPSED_STRING      = 324;
    const T_STRING_VARNAME                = 325;
    const T_NUM_STRING                    = 326;
    const T_EXIT                          = 327;
    const T_IF                            = 328;
    const T_ECHO                          = 329;
    const T_DO                            = 330;
    const T_WHILE                         = 331;
    const T_ENDWHILE                      = 332;
    const T_FOR                           = 333;
    const T_ENDFOR                        = 334;
    const T_FOREACH                       = 335;
    const T_ENDFOREACH                    = 336;
    const T_DECLARE                       = 337;
    const T_ENDDECLARE                    = 338;
    const T_AS                            = 339;
    const T_SWITCH                        = 340;
    const T_ENDSWITCH                     = 341;
    const T_CASE                          = 342;
    const T_DEFAULT                       = 343;
    const T_BREAK                         = 344;
    const T_CONTINUE                      = 345;
    const T_GOTO                          = 346;
    const T_FUNCTION                      = 347;
    const T_CONST                         = 348;
    const T_RETURN                        = 349;
    const T_TRY                           = 350;
    const T_CATCH                         = 351;
    const T_FINALLY                       = 352;
    const T_THROW                         = 353;
    const T_USE                           = 354;
    const T_INSTEADOF                     = 355;
    const T_GLOBAL                        = 356;
    const T_VAR                           = 357;
    const T_UNSET                         = 358;
    const T_ISSET                         = 359;
    const T_EMPTY                         = 360;
    const T_HALT_COMPILER                 = 361;
    const T_CLASS                         = 362;
    const T_TRAIT                         = 363;
    const T_INTERFACE                     = 364;
    const T_EXTENDS                       = 365;
    const T_IMPLEMENTS                    = 366;
    const T_OBJECT_OPERATOR               = 367;
    const T_LIST                          = 368;
    const T_ARRAY                         = 369;
    const T_CALLABLE                      = 370;
    const T_LINE                          = 371;
    const T_FILE                          = 372;
    const T_DIR                           = 373;
    const T_CLASS_C                       = 374;
    const T_TRAIT_C                       = 375;
    const T_METHOD_C                      = 376;
    const T_FUNC_C                        = 377;
    const T_COMMENT                       = 378;
    const T_DOC_COMMENT                   = 379;
    const T_OPEN_TAG                      = 380;
    const T_OPEN_TAG_WITH_ECHO            = 381;
    const T_CLOSE_TAG                     = 382;
    const T_WHITESPACE                    = 383;
    const T_START_HEREDOC                 = 384;
    const T_END_HEREDOC                   = 385;
    const T_DOLLAR_OPEN_CURLY_BRACES      = 386;
    const T_CURLY_OPEN                    = 387;
    const T_PAAMAYIM_NEKUDOTAYIM          = 388;
    const T_NAMESPACE                     = 389;
    const T_NS_C                          = 390;
    const T_NS_SEPARATOR                  = 391;
    const T_ELLIPSIS                      = 392;
    const T_DOUBLE_COLON                  = 388;

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
                     '|'  => self::T_PIPE,
                     '^'  => self::T_CARET,
                     '`'  => self::T_BACKTICK,
                   );
}
?>
