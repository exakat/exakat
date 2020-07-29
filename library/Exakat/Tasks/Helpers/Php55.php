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

class Php55 extends Php {

    // PHP tokens
    const T_REQUIRE_ONCE                  = 258;
    const T_REQUIRE                       = 259;
    const T_EVAL                          = 260;
    const T_INCLUDE_ONCE                  = 261;
    const T_INCLUDE                       = 262;
    const T_LOGICAL_OR                    = 263;
    const T_LOGICAL_XOR                   = 264;
    const T_LOGICAL_AND                   = 265;
    const T_PRINT                         = 266;
    const T_SR_EQUAL                      = 268;
    const T_SL_EQUAL                      = 269;
    const T_XOR_EQUAL                     = 270;
    const T_OR_EQUAL                      = 271;
    const T_AND_EQUAL                     = 272;
    const T_MOD_EQUAL                     = 273;
    const T_CONCAT_EQUAL                  = 274;
    const T_DIV_EQUAL                     = 275;
    const T_MUL_EQUAL                     = 276;
    const T_MINUS_EQUAL                   = 277;
    const T_PLUS_EQUAL                    = 278;
    const T_BOOLEAN_OR                    = 279;
    const T_BOOLEAN_AND                   = 280;
    const T_IS_NOT_IDENTICAL              = 281;
    const T_IS_IDENTICAL                  = 282;
    const T_IS_NOT_EQUAL                  = 283;
    const T_IS_EQUAL                      = 284;
    const T_IS_GREATER_OR_EQUAL           = 285;
    const T_IS_SMALLER_OR_EQUAL           = 286;
    const T_SR                            = 287;
    const T_SL                            = 288;
    const T_INSTANCEOF                    = 289;
    const T_UNSET_CAST                    = 290;
    const T_BOOL_CAST                     = 291;
    const T_OBJECT_CAST                   = 292;
    const T_ARRAY_CAST                    = 293;
    const T_STRING_CAST                   = 294;
    const T_DOUBLE_CAST                   = 295;
    const T_INT_CAST                      = 296;
    const T_DEC                           = 297;
    const T_INC                           = 298;
    const T_CLONE                         = 299;
    const T_NEW                           = 300;
    const T_EXIT                          = 301;
    const T_IF                            = 302;
    const T_ELSEIF                        = 303;
    const T_ELSE                          = 304;
    const T_ENDIF                         = 305;
    const T_LNUMBER                       = 306;
    const T_DNUMBER                       = 307;
    const T_STRING                        = 308;
    const T_STRING_VARNAME                = 309;
    const T_VARIABLE                      = 310;
    const T_NUM_STRING                    = 311;
    const T_INLINE_HTML                   = 312;
    const T_CHARACTER                     = 313;
    const T_BAD_CHARACTER                 = 314;
    const T_ENCAPSED_AND_WHITESPACE       = 315;
    const T_CONSTANT_ENCAPSED_STRING      = 316;
    const T_ECHO                          = 317;
    const T_DO                            = 318;
    const T_WHILE                         = 319;
    const T_ENDWHILE                      = 320;
    const T_FOR                           = 321;
    const T_ENDFOR                        = 322;
    const T_FOREACH                       = 323;
    const T_ENDFOREACH                    = 324;
    const T_DECLARE                       = 325;
    const T_ENDDECLARE                    = 326;
    const T_AS                            = 327;
    const T_SWITCH                        = 328;
    const T_ENDSWITCH                     = 329;
    const T_CASE                          = 330;
    const T_DEFAULT                       = 331;
    const T_BREAK                         = 332;
    const T_CONTINUE                      = 333;
    const T_GOTO                          = 334;
    const T_FUNCTION                      = 335;
    const T_CONST                         = 336;
    const T_RETURN                        = 337;
    const T_YIELD                         = 267;
    const T_TRY                           = 338;
    const T_CATCH                         = 339;
    const T_FINALLY                       = 340;
    const T_THROW                         = 341;
    const T_USE                           = 342;
    const T_INSTEADOF                     = 343;
    const T_GLOBAL                        = 344;
    const T_PUBLIC                        = 345;
    const T_PROTECTED                     = 346;
    const T_PRIVATE                       = 347;
    const T_FINAL                         = 348;
    const T_ABSTRACT                      = 349;
    const T_STATIC                        = 350;
    const T_VAR                           = 351;
    const T_UNSET                         = 352;
    const T_ISSET                         = 353;
    const T_EMPTY                         = 354;
    const T_HALT_COMPILER                 = 355;
    const T_CLASS                         = 356;
    const T_TRAIT                         = 357;
    const T_INTERFACE                     = 358;
    const T_EXTENDS                       = 359;
    const T_IMPLEMENTS                    = 360;
    const T_OBJECT_OPERATOR               = 361;
    const T_DOUBLE_ARROW                  = 362;
    const T_LIST                          = 363;
    const T_ARRAY                         = 364;
    const T_CALLABLE                      = 365;
    const T_CLASS_C                       = 366;
    const T_TRAIT_C                       = 367;
    const T_METHOD_C                      = 368;
    const T_FUNC_C                        = 369;
    const T_LINE                          = 370;
    const T_FILE                          = 371;
    const T_COMMENT                       = 372;
    const T_DOC_COMMENT                   = 373;
    const T_OPEN_TAG                      = 374;
    const T_OPEN_TAG_WITH_ECHO            = 375;
    const T_CLOSE_TAG                     = 376;
    const T_WHITESPACE                    = 377;
    const T_START_HEREDOC                 = 378;
    const T_END_HEREDOC                   = 379;
    const T_DOLLAR_OPEN_CURLY_BRACES      = 380;
    const T_CURLY_OPEN                    = 381;
    const T_PAAMAYIM_NEKUDOTAYIM          = 382;
    const T_NAMESPACE                     = 383;
    const T_NS_C                          = 384;
    const T_DIR                           = 385;
    const T_NS_SEPARATOR                  = 386;
    const T_DOUBLE_COLON                  = 382;

    const T_SPACESHIP                     = 1000;
    const T_YIELD_FROM                    = 1000;
    const T_COALESCE                      = 1000;
    const T_COALESCE_EQUAL                = 1000;
    const T_FN                            = 1000;
    const T_POW_EQUAL                     = 1000;
    const T_POW                           = 1000;
    const T_ELLIPSIS                      = 1000;
    const T_NAME_FULLY_QUALIFIED          = 1000;
    const T_NAME_RELATIVE                 = 1000;
    const T_NAME_QUALIFIED                = 1000;
    const T_NULLSAFE_OBJECT_OPERATOR      = 1000;
    const T_MATCH                         = 1000;
    const T_ATTRIBUTE                     = 1000;
}
?>
