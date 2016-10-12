<?php

namespace Consumer\Controller\Group;

use Consumer\Model\Group\GroupRepositoryInterface;
use Zend\Mvc\Controller\AbstractActionController;
use InvalidArgumentException;

/**
 * Class ListController
 *
 * @package Consumer\Controller\Group
 */
class ListController extends AbstractActionController
{
  /**
   * @var GroupRepositoryInterface
   */
  private $groupRepository;
  
  /**
   * ListController constructor.
   *
   * @param GroupRepositoryInterface $groupRepository
   */
  public function __construct(GroupRepositoryInterface $groupRepository)
  {
    $this->groupRepository = $groupRepository;
  }
  
  /**
   * @return array
   */
  public function indexAction()
  {
    $this->forward()->dispatch('Login\Controller\LoginController', ['action' => 'check']);
    
    $paginator = $this->groupRepository->getAllGroups(TRUE, $this->params()->fromQuery());
    
    $page = (int) $this->params()->fromQuery('page', 1);
    $page = ($page < 1) ? 1 : $page;
    $paginator->setCurrentPageNumber($page);
    
    // Set the number of items per page to 10:
    $paginator->setItemCountPerPage(5);
    
    $params = [
      'sort'  => $this->params()->fromQuery('sort', ''),
      'order' => $this->params()->fromQuery('order', ''),
      'page'  => $page,
    ];
    
    return ['paginator' => $paginator, 'params' => $params];
  }
  
  /**
   * @return array|\Zend\Http\Response
   */
  public function detailAction()
  {
    $this->forward()->dispatch('Login\Controller\LoginController', ['action' => 'check']);
    
    $id = $this->params()->fromRoute('id');
    
    try {
      $group = $this->groupRepository->getGroup($id);
    } catch (\InvalidArgumentException $ex) {
      return $this->redirect()->toRoute('consumer/group');
    }
    
    return ['group' => $group];
  }
}