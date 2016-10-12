<?php

namespace Consumer\Form\Consumer;

use Zend\Form\Fieldset;
use Consumer\Model\Consumer\Consumer;
use Zend\Hydrator\Reflection as ReflectionHydrator;

/**
 * Class ConsumerFieldset
 *
 * @package Consumer\Form\Consumer
 */
class ConsumerFieldset extends Fieldset
{
  /**
   *
   */
  public function init()
  {
    $this->setHydrator(new ReflectionHydrator());
    $this->setObject(new Consumer());
    
    $this->add([
      'type' => 'hidden',
      'name' => 'consumerId',
    ]);
    
    $this->add([
      'type'       => 'select',
      'name'       => 'groupId',
      'options'    => [
        'label' => 'Group',
      ],
      'attributes' => [
        'class' => 'form-control',
      ],
    ]);
    
    $this->add([
      'type'       => 'text',
      'name'       => 'login',
      'options'    => [
        'label' => 'Login',
      ],
      'attributes' => [
        'class' => 'form-control',
      ],
    ]);
    
    $this->add([
      'type'       => 'password',
      'name'       => 'password',
      'options'    => [
        'label' => 'Password',
      ],
      'attributes' => [
        'class' => 'form-control',
      ],
    ]);
    
    $this->add([
      'type'       => 'email',
      'name'       => 'email',
      'options'    => [
        'label' => 'Email',
      ],
      'attributes' => [
        'class' => 'form-control',
      ],
    ]);
    
    $this->add([
      'type'    => 'file',
      'name'    => 'image',
      'options' => [
        'label' => 'Image',
      ],
    ]);
  }
}