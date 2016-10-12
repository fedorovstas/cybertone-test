<?php

namespace Consumer\Model\Consumer;

/**
 * Interface ConsumerRepositoryInterface
 *
 * @package Consumer\Model\Consumer
 */
interface ConsumerRepositoryInterface
{
  /**
   * @return Consumer[]
   */
  public function getAllConsumers();
  
  /**
   * @param $id
   *
   * @return Consumer
   */
  public function getConsumer($id);
}