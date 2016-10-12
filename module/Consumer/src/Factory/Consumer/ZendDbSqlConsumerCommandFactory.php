<?php

namespace Consumer\Factory\Consumer;

use Interop\Container\ContainerInterface;
use Consumer\Model\Consumer\ZendDbSqlConsumerCommand;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ZendDbSqlConsumerCommandFactory
 *
 * @package Consumer\Factory\Consumer
 */
class ZendDbSqlConsumerCommandFactory implements FactoryInterface
{
  /**
   * @param ContainerInterface $container
   * @param string             $requestedName
   * @param array|NULL         $options
   *
   * @return ZendDbSqlConsumerCommand
   */
  public function __invoke(
    ContainerInterface $container,
    $requestedName,
    array $options = NULL
  ) {
    return new ZendDbSqlConsumerCommand($container->get(AdapterInterface::class));
  }
}