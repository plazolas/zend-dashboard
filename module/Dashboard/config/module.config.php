<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Dashboard;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'router_class' => TreeRouteStack::class,
        'routes' => [
            'home' => [
                'type' => Literal::class,
                //'priority' => 0,
                'options' => [
                    'route'    => '/home',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'dashboard' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/dashboard',
                    'defaults' => [
                        'controller' => Controller\DashboardController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'login' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/login[/:action]',
                    'defaults' => [
                        'controller' => Controller\LoginController::class,
                        'action'     => 'index',
                    ],
                ],
            ],

            'allergen' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/allergen',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\AllergenController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    // Segment route for viewing user by id
                    'edit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/edit/[:id]',
                            'constraints' => [
                                'id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'action' => 'edit',
                            ],
                        ],
                    ],
                    // Segment route for viewing user by id
                    'view' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/view/[:id]',
                            'constraints' => [
                                'id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'action' => 'view',
                            ],
                        ],
                    ],
                    'delete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/delete/[:id]',
                            'constraints' => [
                                'id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'action' => 'delete',
                            ],
                        ],
                    ],
                    'add' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/add',
                            'defaults' => [
                                'action' => 'add',
                            ]
                        ]
                    ]
                ],
            ],
            'learning' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/learning',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\LearningController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    // Segment route for viewing user by id
                    'edit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/edit/[:id]',
                            'constraints' => [
                                'id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'action' => 'edit',
                            ],
                        ],
                    ],
                    // Segment route for viewing user by id
                    'view' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/view/[:id]',
                            'constraints' => [
                                'id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'action' => 'view',
                            ],
                        ],
                    ],
                    'delete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/delete/[:id]',
                            'constraints' => [
                                'id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'action' => 'delete',
                            ],
                        ],
                    ],
                    'add' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/add',
                            'defaults' => [
                                'action' => 'add',
                            ]
                        ]
                    ]
                ],
            ],
            'user' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/user',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    // Segment route for viewing user by id
                    'edit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/edit/[:id]',
                            'constraints' => [
                                'id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'action' => 'edit',
                            ],
                        ],
                    ],
                    // Segment route for viewing user by id
                    'view' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/view/[:id]',
                            'constraints' => [
                                'id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'action' => 'view',
                            ],
                        ],
                    ],
                    'delete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/delete/[:id]',
                            'constraints' => [
                                'id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'action' => 'delete',
                            ],
                        ],
                    ],
                    'add' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/add',
                            'defaults' => [
                                'action' => 'add',
                            ]
                        ]
                    ]
                ],
            ],
        ],
    ],
    'navigation' => [
        'default' => [
            [
                'label' => 'Home',
                'route' => 'home',
            ],
            [
                'label' => 'Login',
                'route' => 'login',
            ],
            [
                'label' => 'Dashboard',
                'route' => 'dashboard',
                'class' => 'dropdown-menu',
                'pages' => [
                    [
                        'label'  => 'Users',
                        'route'  => 'user',
                        'action' => 'index',
                        'class'  => 'dropdown'
                    ],
                    [
                        'label'  => 'Allergens',
                        'route'  => 'allergen',
                        'action' => 'index',
                        'class'  => 'dropdown'
                    ],
                    [
                        'label'  => 'Learning Centers',
                        'route'  => 'learning',
                        'action' => 'index',
                        'class'  => 'dropdown'
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\DashboardController::class => InvokableFactory::class,
            Controller\DashboardController::class => Controller\Factory\DashboardControllerFactory::class,
            Controller\UserController::class => Controller\Factory\UserControllerFactory::class,
            Controller\AllergenController::class => Controller\Factory\AllergenControllerFactory::class,
            Controller\LearningController::class => Controller\Factory\LearningControllerFactory::class,
            Controller\LoginController::class => Controller\Factory\LoginControllerFactory::class,
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\MailSender::class => InvokableFactory::class
        ],
        'abstract_factories' => [
            'Zend\Db\Adapter\AdapterAbstractServiceFactory'
        ]
    ],
    'session_containers' => [
        'Dashboard'
    ],
    // The following registers our custom view
    // helper classes in view plugin manager.
    'view_helpers' => [
        'factories' => [
            View\Helper\Menu::class => InvokableFactory::class,
            View\Helper\Breadcrumbs::class => InvokableFactory::class,
        ],
        'aliases' => [
            'mainMenu' => View\Helper\Menu::class,
            'pageBreadcrumbs' => View\Helper\Breadcrumbs::class,
        ],
    ],
    'view_manager' => [
        'template_map' => [
            'display_not_found_reason' => true,
            'display_exceptions'       => true,
            'doctype'                  => 'HTML5',
            'not_found_template'       => 'error/404',
            'exception_template'       => 'error/index',
            'template_map' => [
                'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
                'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
                'error/404'               => __DIR__ . '/../view/error/404.phtml',
                'error/index'             => __DIR__ . '/../view/error/index.phtml',
                ]
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
