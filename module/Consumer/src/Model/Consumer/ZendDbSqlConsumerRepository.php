<?php

namespace Consumer\Model\Consumer;

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
use Zend\Db\Sql\Join;

/**
 * Class ZendDbSqlConsumerRepository
 *
 * @package Consumer\Model\Consumer
 */
class ZendDbSqlConsumerRepository implements ConsumerRepositoryInterface
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
   * @var Consumer
   */
  private $consumerPrototype;
  
  /**
   * ZendDbSqlConsumerRepository constructor.
   *
   * @param AdapterInterface  $db
   * @param HydratorInterface $hydrator
   * @param Consumer          $consumerPrototype
   */
  public function __construct(
    AdapterInterface $db,
    HydratorInterface $hydrator,
    Consumer $consumerPrototype
  ) {
    $this->db = $db;
    $this->hydrator = $hydrator;
    $this->consumerPrototype = $consumerPrototype;
  }
  
  /**
   * @param array|NULL $options
   *
   * @return array|Paginator
   */
  public function getAllConsumers(array $options = NULL)
  {
    $sql = new Sql($this->db);
    $select = $sql->select('consumer')
                  ->join('group', 'group.groupId = consumer.groupId', ['groupName' => 'name'], Join::JOIN_LEFT);
    
    $filter = [];
    if (isset($options['filter'])) {
      $filterData = [];
      if ($options['filter']) {
        foreach (explode(';', $options['filter']) as $value) {
          $param = explode(':', $value);
          $filterData[$param[0]] = $param[1];
        }
      }
      
      if ($filterData) {
        if (isset($filterData['groupId']) && $filterData['groupId']) {
          $filter[] = 'group.groupId = ' . $filterData['groupId'];
        }
        if (isset($filterData['expirationDateTimeFrom'])) {
          $filter[] = 'consumer.expirationDateTime >= "'
            . date('Y-m-d H:i:s',
              strtotime($filterData['expirationDateTimeFrom']))
            . '"';
        }
        if (isset($filterData['expirationDateTimeTo'])) {
          $filter[] = 'consumer.expirationDateTime <= "'
            . date('Y-m-d H:i:s',
              strtotime($filterData['expirationDateTimeTo']))
            . '"';
        }
      }
    }
    
    if ($filter) {
      $select->where($filter);
    }
    
    $order = [];
    if (isset($options['sort'])) {
      $order[] = $options['sort'];
    }
    if (isset($options['order'])) {
      $order[] = $options['order'];
    }
    
    if ($order) {
      $select->order(implode(" ", $order));
    } else {
      $select->order('consumerId');
    }
    
    $stmt = $sql->prepareStatementForSqlObject($select);
    $result = $stmt->execute();
    
    if ( ! $result instanceof ResultInterface || ! $result->isQueryResult()) {
      return [];
    }
    
    $resultSet = new HydratingResultSet($this->hydrator, $this->consumerPrototype);
    $resultSet->initialize($result);
    
    $resultSetPrototype = new ResultSet();
    $resultSetPrototype->setArrayObjectPrototype(new Consumer());
    
    // Create new pagination adapter object
    $paginatorAdapter = new DbSelect(
    // our configured select object:
      $select,
      // the adapter to run it against:
      $sql,
      // the result set to hydrate:
      $resultSetPrototype
    );
    
    $paginator = new Paginator($paginatorAdapter);
    
    return $paginator;
  }
  
  /**
   * @inheritDoc
   */
  public function getConsumer($id)
  {
    $sql = new Sql($this->db);
    $select = $sql->select('consumer')
                  ->join('group', 'group.groupId = consumer.groupId', ['groupName' => 'name'], Join::JOIN_LEFT)
                  ->where(['consumerId = ?' => $id]);
    
    $stmt = $sql->prepareStatementForSqlObject($select);
    $result = $stmt->execute();
    
    if ( ! $result instanceof ResultInterface || ! $result->isQueryResult()) {
      throw new RuntimeException(sprintf(
        'Failed retrieving consumer with identifier "%s"; unknown database error.',
        $id
      ));
    }
    
    $resultSet = new HydratingResultSet($this->hydrator, $this->consumerPrototype);
    $resultSet->initialize($result);
    $consumer = $resultSet->current();
    
    if ( ! $consumer) {
      throw new InvalidArgumentException(sprintf(
        'Consumer with identifier "%s" not found.',
        $id
      ));
    }
    
    return $consumer;
  }
}