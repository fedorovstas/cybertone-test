<?php

namespace Consumer\Factory\Group;

use Interop\Container\ContainerInterface;
use Consumer\Model\Group\ZendDbSqlGroupRepository;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Consumer\Model\Group\Group;

/**
 * Class ZendDbSqlGroupRepositoryFactory
 *
 * @package Consumer\Factory\Group
 */
class ZendDbSqlGroupRepositoryFactory implements FactoryInterface
{
  /**
   * @param ContainerInterface $container
   * @param string             $requestedName
   * @param array|NULL         $options
   *
   * @return ZendDbSqlGroupRepository
   */
  public function __invoke(
    ContainerInterface $container,
    $requestedName,
    array $options = NULL
  ) {
    return new ZendDbSqlGroupRepository(
      $container->get(AdapterInterface::class),
      new ReflectionHydrator(),
      new Group()
    );
  }
}