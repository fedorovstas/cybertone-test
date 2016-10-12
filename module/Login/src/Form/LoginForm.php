<?php

namespace Login\Form;

use Zend\Form\Form;

/**
 * Class LoginForm
 *
 * @package Login\Form
 */
class LoginForm extends Form
{
  /**
   * LoginForm constructor.
   *
   * @param null $name
   */
  public function __construct($name = NULL)
  {
    parent::__construct('login');
    
    $this->setAttribute('action', '/login');
    
    $this->add([
      'name'       => 'login',
      'type'       => 'text',
      'options'    => [
        'label' => 'Логин',
      ],
      'attributes' => [
        'class'       => 'form-control',
        'placeholder' => 'Введите Ваш логин',
      ],
    ]);
    
    $this->add([
      'name'       => 'password',
      'type'       => 'password',
      'class'      => 'form-control',
      'options'    => [
        'label' => 'Пароль',
      ],
      'attributes' => [
        'class'       => 'form-control',
        'placeholder' => 'Введите Ваш пароль',
      ],
    ]);
    
    $this->add([
      'name'       => 'submit',
      'type'       => 'submit',
      'attributes' => [
        'value' => 'Войти',
        'id'    => 'submitbutton',
        'class' => 'form-control',
      ],
    ]);
  }
}