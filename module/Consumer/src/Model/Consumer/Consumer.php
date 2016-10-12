<?php

namespace Consumer\Model\Consumer;

use DomainException;
use Zend\Filter\File\RenameUpload;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;
use Zend\Validator\EmailAddress;
use Zend\Validator\File\Extension;

/**
 * Class Consumer
 *
 * @package Consumer\Model\Consumer
 */
class Consumer implements InputFilterAwareInterface
{
  /**
   * @var int
   */
  private $consumerId;
  /**
   * @var int
   */
  private $groupId;
  /**
   * @var string
   */
  private $groupName;
  /**
   * @var string
   */
  private $login;
  /**
   * @var string
   */
  private $password;
  /**
   * @var string
   */
  private $email;
  /**
   * @var string
   */
  private $expirationDateTime;
  /**
   * @var string
   */
  private $imageExtention;
  /**
   * @var mixed|null
   */
  private $image;
  /**
   * @var
   */
  private $inputFilter;
  
  /**
   * Consumer constructor.
   *
   * @param array|NULL $data
   */
  public function __construct(array $data = NULL)
  {
    $this->consumerId = (isset($data['consumerId']) ? (int) $data['consumerId'] : 0);
    $this->groupId = (isset($data['groupId']) ? (int) $data['groupId'] : 0);
    $this->groupName = (isset($data['groupName']) ? (string) $data['groupName'] : '');
    $this->login = (isset($data['login']) ? (string) $data['login'] : '');
    $this->password = (isset($data['password']) ? (string) $data['password'] : '');
    $this->email = (isset($data['email']) ? (string) $data['email'] : '');
    $this->expirationDateTime = (isset($data['expirationDateTime']) ? (string) $data['expirationDateTime'] : '');
    $this->imageExtention = (isset($data['imageExtention']) ? (string) $data['imageExtention'] : '');
    $this->image = (isset($data['image']) ? $data['image'] : NULL);
  }
  
  /**
   * @param array|NULL $data
   */
  public function exchangeArray(array $data = NULL)
  {
    $this->consumerId = (isset($data['consumerId']) ? $data['consumerId'] : 0);
    $this->groupId = (isset($data['groupId']) ? $data['groupId'] : 0);
    $this->groupName = (isset($data['groupName']) ? $data['groupName'] : '');
    $this->login = (isset($data['login']) ? $data['login'] : '');
    $this->password = (isset($data['password']) ? $data['password'] : '');
    $this->email = (isset($data['email']) ? $data['email'] : '');
    $this->expirationDateTime = (isset($data['expirationDateTime']) ? $data['expirationDateTime'] : '');
    $this->imageExtention = (isset($data['imageExtention']) ? $data['imageExtention'] : '');
    $this->image = (isset($data['image']) ? $data['image'] : NULL);
  }
  
  /**
   * @param $imageExtention
   */
  public function setImageExtention($imageExtention)
  {
    $this->imageExtention = $imageExtention;
  }
  
  /**
   * @return int
   */
  public function getConsumerId()
  {
    return $this->consumerId;
  }
  
  /**
   * @return int
   */
  public function getGroupId()
  {
    return $this->groupId;
  }
  
  /**
   * @return string
   */
  public function getGroupName()
  {
    return $this->groupName;
  }
  
  /**
   * @return string
   */
  public function getLogin()
  {
    return $this->login;
  }
  
  /**
   * @return string
   */
  public function getPassword()
  {
    return $this->password;
  }
  
  /**
   * @return string
   */
  public function getEmail()
  {
    return $this->email;
  }
  
  /**
   * @return string
   */
  public function getExpirationDateTime()
  {
    return $this->expirationDateTime;
  }
  
  /**
   * @return string
   */
  public function getImageExtention()
  {
    return $this->imageExtention;
  }
  
  /**
   * @return mixed|null
   */
  public function getImage()
  {
    return $this->image;
  }
  
  /**
   * @param InputFilterInterface $inputFilter
   */
  public function setInputFilter(InputFilterInterface $inputFilter)
  {
    throw new DomainException(sprintf(
      '%s does not allow injection of an alternate input filter',
      __CLASS__
    ));
  }
  
  /**
   * @return InputFilter
   */
  public function getInputFilter()
  {
    if ($this->inputFilter) {
      return $this->inputFilter;
    }
    
    $inputFilter = new InputFilter();
    
    $inputFilter->add([
      'name'     => 'consumerId',
      'required' => FALSE,
      'filters'  => [
        ['name' => ToInt::class],
      ],
    ]);
    
    $inputFilter->add([
      'name'     => 'groupId',
      'required' => TRUE,
      'filters'  => [
        ['name' => ToInt::class],
      ],
    ]);
    
    $inputFilter->add([
      'name'       => 'login',
      'required'   => TRUE,
      'filters'    => [
        ['name' => StripTags::class],
        ['name' => StringTrim::class],
      ],
      'validators' => [
        [
          'name'    => StringLength::class,
          'options' => [
            'encoding' => 'UTF-8',
            'min'      => 1,
            'max'      => 50,
          ],
        ],
      ],
    ]);
    
    $inputFilter->add([
      'name'       => 'password',
      'required'   => TRUE,
      'filters'    => [
        ['name' => StripTags::class],
        ['name' => StringTrim::class],
      ],
      'validators' => [
        [
          'name'    => StringLength::class,
          'options' => [
            'encoding' => 'UTF-8',
            'min'      => 1,
            'max'      => 50,
          ],
        ],
      ],
    ]);
    
    $inputFilter->add([
      'name'       => 'email',
      'required'   => TRUE,
      'filters'    => [
        ['name' => StripTags::class],
        ['name' => StringTrim::class],
      ],
      'validators' => [
        [
          'name' => EmailAddress::class,
        ],
      ],
    ]);
    
    $inputFilter->add([
      'type'       => FileInput::class,
      'name'       => 'image',
      'required'   => TRUE,
      'filters'    => [
        [
          'name'    => RenameUpload::class,
          'options' => [
            'target'               => $_SERVER['DOCUMENT_ROOT'] . '/files/consumer/',
            'randomize '           => TRUE,
            'use_upload_extension' => TRUE,
          ],
        ],
      ],
      'validators' => [
        [
          'name'    => Extension::class,
          'options' => [
            'jpg',
            'jpeg',
            'png',
          ],
        ],
      ],
    ]);
    
    $this->inputFilter = $inputFilter;
    
    return $this->inputFilter;
  }
}