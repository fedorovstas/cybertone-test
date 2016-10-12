<?php

namespace Consumer\Form\Group;

use Zend\Form\Fieldset;
use Consumer\Model\Group\Group;
use Zend\Hydrator\Reflection as ReflectionHydrator;

/**
 * Class GroupFieldset
 *
 * @package Consumer\Form\Group
 */
class GroupFieldset extends Fieldset
{
  /**
   *
   */
  public function init()
  {
    $this->setHydrator(new ReflectionHydrator());
    $this->setObject(new Group());
    
    $this->add([
      'type' => 'hidden',
      'name' => 'groupId',
    ]);
    
    $this->add([
      'type'       => 'text',
      'name'       => 'name',
      'options'    => [
        'label' => 'Name',
      ],
      'attributes' => [
        'class' => 'form-control',
      ],
    ]);
  }
}