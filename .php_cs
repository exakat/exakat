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
        'list_syntax'  => array('syntax' => 'long'),
        'elseif' => true,
        'lowercase_constants' => true,
        'new_with_braces' => true,
         'no_trailing_whitespace' => true,
         'single_quote' => true,
         'visibility_required' => true,
         'function_declaration' => true,
         'declare_equal_normalize' => array('space' => 'single'),
         'concat_space' => array('spacing' => 'one'),

// Interesting, but too many fixes at once
//        'no_extra_consecutive_blank_lines' => true,

// experimental
//         'yoda_style' => true,

// Too many problemes with double-escaped \ (inside gremlin queries)
//         'heredoc_to_nowdoc' => true,

// experimental
//         'braces' => [
//             'position_after_control_structures'            => 'same',
//             'position_after_functions_and_oop_constructs'  => 'same',
//             'position_after_anonymous_constructs'          => 'same',
//    ],
        )
    )
    ->setFinder($finder)
;
