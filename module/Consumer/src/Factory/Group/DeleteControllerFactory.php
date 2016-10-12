<?php

namespace Consumer\Factory\Group;

use Consumer\Controller\Group\DeleteController;
use Consumer\Model\Group\GroupCommandInterface;
use Consumer\Model\Group\GroupRepositoryInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class DeleteControllerFactory
 *
 * @package Consumer\Factory\Group
 */
class DeleteControllerFactory implements FactoryInterface
{
  /**
   * @param ContainerInterface $container
   * @param string             $requestedName
   * @param array|NULL         $options
   *
   * @return DeleteController
   */
  public function __invoke(
    ContainerInterface $container,
    $requestedName,
    array $options = NULL
  ) {
    return new DeleteController(
      $container->get(GroupCommandInterface::class),
      $container->get(GroupRepositoryInterface::class)
    );
  }
}