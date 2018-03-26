<?php
preview_theme_ob_filter_callback();
\get_post_type_labels();
preview_THEME_ob_filter_callback(2);
wp_get_post(1);

$a = '_transition_post_status';

$b = 'preview_theme_'.'ob_filter_callback';

$a = <<<HEREDOC
preview_theme_ob_filter_callback

HEREDOC;

function preview_theme_ob_filter_callback() { echo __METHOD__;}

$a = <<<'NOWDOC'
wp_set_sidebars_widgets
NOWDOC;


$a = <<<'NOWDOC'
wp_set_sidebars_widgets2
NOWDOC;




?>