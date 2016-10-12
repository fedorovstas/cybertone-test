<?php

namespace Consumer\Factory\Consumer;

use Interop\Container\ContainerInterface;
use Consumer\Model\Consumer\ZendDbSqlConsumerRepository;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Consumer\Model\Consumer\Consumer;

/**
 * Class ZendDbSqlConsumerRepositoryFactory
 *
 * @package Consumer\Factory\Consumer
 */
class ZendDbSqlConsumerRepositoryFactory implements FactoryInterface
{
  /**
   * @param ContainerInterface $container
   * @param string             $requestedName
   * @param array|NULL         $options
   *
   * @return ZendDbSqlConsumerRepository
   */
  public function __invoke(
    ContainerInterface $container,
    $requestedName,
    array $options = NULL
  ) {
    return new ZendDbSqlConsumerRepository(
      $container->get(AdapterInterface::class),
      new ReflectionHydrator(),
      new Consumer()
    );
  }
}