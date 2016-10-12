<?php

namespace Consumer\Factory\Consumer;

use Consumer\Controller\Consumer\ListController;
use Consumer\Model\Consumer\ConsumerRepositoryInterface;
use Consumer\Model\Group\GroupRepositoryInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Consumer\Form\Consumer\FilterForm;

/**
 * Class ListControllerFactory
 *
 * @package Consumer\Factory\Consumer
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
    $formManager = $container->get('FormElementManager');
    
    return new ListController($container->get(ConsumerRepositoryInterface::class),
      $container->get(GroupRepositoryInterface::class),
      $formManager->get(FilterForm::class));
  }
}