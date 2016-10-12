<?php

namespace Consumer\Form\Group;

use Zend\Form\Form;

/**
 * Class GroupForm
 *
 * @package Consumer\Form\Group
 */
class GroupForm extends Form
{
  /**
   * @param null $name
   */
  public function init($name = NULL)
  {
    parent::__construct('group');
    
    $this->add([
      'name'    => 'group',
      'type'    => GroupFieldset::class,
      'options' => [
        'use_as_base_fieldset' => TRUE,
      ],
    ]);
    
    $this->add([
      'type'       => 'submit',
      'name'       => 'submit',
      'attributes' => [
        'value' => 'Add new group',
      ],
    ]);
  }
}