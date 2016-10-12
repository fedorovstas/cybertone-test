<?php

namespace Consumer;

use Consumer\Factory\Consumer\ZendDbSqlConsumerRepositoryFactory;
use Consumer\Factory\Consumer\ZendDbSqlConsumerCommandFactory;
use Consumer\Factory\Group\ZendDbSqlGroupRepositoryFactory;
use Consumer\Factory\Group\ZendDbSqlGroupCommandFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
  'service_manager' => [
    'aliases'   => [
      //consumer
      Model\Consumer\ConsumerRepositoryInterface::class => Model\Consumer\ZendDbSqlConsumerRepository::class,
      Model\Consumer\ConsumerCommandInterface::class    => Model\Consumer\ZendDbSqlConsumerCommand::class,
      // group
      Model\Group\GroupRepositoryInterface::class       => Model\Group\ZendDbSqlGroupRepository::class,
      Model\Group\GroupCommandInterface::class          => Model\Group\ZendDbSqlGroupCommand::class,
    ],
    'factories' => [
      //consumer
      Model\Consumer\ZendDbSqlConsumerRepository::class => Factory\Consumer\ZendDbSqlConsumerRepositoryFactory::class,
      Model\Consumer\ZendDbSqlConsumerCommand::class    => Factory\Consumer\ZendDbSqlConsumerCommandFactory::class,
      // group
      Model\Group\ZendDbSqlGroupRepository::class       => Factory\Group\ZendDbSqlGroupRepositoryFactory::class,
      Model\Group\ZendDbSqlGroupCommand::class          => Factory\Group\ZendDbSqlGroupCommandFactory::class,
    ],
  ],
  'controllers'     => [
    'factories' => [
      //consumer
      Controller\Consumer\ListController::class   => Factory\Consumer\ListControllerFactory::class,
      Controller\Consumer\WriteController::class  => Factory\Consumer\WriteControllerFactory::class,
      Controller\Consumer\DeleteController::class => Factory\Consumer\DeleteControllerFactory::class,
      // group
      Controller\Group\ListController::class      => Factory\Group\ListControllerFactory::class,
      Controller\Group\WriteController::class     => Factory\Group\WriteControllerFactory::class,
      Controller\Group\DeleteController::class    => Factory\Group\DeleteControllerFactory::class,
    ],
  ],
  'router'          => [
    'routes' => [
      'consumer' => [
        'type'          => Literal::class,
        'options'       => [
          'route'    => '/consumer',
          'defaults' => [
            'controller' => Controller\Consumer\ListController::class,
            'action'     => 'index',
          ],
        ],
        'may_terminate' => TRUE,
        'child_routes'  => [
          'group'  => [
            'type'          => Literal::class,
            'options'       => [
              'route'    => '/group',
              'defaults' => [
                'controller' => Controller\Group\ListController::class,
                'action'     => 'index',
              ],
            ],
            'may_terminate' => TRUE,
            'child_routes'  => [
              'delete' => [
                'type'    => Segment::class,
                'options' => [
                  'route'       => '/delete/:id',
                  'defaults'    => [
                    'controller' => Controller\Group\DeleteController::class,
                    'action'     => 'delete',
                  ],
                  'constraints' => [
                    'id' => '[1-9]\d*',
                  ],
                ],
              ],
              'edit'   => [
                'type'    => Segment::class,
                'options' => [
                  'route'      => '/edit/:id',
                  'defaults'   => [
                    'controller' => Controller\Group\WriteController::class,
                    'action'     => 'edit',
                  ],
                  'constraits' => [
                    'id' => '[1-9]\d*',
                  ],
                ],
              ],
              'add'    => [
                'type'    => Literal::class,
                'options' => [
                  'route'    => '/add',
                  'defaults' => [
                    'controller' => Controller\Group\WriteController::class,
                    'action'     => 'add',
                  ],
                ],
              ],
              'detail' => [
                'type'    => Segment::class,
                'options' => [
                  'route'       => '/:id',
                  'defaults'    => [
                    'action' => 'detail',
                  ],
                  'constraints' => [
                    'id' => '[1-9]\d*',
                  ],
                ],
              ],
            ],
          ],
          'delete' => [
            'type'    => Segment::class,
            'options' => [
              'route'       => '/delete/:id',
              'defaults'    => [
                'controller' => Controller\Consumer\DeleteController::class,
                'action'     => 'delete',
              ],
              'constraints' => [
                'id' => '[1-9]\d*',
              ],
            ],
          ],
          'edit'   => [
            'type'    => Segment::class,
            'options' => [
              'route'      => '/edit/:id',
              'defaults'   => [
                'controller' => Controller\Consumer\WriteController::class,
                'action'     => 'edit',
              ],
              'constraits' => [
                'id' => '[1-9]\d*',
              ],
            ],
          ],
          'add'    => [
            'type'    => Literal::class,
            'options' => [
              'route'    => '/add',
              'defaults' => [
                'controller' => Controller\Consumer\WriteController::class,
                'action'     => 'add',
              ],
            ],
          ],
          'detail' => [
            'type'    => Segment::class,
            'options' => [
              'route'       => '/:id',
              'defaults'    => [
                'action' => 'detail',
              ],
              'constraints' => [
                'id' => '[1-9]\d*',
              ],
            ],
          ],
        ],
      ],
    ],
  ],
  'view_manager'    => [
    'template_path_stack' => [
      __DIR__ . '/../view',
    ],
  ],
];