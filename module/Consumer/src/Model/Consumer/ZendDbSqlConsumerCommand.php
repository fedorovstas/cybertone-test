<?php

namespace Consumer\Model\Consumer;

use RuntimeException;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;

/**
 * Class ZendDbSqlConsumerCommand
 *
 * @package Consumer\Model\Consumer
 */
class ZendDbSqlConsumerCommand implements ConsumerCommandInterface
{
  /**
   * @var AdapterInterface
   */
  private $db;
  
  /**
   * ZendDbSqlConsumerCommand constructor.
   *
   * @param AdapterInterface $db
   */
  public function __construct(AdapterInterface $db)
  {
    $this->db = $db;
  }
  
  /**
   * @param Consumer $consumer
   *
   * @return Consumer
   */
  public function addConsumer(Consumer $consumer)
  {
    $insert = new Insert('consumer');
    $insert->values([
      'groupId'            => $consumer->getGroupId(),
      'login'              => $consumer->getLogin(),
      'password'           => md5($consumer->getPassword()),
      'email'              => $consumer->getEmail(),
      'expirationDateTime' => $consumer->getExpirationDateTime(),
      'imageExtention'     => $consumer->getImageExtention(),
    ]);
    
    $sql = new Sql($this->db);
    $stmt = $sql->prepareStatementForSqlObject($insert);
    $result = $stmt->execute();
    
    if ( ! $result instanceof ResultInterface) {
      throw new RuntimeException(
        'Database error occurred during consumer insert operation'
      );
    }
    
    $data = [
      'consumerId'         => $result->getGeneratedValue(),
      'groupId'            => $consumer->getGroupId(),
      'login'              => $consumer->getLogin(),
      'password'           => $consumer->getPassword(),
      'email'              => $consumer->getEmail(),
      'expirationDateTime' => $consumer->getExpirationDateTime(),
      'imageExtention'     => $consumer->getImageExtention(),
      'image'              => $consumer->getImage(),
    ];
    
    return new Consumer($data);
  }
  
  /**
   * @param Consumer $consumer
   *
   * @return Consumer
   */
  public function updateConsumer(Consumer $consumer)
  {
    if ( ! $consumer->getConsumerId()) {
      throw RuntimeException('Cannot update consumer; missing identifier');
    }
    
    $update = new Update('consumer');
    $update->set([
      'groupId'            => $consumer->getGroupId(),
      'login'              => $consumer->getLogin(),
      'password'           => md5($consumer->getPassword()),
      'email'              => $consumer->getEmail(),
      'expirationDateTime' => $consumer->getExpirationDateTime(),
      'imageExtention'     => $consumer->getImageExtention(),
    ]);
    $update->where(['consumerId = ?' => $consumer->getConsumerId()]);
    
    $sql = new Sql($this->db);
    $stmt = $sql->prepareStatementForSqlObject($update);
    $result = $stmt->execute();
    
    if ( ! $result instanceof ResultInterface) {
      throw new RuntimeException(
        'Database error occurred during consumer update operation'
      );
    }
    
    return $consumer;
  }
  
  /**
   * @param Consumer $consumer
   *
   * @return bool
   */
  public function deleteConsumer(Consumer $consumer)
  {
    if ( ! $consumer->getConsumerId()) {
      throw RuntimeException('Cannot update consumer; missing identifier');
    }
    
    $delete = new Delete('consumer');
    $delete->where(['consumerId = ?' => $consumer->getConsumerId()]);
    
    $sql = new Sql($this->db);
    $stmt = $sql->prepareStatementForSqlObject($delete);
    $result = $stmt->execute();
    
    if ( ! $result instanceof ResultInterface) {
      return FALSE;
    }
  
    unlink($_SERVER['DOCUMENT_ROOT'] . '/files/consumer/' . $consumer->getConsumerId() . '.' . $consumer->getImageExtention());
    
    return TRUE;
  }
}