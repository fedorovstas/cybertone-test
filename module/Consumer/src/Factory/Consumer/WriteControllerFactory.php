<?php

namespace Consumer\Factory\Consumer;

use Consumer\Controller\Consumer\WriteController;
use Consumer\Form\Consumer\ConsumerForm;
use Consumer\Model\Consumer\ConsumerCommandInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Consumer\Model\Consumer\ConsumerRepositoryInterface;
use Consumer\Model\Group\GroupRepositoryInterface;

/**
 * Class WriteControllerFactory
 *
 * @package Consumer\Factory\Consumer
 */
class WriteControllerFactory implements FactoryInterface
{
  /**
   * @param ContainerInterface $container
   * @param string             $requestedName
   * @param array|NULL         $option
   *
   * @return WriteController
   */
  public function __invoke(
    ContainerInterface $container,
    $requestedName,
    array $option = NULL
  ) {
    $formManager = $container->get('FormElementManager');
    
    return new WriteController(
      $container->get(ConsumerCommandInterface::class),
      $formManager->get(ConsumerForm::class),
      $container->get(ConsumerRepositoryInterface::class),
      $container->get(GroupRepositoryInterface::class)
    );
  }
}