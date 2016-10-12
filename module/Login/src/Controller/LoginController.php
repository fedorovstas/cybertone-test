<?php

namespace Login\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Login\Model\Login;
use Login\Form\LoginForm;
use Zend\Session\Container;

/**
 * Class LoginController
 * @package Login\Controller
 */
class LoginController extends AbstractActionController
{
  /**
   * @return array|\Zend\Http\Response
   */
  public function indexAction()
  {
    $container = new Container('auth');
    $container->isAuth = FALSE;
    
    $form = new LoginForm();
    
    $request = $this->getRequest();
    
    if ( ! $request->isPost()) {
      return ['form' => $form];
    }
    
    $login = new Login();
    
    $form->setInputFilter($login->getInputFilter());
    $form->setData($request->getPost());
    
    if ( ! $form->isValid()) {
      return ['form' => $form];
    }
    
    if ( ! $login->checkLoginData($this->getRequest()->getPost('login'), $this->getRequest()->getPost('password'))) {
      return ['form' => $form, 'auth_error' => 'Не верный логин и/или пароль'];
    }
    
    $container->isAuth = TRUE;
    
    return $this->redirect()->toRoute('consumer');
  }
  
  /**
   * @return array|\Zend\Http\Response
   */
  public function checkAction()
  {
    $container = new Container('auth');
    if ( ! $container->isAuth) {
      return $this->redirect()->toRoute('login');
    }
    
    return [];
  }
}