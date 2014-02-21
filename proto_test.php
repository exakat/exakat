<?php

if (isset($argv[1])) { $size = $argv[1] + 0; }
else { $size = 4; }

print "Test for $size\n";

/*
big array, to test arguments
$code = "<?php \n\$x = array(0, ";

for($i = 1; $i < $size; $i++) {
    $code .= ', '.join(', ', range($i * 10, ($i + 1) * 10 - 1))."\n";
}

$code .= ");\n?>";
*/

/*
//big concatenation
$code = "<?php \n\$x = '0' ";

for($i = 1; $i < $size; $i++) {
    $code .= " . ".join(" . ", generate_code(10, $i * 10))."\n";
}

$code .= ";\n?>";

*/

//big sequences
$code = "<?php \n";

for($i = 1; $i < $size; $i++) {
    $code .= join(";\n", generate_sequence(10, $i * 10)).";\n";
}

$code .= "\n?>";

file_put_contents('test.php', $code);

function generate_code($nb = 10, $offset = 0) {
    $code = array('1|', '$a|', '$b|->c|', '(2| + 3|)', '$d|[1|]', '$e|[2|][3|]', 'f|(1|)', 'g|(1|,2|)', 'array(4|,5|,6|)', '7|');
    shuffle($code);
//    $code = range($offset + 0, $offset + 10);

    $r = array();
    
    for($i = $offset; $i < $offset + $nb; $i++) {
        $r[] = str_replace('|', $i, $code[array_rand($code, 1)]);
    }
    
    return $r;
}

function generate_sequence($nb = 10, $offset = 0) {
    $code = array('$x| = 1|', '$a|++', '$b|->c|', '(2| + 3|)');
    shuffle($code);
//    $code = range($offset + 0, $offset + 10);

    $r = array();
    
    for($i = $offset; $i < $offset + $nb; $i++) {
        $r[] = str_replace('|', $i, $code[array_rand($code, 1)]);
    }
    
    return $r;
}

?>