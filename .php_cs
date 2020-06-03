<?php

$finder = PhpCsFixer\Finder::create()
    ->in('./library/Exakat/Dump')

    ->in('./library/Exakat/Analyzer')
    ->in('./library/Exakat/Reports')
    ->in('./library/Exakat/Data')
    ->in('./library/Exakat/Tasks')
    ->in('./library/Exakat/Vcs')
    ->in('./library/Exakat/Loader')

    ->in('./library/Exakat/Graph')

    ->in('./library/Exakat/Query')
    ->in('./library/Exakat/Analyzer')
    
    ->name('*.php');

return PhpCsFixer\Config::create()
    ->setRules(        
        array(
         'encoding'                        => true,
         'line_ending'                     => true,
         'elseif'                          => true,
         'no_trailing_whitespace'          => true,
         'indentation_type'                => true,
         'array_syntax'                    => array('syntax' => 'long'),
         'list_syntax'                     => array('syntax' => 'long'),
         'elseif'                          => true,
         'lowercase_constants'             => true,
         'new_with_braces'                 => true,
         'no_trailing_whitespace'          => true,
         'single_quote'                    => true,
         'visibility_required'             => true,
         'function_declaration'            => true,
         'declare_equal_normalize'         => array('space' => 'single'),
         'concat_space'                    => array('spacing' => 'one'),
         'no_leading_import_slash'         => true,
         'cast_spaces'                     => array('space' => 'single'),
         'no_unused_imports'               => true,
         'no_useless_return'               => true,
         'no_whitespace_in_blank_line'     => true,
         'return_type_declaration'         => true,
         'single_line_after_imports'       => true,
         'standardize_not_equals'          => true,
         'ternary_operator_spaces'         => true,
         'whitespace_after_comma_in_array' => true,
         'no_unused_imports'               => true,
         'declare_strict_types'            => true,
         'fopen_flag_order'                => true,
         'fully_qualified_strict_types'    => true,
         'full_opening_tag'                => true,
         'function_typehint_space'         => true,
         'heredoc_to_nowdoc'               => true,
         'increment_style'                 => true,
         
         
         
// Risky
//         'self_accessor' => true,
//         'static_lambda' => true,

//         'braces' => array('position_after_functions_and_oop_constructs' => 'same', 
                ),

// Interesting, but too many fixes at once
//         'class_definition' => false,
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
    ->setFinder($finder)
;
