<?php

namespace Consumer\Model\Group;

use DomainException;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

/**
 * Class Group
 *
 * @package Consumer\Model\Group
 */
class Group implements InputFilterAwareInterface
{
  /**
   * @var int
   */
  private $groupId;
  /**
   * @var string
   */
  private $name;
  /**
   * @var
   */
  private $inputFilter;
  
  /**
   * Group constructor.
   *
   * @param array|NULL $data
   */
  public function __construct(array $data = NULL)
  {
    $this->groupId = (isset($data['groupId']) ? (int) $data['groupId'] : 0);
    $this->name = (isset($data['name']) ? (string) $data['name'] : '');
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
  public function getName()
  {
    return $this->name;
  }
  
  /**
   * @param array|NULL $data
   */
  public function exchangeArray(array $data = NULL)
  {
    $this->groupId = (isset($data['groupId']) ? (int) $data['groupId'] : 0);
    $this->name = (isset($data['name']) ? (string) $data['name'] : '');
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
      'name'     => 'id',
      'required' => FALSE,
      'filters'  => [
        ['name' => ToInt::class],
      ],
    ]);
    
    $inputFilter->add([
      'name'       => 'name',
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
    
    $this->inputFilter = $inputFilter;
    
    return $this->inputFilter;
  }
}