<?php

namespace Consumer;

/**
 * Class Module
 *
 * @package Consumer
 */
class Module
{
  /**
   * @return array
   */
  public function getConfig()
  {
    return include __DIR__ . '/../config/module.config.php';
  }
}