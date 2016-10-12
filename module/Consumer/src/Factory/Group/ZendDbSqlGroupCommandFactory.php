<?php

namespace Consumer\Factory\Group;

use Interop\Container\ContainerInterface;
use Consumer\Model\Group\ZendDbSqlGroupCommand;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ZendDbSqlGroupCommandFactory
 *
 * @package Consumer\Factory\Group
 */
class ZendDbSqlGroupCommandFactory implements FactoryInterface
{
  /**
   * @param ContainerInterface $container
   * @param string             $requestedName
   * @param array|NULL         $options
   *
   * @return ZendDbSqlGroupCommand
   */
  public function __invoke(
    ContainerInterface $container,
    $requestedName,
    array $options = NULL
  ) {
    return new ZendDbSqlGroupCommand($container->get(AdapterInterface::class));
  }
}