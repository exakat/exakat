<?php

$finder = PhpCsFixer\Finder::create()
    ->in('./library/Exakat/Analyzer')
    ->in('./library/Exakat/Tasks')
    ->in('./library/Exakat/Reports')
    ->in('./library/Exakat/Data')
    ->in('./library/Exakat/Vcs')
    ->in('./library/Exakat/Loader')
    ->in('./library/Exakat/Graph')
    ->in('./library/Exakat/Query')
    ->name('*.php');

return PhpCsFixer\Config::create()
    ->setRules(        
        array(
        'encoding' => true,
        'line_ending' => true,
        'elseif' => true,
        'no_trailing_whitespace' => true,
        'indentation_type' => true,
        'array_syntax' => array('syntax' => 'long'),
        'elseif' => true,
        'lowercase_constants' => true,
        'new_with_braces' => true,
//        'no_extra_consecutive_blank_lines' => true,
         'no_trailing_whitespace' => true,
         'single_quote' => true,
//         'yoda_style' => true,
         'visibility_required' => true,
        )
    )
    ->setFinder($finder)
;
