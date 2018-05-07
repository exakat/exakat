<?php

$finder = PhpCsFixer\Finder::create()
    ->in('./library/Exakat/Analyzer')
    ->in('./library/Exakat/Tasks')
    ->name('*.php');

return PhpCsFixer\Config::create()
    ->setRules(        
        array(
        'encoding' => true,
        'line_ending' => true,
        'elseif' => true,
        'no_trailing_whitespace' => true,
        'indentation_type' => true
        )
    )
    ->setFinder($finder)
;
