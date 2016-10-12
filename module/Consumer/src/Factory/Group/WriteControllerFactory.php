<?php

namespace Consumer\Factory\Group;

use Consumer\Controller\Group\WriteController;
use Consumer\Form\Group\GroupForm;
use Consumer\Model\Group\GroupCommandInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Consumer\Model\Group\GroupRepositoryInterface;

/**
 * Class WriteControllerFactory
 *
 * @package Consumer\Factory\Group
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
      $container->get(GroupCommandInterface::class),
      $formManager->get(GroupForm::class),
      $container->get(GroupRepositoryInterface::class)
    );
  }
}