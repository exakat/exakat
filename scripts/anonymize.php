<?php

$file = $argv[1];

if (!file_exists($file)) {
    die("Usage : php script/anonymize.php <filename>");
}
print "Processing file $file into $file.anon\n";

$php = file_get_contents($file);
$tokens = token_get_all($php);

$lnumber = 0;
$variables = "a";
$strings = "A";

$php = '';
foreach($tokens as $t) {
    if (is_array($t)) {
        switch($t[0]) {
            case T_LNUMBER: 
                $t[1] = $lnumber++;
                break;
            case T_VARIABLE: 
                $t[1] = '$'.$variables++;
                break;
            case T_CONSTANT_ENCAPSED_STRING:
                $strings++;
                if (in_array($strings, array('IF', 'AS', 'DO', 'OR'))) { print "Skip T_CONSTANT_ENCAPSED_STRING : $strings\n"; $strings++; }
                $t[1] = "'".$strings."'";
                break;
            case T_STRING:
            case T_ENCAPSED_AND_WHITESPACE :
                $strings++;
                if (in_array($strings, array('IF', 'AS', 'DO', 'OR'))) { print "Skip T_ENCAPSED_AND_WHITESPACE : $strings\n"; $strings++; }
                $t[1] = $strings;
                break;
            case T_DOC_COMMENT:
            case T_COMMENT:
                $t[1] = "";
                break;

            case T_INLINE_HTML : 
                $t[1] = $strings++;
                break;

            case T_ISSET : 
            case T_EXIT : 

            case T_INT_CAST : 
            case T_STRING_CAST : 
            case T_ARRAY_CAST : 
            case T_RETURN :
            case T_SWITCH : 
            case T_CASE : 
            case T_DEFAULT : 
            case T_ENDSWITCH : 
            case T_ECHO : 
            case T_ENDFOREACH : 
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
            case T_MINUS_EQUAL :
            case T_WHILE : 
            case T_IS_GREATER_OR_EQUAL : 
            case T_PLUS_EQUAL : 
            case T_CLASS : 
            case T_CONTINUE :
            case T_WHITESPACE : 
            case T_AS : 
            case T_BOOLEAN_OR :
            case T_BOOLEAN_AND :
            case T_IS_GREATER_OR_EQUAL :
            case T_BREAK : 
            case T_CLASS_C : 
            case T_DEC :
            case T_DO :
            case T_FUNC_C :
            case T_IS_NOT_IDENTICAL : 
            case T_SR_EQUAL : 
            case T_XOR_EQUAL :
            case T_OR_EQUAL : 
            case T_IS_NOT_EQUAL : 
            case T_FOR :
            case T_FOREACH : 
            case T_FUNCTION : 
            case T_INC:
            case T_DOUBLE_COLON:
            case T_THROW:
            case T_NEW:
            case T_NS_SEPARATOR:
            case T_OBJECT_OPERATOR:
            case T_REQUIRE_ONCE:
            case T_DIR:
            case T_STATIC:
            case T_WHITESPACE:
            case T_OPEN_TAG:
            case T_CLOSE_TAG:
            case T_INSTANCEOF:

            case T_IF:
            case T_ELSEIF:
            case T_ENDIF : 

            case T_IS_IDENTICAL:
            case T_CONCAT_EQUAL:
                // simply ignore
                break;
            default: 
                print token_name($t[0])."\n";
                print_r($t);
        }

        $php .= $t[1];
    } else {
        $php .= $t;
    }
}
file_put_contents($file.'.anon', $php);

?>