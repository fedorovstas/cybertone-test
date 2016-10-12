<?php

namespace Login;

use Zend\Router\Http\Literal;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
  'router' => [
    'routes' => [
      'login' => [
        'type' => Literal::class,
        'options' => [
          'route' => '/login',
          'defaults' => [
            'controller' => Controller\LoginController::class,
            'action' => 'index',
          ],
        
        ],
        'may_terminate' => TRUE,
        'child_routes' => [
          'check' => [
            'type' => Literal::class,
            'options' => [
              'route' => '/check',
              'defaults' => [
                'controller' => Controller\LoginController::class,
                'action' => 'check',
              ],
            ],
          ],
        ],
      ],
    ],
  ],
  'controllers' => [
    'factories' => [
      Controller\LoginController::class => InvokableFactory::class,
    ],
  ],
  'view_manager'    => [
    'template_path_stack' => [
      __DIR__ . '/../view',
    ],
  ],
];