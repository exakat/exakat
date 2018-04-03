<?php

$expected     = array('\'_transition_post_status\'',
                      'preview_theme_ob_filter_callback',
                      'wp_set_sidebars_widgets',
                      '\\get_post_type_labels( )',
                      'preview_theme_ob_filter_callback( )',
                      'preview_THEME_ob_filter_callback(2)',
                     );

$expected_not = array('wp_get_post(1)',
                      'wp_set_sidebars_widgets2',
                     );

?>