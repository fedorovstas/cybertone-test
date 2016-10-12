<?php


namespace Consumer\Controller\Consumer;

use Consumer\Model\Consumer\ConsumerCommandInterface;
use Consumer\Model\Consumer\ConsumerRepositoryInterface;
use InvalidArgumentException;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class DeleteController
 *
 * @package Consumer\Controller\Consumer
 */
class DeleteController extends AbstractActionController
{
  /**
   * @var ConsumerCommandInterface
   */
  private $command;
  /**
   * @var ConsumerRepositoryInterface
   */
  private $repository;
  
  /**
   * DeleteController constructor.
   *
   * @param ConsumerCommandInterface $command
   * @param ConsumerRepositoryInterface $repository
   */
  public function __construct(
    ConsumerCommandInterface $command,
    ConsumerRepositoryInterface $repository
  )
  {
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
      return $this->redirect()->toRoute('consumer');
    }
    
    try {
      $consumer = $this->repository->getConsumer($id);
    } catch (InvalidArgumentException $ex) {
      return $this->redirect()->toRoute('consumer');
    }
    
    $request = $this->getRequest();
    if ( ! $request->isPost()) {
      return ['consumer' => $consumer];
    }
    
    if ($id != $request->getPost('id')
      || 'Delete' !== $request->getPost('confirm', 'no')
    ) {
      return $this->redirect()->toRoute('consumer');
    }

    $consumer = $this->command->deleteConsumer($consumer);
    return $this->redirect()->toRoute('consumer');
  }
}