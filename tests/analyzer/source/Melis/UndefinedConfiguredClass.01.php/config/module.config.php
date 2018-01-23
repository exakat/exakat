<?php

class Foo {}
class Foo2 {}
/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2017 Melis Technology (http://www.melistechnology.com)
 *
 */

return array(
    'router' => array(
        'routes' => array(
        	'melis-backoffice' => array(
                'child_routes' => array(
                    'application-MelisMessenger' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => 'MelisMessenger',
                            'defaults' => array(
                                '__NAMESPACE__' => 'MelisMessenger\Controller',
                                'controller'    => 'Index',
                                'action'        => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'default' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/[:controller[/:action][/:id]]',
                                    'constraints' => array(
                                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id'     => '[0-9]+',
                                    ),
                                    'defaults' => array(
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),    
        	),
        ),
    ),
    'translator' => array(
        'locale' => 'en_EN',
    ),
    'service_manager' => array(
        'aliases' => array(
            'translator' => 'MvcTranslator',
            'MelisMessengerMsgTable' => 'Foo',
            'MelisMessengerMsgContentTable' => 'Undefined',
        ),
        'factories' => array(
            'MelisMessengerMsgTable' => 'Foo2',
            'MelisMessengerMsgContentTable' => 'Undefined2',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'MelisMessenger\Controller\MelisMessenger' => 'MelisMessenger\Controller\MelisMessengerController',
        ),
    ),
    'form_elements' => array(
        'factories' => array(
            'MelisMessengerInput' => 'MelisMessenger\Form\Factory\MelisMessengerInputFactory',
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            'MelisMessengerFieldCollection' => 'MelisMessenger\Form\View\Helper\MelisMessengerFieldCollection',
            'MelisMessengerFieldRow' => 'MelisMessenger\Form\View\Helper\MelisMessengerFieldRow',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_map' => array(
            'layout/layout'             => __DIR__ . '/../view/layout/default.phtml',
            'MelisMessenger/test'       => __DIR__ . '/../view/melis-messenger/melis-messenger/plugins/test.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
