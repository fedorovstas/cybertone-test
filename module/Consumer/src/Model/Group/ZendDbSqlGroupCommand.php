<?php

namespace Consumer\Model\Group;

use RuntimeException;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;

/**
 * Class ZendDbSqlGroupCommand
 *
 * @package Consumer\Model\Group
 */
class ZendDbSqlGroupCommand implements GroupCommandInterface
{
  /**
   * @var AdapterInterface
   */
  private $db;
  
  /**
   * ZendDbSqlGroupCommand constructor.
   *
   * @param AdapterInterface $db
   */
  public function __construct(AdapterInterface $db)
  {
    $this->db = $db;
  }
  
  /**
   * @param Group $group
   *
   * @return Group
   */
  public function addGroup(Group $group)
  {
    $insert = new Insert('group');
    $insert->values([
      'name' => $group->getName(),
    ]);
    
    $sql = new Sql($this->db);
    $stmt = $sql->prepareStatementForSqlObject($insert);
    $result = $stmt->execute();
    
    if ( ! $result instanceof ResultInterface) {
      throw new RuntimeException(
        'Database error occurred during group insert operation'
      );
    }
    
    $data = [
      'groupId' => $result->getGeneratedValue(),
      'name'    => $group->getName(),
    ];
    
    return new Group($data);
  }
  
  /**
   * @param Group $group
   *
   * @return Group
   */
  public function updateGroup(Group $group)
  {
    if ( ! $group->getGroupId()) {
      throw RuntimeException('Cannot update group; missing identifier');
    }
    
    $update = new Update('group');
    $update->set([
      'groupId' => $group->getGroupId(),
      'name'    => $group->getName(),
    ]);
    $update->where(['groupId = ?' => $group->getGroupId()]);
    
    $sql = new Sql($this->db);
    $stmt = $sql->prepareStatementForSqlObject($update);
    $result = $stmt->execute();
    
    if ( ! $result instanceof ResultInterface) {
      throw new RuntimeException(
        'Database error occurred during group update operation'
      );
    }
    
    return $group;
  }
  
  /**
   * @param Group $group
   *
   * @return bool
   */
  public function deleteGroup(Group $group)
  {
    if ( ! $group->getGroupId()) {
      throw RuntimeException('Cannot update group; missing identifier');
    }
    
    $delete = new Delete('group');
    $delete->where(['groupId = ?' => $group->getGroupId()]);
    
    $sql = new Sql($this->db);
    $stmt = $sql->prepareStatementForSqlObject($delete);
    $result = $stmt->execute();
    
    if ( ! $result instanceof ResultInterface) {
      return FALSE;
    }
    
    return TRUE;
  }
}