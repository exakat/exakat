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


class Php80 extends Php {

    // PHP tokens
    const T_THROW                         = 258;
    const T_INCLUDE                       = 260;
    const T_INCLUDE_ONCE                  = 261;
    const T_REQUIRE                       = 262;
    const T_REQUIRE_ONCE                  = 263;
    const T_LOGICAL_OR                    = 264;
    const T_LOGICAL_XOR                   = 265;
    const T_LOGICAL_AND                   = 266;
    const T_PRINT                         = 267;
    const T_YIELD                         = 268;
    const T_DOUBLE_ARROW                  = 269;
    const T_YIELD_FROM                    = 270;
    const T_PLUS_EQUAL                    = 271;
    const T_MINUS_EQUAL                   = 272;
    const T_MUL_EQUAL                     = 273;
    const T_DIV_EQUAL                     = 274;
    const T_CONCAT_EQUAL                  = 275;
    const T_MOD_EQUAL                     = 276;
    const T_AND_EQUAL                     = 277;
    const T_OR_EQUAL                      = 278;
    const T_XOR_EQUAL                     = 279;
    const T_SL_EQUAL                      = 280;
    const T_SR_EQUAL                      = 281;
    const T_POW_EQUAL                     = 282;
    const T_COALESCE_EQUAL                = 283;
    const T_COALESCE                      = 284;
    const T_BOOLEAN_OR                    = 285;
    const T_BOOLEAN_AND                   = 286;
    const T_IS_EQUAL                      = 287;
    const T_IS_NOT_EQUAL                  = 288;
    const T_IS_IDENTICAL                  = 289;
    const T_IS_NOT_IDENTICAL              = 290;
    const T_SPACESHIP                     = 291;
    const T_IS_SMALLER_OR_EQUAL           = 292;
    const T_IS_GREATER_OR_EQUAL           = 293;
    const T_SL                            = 294;
    const T_SR                            = 295;
    const T_INSTANCEOF                    = 296;
    const T_INT_CAST                      = 297;
    const T_DOUBLE_CAST                   = 298;
    const T_STRING_CAST                   = 299;
    const T_ARRAY_CAST                    = 300;
    const T_OBJECT_CAST                   = 301;
    const T_BOOL_CAST                     = 302;
    const T_UNSET_CAST                    = 303;
    const T_POW                           = 304;
    const T_CLONE                         = 305;
    const T_ELSEIF                        = 307;
    const T_ELSE                          = 308;
    const T_LNUMBER                       = 309;
    const T_DNUMBER                       = 310;
    const T_STRING                        = 311;
    const T_VARIABLE                      = 312;
    const T_INLINE_HTML                   = 313;
    const T_ENCAPSED_AND_WHITESPACE       = 314;
    const T_CONSTANT_ENCAPSED_STRING      = 315;
    const T_STRING_VARNAME                = 316;
    const T_NUM_STRING                    = 317;
    const T_EVAL                          = 318;
    const T_INC                           = 379;
    const T_DEC                           = 380;
    const T_NEW                           = 319;
    const T_EXIT                          = 320;
    const T_IF                            = 321;
    const T_ENDIF                         = 322;
    const T_ECHO                          = 323;
    const T_DO                            = 324;
    const T_WHILE                         = 325;
    const T_ENDWHILE                      = 326;
    const T_FOR                           = 327;
    const T_ENDFOR                        = 328;
    const T_FOREACH                       = 329;
    const T_ENDFOREACH                    = 330;
    const T_DECLARE                       = 331;
    const T_ENDDECLARE                    = 332;
    const T_AS                            = 333;
    const T_SWITCH                        = 334;
    const T_ENDSWITCH                     = 335;
    const T_CASE                          = 336;
    const T_DEFAULT                       = 337;
    const T_BREAK                         = 338;
    const T_CONTINUE                      = 339;
    const T_GOTO                          = 340;
    const T_FUNCTION                      = 341;
    const T_FN                            = 342;
    const T_CONST                         = 343;
    const T_RETURN                        = 344;
    const T_TRY                           = 345;
    const T_CATCH                         = 346;
    const T_FINALLY                       = 347;
    const T_USE                           = 348;
    const T_INSTEADOF                     = 349;
    const T_GLOBAL                        = 350;
    const T_STATIC                        = 351;
    const T_ABSTRACT                      = 352;
    const T_FINAL                         = 353;
    const T_PRIVATE                       = 354;
    const T_PROTECTED                     = 355;
    const T_PUBLIC                        = 356;
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
    const T_OBJECT_OPERATOR               = 381;
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
    const T_COMMENT                       = 382;
    const T_DOC_COMMENT                   = 383;
    const T_OPEN_TAG                      = 384;
    const T_OPEN_TAG_WITH_ECHO            = 385;
    const T_CLOSE_TAG                     = 386;
    const T_WHITESPACE                    = 387;
    const T_START_HEREDOC                 = 388;
    const T_END_HEREDOC                   = 389;
    const T_DOLLAR_OPEN_CURLY_BRACES      = 390;
    const T_CURLY_OPEN                    = 391;
    const T_PAAMAYIM_NEKUDOTAYIM          = 392;
    const T_NAMESPACE                     = 367;
    const T_NS_C                          = 378;
    const T_NS_SEPARATOR                  = 393;
    const T_ELLIPSIS                      = 394;
    const T_BAD_CHARACTER                 = 395;
    const T_DOUBLE_COLON                  = 392;
}
?>
