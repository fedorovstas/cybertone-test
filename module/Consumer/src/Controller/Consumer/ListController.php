<?php

namespace Consumer\Controller\Consumer;

use Consumer\Model\Consumer\ConsumerRepositoryInterface;
use Consumer\Model\Group\GroupRepositoryInterface;
use Zend\Mvc\Controller\AbstractActionController;
use InvalidArgumentException;
use Consumer\Form\Consumer\FilterForm;
use Zend\Session\Container;

/**
 * Class ListController
 *
 * @package Consumer\Controller\Consumer
 */
class ListController extends AbstractActionController
{
  /**
   * @var ConsumerRepositoryInterface
   */
  private $consumerRepository;
  /**
   * @var GroupRepositoryInterface
   */
  private $groupRepository;
  /**
   * @var FilterForm
   */
  private $filterForm;
  
  /**
   * ListController constructor.
   *
   * @param ConsumerRepositoryInterface $consumerRepository
   * @param GroupRepositoryInterface $groupRepository
   * @param FilterForm $filterForm
   */
  public function __construct(
    ConsumerRepositoryInterface $consumerRepository,
    GroupRepositoryInterface $groupRepository,
    FilterForm $filterForm
  )
  {
    $this->consumerRepository = $consumerRepository;
    $this->groupRepository = $groupRepository;
    $this->filterForm = $filterForm;
  }
  
  /**
   * @return array
   */
  public function indexAction()
  {
    $this->forward()->dispatch('Login\Controller\LoginController', ['action' => 'check']);
    
    $paginator = $this->consumerRepository->getAllConsumers($this->params()->fromQuery());
    
    $page = (int) $this->params()->fromQuery('page', 1);
    $page = ($page < 1) ? 1 : $page;
    $paginator->setCurrentPageNumber($page);
    
    // Set the number of items per page to 10:
    $paginator->setItemCountPerPage(5);
    
    $params = [
      'page' => $page,
      'sort' => $this->params()->fromQuery('sort', ''),
      'order' => $this->params()->fromQuery('order', ''),
      'filter' => $this->params()->fromQuery('filter', ''),
    ];
    
    $groups = $this->groupRepository->getAllGroups(FALSE);
    
    $getFilter = $this->params()->fromQuery('filter', '');
    $filterData = [];
    if ($getFilter) {
      foreach (explode(';', $getFilter) as $value) {
        $param = explode(':', $value);
        $filterData[$param[0]] = $param[1];
      }
    }
    
    $this->filterForm->setData($filterData);
    
    return [
      'filterForm' => $this->filterForm,
      'groups' => $groups,
      'paginator' => $paginator,
      'params' => $params,
    ];
  }
  
  /**
   * @return array|\Zend\Http\Response
   */
  public function detailAction()
  {
    $this->forward()->dispatch('Login\Controller\LoginController',
      ['action' => 'check']);
    
    $id = $this->params()->fromRoute('id');
    
    try {
      $consumer = $this->consumerRepository->getConsumer($id);
    } catch (\InvalidArgumentException $ex) {
      return $this->redirect()->toRoute('consumer');
    }
    
    return ['consumer' => $consumer];
  }
}