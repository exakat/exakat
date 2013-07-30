<?php

$args = $argv;

$count = @$args[2] ?: 0;
$which = @$args[1] ?: '';

$function = "create_$which";

if ($count == 0) {
    die("Need a count of 1 or more ($count provided)\n Aborting\n");
}

if (empty($which)) {
    die("Need an instruction to test (none provided)\n Aborting\n");
}

if (!function_exists($function)) {
    die("No generator for $which\n Aborting\n");
}

$function($count);

function create_multiplication ($count) {
    $code = "<?php\n/* $count multiplications */\n";
    
    for($i = 0; $i < $count; $i++) {
        if (empty($operators)) {
            $operators = array('*', '/', 'mod');
            shuffle($operators);
        }
        $operator = array_shift($operators);
        $code .= "\$a$i = \$b$i $operator \$c$i;\n";
    }
    
    $code .= "\n?>";
    
    file_put_contents(substr(__FUNCTION__, 7).".$count.php", $code);
    
    return true;
}

function create_addition ($count) {
    $code = "<?php\n/* $count multiplications */\n";
    
    for($i = 0; $i < $count; $i++) {
        if (empty($operators)) {
            $operators = array('+', '-');
            shuffle($operators);
        }
        $operator = array_shift($operators);
        $code .= "\$a$i = \$b$i $operator \$c$i;\n";
    }
    
    $code .= "\n?>";
    
    file_put_contents(substr(__FUNCTION__, 7).".$count.php", $code);
    
    return true;
}

function create_sign ($count) {
    $code = "<?php\n/* $count multiplications */\n";
    
    for($i = 0; $i < $count; $i++) {
        if (empty($operators)) {
            $operators = array('', '-', '+',);
            shuffle($operators);
        }
        $operator = array_shift($operators);
        $code .= "\$a$i = $operator\$b$i;\n";
    }
    
    $code .= "\n?>";
    
    file_put_contents(substr(__FUNCTION__, 7).".$count.php", $code);
    
    return true;
}
