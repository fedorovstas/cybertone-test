<?php

namespace Consumer\Model\Consumer;

/**
 * Interface ConsumerCommandInterface
 *
 * @package Consumer\Model\Consumer
 */
interface ConsumerCommandInterface
{
  /**
   * @param Consumer $consumer
   *
   * @return mixed
   */
  public function addConsumer(Consumer $consumer);
  
  /**
   * @param Consumer $consumer
   *
   * @return mixed
   */
  public function updateConsumer(Consumer $consumer);
  
  /**
   * @param Consumer $consumer
   *
   * @return mixed
   */
  public function deleteConsumer(Consumer $consumer);
}