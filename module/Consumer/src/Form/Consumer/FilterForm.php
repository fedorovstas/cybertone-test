<?php

namespace Consumer\Form\Consumer;

use Zend\Form\Form;

/**
 * Class FilterForm
 *
 * @package Consumer\Form\Consumer
 */
class FilterForm extends Form
{
  /**
   * @param null $name
   */
  public function init($name = NULL)
  {
    parent::__construct('consumerFilter');
    
    $this->add([
      'name'       => 'groupId',
      'type'       => 'select',
      'options'    => [
        'label' => 'Group',
        'value' => [
          ['0' => '---select---'],
        ],
      ],
      'attributes' => [
        'class' => 'form-control',
      ],
    ]);
    
    $this->add([
      'name'       => 'expirationDateTimeFrom',
      'type'       => 'text',
      'options'    => [
        'label' => 'Expr datetime from',
      ],
      'attributes' => [
        'class' => 'form-control',
      ],
    ]);
    
    $this->add([
      'name'       => 'expirationDateTimeTo',
      'type'       => 'text',
      'options'    => [
        'label' => 'Expr datetime to',
      ],
      'attributes' => [
        'class' => 'form-control',
      ],
    ]);
    
    $this->add([
      'name'       => 'submit',
      'type'       => 'submit',
      'attributes' => [
        'class' => 'btn btn-primary',
        'value' => 'Go',
      ],
    ]);
  }
}