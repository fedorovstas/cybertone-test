<?php

namespace Consumer\Model\Group;

use InvalidArgumentException;
use RuntimeException;
use Zend\Hydrator\HydratorInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Paginator;

/**
 * Class ZendDbSqlGroupRepository
 *
 * @package Consumer\Model\Group
 */
class ZendDbSqlGroupRepository implements GroupRepositoryInterface
{
  /**
   * @var AdapterInterface
   */
  private $db;
  /**
   * @var HydratorInterface
   */
  private $hydrator;
  /**
   * @var Group
   */
  private $groupPrototype;
  
  /**
   * ZendDbSqlGroupRepository constructor.
   *
   * @param AdapterInterface  $db
   * @param HydratorInterface $hydrator
   * @param Group             $groupPrototype
   */
  public function __construct(
    AdapterInterface $db,
    HydratorInterface $hydrator,
    Group $groupPrototype
  ) {
    $this->db = $db;
    $this->hydrator = $hydrator;
    $this->groupPrototype = $groupPrototype;
  }
  
  /**
   * @inheritDoc
   */
  public function getAllGroups($paginated = TRUE, array $options = NULL)
  {
    $sql = new Sql($this->db);
    $select = $sql->select('group');
    
    $order = '';
    if (isset($options['sort'])) {
      $order .= $options['sort'] . ' ';
    }
    
    if (isset($options['order'])) {
      $order .= $options['order'];
    }
    
    if ($order) {
      $select->order($order);
    } else {
      $select->order('groupId');
    }
    
    $stmt = $sql->prepareStatementForSqlObject($select);
    $result = $stmt->execute();
    
    if ( ! $result instanceof ResultInterface || ! $result->isQueryResult()) {
      return [];
    }
    
    $resultSet = new HydratingResultSet($this->hydrator,
      $this->groupPrototype);
    $resultSet->initialize($result);
    
    if ($paginated) {
      
      $resultSetPrototype = new ResultSet();
      $resultSetPrototype->setArrayObjectPrototype(new Group());
      
      // Create new pagination adapter object
      $paginatorAdapter = new DbSelect(
      // our configured select object:
        $select,
        // the adapter to run it against:
        $this->db,
        // the result set to hydrate:
        $resultSetPrototype
      );
      
      $paginator = new Paginator($paginatorAdapter);
      
      return $paginator;
    }
    
    return $resultSet;
  }
  
  /**
   * @inheritDoc
   */
  public function getGroup($id)
  {
    $sql = new Sql($this->db);
    $select = $sql->select('group');
    $select->where(['groupId = ?' => $id]);
    
    $stmt = $sql->prepareStatementForSqlObject($select);
    $result = $stmt->execute();
    
    if ( ! $result instanceof ResultInterface || ! $result->isQueryResult()) {
      throw new RuntimeException(sprintf(
        'Failed retrieving consumer with identifier "%s"; unknown database error.',
        $id
      ));
    }
    
    $resultSet = new HydratingResultSet($this->hydrator,
      $this->groupPrototype);
    $resultSet->initialize($result);
    $group = $resultSet->current();
    
    if ( ! $group) {
      throw new InvalidArgumentException(sprintf(
        'Group with identifier "%s" not found.',
        $id
      ));
    }
    
    return $group;
  }
}