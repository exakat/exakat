<?php

return array(
    'router' => array(
        'routes' => array(
            'melis-front' => array(
                'type'    => 'regex',
                'options' => array(
                    'regex'    => '.*/id/(?<idpage>[0-9]+)',
            ),
            'melis-front2' => array(
                'type'    => 'regex',
                'options' => array(
                    'regex'    => '.*/id/(?<idpage>[0-9+)',
            )
          )
        ) 
      )
    )
  );

?>