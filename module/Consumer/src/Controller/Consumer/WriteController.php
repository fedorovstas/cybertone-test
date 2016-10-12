<?php

namespace Consumer\Controller\Consumer;

use Consumer\Form\Consumer\ConsumerForm;
use Consumer\Model\Consumer\Consumer;
use Consumer\Model\Consumer\ConsumerCommandInterface;
use Consumer\Model\Consumer\ConsumerRepositoryInterface;
use Consumer\Model\Group\GroupRepositoryInterface;
use InvalidArgumentException;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class WriteController
 *
 * @package Consumer\Controller\Consumer
 */
class WriteController extends AbstractActionController
{
  /**
   * @var ConsumerCommandInterface
   */
  private $command;
  /**
   * @var ConsumerForm
   */
  private $form;
  /**
   * @var ConsumerRepositoryInterface
   */
  private $repository;
  /**
   * @var GroupRepositoryInterface
   */
  private $groupRepository;
  
  /**
   * WriteController constructor.
   *
   * @param ConsumerCommandInterface    $command
   * @param ConsumerForm                $form
   * @param ConsumerRepositoryInterface $repository
   * @param GroupRepositoryInterface    $groupRepository
   */
  public function __construct(
    ConsumerCommandInterface $command,
    ConsumerForm $form,
    ConsumerRepositoryInterface $repository,
    GroupRepositoryInterface $groupRepository
  ) {
    $this->command = $command;
    $this->form = $form;
    $this->repository = $repository;
    $this->groupRepository = $groupRepository;
  }
  
  /**
   * @return array|\Zend\Http\Response
   * @throws \Exception
   */
  public function addAction()
  {
    $this->forward()->dispatch('Login\Controller\LoginController', ['action' => 'check']);
    
    $groups = $this->groupRepository->getAllGroups(FALSE);
    
    $request = $this->getRequest();
    
    if ( ! $request->isPost()) {
      return ['form' => $this->form, 'groups' => $groups];
    }
    
    $postData = array_merge_recursive($request->getPost()->toArray(),
      $request->getFiles()->toArray());
    
    $this->form->setData($postData);
    
    if ( ! $this->form->isValid()) {
      return ['form' => $this->form, 'groups' => $groups];
    }
    
    $consumer = $this->form->getData();
    
    $consumer = new Consumer([
      'id'                 => NULL,
      'groupId'            => $consumer->getGroupId(),
      'login'              => $consumer->getLogin(),
      'password'           => $consumer->getPassword(),
      'email'              => $consumer->getEmail(),
      'expirationDateTime' => date('Y-m-d H:i:s', strtotime('+ 1 month')),
      'imageExtention'     => pathinfo($consumer->getImage()['tmp_name'])['extension'],
      'image'              => $consumer->getImage(),
    ]);
    
    try {
      $consumer = $this->command->addConsumer($consumer);
    } catch (\Exception $ex) {
      throw $ex;
    }
    
    if ($consumer->getImage()) {
      $imagePath = $consumer->getImage()['tmp_name'];
      $pathInfo = pathinfo($imagePath);
      $newImagePath = $pathInfo['dirname'] . '/' .
        $consumer->getConsumerId() . '.' . $pathInfo['extension'];
      
      rename($imagePath, $newImagePath);
    }
    
    return $this->redirect()->toRoute('consumer/detail', ['id' => $consumer->getConsumerId()]);
  }
  
  /**
   * @return \Zend\Http\Response|ViewModel
   */
  public function editAction()
  {
    $this->forward()->dispatch('Login\Controller\LoginController', ['action' => 'check']);
    
    $groups = $this->groupRepository->getAllGroups(FALSE);
    
    $id = $this->params()->fromRoute('id');
    if ( ! $id) {
      return $this->redirect()->toRoute('consumer');
    }
    
    try {
      $consumer = $this->repository->getConsumer($id);
    } catch (InvalidArgumentException $ex) {
      return $this->redirect()->toRoute('consumer');
    }
    
    $this->form->bind($consumer);
    $viewModel = new ViewModel([
      'form'   => $this->form,
      'groups' => $groups,
    ]);
    
    $request = $this->getRequest();
    if ( ! $request->isPost()) {
      return $viewModel;
    }
    
    $postData = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
    $this->form->setData($postData);
    
    if ( ! $this->form->isValid()) {
      return $viewModel;
    }
    
    if ($consumer->getImage()) {
      $imagePath = $consumer->getImage()['tmp_name'];
      $pathInfo = pathinfo($imagePath);
      $newImagePath = $pathInfo['dirname'] . '/' .
        $consumer->getConsumerId() . '.' . $pathInfo['extension'];
      
      rename($imagePath, $newImagePath);
    }
    
    $consumer = $this->form->getData();
    
    $consumer = new Consumer([
      'consumerId'         => $consumer->getConsumerId(),
      'groupId'            => $consumer->getGroupId(),
      'login'              => $consumer->getLogin(),
      'password'           => $consumer->getPassword(),
      'email'              => $consumer->getEmail(),
      'expirationDateTime' => $consumer->getExpirationDateTime(),
      'imageExtention'     => pathinfo($consumer->getImage()['tmp_name'])['extension'],
      'image'              => $consumer->getImage(),
    ]);
    
    $consumer = $this->command->updateConsumer($consumer);
    
    return $this->redirect()->toRoute('consumer/detail', ['id' => $consumer->getConsumerId()]);
  }
}