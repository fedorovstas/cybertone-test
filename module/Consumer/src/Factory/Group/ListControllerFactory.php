<?php

namespace Consumer\Factory\Group;

use Consumer\Controller\Group\ListController;
use Consumer\Model\Group\GroupRepositoryInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ListControllerFactory
 *
 * @package Consumer\Factory\Group
 */
class ListControllerFactory implements FactoryInterface
{
  /**
   * @param ContainerInterface $container
   * @param string             $requestedName
   * @param array|NULL         $options
   *
   * @return ListController
   */
  public function __invoke(
    ContainerInterface $container,
    $requestedName,
    array $options = NULL
  ) {
    return new ListController($container->get(GroupRepositoryInterface::class));
  }
}