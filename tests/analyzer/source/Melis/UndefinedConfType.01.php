<?php 

return array(
    'plugins' => array(
     	'meliscmsnews_none' => array(
     		'conf' => array(
     			'type' => '/a',
     		),
     	),
     	'meliscmsnews_yes' => array(
     		'conf' => array(
     			'type' => '/b',
     		),
     	),
     	'meliscmsnews_none1' => array(
     		'conf' => array(
     			'type' => '/b/interface/c',
     		),
     	),
     	'meliscmsnews_yes1' => array(
     		'conf' => array(
     			'type' => '/b/interface/d',
     		),
     	),
     	'meliscmsnews_yes2' => array(
     		'conf' => array(
     			'type' => '/e/interface/f/interface/g',
     		),
     	),
    'meliscmsnews' => array(
        'interface' => array(
            'meliscmsnews_list' => array(),
        )
    ),
    // a is missing
    'b' => array(
        'interface' => array(
            'd' => array(
                'interface' => array(),
            ),
        )
    ),
    'e' => array(
        'interface' => array(
            'f' => array(
                'interface' => array(
                    'g' => array(
                        'interface' => array(),
                    ),
                ),
            ),
        )
    ),
),    
    
);