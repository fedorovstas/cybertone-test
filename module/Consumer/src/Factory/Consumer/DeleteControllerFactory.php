<?php

namespace Consumer\Factory\Consumer;

use Consumer\Controller\Consumer\DeleteController;
use Consumer\Model\Consumer\ConsumerCommandInterface;
use Consumer\Model\Consumer\ConsumerRepositoryInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class DeleteControllerFactory
 *
 * @package Consumer\Factory\Consumer
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
      $container->get(ConsumerCommandInterface::class),
      $container->get(ConsumerRepositoryInterface::class)
    );
  }
}