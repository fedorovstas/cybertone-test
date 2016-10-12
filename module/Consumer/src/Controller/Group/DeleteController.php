<?php

namespace Consumer\Controller\Group;

use Consumer\Model\Group\GroupCommandInterface;
use Consumer\Model\Group\GroupRepositoryInterface;
use InvalidArgumentException;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class DeleteController
 *
 * @package Consumer\Controller\Group
 */
class DeleteController extends AbstractActionController
{
  /**
   * @var GroupCommandInterface
   */
  private $command;
  /**
   * @var GroupRepositoryInterface
   */
  private $repository;
  
  /**
   * DeleteController constructor.
   *
   * @param GroupCommandInterface    $command
   * @param GroupRepositoryInterface $repository
   */
  public function __construct(
    GroupCommandInterface $command,
    GroupRepositoryInterface $repository
  ) {
    $this->command = $command;
    $this->repository = $repository;
  }
  
  /**
   * @return array|\Zend\Http\Response
   */
  public function deleteAction()
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
    
    $request = $this->getRequest();
    if ( ! $request->isPost()) {
      return ['group' => $group];
    }
    
    if ($id != $request->getPost('id') || 'Delete' !== $request->getPost('confirm', 'no')
    ) {
      return $this->redirect()->toRoute('consumer/group');
    }
    
    $group = $this->command->deleteGroup($group);
    
    return $this->redirect()->toRoute('consumer/group');
  }
}