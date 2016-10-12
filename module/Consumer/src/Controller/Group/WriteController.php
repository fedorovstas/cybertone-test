<?php

namespace Consumer\Controller\Group;

use Consumer\Form\Group\GroupForm;
use Consumer\Model\Group\Group;
use Consumer\Model\Group\GroupCommandInterface;
use Consumer\Model\Group\GroupRepositoryInterface;
use InvalidArgumentException;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class WriteController
 *
 * @package Consumer\Controller\Group
 */
class WriteController extends AbstractActionController
{
  /**
   * @var GroupCommandInterface
   */
  private $command;
  /**
   * @var GroupForm
   */
  private $form;
  /**
   * @var GroupRepositoryInterface
   */
  private $repository;
  
  /**
   * WriteController constructor.
   *
   * @param GroupCommandInterface    $command
   * @param GroupForm                $form
   * @param GroupRepositoryInterface $repository
   */
  public function __construct(
    GroupCommandInterface $command,
    GroupForm $form,
    GroupRepositoryInterface $repository
  ) {
    $this->command = $command;
    $this->form = $form;
    $this->repository = $repository;
  }
  
  /**
   * @return array|\Zend\Http\Response
   * @throws \Exception
   */
  public function addAction()
  {
    $this->forward()->dispatch('Login\Controller\LoginController', ['action' => 'check']);
    
    $request = $this->getRequest();
    
    if ( ! $request->isPost()) {
      return ['form' => $this->form];
    }
    
    $this->form->setData($request->getPost());
    
    if ( ! $this->form->isValid()) {
      return ['form' => $this->form];
    }
    
    $group = $this->form->getData();
    
    $group = new Group([
      'id'   => NULL,
      'name' => $group->getName(),
    ]);
    
    try {
      $group = $this->command->addGroup($group);
    } catch (\Exception $ex) {
      throw $ex;
    }
    
    return $this->redirect()->toRoute('consumer/group/detail', ['id' => $group->getGroupId()]);
  }
  
  /**
   * @return \Zend\Http\Response|ViewModel
   */
  public function editAction()
  {
    $this->forward()->dispatch('Login\Controller\LoginController', ['action' => 'check']);
    
    $id = $this->params()->fromRoute('id');
    if ( ! $id) {
      return $this->redirect()->toRoute('consumer/group');
    }
    
    try {
      $group = $this->repository->getGroup($id);
    } catch (InvalidArgumentException $ex) {
      return $this->redirect()->toRoute('consumer/group');
    }
    
    $this->form->bind($group);
    $viewModel = new ViewModel([
      'form' => $this->form,
    ]);
    
    $request = $this->getRequest();
    if ( ! $request->isPost()) {
      return $viewModel;
    }
    
    $this->form->setData($request->getPost());
    
    if ( ! $this->form->isValid()) {
      return $viewModel;
    }
    
    $group = $this->form->getData();
    
    $group = new Group([
      'groupId' => $group->getGroupId(),
      'name'    => $group->getName(),
    ]);
    
    $group = $this->command->updateGroup($group);
    
    return $this->redirect()->toRoute('consumer/group/detail', ['id' => $group->getGroupId()]);
  }
}