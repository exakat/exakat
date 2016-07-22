<?php

$version = substr(PHP_VERSION, 0, 1).substr(PHP_VERSION, 2,1);

$x = get_defined_constants(true); 
if (!isset($x['tokenizer'])) { 
    $x['tokenizer'] = array(); 
}; 

$php = '<?php 

namespace Tasks;

';

$total = 0;
foreach($x['tokenizer'] as $name => $value) {
    if (substr($name, 0, 2) != 'T_') { 
        continue; 
    }
    ++$total;

    $php .= "const $name = $value;\n";
}

$php .= "\n".'?>';

print "$total constants found for PHP ".PHP_VERSION."\n";

file_put_contents('library/Tokens/Const'.$version.'.php', $php);

?>