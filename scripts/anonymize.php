<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


if (!isset($argv[1])) {
    die("Usage : php script/anonymize.php <filename>\n");
}

$file = $argv[1];

if (!file_exists($file)) {
    die("Usage : php script/anonymize.php <filename>");
}
echo "Processing file $file into $file.anon\n";

$res = shell_exec($_SERVER['_'].' -l '.$file);
if (substr($res, 0, 28) != 'No syntax errors detected in') {
    die( "Can't compile '$file' script with PHP version ".phpversion().".\n");
}

$php = file_get_contents($file);
$tokens = token_get_all($php);

$lnumberValues = array();
$lnumber = 0;
$dnumberValues = array();
$dnumber = 0;
$variableNames = array();
$variables = "a";
$stringsNames = array();
$strings = "A";

$checks = array('T_TRAIT', 'T_FINALLY', 'T_YIELD');
foreach($checks as $check) {
    if (!defined($check)) { 
        define($check, 1); 
    } 
}

$php = '';
foreach($tokens as $t) {
    if (is_array($t)) {
        switch($t[0]) {
            case T_LNUMBER:  // integers
                if (isset($lnumberValues[$t[1]])) {
                    $t[1] = $lnumberValues[$t[1]];
                } else {
                    $lnumberValues[$t[1]] = $lnumber++;
                    $t[1] = $lnumberValues[$t[1]];
                }
                break;
            case T_DNUMBER:  // real numbers
                if (isset($dnumberValues[$t[1]])) {
                    $t[1] = floor(rand(0, 100000) ) / 100;
                } else {
                    $dnumberValues[$t[1]] = $dnumber++;
                    $t[1] = floor(rand(0, 100000) ) / 100;
                }
                break;
            case T_VARIABLE: 
                if ($t[1] != '$this') {
                    if (isset($variableNames[$t[1]])) {
                        $t[1] = $variableNames[$t[1]];
                    } else {
                        $variableNames[$t[1]] = '$'.$variables++;
                        $t[1] = $variableNames[$t[1]];
                    }
                }
                break;
            case T_CONSTANT_ENCAPSED_STRING:
                $strings++;
                if (in_array($strings, array('IF', 'AS', 'DO', 'OR'))) { 
                    echo 'Skip T_CONSTANT_ENCAPSED_STRING : ', $strings, "\n"; 
                    $strings++; 
                }
                if (isset($stringsNames[$t[1]])) {
                    $t[1] = $stringsNames[$t[1]];
                } else {
                    $stringsNames[$t[1]] = "'".$strings."'";
                    $t[1] = $stringsNames[$t[1]];
                }
                break;
            case T_STRING:
            case T_NUM_STRING:
                if (strtolower($t[1]) == 'null') { break ; }
                // otherwise, fall through!
            case T_ENCAPSED_AND_WHITESPACE :
                $strings++;
                if (in_array($strings, array('IF', 'AS', 'DO', 'OR'))) { 
                    echo 'Skip T_ENCAPSED_AND_WHITESPACE : ', $strings, "\n"; 
                    $strings++; 
                }
                if (isset($stringsNames[$t[1]])) {
                    $t[1] = $stringsNames[$t[1]];
                } else {
                    $stringsNames[$t[1]] = $strings;
                    $t[1] = $stringsNames[$t[1]];
                }
                break;
            case T_DOC_COMMENT:
            case T_COMMENT:
                $t[1] = "";
                break;

            case T_INLINE_HTML : 
                $strings++;
                if (isset($stringsNames[$t[1]])) {
                    $t[1] = $stringsNames[$t[1]];
                } else {
                    $stringsNames[$t[1]] = $strings;
                    $t[1] = $stringsNames[$t[1]];
                }
                break;

            case T_START_HEREDOC:
                $strings++;
                $short = substr($t[1], 3);

                if (!isset($stringsNames[$short])) {
                    $stringsNames[$short] = $strings;
                }

                if ($short[0] == "'") {
                    $t[1] = "<<<'".$stringsNames[$short]."'\n";
                } else {
                    $t[1] = '<<<'.$stringsNames[$short]."\n";
                }

                $heredoc = "\n".$stringsNames[$short];
                
                break;
                
            case T_END_HEREDOC: 
                $t[1] = $heredoc;
                (unset) $heredoc;

                break;

            case T_ISSET : 
            case T_EXIT : 

            case T_ARRAY_CAST : 
            case T_BOOL_CAST : 
            case T_DOUBLE_CAST : 
            case T_OBJECT_CAST : 
            case T_UNSET_CAST : 
            case T_INT_CAST : 
            case T_STRING_CAST : 

            case T_CONST :
            case T_LIST :
            
            case T_NAMESPACE : 
            case T_IMPLEMENTS : 
            
            case T_RETURN :
            case T_SWITCH : 
            case T_CASE : 
            case T_DEFAULT : 
            case T_ENDSWITCH : 
            case T_ECHO : 
            case T_PRINT : 
            case T_EMPTY : 
            case T_ARRAY :
            case T_GLOBAL : 
            case T_TRY : 
            case T_CATCH : 
            case T_DOUBLE_ARROW : 
            case T_CURLY_OPEN:
            case T_ELSE : 
            case T_PUBLIC : 
            case T_PROTECTED : 
            case T_PRIVATE : 
            case T_SL : 
            case T_SR : 
            case T_IS_EQUAL :
            case T_IS_SMALLER_OR_EQUAL :
            case T_MINUS_EQUAL :
            case T_WHILE : 
            case T_ENDWHILE : 
            case T_IS_GREATER_OR_EQUAL : 
            case T_PLUS_EQUAL : 
            case T_POW : 
            case T_CLASS : 
            case T_INTERFACE : 
            case T_CONTINUE :
            case T_WHITESPACE : 
            case T_AS : 
            case T_BOOLEAN_OR :
            case T_BOOLEAN_AND :
            case T_BREAK : 
            case T_DEC :
            case T_DO :
            case T_IS_NOT_IDENTICAL : 
            case T_SR_EQUAL : 
            case T_XOR_EQUAL :
            case T_OR_EQUAL : 
            case T_IS_NOT_EQUAL : 
            
            case T_OPEN_TAG_WITH_ECHO : 
            case T_CALLABLE : 
            case T_UNSET : 

            case T_DOLLAR_OPEN_CURLY_BRACES : 

            case T_FINALLY : 
            case T_YIELD : 

            case T_FOR :
            case T_ENDFOR :
            case T_FOREACH : 
            case T_ENDFOREACH : 

            case T_FUNCTION : 
            case T_INC:
            case T_DOUBLE_COLON:
            case T_THROW:
            case T_NEW:
            case T_CLONE:
            case T_ELLIPSIS:
            case T_NS_SEPARATOR:
            case T_OBJECT_OPERATOR:
            case T_DIR:
            case T_STATIC:
            case T_VAR : 
            case T_OPEN_TAG:
            case T_CLOSE_TAG:
            case T_INSTANCEOF:

            case T_INCLUDE : 
            case T_INCLUDE_ONCE : 
            case T_REQUIRE : 
            case T_REQUIRE_ONCE : 

            case T_TRAIT : 
            case T_EXTENDS : 
            case T_USE : 
            case T_INSTEADOF : 

            case T_LOGICAL_AND : 
            case T_LOGICAL_OR : 
            case T_LOGICAL_XOR : 

            case T_IF:
            case T_ELSEIF:
            case T_ENDIF : 

            case T_FILE : 
            case T_CLASS_C : 
            case T_FUNC_C : 
            case T_LINE : 
            case T_METHOD_C : 
            case T_NS_C : 

            case T_IS_IDENTICAL:
            case T_CONCAT_EQUAL:
                // simply ignore
                break;

            default: 
                echo token_name($t[0]), "\n", print_r($t, true);
        }

        $php .= $t[1];
    } else {
        $php .= $t;
    }
}
file_put_contents($file.'.anon', $php);

?>