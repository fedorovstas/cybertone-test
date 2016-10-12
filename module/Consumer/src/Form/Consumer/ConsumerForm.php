<?php

namespace Consumer\Form\Consumer;

use Zend\Form\Form;

/**
 * Class ConsumerForm
 *
 * @package Consumer\Form\Consumer
 */
class ConsumerForm extends Form
{
  /**
   * @param null $name
   */
  public function init($name = NULL)
  {
    parent::__construct('consumer');
    
    $this->add([
      'name'    => 'consumer',
      'type'    => ConsumerFieldset::class,
      'options' => [
        'use_as_base_fieldset' => TRUE,
      ],
    ]);
    
    $this->add([
      'type'       => 'submit',
      'name'       => 'submit',
      'attributes' => [
        'value' => 'Add new consumer',
      ],
    ]);
  }
}